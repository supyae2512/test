<?php

namespace Tests\Feature;

use App\Pithos\post\CommentsRepository;
use App\Pithos\post\PostsRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CommentTest extends TestCase
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

    /**
     * @return array
     */
    public function getCreateComment()
    {
        return [
            'post_id' => $this->getPostId(),
            'user_id' => $this->getOneUserId(),
            'content' => "Testing Comment is here"
        ];
    }

    public function getPostId() {
        $post = (new PostsRepository())->getLast();
        return $post->id;
    }

    /**
     * @return integer
     */
    public function getLastId()
    {
        $comment = (new CommentsRepository())->getLast();
        return $comment;
    }

    public function getOneUserId()
    {
        $user = (new CommentsRepository())->getLast();
        return $user->creator_id;
    }

    public function getUpdated()
    {
        return [
            'user_id' => $this->getOneUserId(),
            'content' => 'updated comment'
        ];
    }

    /**
     * Get Comment By Post
     */
    public function testGetCommentByPost()
    {
        $response = $this->call('GET', '/api/post/' . $this->getPostId() . '/comments?api_token=' . $this->getAccessToken(), [
            'limit' => 10,
            'page'  => 1
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Get Specific Comment
     */
    public function testGetComment()
    {
        $response = $this->call('GET', '/api/comment/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }


    /**
     * Comment Create
     */
    public function testCommentCreate()
    {
        $response = $this->json(
            'POST',
            '/api/comment/create?api_token=' . $this->getAccessToken(),
            $this->getCreateComment());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Create Comment By Post
     */
    public function testCommentCreateByPost()
    {
        $response = $this->json(
            'POST',
            '/api/post/'.$this->getPostId().'/comment/new?api_token=' . $this->getAccessToken(),
            $this->getCreateComment());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Update Comment
     */
    public function testCommentUpdate()
    {
        $response = $this->json('PUT',
            '/api/comment/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken(),
            $this->getUpdated());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Update Comment By Post
     */
    public function testCommentUpdateByPost()
    {
        $response = $this->json('PUT',
            '/api/post/'.$this->getPostId().'/comment/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken(),
            $this->getUpdated());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Destroy Comment
     */
    public function testCommentDestroy() {
        $response = $this->call('DELETE', '/api/comment/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getLastId()->user_id
        ]);
        $response->assertJson(['meta' => ['success' => true]]);
    }

    /**
     * Destroy Comment By Post
     */
    public function testCommentDestroyByPost() {
        $response = $this->call('DELETE', '/api/post/'.$this->getPostId().'/comment/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getLastId()->user_id
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
