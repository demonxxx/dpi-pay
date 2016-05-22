<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Gate;
use App\User;
use App\Order;
use App\Order_shipper;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ShipperRest extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function findByLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "longitude" => "required",
            "latitude"  => "required",
        ]);
        if ($validator->fails()) {
            return Response::json(
                array(
                    'accept'   => 0,
                    'messages' => $validator->messages(),
                ),
                200
            );
        } else {
            $orders = Order::where('status', ORDER_PENDING)->get();
            foreach ($orders as $order) {
                $user = User::find($order->user_id);
                $user_result = array("name"         => $user->name,
                                     "email"        => $user->email,
                                     "phone_number" => $user->phone_number);
                $order->user = $user_result;
            }
            return Response::json(
                array(
                    'accept'   => 1,
                    'messages' => $orders->toArray(),
                ),
                200
            );
        }
    }

    public function takeOrder($id)
    {
        $order = Order::find($id);
        if (empty($order)) {
            return Response::json(
                array(
                    'accept'   => 1,
                    'messages' => "Order do not exist!",
                ),
                200
            );
        } else {
            if($order->status != ORDER_PENDING){
                return Response::json(
                    array(
                        'accept'   => 0,
                        'messages' => "Đơn hàng đã được nhận!",
                    ),
                    200
                );
            }
            $owner_tmp = User::find($order->user_id);
            if (!empty($owner_tmp)) {
                $owner = array("email"        => $owner_tmp->email,
                               "phone_number" => $owner_tmp->phone_number,
                               "id"           => $owner_tmp->id,
                );
            }
            $user = User::find(Auth::guard('api')->id());
            if (empty($user)) {
                return Response::json(
                    array(
                        'accept'   => 0,
                        'messages' => "Bạn không được nhận đơn hàng này.!",
                    ),
                    200
                );
            } else {
                $order->shipper_id = $user->id;
                $order->taken_order_at = Carbon::now();
                $order->status = ORDER_TAKEN_ORDER;
                $order->save();
                return Response::json(
                    array(
                        'accept'        => 1,
                        'order'         => $order->toArray(),
                    ),
                    200
                );
            }
        }
    }

    public function updateOrderStatusShipper(Request $request, $id)
    {
        $order = Order::find($id);
        if (empty($order)) {
            return Response::json(
                array(
                    'accept'   => 1,
                    'messages' => "Đơn hàng không tồn tại!",
                ),
                200
            );
        } else {
            $shipper_id = Auth::guard('api')->id();
            if ($shipper_id != $order->shipper_id) {
                return Response::json(array(
                    'accept'   => 0,
                    'messages' => 'Bạn không có quyền cập nhật trạng thái.',
                ),
                    200
                );
            } else {
                $validator = Validator::make($request->all(), [
                    "status"   => "required|numeric",
                ]);
                if ($validator->fails()) {
                    return Response::json(
                        array(
                            'accept'   => 0,
                            'messages' => $validator->messages(),
                        ),
                        200
                    );
                } else {
                    $status = $request->status;
                    $description = empty($request->description) ? null : $request->description;
                    if ($status == ORDER_TAKEN_ORDER){
                        $order->taken_order_at = Carbon::now()->toDateTimeString();
                    }else if($status == ORDER_TAKEN_ITEMS){
                        $order->taken_items_at = Carbon::now()->toDateTimeString();
                    }else if($status == ORDER_SHIPPING){
                        $order->cancel_at = Carbon::now()->toDateTimeString();
                    }else if($status == ORDER_SHIP_SUCCESS){
                        $order->ship_success_at = Carbon::now()->toDateTimeString();
                    }else if($status == ORDER_PAYED){
                        $order->payed_at = Carbon::now()->toDateTimeString();
                    }else if($status == ORDER_RETURN_ITEMS){
                        $order->return_items_at = Carbon::now()->toDateTimeString();
                    }
                    $order->status  = $status;
                    $order->description = $description;
                    $order->save();
                    return Response::json(
                        array(
                            'accept'   => 1,
                            'order'    => $order->toArray(),
                            'messages' => array("status" => $status, "description" => $description),
                        ),
                        200
                    );
                }
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
