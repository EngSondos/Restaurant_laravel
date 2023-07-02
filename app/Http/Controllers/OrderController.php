<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Table\TableResource;

use App\Events\OrderCreated;

use App\Models\Order;
use App\Models\Table;
use App\Http\Services\Media;

use App\Models\Product;
use App\Traits\ApiRespone;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;


class OrderController extends Controller
{
    use ApiRespone;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::whereNotIn('status',['served','paid'])
        ->with('products')->paginate(8);
        return OrderResource::collection($orders)
        ->additional(['message' => 'Orders Retrieved Successfully']);  
    
    }

    /**
     * Store a newly created resource in storage.
     */
  

    


    public function store(StoreOrderRequest $request)
    {
        $data = $request->all();
        $tax = isset($data['tax']) ? $data['tax'] : 0.14;
       $service_fee = isset($data['service_fee']) ? $data['service_fee'] : 0.12;

        $total_price = $request->input('total_price') * (1 + $tax) * (1 + $service_fee) - ($data['discount'] ?? 0);
        $data['total_price'] = $total_price;

    
        $order= Order::create($data);
    
    
        $products = $request->input('products');
    
        foreach ($products as $product) {
         
            $order->products()->attach($product['id'], [
                'quantity' => $product['quantity'],
                'total_price' => $product['total_price'],
                'status' => 'progress',
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

//     public function prepareOrders()
// {
//     $orders = Order::with('products')->where('status', 'prepare')->get();

//     return $this->sendData('', OrderResource::collection($orders));
// }

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

    public function getTablesWithPreparedOrders()
    {
        $tableIds = Order::where('status', '=', 'Prepare')->pluck('table_id')->unique();
    
        if ($tableIds->isEmpty()) {
            return $this->error('No tables found with prepared orders');
        }
    
        $tables = Table::whereIn('id', $tableIds)->get();
    
        return TableResource::collection($tables)
            ->additional(['message' => 'Tables with prepared orders retrieved successfully']);
    }


    public function getOrderTable($table_id)
    {

        try{
        $table = Table::findOrFail($table_id);
        } catch (ModelNotFoundException $exception){
            return $this->error('Table not found', Response::HTTP_NOT_FOUND);
        }
    
        $prepareOrders = Order::where('table_id', '=', $table_id)->where('status','=','Prepare')->with('products')->get();
    
        if ($prepareOrders->isEmpty()) {
            return $this->error('No prepared orders found for this table');
        }
    
        return OrderResource::collection($prepareOrders)
            ->additional(['message' => 'Prepared orders for table '.$table_id.' retrieved successfully']);
    }


        public function UpdateOrderStatus(int $order_id,string $new_status)
        {
            try{
                $order = Order::findOrFail($order_id);
                } catch (ModelNotFoundException $exception){
                    return $this->error('Order not found', Response::HTTP_NOT_FOUND);
                }
                $valid_statuses = ['Pending', 'Accepted', 'Prepare', 'Complete', 'Served', 'Canceled', 'Paid'];
                if (!in_array($new_status, $valid_statuses)) {
                    return response()->json(['message' => 'Invalid status'], Response::HTTP_BAD_REQUEST);
                }
                $order->update(['status'=>$new_status]);
                return $this->success('Order status updated successfully');
        }

}
