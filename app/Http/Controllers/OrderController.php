<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Events\OrderCreated;

use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiRespone;
use Illuminate\Http\Response;


class OrderController extends Controller
{
    use ApiRespone;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('products')->paginate(8);
        return OrderResource::collection($orders)
        ->additional(['message' => 'Orders Retrieved Successfully']);    }

    /**
     * Store a newly created resource in storage.
     */
  

    


    public function store(StoreOrderRequest $request)
    {
        $data = $request->all();
        $total_price = $data['total_price'] * (1 + $data['tax']) * (1 + $data['service_fee']) - ($data['discount'] ?? 0);
        $data['total_price'] = $total_price;

        $order= Order::create($data);
    
        $products = $request->input('products');
    
        foreach ($products as $product) {
            $order->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'total_price' => $product['total_price'],
                'status' => 'complete',
            ]);
        }
        event(new OrderCreated($order));


        if ($order){
            return $this->success('order added successfully',Response::HTTP_CREATED);

         }
         return $this->error('order not added ',Response::HTTP_CONFLICT);

    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order=Order::with('products')->find($id);

        if(!$order){
            return $this->error('order not Exist');
        }
        return $this->sendData('',new OrderResource($order));  
    
    }

    public function prepareOrders()
{
    $orders = Order::with('products')->where('status', 'prepare')->get();

    return $this->sendData('', OrderResource::collection($orders));
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
}
