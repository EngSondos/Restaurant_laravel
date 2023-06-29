<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateUserProfileRequest;
use App\Http\Services\Media;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;




use App\Http\Resources\User\UserResource;
use App\Traits\ApiRespone;
// use App\Http\Services\Media;


class UserController extends Controller
{
    use ApiRespone;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        
        $users = User::paginate();
        return UserResource::collection($users)
        ->additional(['message' => 'Users Retrieved Successfully']);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
 
        $data = $request->except('image');
        if ($request->hasFile('image')) {
        $data['image'] = Media::upload($request->image, 'users');
        }

         if (User::create($data)){
            return $this->success('User added successfully',Response::HTTP_CREATED);

         }
         return $this->error('User not added ',Response::HTTP_CONFLICT);



   }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        
        $user=User::find($id);

    if(!$user){
        return $this->error('user not Exist');
    }
    return $this->sendData('',new UserResource($user));

        

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $user=User::find($id);
        
        if(!$user){
            return $this->error('user not Exist');
        }
        
        $data = $request->except('image');

        if ($request->hasFile('image')) 
        {
            if ($user->image) {
                Media::delete($user->image);        
                }
            
            $image = $request->file('image');
            $data['image'] = Media::upload($image, 'users');
        }

       if ($user->update($data)){
        return $this->success('User Updated successfully', Response::HTTP_OK);
       } 
       $this->error('User Not Updated '.Response::HTTP_NOT_MODIFIED);



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

        $user = User::find($id);
    
        if (!$user) {
            return $this->error('User not found');
        }
    
        if ($user->image) {
            Media::delete($user->image);
        }
        $user->delete();
        return $this->success('User Deleted successfully',);
}


        public function UserDetails()
        {
            $user = auth()->user();
            return $this->sendData('',new UserResource($user));
    }
        




}