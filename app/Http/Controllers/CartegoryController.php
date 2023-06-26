<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Media;
use App\Models\Category;

class CartegoryController extends Controller
{
    /*
    ** Display All Categories
    */
    public static function index()
    {
        $categories = Category::paginate();
        return CategoryResource::collection($categories)
        ->additional(['message' => 'All Categories has been retrieved']);
    }

    /*
    ** Display Specific Category
    */
    public static function show($name)
    {
        return ; //return only the data of the specified category
    }

    /*
    ** Create Category
    */
    public static function store(StoreCategoryRequest $req)
    {
        if($req->validated())
        {
            $imageFile = $req->image;

            $dir = 'images\categories';

            Media::upload($imageFile,$dir);

            $data = $req->except('image');

            $data = $req->validated();
                
            $data['image'] = $req->image->getClientOriginalName(); //errory

            if(DB::table('categories')->insert($data)){
                return $data; // return the list of categories
            }
        }
    }

    /*
    ** Edit Category
    */
    public static function edit($id)
    {
        $category = DB::table('categories')->where('id',$id)->first();
        if(is_null($category)){
            //return Invalid Id as a message
        }
        
        return compact('$category'); //return the data of this category
    }

    /*
    ** Update Category
    */
    public static function update(UpdateCategoryRequest $req , $id)
    {
        return ; //either error or redirection to the list of categories
    }

    /*
    ** Delete Category
    */
    public static function destroy($id)
    {
        return ; //either error or redirection to the list of categories
    }

}
