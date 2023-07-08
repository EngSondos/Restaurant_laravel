<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Ingredient;
use App\Models\Product;
use App\Traits\ApiRespone;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiRespone;
    private $user_id ;
    public function __construct() {
        $this->user_id = 1;
    }
    /**
     * Display all stored products
     */
    public function index()
    {
        // dd($this->user_id);
        $carts = Cart::where('user_id', $this->user_id)->first();

        if(!$carts) {
            return $this->success('no cards to be showen');
        }

        $cart = $carts->cartProduct()->with('product.ingredients')->get();

        return $this->sendData('All Carts has been retrieved',[$cart,'cart_total_price' => $carts->total_price]);
    }

    /**
     * Store product in cart
     */
    public function store(StoreCartRequest $req)
    {
        if (DB::table('cart_product')->where('product_id', '=', $req['product_id'])->where('user_id', '=', $this->user_id)->exists()) {
            return $this->error('this product is already in the cart');
        }
        
        $product = Product::findOrFail($req['product_id']);

        $product = Product::with('ingredients')->findOrFail( $req['product_id']);

        $productIngredients = $product->ingredients;

        $this->changeIngredientQTY($productIngredients,0,1);

        CartProduct::create([
            'user_id' => $this->user_id,
            'product_id' => $req['product_id'],
            'total_price' => $product->total_price,
            'quantity' => 1,
            'created_at' => now(),
        ]);
        
        $total_price_on_cart = $this->countTotalPrice($this->user_id);

        return $this->sendData('Product has been added to the cart.', $total_price_on_cart);
    }

    /**
     * Update card quantity
     */
    public function update(UpdateCartRequest $req)
    {
        $cartproduct = CartProduct::with('product.ingredients')->findOrFail( $req['id']);

        $productIngredients = $cartproduct->product->ingredients;

        if((int)$req->quantity < 1)
        {   
            
            return $this->error('The quantity can not be decremented less than 1');
        }
        $this->changeIngredientQTY($productIngredients , (int)$cartproduct->quantity , (int)$req->quantity);

        $cardQTY = $req->quantity;

        $cartproduct->update([
            'quantity' => $cardQTY,
            'total_price' => $cardQTY * $cartproduct->product->total_price
        ]);

        $this->countTotalPrice($this->user_id);

        return $this->success('The quantity has been updated');
    }

    /**
     * Destroy one card or all cards
     */
    public function destroy(Request $request)
    {
        if($request['id']){

            $cartproduct = CartProduct::with('product.ingredients')->findOrFail( $request['id']);

            $productIngredients = $cartproduct->product->ingredients;

            $this->changeIngredientQTY($productIngredients , $cartproduct->quantity , 0);

            DB::table('cart_product')->where('cart_product.id', $request['id'])->delete();

            $total_price_on_cart = $this->countTotalPrice($this->user_id);

            if ($total_price_on_cart == 0) {

                DB::table('carts')->where('user_id', '=', $this->user_id)->delete();

                return $this->success('No Products In The Cart');
            }

            DB::table('carts')->where('user_id', '=', $this->user_id)->update([
                'total_price'=>$total_price_on_cart,
                'updated_at' => now()
            ]);

            return $this->success('Product Deleted Successfully From Cart');
        }
        DB::table('cart_product')->where('cart_product.user_id', $this->user_id)->delete();

        DB::table('carts')->where('user_id', '=', $this->user_id)->delete();

        return $this->success('No Products In The Cart');

    }

    /**
     * Count the total price of all cards
     */
    public static function countTotalPrice($user_id)
    {

        $totalprice = DB::table('cart_product')->where('user_id', $user_id)
            ->sum('total_price');

        if (Cart::where('user_id', $user_id)->exists()) {
            DB::table('carts')->where('user_id', $user_id)->update(['total_price' => $totalprice, 'updated_at' => now()]);
        } else {
            Cart::insert([
                'total_price' => $totalprice,
                'user_id' => $user_id,
                'created_at' => now()
            ]);
        }

        return $totalprice;
    }

    /**
     * Change the quantity of ingredients
     */
    public function changeIngredientQTY($ingredients , $oldQTY , $newQTY){
        
        foreach ($ingredients as $ingredient) {
            if ($newQTY == 1 and $oldQTY == 0)
            {
                $ingredientquantity = $ingredient->quntity - $ingredient->pivot->quantity;
                
            }else if ($newQTY > $oldQTY){
                $difference = $newQTY - $oldQTY;
                $ingredientquantity = $ingredient->quntity - $ingredient->pivot->quantity  * $difference;

            }else{                                                  
                $difference = $oldQTY - $newQTY;
                $ingredientquantity = $ingredient->quntity + $ingredient->pivot->quantity  * $difference;
            }
            Ingredient::query()->where('id',$ingredient->id)->update(['quntity' =>$ingredientquantity]);
        }

    }
}
