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
use GuzzleHttp\Psr7\UploadedFile;

use function PHPUnit\Framework\returnSelf;

class CartegoryController extends Controller
{
    use ApiRespone;


    /*
    ** Display All Categories
    */
    public function index()
    {
        $categories = Category::paginate(8);
        return CategoryResource::collection($categories)
        ->additional(['message' => 'All Categories has been retrieved']);
    }

    /*
    ** Display Specific Category According to search keyword
    */
    public function show(Request $req)
    {
        $filtered = DB::table('categories')->
        select(['name','image'])->
        where('name','like',$req["name"].'%')->
        orderBy('name')->
        get();
        //check if the filtered array contains items or not
        return $filtered->first()?
        $this->sendData('',$filtered):
        $this->error('No category with this name');
    }

    /*
    ** Create Category
    */
    public function store(StoreCategoryRequest $req)
    {
        if($req->validated())
        {
            $data = $req->except('image');

            $data = $req->validated();

            $data['image'] = Media::upload($req->image,'images\categories');  //the hashname of the image is not working so i use the original name of the image
    
            $data['created_at'] = now();

            if(DB::table('categories')->insert($data)){
                return $this->sendData('Category has been stored successfully',$data); // return the list of categories
            }
        }
    }


    /*
    ** Edit category to return the data of this category 
    */
    public function edit(Category $category) 
    {        
        return $this->sendData('',new CategoryResource($category));  
    }

    /*
    ** Update Category
    */
    public function update(UpdateCategoryRequest $req , Category $category)
    {

        $data = $req->except('image','_method');
        if($req->hasFile('image')){
            $imageName = Media::upload($req->file('image'),'images\categories');
            $data['image'] = $imageName;
            Media::delete(public_path("images\categories/{$category->image}"));    
        }   
        return DB::table('categories')->where('id','=',$category->id)->update($data);
    }

    /*
    ** Delete Category
    */
    public function destroy(Category $category) 
    {
        $filteredproducts = DB::table('products')->select('*')->where('category_id','=',$category->id)->get();

        if(sizeof($filteredproducts->all()) > 0)
        {
            DB::table('categories')->where('id','=',$category->id)->update(['status' =>'0']);
            return $this->success('Category cannot be deleted, but it\'s now unavialable',);
        }else{
            $category->delete();
            Media::delete(public_path("images\categories/{$category->image}"));    
            return $this->success('Category Deleted successfully',);
        }
        
    }

}
