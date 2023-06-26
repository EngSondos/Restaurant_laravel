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

use function PHPUnit\Framework\returnSelf;

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
    public function show(Request $req) //OK
    {
        $filtered = DB::table('categories')->
        select(['name','image'])->
        where('name','like',$req["name"].'%')->
        get();
        //check if the filtered array contains items or not
        return $filtered->first()?
        $this->sendData('',$filtered):
        $this->error('No category has this name');
    }

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
    public function edit(Category $category)  //OK
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
