<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\User\UserResource;
use App\Traits\ApiRespone;

class UserController extends Controller
{
    use ApiRespone;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate();
      return  $this->sendData('Users Retrieved Successfully', UserResource::collection($users));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
 

         User::create($request->validated());
        return $this->sendData('User added successfully','');
       


   }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
