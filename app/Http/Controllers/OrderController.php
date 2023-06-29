<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;

use App\Models\Order;
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
        $orders = Order::paginate(8);
        return OrderResource::collection($orders)
        ->additional(['message' => 'Orders Retrieved Successfully']);    }

    /**
     * Store a newly created resource in storage.
     */
  

    


    public function store(StoreOrderRequest $request)
    {
        $data = $request->all();
        $order= Order::create($data);
    
        $products = $request->input('products');
    
        foreach ($products as $product) {
            $order->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'total_price' => $product['total_price'],
                'status' => 'complete',
            ]);
        }

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
        //
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
