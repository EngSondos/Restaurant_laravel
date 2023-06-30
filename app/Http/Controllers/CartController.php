<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\CartProductResource;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
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

        if(!$carts){
            return $this->success('no cards to be showen');
        }
        $cart = $carts->cartProduct()->with('product.category')->paginate(8);

        return CartProductResource::collection($cart)->additional([
            'message' => 'All Carts has been retrieved',
            'cart_total_price' => $carts->total_price,
        ]);
    }

    /**
     * Store product in cart
     */
    public function store(StoreCartRequest $req)
    {
        $userid = 1;
        //check if this product already exists or not
        if (DB::table('cart_product')->where('product_id', '=', $req['product_id'], 'and', 'user_id', '=', $userid)->exists()) {
            return $this->error('this product is already in the cart');
        }
         // Get the product information
        $product = Product::findOrFail($req['product_id']);

        // Create a new cart item
        $cart_item = CartProduct::create([
            'user_id' => $userid,
            'product_id' => $req['product_id'],
            'total_price' => $product->total_price,
            'quantity' => 1,
            'created_at' => now(),
        ]);

        // Calculate the total price on the cart
        $total_price_on_cart = $this->countTotalPrice($userid);

        // Return a success response
        return $this->sendData('Product has been added to the cart.', $total_price_on_cart);
    }

    /**
     * Update card quantity
     */
    public function update(UpdateCartRequest $req)
    {
        $userid = 1;

        $cartproduct = CartProduct::with('product.ingredients')->where('id', $req['id'])->get();

        $productIngredients = $cartproduct[0]->product->ingredients;
        
        $product = $cartproduct[0]->product;

        $cardQTY = $req->quantity;
        $avialability = true;
        foreach ($productIngredients as $ingredient) {
            $avialability = $ingredient->quntity <  $cardQTY *  $ingredient->pivot->quantity ? false : true;
        }
        if(!$avialability){
            return $this->error("this product cannot be increased any more");
        }else{
            $cardPrice = $cardQTY * $product->total_price;

            DB::table('cart_product')->where('id' , $req['id'])->update(['quantity' => $cardQTY,'total_price'=> $cardPrice]);

            $totalpriceofallcarts = $this->countTotalPrice($userid);

            return CartProductResource::collection($cartproduct)->additional([
                'message' => 'All Carts has been retrieved',
                'cart_total_price' => $totalpriceofallcarts,
            ]);
        }

    }

    /**
     * 
     */
    public function show(Request $req)
    {   
        //waiting for response from my team
    }

    /**
     * Destroy card
     */
    public function destroy(CartProduct $cart)
    {
        $userid = 1;
        
        DB::table('cart_product')->where('cart_product.id',$cart->id)->delete();

        $total_price_on_cart = $this->countTotalPrice($userid);

        if($total_price_on_cart == 0){

            DB::table('carts')->where('user_id', '=', $userid)->delete();

            return $this->success('now the cart is totally empty');
        }

        $cartdata['total_price'] = $total_price_on_cart;
        
        $cartdata['updated_at'] = now();

        DB::table('carts')->where('user_id', '=', $userid)->update($cartdata);

        return $this->sendData('',['total_price' =>$total_price_on_cart]);
    }

    /**
     * Destroy all cards
     */
    public function destroyAll()
    {
        $userid = 1;

        DB::table('cart_product')->where('cart_product.user_id',$userid)->delete();

        DB::table('carts')->where('user_id', '=', $userid)->delete();
            
        return $this->success('now the cart is totally empty');
    }

    /**
     * Count the total price of all cards
     */
    public static function countTotalPrice($user_id)
    {
        //get all products belong to specific user then sum all prices of all choosed products
        $totalprice = DB::table('cart_product')->where('user_id', $user_id)
            ->sum('total_price');
        if(Cart::where('user_id', $user_id)->exists())
        {
            DB::table('carts')->where('user_id',$user_id)->update(['total_price' => $totalprice , 'updated_at' => now()]);
        }else{

            $cartdata['total_price'] = $totalprice;

            $cartdata['user_id'] = $user_id;

            $cartdata['created_at'] = now();

            DB::table('carts')->insert($cartdata);
        }
        return $totalprice;
    }
}
