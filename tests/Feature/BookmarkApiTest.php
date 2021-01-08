<?php

namespace Tests\Feature;

use App\Pithos\bookmark\BookmarksRepository;
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

class BookmarkApiTest extends TestCase
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

    public function getCreateBookmark()
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
        $bookmark = (new BookmarksRepository())->getLast();
        return $bookmark;
    }

    /**
     * Like Create
     */
    public function testBookmarkCreate()
    {
        $response = $this->json(
            'POST',
            '/api/' . $this->getLastPostId() . '/bookmark?api_token=' . $this->getAccessToken(),
            ['user_id' => $this->getOneUserId()]
        );
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }


    public function testPostBookmarks()
    {
        $response = $this->call('GET',
            '/api/' . $this->getLastPostId() . '/bookmarks?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testPostSpecificBookmark()
    {
        $response = $this->call('GET',
            '/api/' . $this->getLastPostId() . '/bookmarks/' . $this->getLastId()->id .
            '?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testBookmark()
    {
        $response = $this->call('GET',
            '/api/bookmark/' . $this->getLastId() .
            '?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testBookmarkDestroy()
    {
        $response = $this->call('DELETE', '/api/bookmark/' . $this->getLastId()->id . '/destroy?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getLastId()->user_id
        ]);

        $response->assertJson(['meta' => ['success' => true]]);
    }

    public function testBookmarkByUser()
    {
        $response = $this->call('GET',
            '/api/user/' . $this->getLastId()->user_id .'/bookmarks'.
            '?api_token=' . $this->getAccessToken());

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
