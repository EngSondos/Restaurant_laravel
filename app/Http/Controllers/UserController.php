<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\User\UserResource;
use App\Traits\ApiRespone;
use App\Http\Services\Media;


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
            $data['image'] = Media::upload($request->image, 'images/users');
        }

         User::create($data);
        return $this->sendData('User added successfully','');
       


   }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);

    }

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
}
