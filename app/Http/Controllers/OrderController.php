<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function addOrder(Request $request)   {
        
        $Order = new Order();
        $Order->job_id = \Request::input('job_id');
        $Order->status  = \Request::input('status');
        $Order->save();
        return response()->json(['message'=>'Add Order successfully']);
    }

    function updateOrder()    {

        $id = \Request::input('id');
        $Order = Order::where('id',$id)
            ->update([
                'job_id' => \Request::input('job_id'),
                'status' => \Request::input('status')
            ]);

        return response()->json(['Message' => 'Order Updated']);

    }

    public function getOrders()   {

        $Order = Order::get();
        return response()->json(['jobs' => $Order]);
    }

    function deleteOrder($id)    {
        
        $Order = Order::where('id',$id)->delete();

        return response()->json(['message'=>'delete Order successfully']);

    }

    public function getOrderById($id)     {
        
        $Order = Order::
        where('id',$id)->get();
        return response()->json(['Order' => $Order]);

    }
}
