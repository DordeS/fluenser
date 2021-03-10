<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Kavivanar&display=swap" rel="stylesheet">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- slider -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

        <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <style>
        /* slide show */
                    /* Slideshow container */
            #slide-content {
            position: relative;
            }

            /* Hide the images by default */
            .mySlides {
            display: none;
            }
            .xl-Slides {
                display: none;
            }

            /* Next & previous buttons */
            .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -22px;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
            }

            /* Position the "next button" to the right */
            .next {
            right: 0;
            border-radius: 3px 0 0 3px;
            }

            /* On hover, add a black background color with a little bit see-through */
            .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
            }

            /* Caption text */
            .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
            }

            /* Number text (1/3 etc) */
            .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
            }

            /* The dots/bullets/indicators */
            .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
            }

            .active, .dot:hover {
            background-color: #717171;
            }

            /* Fading animation */
            .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
            }

            @-webkit-keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
            }

            @keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
            }
            /* end slides */

            #wel_btn {
                position: absolute;
                bottom: 25px;
                left: 30px;
            }

            #back_img {
                position: relative;
            }

            #avatar_img {
                position: absolute;
            }
        </style>
    </head>
    <body class="antialiased">
        <div>
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{route('welcome')}}">
                        <img class="h-10" src="{{ asset('img/logo.png') }}" alt="Workflow">
                    </a>
                </div>
                </div>
                <div class="md:block">
                <div class="ml-4 flex items-center md:ml-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Log In</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </nav>
        <main class="bg-gray-100">
            <div>
            <!-- Replace with your content -->
            <div class="sm:px-0">
                <div id="slide-content">
                    <!-- Slideshow container -->
                    <div class="slideshow-container hidden md:block" id="xl_slide">
                        <!-- Full-width images with number and caption text -->
                        <div class="xl-Slides fade">
                            <img src="{{ asset('img/slide-image/xl-slide1.jpg') }}" style="width:100%">
                        </div>
                        <div class="xl-Slides fade">
                            <img src="{{ asset('img/slide-image/xl-slide2.jpg') }}" style="width:100%">
                        </div>
    
                        <div class="xl-Slides fade">
                            <img src="{{ asset('img/slide-image/xl-slide3.jpg') }}" style="width:100%">
                        </div>
                    </div>
                    <div class="slideshow-container md:hidden" id="sm_slide">
                        <!-- Full-width images with number and caption text -->
                        <div class="mySlides fade">
                            <img src="{{ asset('img/slide-image/slide1.jpg') }}" style="width:100%">
                        </div>
    
                        <div class="mySlides fade">
                            <img src="{{ asset('img/slide-image/slide2.jpg') }}" style="width:100%">
                        </div>
    
                        <div class="mySlides fade">
                            <img src="{{ asset('img/slide-image/slide3.jpg') }}" style="width:100%">
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start" id="wel_btn">
                        <div class="rounded-md shadow">
                          <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            Hire an influencer
                          </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                          <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                            Join as influencer
                          </a>
                        </div>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto">
                    <div class="lg:text-center">
                        <p class="mt-20 text-5xl leading-8 font-extrabold tracking-tight text-gray-900 text-center text-indigo-600 tracking-wider leading-normal" style="font-family: 'Kavivanar', cursive;">
                          Featured Influencers
                        </p>
                        <p class="mt-10 max-w-2xl text-xl text-gray-500 mx-auto text-center">
                          Lorem ipsum dolor sit amet consect adipisicing elit. Possimus magnam voluptatum cupiditate veritatis in accusamus quisquam.
                        </p>
                    </div>
                    <div class="mt-24">
                        <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        @foreach ($featuredInfluencers as $influencer)
                            <div class="flex">
                                <dl class="space-y-10 md:space-y-0 md:grid md:grid-rows-7 md:gap-y-8 md:gap-x-8">
                                    <div class="rounded-full row-span-4 pl-7 pr-7">
                                        <div id="back_img" class="pb-10">
                                            <img src="{{ asset('img/back-image/').'/'.$influencer->back_img.".jpg" }}" alt="{{ $influencer->back_img }}" class="mx-auto">
                                        </div>
                                        <img src="{{ asset('img/avatar-image/').'/'.$influencer->avatar.".jpg" }}" alt="{{ $influencer->avatar }}" class="rounded-full mx-auto" id="avatar_img">
                                    </div>
                                    <div class="text-center row-span-3">
                                        <dt class="text-lg leading-6 font-medium text-gray-900 px-15">
                                            Competitive exchange rates
                                        </dt>
                                        <dd class="mt-2 text-base text-gray-500">
                                            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            @endforeach
                        </dl>
                      </div>            
                </div>
                <div class="bg-black">
                    <div class="max-w-7xl mx-auto">
                        <div class="lg:text-center text-white pt-20 pb-20 mt-20">
                            <a href="#">Terms of service</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
    <script>
    $(document).ready(function() {
        $("#user-menu-content").css('display', 'none');
        $("#user-menu").click(function() {
            var con = $("#user-menu-content").css('display');
            if(con == 'block') $("#user-menu-content").css('display', 'none');
            else $("#user-menu-content").css('display', 'block');
        });
    });
    var slideIndex = 0;
    
    showSlides();
    
    
    function showSlides() {
    var slide = ($("#xl_slide").css('display') == 'block') ? "xl-Slides" : "mySlides";
    var i;
    var slides = document.getElementsByClassName(slide);
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    slides[slideIndex-1].style.display = "block";
    setTimeout(showSlides, 4000); // Change image every 2 seconds
    }
    </script>
</html>
