<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\OrderProductResource;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\Order;

use App\Traits\ApiRespone;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;




class OrderProductController extends Controller
{
    use ApiRespone;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderProduct = OrderProduct::find($id);

        if(!$orderProduct){
            return $this->error('orderProduct not Exit');
        }
        return $this->sendData('',new OrderProductResource($orderProduct));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

 
    public function cancelOrderProducts(int $orderId)
    {
        $orderProducts = OrderProduct::where('order_id', $orderId)->get();
        foreach ($orderProducts as $orderProduct) {
            $orderProduct->status = 'Cancel';
            $orderProduct->save();
        }

        return response()->json(['message' => 'Order Products status updated to Canceled']);
    }

    public function completeOrderProducts(int $orderId)
    {
        $orderProducts = OrderProduct::where('order_id', $orderId)->get();
        foreach ($orderProducts as $orderProduct) {
            $orderProduct->status = 'Complete';
            $orderProduct->save();
        }

        return response()->json(['message' => 'Order Products status updated to Complete']);
    }
     






    }





