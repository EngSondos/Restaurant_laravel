<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartProductRequest;
use App\Models\Cart;
use App\Traits\ApiRespone;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiRespone;
    //
    public function index(){
        /****here iam waiting for get request to show all stored carts****/
        $carts = Cart::paginate()->toArray();
        return $this->sendData("carts has been listed successfully",$carts);
    }

    //
    public function store(CartProductRequest $req){

    }

    //
    public function update(CartProductRequest $req){

    }

    //
    public function destroy(){

    }

    //
    public function destroyAll(){

    }

}
