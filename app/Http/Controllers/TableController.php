<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Http\Resources\Table\TableResource;
use App\Http\Resources\Order\OrderResource;
use Illuminate\Http\Response;
use App\Traits\ApiRespone;



class TableController extends Controller
{
    use ApiRespone;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::paginate(8);
        return TableResource::collection($tables)
        ->additional(['message' => 'Tables Retrieved Successfully']);
    }


    public function getAvailableTables()
{
    $status = 1; 

    $tables = Table::where('status', $status)->paginate(8);

    return TableResource::collection($tables)
        ->additional(['message' => 'Available Tables Retrieved Successfully']);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTableRequest $request)
    {
        $data= $request->all();

        if ( Table::create($data)){
            return $this->success('Table added successfully',Response::HTTP_CREATED);

         }
         return $this->error('Table not added ',Response::HTTP_CONFLICT);

       
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $table=Table::find($id);

        if(!$table){
            return $this->error('table not Exist');
        }
        return $this->sendData('',new TableResource($table));  
      }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, int $id)
    {
        $table=Table::find($id);
        
        if(!$table){
            return $this->error('table not Exist');
        }  
        $data = $request->all();
        if ($table->update($data)){
            return $this->success('table Updated successfully', Response::HTTP_OK);
           } 
           $this->error('table Not Updated '.Response::HTTP_NOT_MODIFIED);
    
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function changeStatus(int $id)
    {
        $table = Table::find($id);

        if (!$table) {
            return $this->error('Table not found.', Response::HTTP_NOT_FOUND);
        }
    
        $table->status = !$table->status;
        $table->save();
    
        return $this->success('Table status updated successfully.');
        
    }



    public function getOrders(int $id)
{
    $table = Table::findOrFail($id);

    if (!$table) {
        return $this->error('Table not found', Response::HTTP_NOT_FOUND);
    }

    $orders = $table->orders;

    if (!$orders) {
        return $this->error('No orders found for this table');
    }

    return OrderResource::collection($orders)
        ->additional(['message' => 'Orders retrieved successfully']);
}
}
