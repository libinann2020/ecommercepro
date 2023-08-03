<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;
use Session;
use Stripe;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    public function redirect()
    {
        $usertype = Auth::user()->usertype;
        if($usertype=='1'){
            $total_product = Product::all()->count();
            $total_order = Order::all()->count();
            $total_user = User::all()->count();
            $orders = Order::all();
            $total_revenue = 0;
            foreach($orders as $order)
            {
                $total_revenue = $total_revenue + $order->price;
            }
            $total_delivered = Order::where('delivery_status','delivered')->get()->count();
            $total_processing = Order::where('delivery_status','processing')->get()->count();
            return view('admin.home', compact('total_product','total_order','total_user','total_revenue','total_delivered','total_processing'));
        } else {
            $products = Product::paginate(10);
            $comments = Comment::orderBy('id','DESC')->get();
            $replies = Reply::all();
            return view('home.userpage',compact('products','comments','replies'));
        }
    }

    public function index()
    {
        $products = Product::paginate(10);
        $comments = Comment::orderBy('id','DESC')->get();
        $replies = Reply::all();
        return view('home.userpage',compact('products','comments','replies'));
    }

    public function product_details($id)
    {
        $product = Product::find($id);
        return view('home.product_details', compact('product'));
    }

    public function add_cart(Request $request, $id)
    {
        if(Auth::id())
        {
            $user = Auth::user();
            $product = Product::find($id);
            $product_exist_id = Cart::where('product_id',$id)->where('user_id',$user->id)->get('id')->first();
            if($product_exist_id){
                $cart = Cart::find($product_exist_id)->first();
                $quantity = $cart->quantity;
                $cart->quantity = $quantity+$request->quantity;
                if($product->discount_price!=null){
                    $cart->price=$product->discount_price * $cart->quantity;
                } else {
                    $cart->price=$product->price * $cart->quantity;
                }
                $cart->save();
                Alert::success('Product added successfully','We have added product to the cart');
                return redirect()->back();
            } else {
                $cart = new Cart;
                $cart->name=$user->name;
                $cart->email=$user->email;
                $cart->phone=$user->phone;
                $cart->address=$user->address;
                $cart->user_id=$user->id;
                $cart->product_title=$product->title;
                if($product->discount_price!=null){
                    $cart->price=$product->discount_price * $request->quantity;
                } else {
                    $cart->price=$product->price * $request->quantity;
                }
                $cart->quantity=$request->quantity;
                $cart->image=$product->image;
                $cart->product_id=$product->id;
                $cart->save();
                Alert::success('Product added successfully','We have added product to the cart');
                return redirect()->back();
            }
        } else {
            return redirect('login');
        }
    }

    public function show_cart()
    {
        if(Auth::id())
        {
            $carts = Cart::where('user_id',Auth::user()->id)->get();
            return view('home.showcart',compact('carts'));
        } else{
            return redirect('login');
        }

    }

    public function remove_cart($id)
    {
        $cart = Cart::find($id);
        $cart->delete();
        return redirect()->back()->with('message','Cart Item deleted successfully');
    }

    public function cash_order(){
        $user = Auth::user();
        $userid = $user->id;
        $data = Cart::where('user_id','=',$userid)->get();
        foreach($data as $cart)
        {
            $order = new Order;
            $order->name = $cart->name;
            $order->email = $cart->email;
            $order->phone = $cart->phone;
            $order->address = $cart->address;
            $order->user_id = $cart->user_id;
            $order->product_title = $cart->product_title;
            $order->price = $cart->price;
            $order->quantity = $cart->quantity;
            $order->image = $cart->image;
            $order->product_id = $cart->product_id;
            $order->payment_status = 'cash on delivery';
            $order->delivery_status = 'processing';
            $order->save();
            $cart_id = $cart->id;
            $data = Cart::find($cart_id);
            $data->delete();
        }
        return redirect()->back()->with('message','We have received your order. We will connect with you soon');
    }

    public function stripe($totalprice)
    {
        return view('home.stripe', compact('totalprice'));
    }

    public function stripePost(Request $request, $totalprice)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create ([
                "amount" => $totalprice * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Thanks for payment."
        ]);

        $user = Auth::user();
        $userid = $user->id;
        $data = Cart::where('user_id','=',$userid)->get();
        foreach($data as $cart)
        {
            $order = new Order;
            $order->name = $cart->name;
            $order->email = $cart->email;
            $order->phone = $cart->phone;
            $order->address = $cart->address;
            $order->user_id = $cart->user_id;
            $order->product_title = $cart->product_title;
            $order->price = $cart->price;
            $order->quantity = $cart->quantity;
            $order->image = $cart->image;
            $order->product_id = $cart->product_id;
            $order->payment_status = 'Paid';
            $order->delivery_status = 'processing';
            $order->save();
            $cart_id = $cart->id;
            $data = Cart::find($cart_id);
            $data->delete();
        }

        Session::flash('success', 'Payment successful!');

        return back();
    }

    public function show_order()
    {
        if(Auth::id())
        {
            $user = Auth::user();
            $userid = $user->id;
            $orders = Order::where('user_id',$userid)->get();
            return view('home.order',compact('orders'));
        } else {
            return redirect('login');
        }
    }

    public function cancel_order($id)
    {
        $order = Order::find($id);
        $order->delivery_status = 'You cancelled the order';
        $order->update();
        return redirect()->back();
    }

    public function add_comment(Request $request)
    {
        if(Auth::id())
        {
            $comment = new Comment;
            $comment->name = Auth::user()->name;
            $comment->comment = $request->comment;
            $comment->user_id = Auth::user()->id;
            $comment->save();

            return redirect()->back()->with('message','Comment added successfully');
        } else {
            return redirect('login');
        }
    }

    public function add_reply(Request $request)
    {
        if(Auth::id())
        {
            $reply = new Reply;
            $reply->name = Auth::user()->name;
            $reply->reply = $request->reply;
            $reply->user_id = Auth::user()->id;
            $reply->comment_id = $request->commentId;
            $reply->save();

            return redirect()->back()->with('message','Reply added successfully');
        } else {
            return redirect('login');
        }
    }

    public function product_search(Request $request)
    {
        $search_text = $request->search;
        $products = Product::where('title','LIKE',"%$search_text%")
                    ->orWhere('category','LIKE',"%$search_text%")
                    ->paginate(10);
        $comments = Comment::orderBy('id','DESC')->get();
        $replies = Reply::all();
        return view('home.userpage',compact('products','comments','replies'));

    }

    public function products()
    {
        $products = Product::paginate(10);
        $comments = Comment::orderBy('id','DESC')->get();
        $replies = Reply::all();
        return view('home.all_product',compact('products','comments','replies'));
    }

    public function search_product(Request $request)
    {
        $search_text = $request->search;
        $products = Product::where('title','LIKE',"%$search_text%")
                    ->orWhere('category','LIKE',"%$search_text%")
                    ->paginate(10);
        $comments = Comment::orderBy('id','DESC')->get();
        $replies = Reply::all();
        return view('home.all_product',compact('products','comments','replies'));

    }
}
