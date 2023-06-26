<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Traits\ApiRespone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Media;
use App\Models\Category;

class CartegoryController extends Controller
{
    use ApiRespone;
    /*
    ** Display All Categories
    */
    public function index()  //OK
    {
        $categories = Category::paginate();
        return CategoryResource::collection($categories)
        ->additional(['message' => 'All Categories has been retrieved']);
    }

    /*
    ** Display Specific Category
    */
    // public static function show(Request $req) //OK
    // {
    //     $categories = Category::select(['name','image'])->get();
    //     if($req->name){

    //     }
    //     return ; //return only the data of the specified category
    // }

    /*
    ** Create Category
    */
    public function store(StoreCategoryRequest $req) //OK
    {
        if($req->validated())
        {
            $imageFile = $req->image;

            $dir = 'images\categories';

            Media::upload($imageFile,$dir);

            $data = $req->except('image');

            $data = $req->validated();
                
            $data['image'] = $req->image->getClientOriginalName(); //the hashname of the image is not working so i use the original name of the image

            $data['created_at'] = now();

            if(DB::table('categories')->insert($data)){
                return $data; // return the list of categories
            }
        }
    }

    /*
    ** Edit Category
    */
    public function edit(Category $category)
    {        
        return $this->sendData('',new CategoryResource($category));  
    }

    /*
    ** Update Category
    */

    public function update(UpdateCategoryRequest $req , $id)
    {
        return ; //either error or redirection to the list of categories
    }

    /*
    ** Delete Category
    */
    public function destroy($id)
    {
        return ; //either error or redirection to the list of categories
    }

}
