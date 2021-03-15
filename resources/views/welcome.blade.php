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
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,600;1,300&display=swap" rel="stylesheet">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- slider -->
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

        <link rel="stylesheet" href="{{ asset('css/css/all.css') }}">

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
                width: 200px;
                position: absolute;
                bottom: 150px;
                left: 50vw;
                margin-left: -100px;
            }

            #hire_btn {
                border:none;
                border-radius:10px;
                background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));
            }

            #join_btn {
                border: solid 1px white;
                background: transparent;
            }

            #back_img {
                position: relative;
            }

            #avatar_img {
                position: absolute;
            }
            .clearfix {
                display: table;
                content: '';
                clear: both;
            }
        </style>
    </head>
    <body class="antialiased">
        <div>
        <nav class="bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{route('welcome')}}">
                        <img class="h-10" src="{{ asset('img/logo.jpg') }}" alt="Workflow">
                    </a>
                </div>
                </div>
                <div class="md:block">
                <div class="ml-4 flex items-center md:ml-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="text-gray-300 hover:text-indigo-700 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-indigo-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-gray-500 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
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
        <main class="bg-white">
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
                        <div id="slide-text" style="text-align: center;color:white; position: absolute;bottom:340px;">
                            <p style="font-family: 'Dancing Script', cursive;font-size: 30px;">The home of influencers</p>
                            <p style="font-size: 18px;">Collaborate with influencers directly to promote your brand.</p>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start mx-auto" id="wel_btn">
                        <div class="rounded-md shadow">
                          <a href="#" class="w-full flex items-center justify-center px-8 py-3 text-base font-medium rounded-md text-white hover:bg-indigo-700 md:py-4 md:text-lg md:px-10"
                          id="hire_btn">
                            Hire an influencer
                          </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                          <a href="#" class="w-full flex items-center justify-center px-8 py-3 text-base text-white font-medium rounded-md hover:bg-indigo-200 md:py-4 md:text-lg md:px-10" id="join_btn">
                            Join as influencer
                          </a>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <img src="{{ asset('img/partnerships.png') }}" alt="partnerships" class="mb-10 w-full">
                    <img src="{{ asset('img/phone.png') }}" alt="phone" class="mb-10 w-full">
                    <h1 style="font-family: 'Josefin Sans', sans-serif; text-align:center; background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));-webkit-background-clip:text;-webkit-text-fill-color:transparent; font-size:36px">How it Works</h1>
                    <div class="mx-auto w-10/12 text-center text-gray-500 mb-5">
                        <p>With secure payments and thousands of reviewed influencers to choose from, Fluenser is the simplest and safest way to collaborate with influencers online.</p>
                    </div>
                    <div class="w-11/12 mx-auto rounded px-2 py-2 mb-5" style="box-shadow: 0 0 5px 5px #eee">
                        <div class="w-full">
                            <div class="rounded-full px-1 py-1" style="background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));width: 30px; height:30px;margin:2px 0;float:left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="RGB(255,255,255)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <div class="text-gray-500 ml-3" style="margin-left: 40px;">Sign up, no hidden costs and completely free to user</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="w-11/12 mx-auto rounded px-2 py-2 mb-5" style="box-shadow: 0 0 5px 5px #eee">
                        <div class="w-full">
                            <div class="rounded-full px-1 py-1" style="background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));width: 30px; height:30px;margin:2px 0;float:left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#fff">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                  </svg>                            </div>
                            <div class="text-gray-500 ml-3" style="margin-left: 40px;">Discover thousands of verified influencers.</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="w-11/12 mx-auto rounded px-2 py-2 mb-5" style="box-shadow: 0 0 5px 5px #eee">
                        <div class="w-full">
                            <div class="rounded-full px-1 py-1" style="background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));width: 30px; height:30px;margin:2px 0;float:left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#fff">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                  </svg>
                            </div>
                            <div class="text-gray-500 ml-3" style="margin-left: 40px;">Directly message influencers with your proposal wether it`s gifted or paid promotion.</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="w-11/12 mx-auto rounded px-2 py-2 mb-5" style="box-shadow: 0 0 5px 5px #eee">
                        <div class="w-full">
                            <div class="rounded-full px-1 py-1" style="background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));width: 30px; height:30px;margin:2px 0;float:left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#fff">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                  </svg>
                            </div>
                            <div class="text-gray-500 ml-3" style="margin-left: 40px;">Your money is held securely by us until you approve the influencers work.</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="w-11/12 mx-auto rounded px-2 py-2 mb-5" style="box-shadow: 0 0 5px 5px #eee">
                        <div class="w-full">
                            <div class="rounded-full px-1 py-1" style="background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));width: 30px; height:30px;margin:2px 0;float:left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#fff">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                  </svg>
                            </div>
                            <div class="text-gray-500 ml-3" style="margin-left: 40px;">Collaborate completed time to leave a review!</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <h1 style="font-family: 'Josefin Sans', sans-serif, Semi-bold 600; text-align:center; background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));-webkit-background-clip:text;-webkit-text-fill-color:transparent; font-size:40px" class="mb-10"><span style="font-size: 28px;font-family: 'Josefin Sans', sans-serif, Light 300 italic;line-height:30px">Featured</span><br/>Influencers</h1>
                    <img src="{{ asset('img/featured.png') }}" alt="partnerships" class="mb-5 w-full">
                </div>
                {{-- <div class="max-w-7xl mx-auto">
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
                </div> --}}
                <div class="w-wull relative">
                    <img src="{{asset('img/bottom.png')}}" alt="bottom" class="w-full">
                    <div class="mx-auto absolute text-center text-white bottom-0 text-sm md:text-lg" style="width:80%; left: 50vw; margin-left:-40%">
                        <div id="pages1" style="line-height: 25px;">
                            <a href="#">Home</a> <span class="mx-3">|</span>
                            <a href="#">Blog</a> <span class="mx-3">|</span>
                            <a href="#">Contact Us</a> <span class="mx-3">|</span>
                            <a href="#">FAQ</a>
                        </div>
                        <div id="pages2" style="line-height: 25px;" class="mb-3">
                            <a href="#">Term & Agreement</a> <span class="mx-3">|</span>
                            <a href="#">Privacy</a>
                        </div>
                        <div id="links" class="mb-3">
                            <div class="w-full grid grid-cols-5 gap-x-2">
                                <div class="col-span-1">
                                    <div style="font-size:20px; width:35px;height:35px;margin:auto;line-height:35px;border-radius:50%;color:white;background:rgba(220,220,220,0.5)">
                                        <i class="fab fa-twitter"></i>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div style="font-size:20px; width:35px;height:35px;margin:auto;line-height:35px;border-radius:50%;color:white;background:rgba(220,220,220,0.5)">
                                        <i class="fab fa-facebook-f"></i>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div style="font-size:20px; width:35px;height:35px;margin:auto;line-height:35px;border-radius:50%;color:white;background:rgba(220,220,220,0.5)">
                                        <i class="fab fa-instagram"></i>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div style="font-size:20px; width:35px;height:35px;margin:auto;line-height:35px;border-radius:50%;color:white;background:rgba(220,220,220,0.5)">
                                        <i class="fab fa-tiktok"></i>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div style="font-size:20px; width:35px;height:35px;margin:auto;line-height:35px;border-radius:50%;color:white;background:rgba(220,220,220,0.5)">
                                        <i class="fab fa-youtube"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mx-3 mb-1" color="#eee"/>
                        <p class="text-center mb-1">&copy; 2021 Fluenser</p>
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
