<?php

namespace Tests\Feature;

use App\Pithos\post\CommentsRepository;
use App\Pithos\post\ReportedCommentsRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportedCommentTest extends TestCase
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
    public function getCreateCommentComplaint()
    {
        return [
            'comment_id' => $this->getCommentId(),
            'reporter_id' => $this->getOneUserId(),
            'reason' => "Testing Comment Complaint is here"
        ];
    }

    public function getCommentId() {
        $post = (new CommentsRepository())->getLast();
        return $post->id;
    }

    /**
     * @return integer
     */
    public function getLastId()
    {
        $comment_complaints = (new ReportedCommentsRepository())->getLast();
        return $comment_complaints;
    }

    public function getOneUserId()
    {
        $user = (new ReportedCommentsRepository())->getLast();
        return $user->reporter_id;
    }

    public function getUpdated()
    {
        return [
            'reporter_id' => $this->getOneUserId(),
            'reason' => 'Updated Comment Complaint'
        ];
    }

    /**
     * Get Comment By Post
     */
    public function testGetComplaintByCommentId()
    {
        $complaints = $this->getLastId();

        $response = $this->call('GET', '/api/report_comment/complaints?api_token=' . $this->getAccessToken(), [
            'limit' => 10,
            'page'  => 1,
            'comment_id' => $complaints->comment_id
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Get Specific Comment
     */
    public function testGetCommentComplaint()
    {
        $response = $this->call('GET', '/api/report_comment/complaint/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken());

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }


    /**
     * Comment Create
     */
    public function testCommentComplaintCreate()
    {
        $response = $this->json(
            'POST',
            '/api/report_comment/report?api_token=' . $this->getAccessToken(),
            $this->getCreateCommentComplaint());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Update Comment
     */
    public function testCommentComplaintUpdate()
    {
        $response = $this->json('PUT',
            '/api/report_comment/report/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken(),
            $this->getUpdated());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Destroy Comment
     */
    public function testCommentComplaintDestroy() {
        $response = $this->call('DELETE', '/api/report_comment/report/' . $this->getLastId()->id . '?api_token=' . $this->getAccessToken(), [
            'reporter_id' => $this->getLastId()->reporter_id
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
