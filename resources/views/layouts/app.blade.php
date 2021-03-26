<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @guest
    @else   
    <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endguest

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- pusher scripts-->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    
    <!-- bootstarp -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- croper js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/css/all.css') }}">
    <style>
        .col-md-8 {
            padding: 0 !important;
        }
        a:hover {
            text-decoration: none;
            cursor: pointer;
        }
        input[type='checkbox']:focus {
            border: none;
        }
        .invalid-feedback {
            color: red;
        }
        a.selected {
            background: linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));
            color: white;
        }
        a.unselected {
            background: lightgrey;
            color: grey;
        }
        .clearfix {
            display: table;
            content: '';
            clear: both;
        }
        .menu_selected {
            color: black;
            border-bottom: solid 4px rgb(83, 181, 193);
        }
        #mail-component #messageTab a.active,
        #searchTab a.active {
            border-bottom: solid 2px #4db3c1;
        }
        #searchTab a.active {
            color: #4db3c1;
        }
        a:focus {
            color: black !important;
        }
        #lg_tabMenu a.active {
            color: black;
        }
        #buttons button:hover {
            color:lightgrey;
        }
        #buttons button:disabled:hover {
            color: white;
        }

        .hasImage:hover section {
        background-color: rgba(5, 5, 5, 0.4);
        }
        .hasImage:hover button:hover {
        background: rgba(5, 5, 5, 0.45);
        }

        /* #overlay p, i {
        opacity: 0;
        } */

        #overlay.draggedover {
        background-color: rgba(255, 255, 255, 0.7);
        }
        #overlay.draggedover p,
        #overlay.draggedover i {
        opacity: 1;
        }

        .group:hover .group-hover\:text-blue-800 {
        color: #2b6cb0;
        }    

        img#image {
        display: block;
        max-width: 100%;
        }
        .preview {
        overflow: hidden;
        width: 160px; 
        height: 160px;
        margin: 10px;
        border: 1px solid red;
        }
        .modal-lg{
        max-width: 1000px !important;
        }
        input[type=checkbox]:checked {
            background-image: url({{ asset('img/check.png') }});
        }
        #searchCategory label {
            font-family:'Poppins', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #bbf3f1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #2bc5b5;
            border-radius: 4px;
        }
        a.payMethod.active div.payMethod {
            background: #2bc5b5 !important;
            border: none;
        }
        a.payMethod.active div.payMethod p {
            color: white;
        }
    </style>
</head>
<body>

    <div>
        <nav class="shadow-xl">      
          <!-- Mobile menu, show/hide based on menu state. -->
          @guest
          @else
            <div class="w-full fixed bottom-0 z-50">
              <div class="bg-white w-full md:max-w-7xl mx-auto object-center" id="mobile-menu">
                  <div class="px-1 py-1 grid grid-cols-5 sm:px-3 w-full border-t-xl" style="border-top: 2px solid lightgrey;">
                  <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                  <a href="{{ route('home') }}" class="text-gray-400 text-xl md:text-2xl hover:text-black block py-2 text-center">
                      <i class="fas fa-home"></i>
                  </a>
          
                  <a href="{{ route('inbox') }}" class="text-gray-400 text-xl md:text-2xl hover:text-black block py-2 text-center">
                      <i class="far fa-envelope"></i>
                  </a>
          
                  <a href="{{ route('task') }}" class="text-gray-400 text-xl md:text-2xl hover:text-black block py-2 text-center">
                      <i class="fas fa-paperclip"></i>
                  </a>
          
                  <a href="{{ route('search') }}" class="text-gray-400 text-xl md:text-2xl hover:text-black block py-2 text-center">
                      <i class="fas fa-search"></i>
                  </a>
          
                  <a href="{{ route('dashboard') }}" class="text-gray-400 text-xl md:text-2xl hover:text-black block py-2 text-center">
                      <i class="far fa-user"></i>
                  </a>
                  </div>
              </div>
            </div>
          @endguest
        </nav>
        @yield('content')
    </div>
    <script>
        $(document).ready(function() {
          var page = {{ $page }};
          var element = $("#mobile-menu a").eq(page - 1);
          console.log("loaded");
          console.log(element);
          element.addClass('menu_selected');
          $("#lg_tabMenu a").eq(page-1).addClass('active');
          $("#user-menu-content").css('display', 'none');
          $("#user-menu").click(function() {
            var con = $("#user-menu-content").css('display');
            if(con == 'block') $("#user-menu-content").css('display', 'none');
            else $("#user-menu-content").css('display', 'block');
          });
          $("#togglebar a").click(function() {
              var classname = $(this).attr('class');
              if(classname == 'unselected') {
                $("#togglebar a.selected").addClass('unselected').removeClass('selected');
                $(this).addClass('selected').removeClass('unselected');
                if($(this).attr('id') == 'influencer') {
                  $("#radio-btn input[type=radio]")[0].checked = true;
                  $("#radio-btn input[type=radio]")[1].checked = false;
                  $("#accountAlert p").text('You are registering as an Influencer');
                } else {
                  $("#radio-btn input[type=radio]")[0].checked = false;
                  $("#radio-btn input[type=radio]")[1].checked = true;
                  $("#accountAlert p").text('You are registering as a Brand');
                }
              };
            });
            $("#searchTab a").click(function() {
                console.log($(this).attr('href'));
                $("#searchTab a.active").removeClass('active');
                $(this).addClass('active');
            });
            $("#gallery .delete").click(function() {
                console.log('ok');
                $(this).parent().remove();
            });
            $("a.payMethod").click(function() {
                $("a.payMethod.active").removeClass('active');
                $(this).addClass('active');
            })

        });
    </script>
</body>
</html>
