<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartegoryController extends Controller
{
    /*
    ** Display All Categories
    */
    public static function index()
    {
        return ; //return all categories
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
            $imageName = $req->file('image')->hashName(); // to get the file name of the image and store it in the variable

            $req->file('image')->move(public_path('images\categories'),$imageName);

            $data = $req->except('image');

            $data = $req->validated();
            
            $data['image'] = $imageName;

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
        return ; //return the data of this category
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
