<?php

namespace Tests\Feature;

use App\Pithos\category\CategoriesRepository;
use App\Pithos\post\PostsRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryApiTest extends TestCase
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
    public function getCreateCategory()
    {
        return [
            'merchant_id' => Auth::guard('web')->loginUsingId($this->loginId)->id,
            'name' => "Testing Category"
        ];
    }

    /**
     * @return array
     */
    public function getUpdateCategory()
    {
        return [
            'merchant_id' => Auth::guard('web')->loginUsingId($this->loginId)->id,
            'name' => "Testing Updated Category"
        ];
    }

    /**
     * @return integer
     */
    public function getLastId()
    {
        $category = (new CategoriesRepository())->getLast();
        return $category->id;
    }

    public function getOneUserId()
    {
        $user = (new PostsRepository())->getLast();
        return $user->creator_id;
    }


    /**
     * Category Create
     * [note] /category/create
     */
    public function testCategoryCreate()
    {
        $response = $this->json(
            'POST',
            '/api/category/create?api_token=' . $this->getAccessToken(),
            $this->getCreateCategory());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }


    /**
     * Category Update
     * [note] /category/update/{category_id}
     */
    public function testCategoryUpdate()
    {
        $response = $this->json('POST',
            '/api/category/update/' . $this->getLastId() . '?api_token=' . $this->getAccessToken(),
            $this->getUpdateCategory());
        $data = $this->parseJson($response);
        $this->assertIsJson($data);
    }

    /**
     * Category Destroy
     * [note] /category/destroy/{category_id}
     */
    public function testCategoryDestroy()
    {
        $response = $this->call('DELETE', '/api/category/destroy/' . $this->getLastId() . '?api_token=' . $this->getAccessToken());

        $response->assertStatus(200);
    }

    public function testCategorySearch()
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
