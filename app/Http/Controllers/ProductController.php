<?php

namespace App\Http\Controllers;
// here we will import request from http header for our controller function
use Illuminate\Http\Request;
// we imported product model for fatching data from product table of database
use App\Models\Product;
// same here imported Cart model
use App\Models\Cart;
// we have to use session for making sure that a user is logged in and they can use all the private functioning of our app
use Session;
// this is for using all database operations like CRUD
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class ProductController extends Controller
{
    //here we are fatching data from Product table and render it on products view
    function index(){
    $data= Product::all();
    return view('product',['products'=>$data]);
     }

    // here we are fatching data of a perticular item's details from Product table
   function detail($id){
    $data= Product::find($id);
    return view('detail',['product'=>$data]);
       }

    //here when a user click on an item so they can add that item to cart
    function addToCart(Request $req)
       {
           if($req->session()->has('user'))
           {
               $cart= new Cart;
               $cart->user_id=$req->session()->get('user')['id'];
               $cart->product_id=$req->product_id;
               $cart->save();
               return redirect('/');

           }
           else
           {
               return redirect('/singin');
           }
       }

// this is a static function because we are using this function in header view
// this function is calculating the no of item in the cart
static function cartItem()
       {
           $userId= Session::get('user')['id'];
           return Cart::where('user_id',$userId)->count();
       }

 // here we are fatching data of list of all items in user's cart and render it in the cartlist view
function cartList()
       {
           $userId= Session::get('user')['id'];
          $data=  DB::table('cart')
            ->join('products','cart.product_id','products.id')
            ->select('products.*','cart.id as cart_id')
            ->where('cart.user_id',$userId)
            ->get();

            return view('cartlist',['products'=>$data]);

       }

      // here we are removing an item from user's cart
       function removeCart($id)
    {
         Cart::destroy($id);
        return redirect('cartlist');
    }

    // here we are making a function for user who want to order items in cart so we are making functioning for that
    function orderNow()
    {
        $userId= Session::get('user')['id'];
        $total = DB::table('cart')
          ->join('products','cart.product_id','products.id')
          ->where('cart.user_id',$userId)
          ->sum('products.price');

          return view('ordernow',['total'=>$total]);
    }

    // here a user is placing an order with the details
    function orderPlace(Request $req)
    {
        $userId= Session::get('user')['id'];
        $allCart=Cart::where('user_id',$userId)->get();
        foreach($allCart as $cart)
        {
            $order= new Order;
            $order->product_id=$cart['product_id'];
            $order->user_id=$cart['user_id'];
            $order->address=$req->address;
            $order->status="pending";
            $order->payment_method=$req->payment;
            $order->payment_status="pending";
            $order->save();
        }
        Cart::where('user_id',$userId)->delete();
        return redirect('/');

    }

    // here a user can see that what order they have made for the items
    function myOrder()
    {
        $userId= Session::get('user')['id'];
        $orders= DB::table('orders')
          ->join('products','orders.product_id','products.id')
          ->where('orders.user_id',$userId)
          ->get();

          return view('myorder',['orders'=>$orders]);
    }
}
