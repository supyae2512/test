<?php
//
//namespace Tests\Feature;
//
//use App\Pithos\merchant\MerchantRepository;
//use Illuminate\Support\Facades\Auth;
//use Tests\TestCase;
//
//class MerchantApiTest extends TestCase
//{
//    private $loginId = 1;
//    private $create_merchant = [
//        'email'    => 'mail1@gmail.com',
//        'password' => 'password',
//        'active'   => 'true'
//    ];
//
//    private $update_merchant = [
//        'email'    => 'mail1_updated@gmail.com',
//        'password' => 'password',
//        'active'   => 'true'
//    ];
//
//    /**
//     * A basic test example.
//     *
//     * @return void
//     */
//    public function testExample()
//    {
//        $this->assertTrue(true);
//    }
//
//    /**
//     * @return integer
//     */
//    public function getLastId()
//    {
//        $merchant = (new MerchantRepository())->getLast();
//        return $merchant->id;
//    }
//
//    /**
//     * get sample token to test for endpoint need to be authorized;
//     *
//     * @return string
//     */
//    public function getAccessToken()
//    {
//        $users = Auth::guard('web')->loginUsingId($this->loginId);
//        return $users->api_token;
//    }
//
//    /**
//     * Merchant Account Registration
//     */
//    public function testMerchantRegister()
//    {
//        $response = $this->json('POST', '/api/merchant/register', $this->create_merchant);
//    }
//
//    /**
//     * Merchant Account Update
//     * [note] /merchant/update/{merchant_id}
//     */
//    public function testMerchantUpdate()
//    {
//        $this->json('POST',
//            '/api/merchant/update/' . $this->getLastId() . '?api_token=' . $this->getAccessToken(),
//            $this->update_merchant);
//    }
//
//    /**
//     * Merchant Account Destroy
//     * [note] /merchant/destroy/{merchant_id}
//     */
//    public function testMerchantDestroy()
//    {
//        $this->call('DELETE', '/api/merchant/destroy/' . $this->getLastId() . '?api_token=' . $this->getAccessToken());
//
//    }
//
//}
