<?php

namespace App\Http\Controllers\Admin;

use App\myapp\CartRepository;
use App\myapp\ItemRepository;
use App\myapp\Items;
use App\Pithos\common\Constant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Wilgucki\Csv\Reader;

class CartController extends Controller
{
    private $repository;

    private $route;
    
    public function __construct(CartRepository $itemRepository)
    {
        $this->repository = $itemRepository;
        $this->route = "/";
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function addToCart($id)
    {
        try {
            //$id = Input::get('item_id');
            $product = Items::find($id);

            $cart = session()->get('cart');

            if(!$cart) {
                $cart = [
                    $id => [
                        "id" => $product->id,
                        "name" => $product->name,
                        "quantity" => 1,
                        "price" => $product->price,
                        "photo" => $product->photo
                    ]
                ];
                session()->put('cart', $cart);
                return Redirect::route($this->route)
                    ->with(session()->flash('msg', 'Product has been added'));
            }
            if(isset($cart[$id])) {
                $cart[$id]['quantity']++;
                session()->put('cart', $cart);
                return Redirect::route($this->route)
                    ->with(session()->flash('msg', 'Product has been added'));          }
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "photo" => $product->photo
            ];

            session()->put('cart', $cart);
            return Redirect::route($this->route)
                ->with(session()->flash('msg', 'Product has been added'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function checkout() {
        $cart = session()->get('cart');

        if(isset($cart) && sizeof($cart) > 0) {
            //
        }
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function destroy($id)
    {
        try {
            $this->repository->destroy($id);
            return Redirect::route($this->route)
                ->with(session()->flash('msg', Lang::get('message.destroy-success')));

        } catch (\Exception $e) {

            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
