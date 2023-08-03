<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style type="text/css">
        .center
        {
            margin: auto;
            width: 50%;
            text-align: center;
            margin-top: 30px;
            border: 3px solid white;
        }
        .font_size
        {
            text-align: center;
            font-size: 40px;
            padding-top: 20px;
        }
        .image-size
        {
            width: 200px;
            height:200px;
        }
        .th_color
        {
            background: skyblue;
        }
        .th_deg
        {
            padding: 30px;
        }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
        <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
            @include('admin.header')
        <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    <h2 class="font_size">All Products</h2>
                    <table class="center">
                        <tr class="th_color">
                            <td class="th_deg">Product Title</td>
                            <td class="th_deg">Description</td>
                            <td class="th_deg">Quantity</td>
                            <td class="th_deg">Category</td>
                            <td class="th_deg">Price</td>
                            <td class="th_deg">Discount Price</td>
                            <td class="th_deg">Image</td>
                            <td class="th_deg">Delete</td>
                            <td class="th_deg">Edit</td>
                        </tr>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->discount_price }}</td>
                            <td>
                                <img src="/product/{{ $product->image }}" class="image_size">
                            </td>
                            <td>
                                <a onclick="return confirm('Are you sure to delete this?')" href="{{ url('delete_product',$product->id) }}" class="btn btn-danger">Delete</a>
                            </td>
                            <td>
                                <a href="{{ url('update_product',$product->id) }}" class="btn btn-success">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        <!-- main-panel ends -->
        </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    @include('admin.script')
  </body>
</html>
