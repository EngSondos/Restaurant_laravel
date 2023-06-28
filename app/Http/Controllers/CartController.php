<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartProductResource;
use App\Models\Cart;
use App\Traits\ApiRespone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use ApiRespone;
    /**
     * Display all stored products
     */
    public function index()
    {
        $user_id = '1';

        $carts = Cart::where('user_id', $user_id)->first();

        $cart = $carts->cartProduct()->with('product.category')->paginate(8);

        return CartProductResource::collection($cart)->additional([
            'message' => 'All Carts has been retrieved',
            'cart_total_price' => $carts->total_price,
        ]);
    }

    /**
     * 
     */
    public function store(StoreCartRequest $req)
    {
        $userid = 1;

        if (!DB::table('cart_product')->where('product_id', '=', $req['product_id'], 'and', 'user_id', '=', $userid)->exists()) {

            $product = DB::table('products')->where('id', '=', (int) $req['product_id'])->first();

            $data['created_at'] = now();

            $data['product_id'] = $req['product_id'];

            $data['total_price'] =  $product->total_price;

            $data['user_id'] = $userid;

            DB::table('cart_product')->insert($data);

            $total_price_on_cart = $this->countTotalPrice($userid);

            if (DB::table('carts')->where('user_id', '=', $userid)->exists()) {

                $cartdata['total_price'] = $total_price_on_cart;

                $cartdata['updated_at'] = now();

                DB::table('carts')->where('user_id', '=', $userid)->update($cartdata);
            } else {

                $cartdata['total_price'] = $total_price_on_cart;

                $cartdata['user_id'] = $userid;

                $cartdata['created_at'] = now();

                DB::table('carts')->insert($cartdata);
            }

            return $this->success('Category has been stored successfully');
        }

        return $this->error('this product is already in the cart');
    }

    /**
     * 
     */
    public function update(UpdateCartRequest $req)
    {
    }

    /**
     * 
     */
    public function destroy()
    {
    }

    /**
     * 
     */
    public function destroyAll()
    {
    }

    /**
     * 
     */
    public static function countTotalPrice($user_id)
    {
        //get all products belong to specific user then sum all prices of all choosed products
        $totalprice = DB::table('cart_product')->where('user_id', $user_id)
            ->sum('total_price');
        return $totalprice;
    }
}
