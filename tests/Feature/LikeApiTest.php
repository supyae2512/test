<?php

namespace Tests\Feature;

use App\Pithos\like\LikesRepository;
use App\Pithos\post\Posts;
use App\Pithos\post\PostsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeApiTest extends TestCase
{
    private $loginId = 1;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * get sample token to test for endpoint need to be authorized;
     *
     * @return string
     */
    public function getAccessToken()
    {
        $users = Auth::guard('web')->loginUsingId($this->loginId);
        return $users->api_token;
    }

    public function getCreateLike()
    {
        return [
            'user_id' => $this->getOneUserId(),
            'post_id' => $this->getLastPostId()
        ];
    }

    public function getOneUserId()
    {
        $user = (new PostsRepository())->getLast();
        return $user->creator_id;
    }

    /**
     * @return integer
     */
    public function getLastPostId()
    {
        $post = (new PostsRepository())->getLast();
        return $post->id;
    }

    public function getLastId()
    {
        $like = (new LikesRepository())->getLast();
        return $like->id;
    }

    public function getLastUserId() {
        $like = (new LikesRepository())->getLast();
        return $like->user_id;
    }

    /**
     * Like Create
     */
    public function testLikeCreate()
    {
        $response = $this->json(
            'POST',
            '/api/' . $this->getLastPostId() . '/like?api_token=' . $this->getAccessToken(),
            ['user_id' => $this->getOneUserId()]
        );
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }


    public function testPostLikes()
    {
        $response = $this->call('GET',
            '/api/' . $this->getLastPostId() . '/likes?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testPostSpecificLike()
    {
        $response = $this->call('GET',
            '/api/' . $this->getLastPostId() . '/likes/' . $this->getLastId() .
            '?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testLike()
    {
        $response = $this->call('GET',
            '/api/like/' . $this->getLastId() .
            '?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testLikeDestroy()
    {
        $response = $this->call('DELETE', '/api/like/' . $this->getLastId() . '/destroy?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getLastUserId()
        ]);
        $response->assertJson(['meta' => ['success' => true]]);

    }

    protected function parseJson($response)
    {
        return json_decode($response->getContent());
    }

    protected function assertIsJson($data)
    {
        $this->assertEquals(0, json_last_error());
    }


}
