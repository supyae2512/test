<?php

namespace Tests\Feature;

use App\Pithos\post\Posts;
use App\Pithos\post\PostsRepository;
use App\Pithos\rating\RatingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RatingApiTest extends TestCase
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

    public function getCreated()
    {
        return [
            'user_id'      => 1,
            'rating_value' => '3',
        ];
    }

    public function getUpdated()
    {
        return [
            'user_id' => 1,
            'comment' => 'updated comment'
        ];
    }

    public function getOnePostId()
    {
        $post = (new PostsRepository())->getLast();
        return $post->id;
    }


    /**
     * @return integer
     */
    public function getLastId()
    {
        $rating = (new RatingRepository())->getLast();
        return $rating->id;
    }

    /**
     * Post Create
     * [note] /post/create
     */
    public function testRatingCreate()
    {
        $response = $this->json(
            'POST',
            '/api/post/' . $this->getOnePostId() . '/rating?api_token=' . $this->getAccessToken(),
            $this->getCreated());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Post Update
     * [note] /post/update/{post_id}
     */
    public function testRatingUpdate()
    {
        $response = $this->json('PUT',
            '/api/rating/' . $this->getLastId() . '?api_token=' . $this->getAccessToken(),
            $this->getUpdated());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Post Destroy
     * [note] /post/destroy/{post_id}
     */
    public function testRatingDestroy()
    {
        $response = $this->call('DELETE', '/api/rating/' . $this->getLastId() . '?api_token=' . $this->getAccessToken());
        $response->assertJson(['meta' => ['success' => true]]);

    }

    public function testRatingAll()
    {
        $response = $this->call('GET', '/api/post/' . $this->getOnePostId() . '/rating?api_token=' . $this->getAccessToken(), [
            'limit' => 10,
            'page'  => 1
        ]);

        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    public function testRating()
    {
        $response = $this->call('GET', '/api/rating/' . $this->getOnePostId() . '?api_token=' . $this->getAccessToken());

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
