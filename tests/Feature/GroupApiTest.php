<?php

namespace Tests\Feature;

use App\Pithos\group\GroupsRepository;
use App\Pithos\post\PostsRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupApiTest extends TestCase
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

    /**
     * @return array
     */
    public function getCreateGroup()
    {
        return [
            'merchant_id' => Auth::guard('web')->loginUsingId($this->loginId)->id,
            'name' => "Testing Group"
        ];
    }

    /**
     * @return array
     */
    public function getUpdateGroup()
    {
        return [
            'merchant_id' => Auth::guard('web')->loginUsingId($this->loginId)->id,
            'name' => "Testing Updated Group"
        ];
    }

    /**
     * @return integer
     */
    public function getLastId()
    {
        $category = (new GroupsRepository())->getLast();
        return $category->id;
    }

    public function getOneUserId()
    {
        $user = (new PostsRepository())->getLast();
        return $user->creator_id;
    }

    /**
     * Group Create
     * [note] /group/create
     */
    public function testGroupCreate()
    {
        $response = $this->json(
            'POST',
            '/api/group/create?api_token=' . $this->getAccessToken(),
            $this->getCreateGroup());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }


    /**
     * Group Update
     * [note] /group/update/{group_id}
     */
    public function testGroupUpdate()
    {
        $response = $this->json('POST',
            '/api/group/update/' . $this->getLastId() . '?api_token=' . $this->getAccessToken(),
            $this->getUpdateGroup());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Group Destroy
     * [note] /group/destroy/{group_id}
     */
    public function testGroupDestroy()
    {
        $response = $this->call('DELETE', '/api/group/destroy/' . $this->getLastId() . '?api_token=' . $this->getAccessToken());
        $response->assertJson(['meta' => ['success' => true]]);

    }

    public function testGroupSearch()
    {
        $response = $this->call('GET', '/api/category/search/' . $this->searchkeyword . '?api_token=' . $this->getAccessToken(), [
            'user_id' => $this->getOneUserId()
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
