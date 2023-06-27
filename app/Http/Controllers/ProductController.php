<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Traits\ApiRespone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    use ApiRespone;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with('ingredients')->paginate(8);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // info product info -> ingrdents[id => quntity this product need] , extra [ids]

        $data = $request->except('ingredients');
        $product = new Product;
        $product->name = $data['name'];
        $product->image = $request->image;
        $product->category_id = $request->category_id;
        $product->total_price = $request->total_price;
        $product->extra=json_encode($request->extra);
        $product->save();
        $ingredientsarr =  $this->addIngredientToProduct($request);

       if( $product->ingredients()->sync($ingredientsarr)){
        return $this->success('Product Add Succesfully');
       }
       return $this->error('Product Not Add Succesfully');

    }

    private function addIngredientToProduct($request)
    {

        $ingredientsData = [];
        foreach ($request->ingredients as $ingredientData) {
            $ingredientId = $ingredientData['id'];
            $quantity = $ingredientData['quantity'];
            $total = $ingredientData['quantity'] * $ingredientData['price'];
            $price = $ingredientData['price'];
            $ingredientsData[$ingredientId] = compact('quantity', 'total', 'price');
        }
        return $ingredientsData;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = Product::with('ingredients')->find($id);
        if(!$product){
            return $this->error('This Product Not Exist');
        }
        return $this->sendData('',$product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $product = Product::find($id);
        if(!$product){
            return $this->error('This Product Not Exist');
        }
        $data = $request->all();
       if( $product->update($data)){
        return $this->success('Product Update Succesfully');

       }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function changeStatus(int $id)
    {
        $porduct = Product::find($id);

        if (!$porduct) {
            return $this->error('This Ingredient Not Exist');
        }

        $porduct->status = !$porduct->status;
        if ($porduct->save()) {
            return $this->success("Ingredient Updated Successfully");
        } else {
            return $this->error('Ingredient Not Updated ', Response::HTTP_NOT_MODIFIED);
        }
    }
}
