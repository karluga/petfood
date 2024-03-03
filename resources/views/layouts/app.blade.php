@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pet Food') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <!-- BOOTSTRAP LIBRARY WITH JQUERY -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script></head>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <meta name="theme-color" content="#9C3B1D">
    <link rel="icon" href="{{ asset('assets/pet_food_logo_filled.svg') }}">
    <link rel="mask-icon" href="{{ asset('assets/pet_food_logo_filled.svg') }}" color="#9C3B1D">
    <link rel="apple-touch-icon" href="{{ asset('assets/pet_food_logo_filled.svg') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">{{-- from /public --}}
    <link rel="stylesheet" href="{{ asset('css/search-box.css') }}">
</head>
<body>
        <div id="app" class="
            @if($errors->hasBag('login') && !$errors->hasBag('register'))
                login-visible
            @elseif($errors->hasBag('register') && !$errors->hasBag('login'))
                register-visible
            @endif
        ">        <!--SIGN-IN-->
        <form id="signIn" class="authenticate" action="/login" method="POST">
            @csrf
            <text class="auth-title" class="mt-auto">Log In</text>
            <input class="input form-control @error('email', 'login') is-invalid @enderror" type="text" name="email" placeholder="Email Address...">
            @error('email', 'login')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            
            <div class="input position-relative">
                <i class="bi bi-eye-slash" id="togglePasswordd"></i>
                <input class="input form-control @error('password', 'login') is-invalid @enderror" id="log-psw" type="password" name="password" placeholder="Password...">
                @error('password', 'login')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <a class="btn btn-link" href="password/reset">
                Forgot Your Password?
            </a>
            <button type="submit" name="submit" value="Login">Log In</button>
            <a onclick="signUp()" class="white-txt mb-auto" id="link">Sign Up</a>
            <span class="close-x-btn" onclick="closePopup()">X</span>
        </form>

        <!--SIGN-UP-->
        <form id="signUp" class="authenticate" action="/register" method="POST">
            @csrf
            <text class="auth-title" class="mt-auto">Sign Up</text>
            <input required class="input form-control @error('name', 'register') is-invalid @enderror" type="text" name="name" placeholder="First and last names...">       
            @error('name', 'register')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            <input required class="input form-control @error('email', 'register') is-invalid @enderror" type="email" name="email" placeholder="Email Address...">
            @error('email', 'register')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            <div class="input position-relative">
                <i class="bi bi-eye-slash" id="togglePassword"></i>
                <input required class="input form-control @error('password', 'register') is-invalid @enderror" id="reg-psw" type="password" name="password" placeholder="Password..." title="Must contain at least one number and one uppercase and lowercase letter, and at least 10 or more characters." autocomplete="off">
                @error('password', 'register')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <input required class="input form-control" type="password" id="psw-repeat" name="password_confirmation" placeholder="Repeat Password...">
            <button type="submit" name="save">Sign Up</button>
            <a onclick="signIn()" class="white-txt mb-auto" id="link">Log In</a>
            <span class="close-x-btn" onclick="closePopup()">X</span>
        </form>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <div class="logo-container">
                        <div class="logo-gradient" style="background: rgb(51, 3, 13);box-shadow: none;"></div>
                        <div class="logo-box">
                            @svg('assets/pet_food_logo_with_text.svg', 'petfood-logo')
                        </div>
                    </div>
                </a>
                <input type="checkbox" id="check">
                <label for="check" class="m-0 p-3 d-flex align-items-center">
                    <svg id="btn" fill="rgb(171 119 74)" width="40px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <title>Open Menu</title>
                        <path d="M2 8.749h28c0.414 0 0.75-0.336 0.75-0.75s-0.336-0.75-0.75-0.75v0h-28c-0.414 0-0.75 0.336-0.75 0.75s0.336 0.75 0.75 0.75v0zM30 15.25h-28c-0.414 0-0.75 0.336-0.75 0.75s0.336 0.75 0.75 0.75v0h28c0.414 0 0.75-0.336 0.75-0.75s-0.336-0.75-0.75-0.75v0zM30 23.25h-28c-0.414 0-0.75 0.336-0.75 0.75s0.336 0.75 0.75 0.75v0h28c0.414 0 0.75-0.336 0.75-0.75s-0.336-0.75-0.75-0.75v0z"></path>
                    </svg>
                    <svg id="cancel" fill="rgb(171 119 74)" width="40px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <title>Close Menu</title>
                        <path d="M17.062 16l9.37-9.37c0.136-0.136 0.219-0.323 0.219-0.53 0-0.415-0.336-0.751-0.751-0.751-0.208 0-0.395 0.084-0.531 0.22v0l-9.369 9.369-9.37-9.369c-0.135-0.131-0.319-0.212-0.522-0.212-0.414 0-0.75 0.336-0.75 0.75 0 0.203 0.081 0.387 0.212 0.522l9.368 9.369-9.369 9.369c-0.136 0.136-0.22 0.324-0.22 0.531 0 0.415 0.336 0.751 0.751 0.751 0.207 0 0.394-0.084 0.53-0.219v0l9.37-9.37 9.369 9.37c0.136 0.136 0.324 0.22 0.531 0.22 0.415 0 0.751-0.336 0.751-0.751 0-0.207-0.084-0.395-0.22-0.531v0z"></path>
                    </svg>
                </label>
                <div id="sidebar">
                    <a class="a active" href=""><span>Home</span><img src="{{ asset('assets/icons/white-home-icon-png-21.jpg') }}" alt="Home Icon"></a>
                    <a class="a" href=""><span>Blog</span><img src="{{ asset('assets/icons/chat-svgrepo-com.svg') }}" alt="Blog Icon"></a>
                    @auth
                    <a class="a" href="{{ '/' . app()->getLocale() . '/pets' }}"><span>My Pets</span><img src="{{ asset('assets/icons/pet-leash.png') }}" alt="My Pets Icon"></a>
                    <a class="a" href=""><span>Add Pets</span><img src="{{ asset('assets/icons/veterinary_7209231.png') }}" alt="Add Pets Icon"></a>
                    <a class="a" href="{{ '/' . app()->getLocale() . '/profile' }}"><span>Kārlis Ivars Braķis <img src="assets/icons/verified.png" alt="Verified Icon"></span><img src="{{ asset('assets/icons/user.png') }}" alt="User Icon"></a>
                    <a class="a" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                     <span>{{ __('Logout') }}</span><img src="{{ asset('assets/icons/logout-icon.png') }}" alt="Logout Icon">
                    </a>
                    @else
                    <a onclick="signIn()" class="a"><span>Log In</span><img src="{{ asset('assets/icons/user.png') }}" alt="User Icon"></a>
                    @endauth
                    <div id="language-mobile" data-bs-toggle="modal" data-bs-target="#languageChangeModal">
                        <img class="img-1" src="{{ asset('assets/flags/' . (App::currentLocale())) }}.svg" alt="flag-{{ App::currentLocale() }}">
                        {{ config('languages')[App::currentLocale()]['name'] }}
                        <img class="img-2" src="https://www.svgrepo.com/show/453365/language.svg">
                    </div>
                    <label for="check" class="close-bottom p-3">
                        <svg fill="rgb(255 255 255)" width="40px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <title>Close Menu</title>
                            <path d="M17.062 16l9.37-9.37c0.136-0.136 0.219-0.323 0.219-0.53 0-0.415-0.336-0.751-0.751-0.751-0.208 0-0.395 0.084-0.531 0.22v0l-9.369 9.369-9.37-9.369c-0.135-0.131-0.319-0.212-0.522-0.212-0.414 0-0.75 0.336-0.75 0.75 0 0.203 0.081 0.387 0.212 0.522l9.368 9.369-9.369 9.369c-0.136 0.136-0.22 0.324-0.22 0.531 0 0.415 0.336 0.751 0.751 0.751 0.207 0 0.394-0.084 0.53-0.219v0l9.37-9.37 9.369 9.37c0.136 0.136 0.324 0.22 0.531 0.22 0.415 0 0.751-0.336 0.751-0.751 0-0.207-0.084-0.395-0.22-0.531v0z"></path>
                        </svg>
                    </label>
                </div>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <div class="ln">
                            <!-- Current Language - Displayed First -->
                            <a class="square dropdown-button" href="#" title="{{ config('languages')[App::currentLocale()]['name'] }}">
                                <img src="{{ asset('assets/flags/' . (App::currentLocale())) }}.svg" alt="flag-{{ App::currentLocale() }}">
                                <p>
                                    <span class="language-code">{{ config('languages')[App::currentLocale()]['code'] }}</span>
                                    <span class="language-name">{{ config('languages')[App::currentLocale()]['name'] }}</span>
                                </p>
                            </a>
                        
                            <!-- Other Languages -->
                            @foreach(config('languages') as $key => $language)
                                @if(App::currentLocale() != $key)
                                    <a class="square" href="{{ $key }}" title="{{ $language['name'] }}" style="display: none;">
                                        <img src="{{ asset('assets/flags/'.$key) }}.svg" alt="flag-{{ $key }}">
                                        <p>
                                            <span class="language-code">{{ $language['code'] }}</span>
                                            <span class="language-name">{{ $language['name'] }}</span>
                                        </p>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        
                        @auth
                        <a href="#" id="nav-pets" class="d-flex align-items-center mx-2">
                            <img class="nav-icon" src="{{ asset('assets/icons/veterinary_7209231.png') }}" height="46" alt="Add Pets Icon">
                            <span>Add Pets</span>
                        </a>
                        @endauth
                        <a href="/" id="nav-search" class="{{ request()->is('/') ? 'd-none' : 'd-flex' }} align-items-center mx-2">
                            <img class="nav-icon" src="{{ asset('assets/icons/search-icon-png-9985.png') }}" height="46" alt="Search Icon">
                            <span>Search</span>
                        </a>
                    </ul>
                    <script>
                        $(".dropdown-button").click(function () {
                        $(".ln a:not(.dropdown-button)")
                            .finish()
                            .each(function (index) {
                            if (!$(".dropdown-button").hasClass("visible")) {
                                $(this)
                                .delay(20 * index)
                                .slideDown(); // change delay to 200 to see cooler and slower animation (but less user-friendly)
                            } else {
                                $(this)
                                .delay(20 * index)
                                .slideUp();
                            }
                            });
                        $(".dropdown-button").toggleClass("visible");
                        });

                    </script>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            <a onclick="signIn()" class="cool-button">Log In</a>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                        <img src="{{ asset('assets/icons/logout-icon.png') }}" height="35" alt="Logout Icon">
                                    </a>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="profile">
                                        {{ __('Profile') }}
                                        <img src="{{ asset('assets/icons/user.png') }}" height="35" alt="User Icon">
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        <footer>
            <p>Pet Food | Source of information on what you can or cannot feet to your pets, provided by the community.</p>
        </footer>
    </div>
    <!-- Modal Language change -->
    <div class="modal fade" id="languageChangeModal" tabindex="-1" role="dialog" aria-labelledby="languageChangeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="GET" action="{{ route('change.language') }}" class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="languageChangeModalLabel">Change Language</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            
            <!-- Mobile Version -->
            <div class="modal-body d-flex flex-wrap">
                @foreach(config('languages') as $key => $language)
                    <div class="language-item">
                        <input @if($key == App::currentLocale()) checked @endif type="radio" name="language" value="{{ $key }}" id="{{ $key }}" class="d-none">
                        <label for="{{ $key }}" class="language-mobile" href="#" title="{{ $language['name'] }}">
                            <div class="flag-container">
                                <img src="{{ asset('assets/flags/'.$key) }}.svg" alt="flag-{{ $key }}">
                                <span class="language-code-mobile">{{ strtoupper($language['code']) }}</span>
                            </div>
                            <span class="language-name-mobile">{{ $language['name'] }} @if($key == App::currentLocale()) (current) @endif</span>
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
    </div>
    <script>
        var appContainer = document.getElementById('app');
        var signUpContainer = document.getElementById('signUp');
        var signInContainer = document.getElementById('signIn');

        function signIn() {
            appContainer.classList.remove('register-visible');
            appContainer.classList.add('login-visible');
            event.stopPropagation();
        }

        function signUp() {
            appContainer.classList.remove('login-visible');
            appContainer.classList.add('register-visible');
            event.stopPropagation();
        }

        function closePopup() {
            appContainer.classList.remove('login-visible', 'register-visible');
        }
