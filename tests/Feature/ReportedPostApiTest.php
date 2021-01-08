<?php

namespace Tests\Feature;

use App\Pithos\post\PostsRepository;
use App\Pithos\post\ReportedPostsRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportedPostApiTest extends TestCase
{
    private $loginId = 1;

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

    public function getFirstId()
    {
        $post = (new PostsRepository())->getFirst();
        return $post->id;
    }

    public function getLastReport()
    {
        $report = (new ReportedPostsRepository())->getLast();
        return $report;
    }

    /**
     * Feature Test for reported Post
     */
    public function testReportPost()
    {
        $response = $this->call('POST', '/api/post/' . $this->getFirstId() . '/reportPost?api_token=' . $this->getAccessToken(), [
            'reporter_id' => $this->getOneUserId(),
            'reason'      => 'including inappropriate content'
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);

    }

    public function testPostComplaint()
    {
        $response = $this->call('POST', '/api/complaints?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getOneUserId(),
            'post_id' => $this->getFirstId(),
            'reason'  => 'including inappropriate content testing'
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);

    }


    public function testComplaintResolve()
    {
        $response = $this->call('POST', '/api/complaints/resolve?api_token=' . $this->getAccessToken(), [
            'action' => 'delete_post',
            'post_id' => $this->getFirstId()
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);

    }

    public function testComplaintsAll()
    {
        $response = $this->call('GET', '/api/complaints?api_token=' . $this->getAccessToken(), [
            'limit'       => 10,
            'page'        => 1,
            'reporter_id' => $this->getLastReport()->reporter_id
        ]);
        $data = $this->parseJson($response);
        $this->assertIsJson($data);

    }

    public function testReportGet()
    {
        $response = $this->call('GET', '/api/complaints/' . $this->getLastReport()->id . '?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getOneUserId()
        ]);
        $data = $this->parseJson($response);
        $this->assertIsJson($data);

    }

    public function testReportDestroy()
    {
        $response = $this->call('DELETE', '/api/complaints/' . $this->getLastReport()->id . '?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getLastReport()->reporter_id
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

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
