<!DOCTYPE html>
<html>
   <head>
      <!-- Basic -->
      <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/favicon.png" type="">
    <title>Famms - Fashion HTML Template</title>
    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('home/css/bootstrap.css') }}" />
    <!-- font awesome style -->
    <link href="{{ asset('home/css/font-awesome.min.css')}}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('home/css/style.css')}}" rel="stylesheet" />
    <!-- responsive style -->
    <link href="{{ asset('home/css/responsive.css')}}" rel="stylesheet" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
   <body>
      <div class="hero_area">
         <!-- header section strats -->
            @include('home.header')
         <!-- end header section -->
      <!-- product section -->
            @include('home.product_view')
      <!-- end product section -->

      <!-- COMMENT AND REPLY SYSTEM STARTS HERE -->
        <div style="text-align: center; padding-bottom:30px;">
            <h1 style="font-size:30px; text-align:center; padding-top:20px; padding-bottom:20px;">Comments</h1>
            <form action="{{ url('add_comment') }}" method="POST">
                @csrf
                <textarea name="comment" style="height:150px; width:600px" placeholder="Comment somethig here"></textarea>
                <br/>
                <input type="submit" class="btn btn-primary" value="Comment">
            </form>
        </div>
        <div style="padding-left:20%;">
            <h1 style="font-size:20px; padding-bottom:20px;">All Comments</h1>
            @foreach($comments as $comment)
            <div>
                <b>{{ $comment->name }}</b>
                <p>{{ $comment->comment }}</p>

                <a href="javascript:void(0);" onclick="reply(this)" data-comment-id="{{ $comment->id }}">Reply</a>
                @foreach($replies as $reply)
                @if($reply->comment_id == $comment->id)
                <div style="padding-left:3%; padding-top:10px; padding-bottom:10px;">
                    <b>{{ $reply->name }}</b>
                    <p>{{ $reply->reply }}</p>
                    <a style="color:blue;" href="javascript:void(0);" onclick="reply(this)" data-comment-id="{{ $comment->id }}">Reply</a>
            </div>
                @endif
                @endforeach
            </div>
            @endforeach
            <div style="display:none;" class="replyDiv">
                <form action="{{ url('add_reply') }}" method="POST">
                    @csrf
                    <input type="hidden" id="commentId" name="commentId"/>
                    <textarea style="height:100;width:500px;" name="reply" placeholder="Write something here"></textarea>
                    <br/>
                    <button type="submit" class="btn btn-warning">Reply</button>
                    <a href="javascript:void(0);" class="btn" onClick="reply_close(this)">Close </a>
                </form>
            </div>
        </div>
      <!-- COMMENT AND REPLY SYSTEM ENDS HERE -->

      <div class="cpy_">
         <p class="mx-auto">© 2021 All Rights Reserved By <a href="https://html.design/">Free Html Templates</a><br>

            Distributed By <a href="https://themewagon.com/" target="_blank">ThemeWagon</a>

         </p>
      </div>
      </div>
      <script type="text/javascript">
        function reply(caller)
        {
            document.getElementById('commentId').value=$(caller).attr('data-comment-id');
            $('.replyDiv').insertAfter($(caller));
            $('.replyDiv').show();
        }

        function reply_close(caller){
            $('.replyDiv').hide();
        }
      </script>
      <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            var scrollpos = localStorage.getItem('scrollpos');
            if (scrollpos) window.scrollTo(0, scrollpos);
        });

        window.onbeforeunload = function(e) {
            localStorage.setItem('scrollpos', window.scrollY);
        };
    </script>
      <!-- jQery -->
      <script src="home/js/jquery-3.4.1.min.js"></script>
      <!-- popper js -->
      <script src="home/js/popper.min.js"></script>
      <!-- bootstrap js -->
      <script src="home/js/bootstrap.js"></script>
      <!-- custom js -->
      <script src="home/js/custom.js"></script>
   </body>
</html>
