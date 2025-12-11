<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Billing</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap-5/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/navbar.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/about_us.css')}}" /> 
    <link rel="stylesheet" href="{{asset('assets/css/footer.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/login_signup.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/contact_us.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/addcustomerS.css')}}" />




</head>

<body>
    <header>
        @include('layout.navbar')
    </header>
    <div>
        @yield('content')
    </div>








    <footer class="footer pt-5 pb-4">
        @include('layout.footer')
    </footer>


    <script src="{{asset('assets/jquery/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap-5/js/bootstrap.bundle.min.js')}}"></script>
</body>

</html>