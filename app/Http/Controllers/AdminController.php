<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use PDF;
use Notification;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function view_category()
    {
        if(Auth::id()){
            $categories = Category::all();
            return view('admin.category',compact('categories'));
        } else {
            return redirect('login');
        }
    }

    public function add_category(Request $request)
    {
        if(Auth::id()){
            $data = new Category;
            $data->category_name = $request->category;
            $data->save();
            return redirect()->back()->with('message','Category Added Successfully');
        } else {
            return redirect('login');
        }
    }

    public function delete_category($id)
    {
        if(Auth::id()){
            $data = Category::find($id);
            $data->delete();
            return redirect()->back()->with('message','Category deleted successfully');
        } else {
            return redirect('login');
        }
    }

    public function view_product()
    {
        if(Auth::id()){
            $categories = Category::all();
            return view('admin.product', compact('categories'));
        } else {
            return redirect('login');
        }
    }

    public function add_product(Request $request)
    {
        if(Auth::id()){
            $data = new Product;
            $data->title=$request->title;
            $data->description=$request->description;
            $data->category=$request->category;
            $data->quantity=$request->quantity;
            $data->price=$request->price;
            $data->discount_price=$request->discount_price;

            $image = $request->image;
            $imagename = time() .'.'.$image->getClientOriginalExtension();
            $request->image->move('product',$imagename);
            $data->image=$imagename;

            $data->save();
            return redirect()->back()->with('message','Product Added Successfully');
        } else {
            return redirect('login');
        }
    }

    public function show_product()
    {
        if(Auth::id()){
            $products = Product::all();
            return view('admin.show_product',compact('products'));
        } else {
            return redirect('login');
        }
    }

    public function delete_product($id)
    {
        if(Auth::id()){
            $data = Product::find($id);
            $data->delete();
            return redirect()->back()->with('message','Product deleted successfully');
        } else {
            return redirect('login');
        }
    }

    public function update_product($id)
    {
        if(Auth::id()){
            $product = Product::find($id);
            $categories = Category::all();
            return view('admin.update_product',compact('product','categories'));
        } else {
            return redirect('login');
        }
    }

    public function update_product_confirm(Request $request, $id)
    {
        if(Auth::id()){
            $product = Product::find($id);
            $product->title=$request->title;
            $product->description=$request->description;
            $product->category=$request->category;
            $product->quantity=$request->quantity;
            $product->price=$request->price;
            $product->discount_price=$request->discount_price;

            if($request->image)
            {
                $image = $request->image;
                $imagename = time() .'.'.$image->getClientOriginalExtension();
                $request->image->move('product',$imagename);
                $product->image=$imagename;
            }

            $product->update();
            return redirect()->back()->with('message','Product Updated Successfully');
        } else {
            return redirect('login');
        }
    }

    public function order()
    {
        if(Auth::id()){
            $orders = Order::all();
            return view('admin.order', compact('orders'));
        } else {
            return redirect('login');
        }
    }

    public function delivered($id)
    {
        if(Auth::id()){
            $order = Order::find($id);
            $order->delivery_status = "delivered";
            $order->payment_status = "Paid";
            $order->save();
            return redirect()->back();
        } else {
            return redirect('login');
        }
    }

    public function print_pdf($id)
    {
        if(Auth::id()){
            $order = Order::find($id);
            $pdf = PDF::loadView('admin.pdf', compact('order'));
            return $pdf->download('order_details.pdf');
        } else {
            return redirect('login');
        }
    }

    public function send_email($id)
    {
        if(Auth::id()){
            $order = Order::find($id);
            return view('admin.email_info',compact('order'));
        } else {
            return redirect('login');
        }
    }

    public function send_user_email(Request $request,$id)
    {
        if(Auth::id()){
            $order = Order::find($id);
            $details = [
                'greeting' => $request->greeting,
                'firstline' => $request->firstline,
                'body' => $request->body,
                'button' => $request->button,
                'url' => $request->url,
                'lastline' => $request->lastline,
            ];
            Notification::send($order, new SendEmailNotification($details));
            return redirect()->back();
        } else {
            return redirect('login');
        }
    }

    public function searchdata(Request $request)
    {
        if(Auth::id()){
            $searchText = $request->search;
            $orders = Order::where('name','LIKE',"%$searchText%")
                        ->orWhere('phone','LIKE',"%$searchText%")
                        ->orWhere('product_title','LIKE',"%$searchText%")
                        ->get();
            return view('admin.order',compact('orders'));
        } else {
            return redirect('login');
        }
    }
}

