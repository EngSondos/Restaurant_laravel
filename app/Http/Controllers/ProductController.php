<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductIngredientRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Services\Media;
use App\Models\Category;
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
    public function store(StoreProductRequest $request)
    {
        // info product info -> ingrdents[id => quntity this product need] , extra [ids]

        $data = $request->except('ingredients');
       $this->checkCategory($request->category_id);

        $product = new Product;
        $product->name = $data['name'];
        $product->category_id = $request->category_id;
        $product->total_price = $request->total_price;
        $product->extra=json_encode($request->extra);

        $image_name =  Media::upload($request->image,'products');
        $product->image = $image_name;

        $product->save();
       if(  $this->addIngredientToProduct($request,$product)){
        return $this->success('Product Add Succesfully');
       }
       return $this->error('Product Not Add Succesfully');

    }

    private function checkCategory(int $categoryId)
    {
        $category=Category::find($categoryId);
        if(!$category->status && empty($category->products[0]))
        {
            $category->status =1;
            $category->save();
        }
    }

    private function Checkstatus($product)
    {
        foreach( $product->Ingredients as $ingredient_pro)
        {

                if($ingredient_pro->status==0)
                {
                    $product->status=0;
                }
                $product->save();

       }
    }

    private function addIngredientToProduct($request,$product)
    {

        $ingredientsData = [];
        foreach ($request->ingredients as $ingredientData) {
            $ingredientId = $ingredientData['id'];
            $quantity = $ingredientData['quantity'];
            $total = $ingredientData['quantity'] * $ingredientData['price'];
            $price = $ingredientData['price'];
            $ingredientsData[$ingredientId] = compact('quantity', 'total', 'price');
        }
        return  $product->ingredients()->sync($ingredientsData);
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
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::find($id);
        if(!$product){
            return $this->error('This Product Not Exist');
        }

        $data = $request->except('image');
        if($request->hasFile('image'))
        {
            Media::delete($product->image);
            $path=  Media::upload($request->image,'products');
            $data['image']=$path;
        }
        $this->Checkstatus($product);
       if( $product->update($data))
       {
        return $this->success('Product Update Succesfully');
       }

    }

    public function updateIngredientsForProduct(UpdateProductIngredientRequest $request,int $id)
    {
        $product = Product::find($id);
        if(  $this->addIngredientToProduct($request,$product)){
            return $this->success('Product Ingredients Updated Succesfully');
           }
           return $this->error('Product Ingredients Not Update Succesfully');

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

    public function search(Request $request)
    {
        $keyword =$request->input('keyword','');
        return $this->sendData('',Product::where('name','like',"%$keyword%")->paginate(8));
    }

    public function getActiveProducts()
    {
        return $this->sendData('',Product::where('status','=',1)->paginate(8));
    }


    //filter by price

    public function getByPrice()
    {

    }
}
