<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addtocart(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;


            $productcheck = Product::where('id' , $product_id)->first();
            if(Cart::where('product_id' , $product_id)->where('user_id' , $user_id)->exists())
            {
                return response()->json([
                    'status' => 409,
                    'messages' => 'Already Add to Cart'
                ]);
            }
            else
            {
                $cartitem = new Cart;
                $cartitem -> user_id = $user_id;
                $cartitem -> product_id = $product_id;
                $cartitem -> product_qty = $product_qty;
                $cartitem->save();

                return response()->json([
                    'status' => 200,
                    'messages' => 'Ditambahkan ke keranjang'
                ]);
            }

            if($productcheck)
            {
                return response()->json([
                    'status' => 200,
                    'messages' => 'Ditambahkan ke keranjang'
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 401,
                    'messages' => 'Product Not Found',
                ]);
            }

            return response()->json([
                'status' => 200,
                'messages' => 'Ditambahkan ke keranjang'
            ]);
        }
        else
        {
            return response()->json([
                'status' => 401,
                'messages' => 'Login dlu baru belanja'
            ]);
        }
    }

    public function showcart()
    {
         if(auth('sanctum')->check())
         {
            $user_id = auth('sanctum')->user()->id;
            $cartitems = Cart::where('user_id' , $user_id)->get();

            return response()->json([
                'status' => 200,
                "cart" => $cartitems,
            ]);
         }
         else
         {
            return response()->json([
                'status' => 409,
                'message' => 'login to View cart',
            ]);
         }
    }

    public function updateqty($cart_id, $scope)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('id' , $cart_id)->where('id' , $user_id)->first();

            if($scope == 'inc')
            {
                $cartitem->product_qty += 1;
            }
            else if($scope == 'dec')
            {
                $cartitem ->product_qty -= 1;
            }
            $cartitem->update();
            return response()->json([
                'status' => 200,
                'messages' => 'Data Berhasil Ditambah'
            ]);
        }
        else
        {
            return response()->json([
                'status' => 401,
                'messages' => 'Login to Continue'
            ]);
        }
    }

    public function delete($cart_id)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitems = Cart::where('id' , $cart_id)->where('user_id' , $user_id)->first();

            if($cartitems)
            {
                $cartitems->delete();
                return response()->json([
                    'status' => 200,
                    'messages' => 'Item Deleted'
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'messages' => 'Cart Not Found'
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 401,
                'messages' => 'Login to Continue'
            ]);
        }
    }
}
