<?php

namespace Tests\Feature;

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

class PostApiTest extends TestCase
{
    private $loginId = 1;

    private $searchkeyword = "ab";

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

    public function getCreatePost()
    {
        return [
            'merchant_id' => Auth::guard('web')->loginUsingId($this->loginId)->id,
            'creator_id'  => 1,
            'content'     => 'Testing blog blog',
            'type'        => 'text',
            'access'      => 'public',
        ];
    }

    public function getUpdatePost()
    {
        return [
            'merchant_id' => Auth::guard('web')->loginUsingId($this->loginId)->id,
            'creator_id'  => 1,
            'content'     => 'Testing blog updated',
            'type'        => 'text',
            'access'      => 'public',
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
    public function getLastId()
    {
        $post = (new PostsRepository())->getLast();
        return $post->id;
    }

    /**
     * Post Create
     * [note] /post/create
     */
    public function testPostCreate()
    {
        $response = $this->json(
            'POST',
            '/api/post/create?api_token=' . $this->getAccessToken(),
            $this->getCreatePost());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Post Update
     * [note] /post/update/{post_id}
     */
    public function testPostUpdate()
    {
        $response = $this->json('POST',
            '/api/post/update/' . $this->getLastId() . '?api_token=' . $this->getAccessToken(),
            $this->getUpdatePost());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Post Destroy
     * [note] /post/destroy/{post_id}
     */
    public function testMerchantDestroy()
    {
        $response = $this->call('DELETE', '/api/post/destroy/' . $this->getLastId() . '?api_token=' . $this->getAccessToken());
        $response->assertJson(['meta' => ['success' => true]]);

    }

    public function testPostSearch()
    {

        $response = $this->call('GET', '/api/post/search/' . $this->searchkeyword . '?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getOneUserId()
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function getUpdatePermission()
    {
        return [
            'user_id'     => 1,
            'access'      => 'restricted',
            'permissions' => [
                0 => 'view',
                1 => 'edit'
            ]
        ];
    }

    public function getPrivatePostId()
    {
        $post = (new Posts())->where('access', 'private')->get();

        if (empty($post[0])) {
            return 1;
        }
        return $post[0]->id;
    }

    public function testPostUpdatePermissions()
    {
        $response = $this->json('POST',
            '/api/post/' . $this->getPrivatePostId() . '/updatePermission?api_token=' . $this->getAccessToken(),
            $this->getUpdatePermission());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testPopularPost()
    {
        $response = $this->call('GET', '/api/post/popular?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getOneUserId(),
            'page'    => 1,
            'order_by' => 'likes',
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
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
