<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Shipper extends Model {

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = 'shippers';

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function get_all_shippers($post) {
        $builder = DB::table("shippers");
        $builder->select(array("users.id", "shippers.code", "users.name", "users.email", "users.identity_card", "shippers.average_score",
                    "shippers.isActive", "shippers.id as shipper_id", "shippers.profile_status", "administrative_units.name as home_district", "users.phone_number", DB::raw('COUNT(shipper_order_histories.id) as count_order'),))
                ->join("users", "shippers.user_id", "=", "users.id")
                ->leftjoin("administrative_units", "shippers.home_district_id", "=", "administrative_units.id")
                ->leftjoin("shipper_order_histories", "shipper_order_histories.shipper_id", "=", "users.id");
        $search_params = $post['searchParams'];
        $this->table_condition($builder, $search_params);
        $builder->skip($post["iDisplayStart"])->take($post["iDisplayLength"])
                ->orderBy($post["orderBy"], $post["orderSort"])
                ->groupBy("users.id");
        $data = $builder->get();
        foreach ($data AS $key => $value) {
            $success_orders = DB::table("orders")
                    ->where("shipper_id", "=", $value->shipper_id)
                    ->where("shipper_id", "=", ORDER_SHIP_SUCCESS)
                    ->count();
            $payed_orders = DB::table("orders")
                    ->where("shipper_id", "=", $value->shipper_id)
                    ->where("shipper_id", "=", ORDER_PAYED)
                    ->count();
            $payed_ship_success = $success_orders + $payed_orders;

            $return_orders = DB::table("orders")
                    ->where("shipper_id", "=", $value->shipper_id)
                    ->where("shipper_id", "=", ORDER_RETURN_ITEMS)
                    ->count();
            $returning_order = DB::table("orders")
                    ->where("shipper_id", "=", $value->shipper_id)
                    ->where("shipper_id", "=", ORDER_RETURNING)
                    ->count();
            $cancel_order = DB::table("orders")
                    ->where("shipper_id", "=", $value->shipper_id)
                    ->where("shipper_id", "=", ORDER_SHOP_CANCEL)
                    ->count();
            $return_returning_cancel = $return_orders + $returning_order + $cancel_order;

            $data[$key]->payed_ship_success = $payed_ship_success;
            $data[$key]->return_returning_cancel = $return_returning_cancel;
        }
        return $data;
    }

    public function count_all($post) {
        $builder = DB::table("shippers");
        $builder->select("users.id")
                ->join("users", "shippers.user_id", "=", "users.id");
        $search_params = $post['searchParams'];
        $this->table_condition($builder, $search_params);
        $count = $builder->count();
        return $count;
    }

    public function table_condition($builder, $search_params) {
        if (!empty($search_params)) {
            if (array_key_exists('code', $search_params)) {
                $builder->where('shippers.code', 'like', '%' . $search_params['code'] . '%');
            }
            if (array_key_exists('name', $search_params)) {
                $builder->where('users.name', 'like', '%' . $search_params['name'] . '%');
            }
            if (array_key_exists('email', $search_params)) {
                $builder->where('users.email', 'like', '%' . $search_params['email'] . '%');
            }
            if (array_key_exists('identity_card', $search_params)) {
                $builder->where('users.identity_card', 'like', '%' . $search_params['identity_card'] . '%');
            }
            if (array_key_exists('home_number', $search_params)) {
                $builder->where('shippers.home_number', 'like', '%' . $search_params['home_number'] . '%');
            }
            if (array_key_exists('home_ward', $search_params)) {
                $builder->where('shippers.home_ward', 'like', '%' . $search_params['home_ward'] . '%');
            }
            if (array_key_exists('home_district', $search_params)) {
                $builder->where('shippers.home_district_id', '=', $search_params['home_district']);
            }
            if (array_key_exists('home_city', $search_params)) {
                $builder->where('shippers.home_city', 'like', '%' . $search_params['home_city'] . '%');
            }
            if (array_key_exists('phone_number', $search_params)) {
                $builder->where('users.phone_number', 'like', '%' . $search_params['phone_number'] . '%');
            }
        }
        return $builder;
    }

}
