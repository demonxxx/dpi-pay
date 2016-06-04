<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Order extends Model
{
    protected $table = 'orders';
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        
    ];

    protected $hidden = [

    ];

    public function user(){
    	$this->belongsTo('App\User','users');
    }

    public function shippers(){
        $this->belongsToMany('App\User','order_shipper');
    }

    public function orderShippers(){
        return $this->hasMany('App\Order_shipper');
    }
    
    public function get_all_orders($post, $user_id) {
        $builder = DB::table("orders");
        $builder->select("orders.id","shops.code as shop_code","shippers.code as shipper_code", "orders.code", "street_from.name as street_from_name",
                        "district_from.name as district_from_name", "street_to.name as street_to_name", "district_to.name as district_to_name")
                ->leftJoin("shops", "orders.user_id", "=", "shops.user_id")
                ->leftJoin("shippers", "orders.shipper_id", "=", "shippers.user_id")
                ->leftJoin("administrative_units as street_from", "orders.street_from", "=", "street_from.id")
                ->leftJoin("administrative_units as district_from", "orders.district_from", "=", "district_from.id")
                ->leftJoin("administrative_units as street_to", "orders.street_to", "=", "street_to.id")
                ->leftJoin("administrative_units as district_to", "orders.district_to", "=", "district_to.id")
                ->where('orders.deleted_at')
                ->where("orders.user_id", $user_id);
        $search_params = $post['searchParams'];
        $this->table_condition($builder, $search_params);
        $builder->skip($post["iDisplayStart"])->take($post["iDisplayLength"])
                ->orderBy($post["orderBy"], $post["orderSort"]);
        $data = $builder->get();
        return $data;
    }

    public function count_all($post, $user_id) {
        $builder = DB::table("orders");
        $builder->select("orders.id")
                ->leftJoin("shops", "orders.user_id", "=", "shops.user_id")
                ->leftJoin("shippers", "orders.shipper_id", "=", "shippers.user_id")
                ->leftJoin("administrative_units as street_from", "orders.street_from", "=", "street_from.id")
                ->leftJoin("administrative_units as district_from", "orders.district_from", "=", "district_from.id")
                ->leftJoin("administrative_units as street_to", "orders.street_to", "=", "street_to.id")
                ->leftJoin("administrative_units as district_to", "orders.district_to", "=", "district_to.id")
                ->where("orders.user_id", $user_id);
        $search_params = $post['searchParams'];
        $this->table_condition($builder, $search_params);
        $count = $builder->count();
        return $count;
    }
    
    public function table_condition($builder, $search_params) {
        if (!empty($search_params)) {
            if (array_key_exists('code', $search_params)) {
                $builder->where('orders.code', 'like', '%' . $search_params['code'] . '%');
            }
            if (array_key_exists('shop_code', $search_params)) {
                $builder->where('shops.code', 'like', '%' . $search_params['shop_code'] . '%');
            }
            if (array_key_exists('shipper_code', $search_params)) {
                $builder->where('shippers.code', 'like', '%' . $search_params['shipper_code'] . '%');
            }
            if (array_key_exists('street_from', $search_params)) {
                $builder->where('orders.street_from', 'like', '%' . $search_params['street_from'] . '%');
            }
            if (array_key_exists('street_to', $search_params)) {
                $builder->where('orders.street_to', 'like', '%' . $search_params['street_to'] . '%');
            }
            if (array_key_exists('district_from', $search_params)) {
                $builder->where('orders.district_from', 'like', '%' . $search_params['district_from'] . '%');
            }
            if (array_key_exists('district_to', $search_params)) {
                $builder->where('orders.district_to', 'like', '%' . $search_params['district_to'] . '%');
            }
        }
        return $builder;
    }
}
