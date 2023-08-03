<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style>
        .title_deg
        {
            text-align: center;
            font-size: 25px;
            font-weight: bold;
            padding-bottom: 40px;
        }
        .table_deg
        {
            border: 2px solid white;
            width: 100%;
            margin: auto;
            text-align: center;
        }
        .th_deg
        {
            background-color: skyblue;
        }
        .img_size
        {
            width: 200px;
            height: 150px;
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
                    <h1 class="title_deg">All Orders</h1>
                    <div style="padding-left:400px; padding-bottom:30px;">
                        <form action="{{ url('search') }}" method="GET">
                            @csrf
                            <input type="text" name="search" placeholder="Search for something" style="color:black;"/>
                            <input type="submit" value="Search" class="btn btn-outline-primary"/>
                        </form>
                    </div>
                    <table class="table_deg">
                        <tr class="th_deg">
                            <th class="padding:10px;">Name</th>
                            <th class="padding:10px;">Email</th>
                            <th class="padding:10px;">Address</th>
                            <th class="padding:10px;">Phone</th>
                            <th class="padding:10px;">Product Title</th>
                            <th class="padding:10px;">Quantity</th>
                            <th class="padding:10px;">Price</th>
                            <th class="padding:10px;">Payment Status</th>
                            <th class="padding:10px;">Delivery Status</th>
                            <th class="padding:10px;">Image</th>
                            <th class="padding:10px;">Delivered</th>
                            <th class="padding:10px;">Print PDF</th>
                            <th class="padding:10px;">Send Email</th>
                        </tr>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->email }}</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->product_title }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ $order->price }}</td>
                            <td>{{ $order->payment_status }}</td>
                            <td>{{ $order->delivery_status }}</td>
                            <td><img class="img_size" src="/product/{{ $order->image }}"></td>
                            <td>
                                @if($order->delivery_status === 'processing')
                                <a href="{{ url('delivered',$order->id) }}" onclick="return confirm('Are you sure this product is delievered!!!')" class="btn btn-primary">Delivered</a>
                                @else
                                <p style="color:green;">Delivered</p>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('print_pdf',$order->id) }}" class="btn btn-secondary">Print PDF</a>
                            </td>
                            <td>
                                <a href="{{ url('send_email',$order->id) }}" class="btn btn-info">Send EMail</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="16">No Data Found</td>
                            </tr>
                        @endforelse
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
