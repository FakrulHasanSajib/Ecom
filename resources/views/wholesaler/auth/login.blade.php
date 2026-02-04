<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
        <title>Log In | {{$generalsetting->name}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="{{$generalsetting->meta_description}}" name="description" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}">
  <link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/newlogin.css">
</head>
<body>
  <!-- Colorful snake animation -->
  <div id="trail-container">
    <div class="trail trail1"></div>
    <div class="trail trail2"></div>
    <div class="trail trail3"></div>
    <div class="trail trail4"></div>
    <div class="trail trail5"></div>
  </div>

  <!-- Logo -->
  <div class="header-logo">
    <img src="{{asset($generalsetting->white_logo)}}" alt="Webuzo Logo" class="logo-image">
   
  </div>

  <!-- Login form -->
  <div class="login-container">
    <form method="POST" action="{{route('wholesale.login')}}" >
        @csrf
      <div class="input-group" style="">
        <label for="username">Email</label>        
        <input type="email" id="emailaddress" class="colorful-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  placeholder="Enter your email">
        @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
           @enderror
      </div>
      
      <div class="input-group">
        <label for="password">Password</label>        
        <input type="password" id="password" class="colorful-input @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password" autofocus placeholder="Enter your password">
        @error('password')
             <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                   </span>
                  @enderror
      </div>
      
      <button type="submit" class="login-button">Login</button>
      <div class="links">
        <a href="#">Forgot Password</a> | <a href="#">Forgot Username</a>
      </div>
      <div class="powered-by">
        <span>Powered by <a href="http://eiconbd.com/"><strong>EiconBD</strong></a></span>
        <img src="https://e1.eicon.com.bd/public/uploads/settings/1737201381-eiconbd-logo.webp"  alt="EiconBD Logo" class="powered-logo">
      </div>
    </form>
  </div>
  

  <script src="{{asset('publiic/backEnd/')}}/assets/js/newlogin.js"></script>
</body>
</html>