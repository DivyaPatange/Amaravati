 <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BookFatafat | | @yield('title')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
   <!-- plugins:css -->
    @include('auth.auth_Layout.link')
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <!-- Bootstrap CSS-->
    @yield('customcss')
  </head>
  <body>
    <!-- navbar-->
    <header class="header mb-5">
    @include('auth.auth_Layout.header')
    </header>
    @yield('content')
    <!--
    *** FOOTER ***
    _________________________________________________________
    -->
    
    <!-- /#footer-->
    <!-- *** FOOTER END ***-->
    
    @include('auth.auth_Layout.footer')
    <!--
    *** COPYRIGHT ***
    _________________________________________________________
    -->
    <!-- *** COPYRIGHT END ***-->
    <!-- JavaScript files-->
    @include('auth.auth_Layout.script')
    @yield('customjs')
    <script>
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('body').on('click', '#submitLogin', function () {
        var mobile_no = $("#mobile-no").val();
        var password = $("#password-modal").val();
        // alert(mobile_no);
        if(mobile_no=="") {
          $("#mobile_err").fadeIn().html("Required");
          setTimeout(function(){ $("#mobile_err").fadeOut(); }, 3000);
          $("#mobile-no").focus();
          return false;
        }
        if(password=="") {
          $("#pwd_err").fadeIn().html("Required");
          setTimeout(function(){ $("#pwd_err").fadeOut(); }, 3000);
          $("#password-modal").focus();
          return false;
        }
        else
        { 
          $.ajax({
            type:"POST",
            url:"{{ route('submit-login-form') }}",
            data:{mobile_no:mobile_no,password:password},
            cache:false,        
            success:function(returndata)
            {
              document.getElementById("submitForm").reset();
              if(returndata.success){
                $('#login-modal').modal('toggle');
                toastr.success(returndata.success);
              }
              else{
                toastr.error(returndata.error);
              }
            }
          });
        }
      })
    </script>
  </body>
</html>