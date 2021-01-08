<?php

namespace App\Pithos\common;

use App\Pithos\merchant\MerchantRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GeneralRepository
{
    protected $model;

    /**
     * GeneralRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param       $id
     * @param array $relation
     *
     * @param       $auth_merchant
     *
     * @return array
     * @internal param array $option
     */
    public function getByRelation($id, array $relation, $auth_merchant)
    {
        $data = [];
        if (!empty($id)) {
            $data = $this->model->with($relation)
                ->where('id', $id)
                ->where('merchant_id', $auth_merchant)
                ->get();
        }
        return $data;
    }

    public function getByOption($relation, $page = 5, $order = 'ASC')
    {
        $data = $this->model->with($relation)->orderBy('id', $order)->paginate($page);
        return $data;
    }

    /**
     * @param     $relation
     * @param int $limit
     * @param int $order_by
     * @param     $order
     * @param     $auth_merchant
     *
     * @return LengthAwarePaginator
     */
    public function getByOptionPaginate($relation, $limit, $order_by, $order, $auth_merchant)
    {
        $data = $this->model
            ->with($relation)
            ->where('merchant_id', $auth_merchant)
            ->orderBy($order_by, $order)
            ->paginate($limit);
//        $slice_value = array_slice($data->toArray(), $offset, $limit);
//        $result = (new LengthAwarePaginator($slice_value, count($data), $limit ?: 4));
//        return $result;q
        return $data;

    }

    /**
     * get total quantity;
     * @return int
     */
    public function getQty()
    {
        $data = $this->model->all()->count();
        return $data;
    }

    /**
     * @param $name
     * @param $col_name
     *
     * @return array
     */
    public function getSearch($name, $col_name)
    {
        $data = $this->model->where($col_name, 'like', "%" . $name . "%")->get();
        return $data;
    }

    /**
     * @return array
     */
    public function getFillables()
    {
        return $this->model->getFillable();
    }

    public function getByPaginate($page)
    {
        $data = $this->model->paginate($page);
        return $data;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $data = $this->model
            ->orderBy('id', 'DESC')
            ->get();
        return $data;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function get($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get Data belongs to auth merchant
     *
     * @param $id
     * @param $auth_merchant
     *
     * @return mixed|static
     */
    public function getByAuth($id, $auth_merchant)
    {
        return $this->model->where('merchant_id', $auth_merchant)->find($id);
    }

    public function getByAllAuth($auth_merchant) {
        return $this->model->where('merchant_id', $auth_merchant)->get();
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param Model $model
     *
     * @return boolean
     */
    public function store(Model $model)
    {
        $this->model = $model;
        return $this->model->save();
    }

    /**
     * @param array $data
     * @param       $id
     *
     * @return boolean
     */
    public function update(array $data, $id)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function destroy($id)
    {
        if (!$this->model->find($id)) {
            return 0;
        }
        if ($this->model->find($id)->delete() == true) {
            return 1;
        }
        return 0;
    }

    /**
     * get last data from table for unit testing
     *
     * @return array
     */
    public function getLast()
    {
        $data = $this->model->orderBy('id', 'DESC')->first();
        return $data;
    }

    public function getFirst()
    {
        $data = $this->model->orderBy('id')->first();
        return $data;
    }

    public function softdelete($id)
    {

        if (!$this->model->find($id)) {
            return 0;
        }

        if ($this->model->find($id)->delete()) {
            return 1;
        } else {
            return 0;
        }
    }


}