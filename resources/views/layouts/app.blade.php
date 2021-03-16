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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- pusher scripts-->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/css/all.css') }}">
    <style>
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
        #mail-component #tabMenu a.active {
            padding-bottom: 8px;
            font-weight: bold;
            border-bottom: solid 3px rgb(92,180,184);
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
    </style>
</head>
<body onresize="resize()">

    <div>
        <nav class="shadow-xl">
            <div class="bg-white w-full px-4 lg:px-8 fixed top-0 shadow-xl py-3 z-50">
              <div class="w-full mx-auto md:max-w-7xl">
                <div class="float-right">
                    <div class="ml-4 flex items-center md:ml-6">
                        @guest
                            {{-- @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-gray-500 hover:text-indigo-700 px-3 py-2 rounded-md text-sm font-medium">{{ __('Login') }}</a>
                            @endif
                            
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-gray-500 hover:text-indigo-700 px-3 py-2 rounded-md text-sm font-medium">{{ __('Register') }}</a>
                            @endif --}}
                        @else
                            <button class="bg-gray-800 p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                            <span class="sr-only">View notifications</span>
                            <!-- Heroicon name: outline/bell -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            </button>
                
                            <!-- Profile dropdown -->
                            <div class="ml-3 relative">
                              <div>
                                  <button type="button" class="max-w-xs bg-gray-800 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" id="user-menu" aria-expanded="false" aria-haspopup="true">
                                  <span class="sr-only">Open user menu</span>
                                  @if (isset($accountInfo->avatar))
                                      <img class="h-8 w-8 rounded-full" src="{{ asset('img/avatar-image').'/'.$accountInfo->avatar.'.jpg' }}" alt="{{ asset('img/avatar-img').'/'.$accountInfo->avatar }}">
                                  @else
                                      <img class="h-8 w-8 rounded-full" src="{{ asset('img/avatar-image/johndoeavatar.jpg') }}" alt="John Doe Avatar">
                                      @endif
                                  </button>
                              </div>
                
    
                              <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu" id="user-menu-content" style="display: none;">
                                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
                  
                                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                  
                                  <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('logout') }}"
                                  onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();" role="menuitem">
                                  {{ __('Logout') }}
                                  </a>
    
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                      @csrf
                                  </form>
                              </div>
                            </div>
                            @endguest                    
                    </div>
                </div>
                <div class="w-full">
                  <a href="{{route('welcome')}}" class="block w-32 mx-auto">
                    <img class="w-full" src="{{ asset('img/logo1.png') }}" alt="site_logo">
                  </a>
                </div>
              </div>
            </div>
      
          <!-- Mobile menu, show/hide based on menu state. -->
          @guest
          @else
            <div class="w-full fixed bottom-0 z-50">
              <div class="bg-white w-full md:max-w-7xl mx-auto object-center" id="mobile-menu">
                  <div class="px-1 py-1 grid grid-cols-5 sm:px-3 w-full border-t-xl" style="border-top: 1px solid lightgrey; border-radius:15px 15px 0 0;">
                  <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                  <a href="{{ route('home') }}" class="text-gray-400 hover:text-black block mx-5 py-2 text-center">
                      <i class="fas fa-home"></i>
                  </a>
          
                  <a href="{{ route('inbox') }}" class="text-gray-400 hover:text-black block mx-5 py-2 text-center">
                      <i class="far fa-comment-alt"></i>
                  </a>
          
                  <a href="{{ route('task') }}" class="text-gray-400 hover:text-black block mx-5 py-2 text-center">
                      <i class="fas fa-paperclip"></i>
                  </a>
          
                  <a href="{{ route('search') }}" class="text-gray-400 hover:text-black block mx-5 py-2 text-center">
                      <i class="fas fa-search"></i>
                  </a>
          
                  <a href="#" class="text-gray-400 hover:text-black block mx-5 py-2 text-center">
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
      function resize() {
        if({{ $page }} == 1) {
          var width = $("#avatar_back_img").css('width').slice(0, -2);
          console.log(width);
          $("#avatar_back_img").css('height', width/2 + 'px');
        }
        var searchContainerHeight = window.innerHeight - 140;
        $("#influencers").css('height', searchContainerHeight + 'px');
        var collaborateContainerHeight = window.innerHeight - 200;
        $("#collaborate").css('height', collaborateContainerHeight + 'px');
      }
        $(document).ready(function() {
          resize();
          var page = {{ $page }};
          var element = $("#mobile-menu a").eq(page - 1);
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
            $("#tabMenu a").click(function() {
                console.log($(this).attr('href'));
                $("#tabMenu a.active").removeClass('active');
                $(this).addClass('active');
            });
        });
        const fileTempl = document.getElementById("file-template"),
        imageTempl = document.getElementById("image-template"),
        empty = document.getElementById("empty");

        // use to store pre selected files
        let FILES = {};

        // check if file is of type image and prepend the initialied
        // template to the target element
        function addFile(target, file) {
        const isImage = file.type.match("image.*"),
            objectURL = URL.createObjectURL(file);

        const clone = isImage
            ? imageTempl.content.cloneNode(true)
            : fileTempl.content.cloneNode(true);

        clone.querySelector("h1").textContent = file.name;
        clone.querySelector("li").id = objectURL;
        clone.querySelector(".delete").dataset.target = objectURL;
        clone.querySelector(".size").textContent =
            file.size > 1024
            ? file.size > 1048576
                ? Math.round(file.size / 1048576) + "mb"
                : Math.round(file.size / 1024) + "kb"
            : file.size + "b";

        isImage &&
            Object.assign(clone.querySelector("img"), {
            src: objectURL,
            alt: file.name
            });

        empty.classList.add("hidden");
        target.prepend(clone);

        FILES[objectURL] = file;
        }

        const gallery = document.getElementById("gallery"),
        overlay = document.getElementById("overlay");

        // click the hidden input of type file if the visible button is clicked
        // and capture the selected files
        const hidden = document.getElementById("hidden-input");
        document.getElementById("button").onclick = () => hidden.click();
        hidden.onchange = (e) => {
        for (const file of e.target.files) {
            addFile(gallery, file);
        }
        };

        // use to check if a file is being dragged
        const hasFiles = ({ dataTransfer: { types = [] } }) =>
        types.indexOf("Files") > -1;

        // use to drag dragenter and dragleave events.
        // this is to know if the outermost parent is dragged over
        // without issues due to drag events on its children
        let counter = 0;

        // reset counter and append file to gallery when file is dropped
        function dropHandler(ev) {
        ev.preventDefault();
        for (const file of ev.dataTransfer.files) {
            addFile(gallery, file);
            overlay.classList.remove("draggedover");
            counter = 0;
        }
        }

        // only react to actual files being dragged
        function dragEnterHandler(e) {
        e.preventDefault();
        if (!hasFiles(e)) {
            return;
        }
        ++counter && overlay.classList.add("draggedover");
        }

        function dragLeaveHandler(e) {
        1 > --counter && overlay.classList.remove("draggedover");
        }

        function dragOverHandler(e) {
        if (hasFiles(e)) {
            e.preventDefault();
        }
        }

        // event delegation to caputre delete events
        // fron the waste buckets in the file preview cards
        gallery.onclick = ({ target }) => {
        if (target.classList.contains("delete")) {
            const ou = target.dataset.target;
            document.getElementById(ou).remove(ou);
            gallery.children.length === 1 && empty.classList.remove("hidden");
            delete FILES[ou];
        }
        };

        // print all selected files
        document.getElementById("submit").onclick = () => {
        alert(`Submitted Files:\n${JSON.stringify(FILES)}`);
        console.log(FILES);
        };

        // clear entire selection
        document.getElementById("cancel").onclick = () => {
        while (gallery.children.length > 0) {
            gallery.lastChild.remove();
        }
        FILES = {};
        empty.classList.remove("hidden");
        gallery.append(empty);
        };
    </script>
</body>
</html>