// Add event listener to the document body for clicks
document.body.addEventListener('click', function (event) {
    // Check if at least one of the classes is present
    if ((appContainer.classList.contains('login-visible') || appContainer.classList.contains('register-visible')) &&
        !signInContainer.contains(event.target) && !signUpContainer.contains(event.target)) {
        closePopup();
    }
});

        function Validate() {
            var password = document.getElementById("reg-psw").value;
            var confirmPassword = document.getElementById("psw-repeat").value;
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
        
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#reg-psw");
        const passwordrepeat = document.querySelector("#psw-repeat");
        
        togglePassword.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            passwordrepeat.setAttribute("type", type);
            
            this.classList.toggle("bi-eye");
        });
        
        const togglePasswordd = document.querySelector("#togglePasswordd");
        const passwordd = document.querySelector("#log-psw");
        
        togglePasswordd.addEventListener("click", function () {
            const type = passwordd.getAttribute("type") === "password" ? "text" : "password";
            passwordd.setAttribute("type", type);
            
            this.classList.toggle("bi-eye");
        });
        
        // // prevent form submit
        // const form = document.querySelector("form");
        // form.addEventListener('submit', function (e) {
        //     e.preventDefault();
        // });
    </script>
</body>
</html>
@php
    dd($errors);
@endphp