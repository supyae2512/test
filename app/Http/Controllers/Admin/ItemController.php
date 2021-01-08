<?php

namespace App\Http\Controllers\Admin;

use App\myapp\ItemRepository;
use App\Pithos\common\Constant;
use App\Pithos\post\Posts;
use App\Pithos\post\PostsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Wilgucki\Csv\Reader;

class ItemController extends Controller
{
    private $repository;

    private $title;
    private $route;
    private $list_view;
    private $detail_view;
    private $list_view_column;


    public function __construct(ItemRepository $itemRepository, Reader $reader)
    {
        $this->repository = $itemRepository;
        $this->title = 'Items';
        $this->route = 'items';
        $this->list_view = 'admin.items.index';
        $this->list_view_column = [
            'name'        => 'Name',
            'description' => 'Description',
            'image' => "Image",
            'price' => "Price"
        ];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page = Constant::DEFAULT_PAGE;
        $header_title = 'Our Products';
        $id = Auth::guard('web')->user()->id;
        $data = $this->repository->getByPaginate($page);

        return view($this->list_view)->with([
            'title'        => $this->title,
            'header_title' => $header_title,
            'route'        => $this->route,
            'column' => $this->list_view_column,
            'data'         => $data,
        ]);
    }

    /**
     * Create Group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $input = Input::all();
        $required_params = ['name'];

        if ($response = $this->checkMissingParams($required_params)) {
            return $response;
        }

        $filter_input_data = $this->repository->getFillables();

        $group_data = [];
        foreach ($input as $key => $value) {
            if (in_array($key, $filter_input_data)) {
                $group_data[$key] = $value;
            }
        }
        try {
            $respond_data = $this->repository->create($group_data);

            if ($respond_data) {

                $status = getStatusCode('created');

                return $this->setStatusCode($status['code'])
                    ->respondWithSuccess(Lang::get('message.add-success'), $respond_data);
            }
            $status = getStatusCode('not-implemented');
            return $this->setStatusCode($status['code'])->respondWithError(Lang::get('message.add-fail'));

        } catch (\Exception $e) {
            return $this->setStatusCode(500)->respondWithError($e->getMessage());
        }
    }

    /**
     * Update Group
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $input = Input::all();
        $filter_input_data = $this->repository->getFillables();

        $group_data = [];
        foreach ($input as $key => $value) {
            if (in_array($key, $filter_input_data)) {
                $group_data[$key] = $value;
            }
        }

        try {
            $group_data['merchant_id'] = Auth::guard('api')->user()->id;
            $respond_data = $this->repository->update($group_data, $id);

            if ($respond_data) {
                $respond_data = [
                    'group_id' => $id,
                ];
                $status = getStatusCode('ok');
                return $this->setStatusCode($status['code'])
                    ->respondWithSuccess(Lang::get('message.update-success'), $respond_data);
            }
            $status = getStatusCode('bad-request');
            return $this->setStatusCode($status['code'])
                ->respondWithError(Lang::get('message.update-fail'), ['group_id' => $id]);

        } catch (\Exception $e) {
            return $this->setStatusCode(500)->respondWithError($e->getMessage());
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
