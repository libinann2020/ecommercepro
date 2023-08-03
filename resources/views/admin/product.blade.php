<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style type="text/css">
        .div_center
        {
            text-align: center;
            padding-top: 40px;
        }
        .h2_font
        {
            font-size: 40px;
            padding-bottom: 40px;
        }
        .input_color
        {
            color: black;
            padding-bottom: 20px;
        }
        .center
        {
            margin: auto;
            width: 50%;
            text-align: center;
            margin-top: 30px;
            border: 3px solid white;
        }
        label{
            display: inline-block;
            width: 200px;
            text-align: left;
        }
        .div_design
        {
            padding-bottom: 15px;
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
                    <div class="div_center">
                        <h2 class="h2_font">Add Product</h2>
                        <form action="{{ url('/add_product') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="div_design">
                                <label for="title">Title</label>
                                <input type="text" class="input_color" name="title" placeholder="write a title" required/>
                            </div>
                            <div class="div_design">
                                <label for="description">Description</label>
                                <input type="text" class="input_color" name="description" placeholder="write a description" required/>
                            </div>
                            <div class="div_design">
                                <label for="price">Price</label>
                                <input type="number" class="input_color" name="price" placeholder="write a price" required/>
                            </div>
                            <div class="div_design">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="input_color" name="quantity" placeholder="write a quantity" required/>
                            </div>
                            <div class="div_design">
                                <label for="discount">Discount Price</label>
                                <input type="number" min="0" class="input_color" name="discount_price" placeholder="write a discount"/>
                            </div>
                            <div class="div_design">
                                <label for="category">Product Category</label>
                                <select name="category" class="input_color" required>
                                    <option value="" selected>Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="div_design">
                                <label for="image">Image</label>
                                <input type="file" name="image" class="input_color" required/>
                            </div>
                            <div class="div_design">
                                <input type="submit" class="btn btn-primary" name="submit" value="Add Product"/>
                            </div>
                        </form>
                    </div>
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
