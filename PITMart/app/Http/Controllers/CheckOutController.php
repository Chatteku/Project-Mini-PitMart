<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckOutController extends Controller
{
    public function placeorder(Request $request)
    {
        if(auth('sanctum')->check())
        {
            $validator = Validator::make($request->all() , [
                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'phone' => 'required|max:191',
                'email' => 'required|max:191',
                'adress' => 'required|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                'zipcode' => 'required|max:191',
            ]);

            if($validator->fails())
            {
                return response()->json([
                    'status' => 402,
                    'message' =>$validator->messages()
                ]);
            }
            else
            {
                $user_id = auth('sanctum')->user()->id;
                $order = new Order;
                $order->user_id = $user_id;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->phone = $request->phone;
                $order->email = $request->email;
                $order->adress = $request->adress;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zipcode = $request->zipcode;

                $order->payment_mode = $request->payment_mode;
                $order->payment_id = $request->payment_id;
                $order->tracking_no = 'PitMart' .rand(1111,9999);
                $order->save();

                $cart = Cart::where('user_id' , $user_id)->get();


                $orderitems = [];
                foreach($cart as $item)
                {
                    $orderitems = [
                        'product_id',
                        'qty'=> $item->product_qty,
                        'price'=> $item->product->price,
                    ];

                    $item->product->update([
                        'qty'=>$item->product->qty = $item->product_qty,
                    ]);
                }

                $order->orderitems()->createMany($orderitems);
                Cart::destroy($cart);

                return response()->json([
                    'status' => 200,
                    'message' => 'Order Placed Successfully',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 401,
                'message' => 'Login to Order'
            ]);
        }
    }
}
