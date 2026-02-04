<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dealer Login | {{$generalsetting->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}">

    <!-- App css -->
    <!-- App css -->
    <link href="{{asset('public/backEnd/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{asset('public/backEnd/assets/css/app.min.css')}}" rel="stylesheet" type="text/css"
        id="app-default-stylesheet" />
    <link href="{{asset('public/backEnd/assets/css/bootstrap-dark.min.css')}}" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" disabled />
    <link href="{{asset('public/backEnd/assets/css/app-dark.min.css')}}" rel="stylesheet" type="text/css"
        id="app-dark-stylesheet" disabled />
    <link href="{{asset('public/backEnd/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/assets/css/toastr.min.css')}}">
</head>

<body class="loading authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="{{route('home')}}" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="50">
                                        </span>
                                    </a>

                                    <a href="{{route('home')}}" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{asset($generalsetting->white_logo)}}" alt="" height="50">
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">Enter your email address and password to access DEALER
                                    panel.</p>
                            </div>

                            <form action="{{route('dealer.login.submit')}}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        id="emailaddress" required="" placeholder="Enter your email" name="email"
                                        value="{{old('email')}}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Enter your password" name="password">
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin"
                                            checked>
                                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    <footer class="footer footer-alt">
        <script>document.write(new Date().getFullYear())</script> &copy; {{$generalsetting->name}} by <a
            href="{{route('home')}}" class="text-white-50">EiconBD</a>
    </footer>

    <!-- Vendor js -->
    <script src="{{asset('public/backEnd/')}}/assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="{{asset('public/backEnd/')}}/assets/js/app.min.js"></script>
    <script src="{{asset('public/backEnd/')}}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!}

</body>

</html>