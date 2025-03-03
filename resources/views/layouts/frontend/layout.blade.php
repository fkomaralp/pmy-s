<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $title)</title>
    <link rel="shortcut icon" type="image/png" href="{{$favicon}}"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/frontend/style.css" />
    <script defer src="/frontend/js/main.js"></script>
    <script
        src="/public/frontend/js/jquery.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart_count', {{$cart_count}});
        })
    </script>
</head>

<body x-data="">
<div class="container">
    <header id="header" class="d-flex flex-wrap justify-content-center">
        <a href="{{route("frontend.index")}}" title="picmyrun"
           class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <img src="@yield('logo', $logo)" alt="" id="headerlogo" />
        </a>

        <ul id="headermenu" class="nav d-flex align-items-center">
            <li class="nav-item"><a href="{{route("frontend.about.index")}}" class="nav-link">About</a></li>
{{--            <li class="nav-item"><a href="#" class="nav-link">Calendar</a></li>--}}
            <li class="nav-item"><a href="{{route("frontend.faq.index")}}" class="nav-link">FAQ</a></li>
            <li class="nav-item"><a href="{{route("frontend.contact.index")}}" class="nav-link">Contact</a></li>
            @if(!isset($without_cart))
                <li class="nav-item"><a href="{{route("frontend.cart.index")}}" class="nav-link">CART <span class="cart-count" x-text="$store.cart_count"></span></a></li>
            @endif
        </ul>
    </header>
</div>

@yield('content')

<div id="footer">
    <div class="footerLogo">
        <img src="@yield('favicon', $favicon)" alt="@yield('title')" />
    </div>
    <div class="social">
        @if($linkedin !== "")<a href="{{$linkedin}}"><img src="/frontend/img/linkedin.svg" alt="Linkedin" /></a>@endif
        @if($twitter !== "")<a href="{{$twitter}}"><img src="/frontend/img/twitter.svg" alt="Twitter" /></a>@endif
        @if($facebook !== "")<a href="{{$facebook}}"><img src="/frontend/img/facebook.svg" alt="Facebook" /></a>@endif
        @if($instagram !== "")<a href="{{$instagram}}"><img src="/frontend/img/instagram.svg" alt="Instagram" /></a>@endif
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
</script>
@stack('scripts')
</body>

</html>
