@extends('layouts.app')
@section('content')
<main>
  <div class="w-full md:max-w-7xl mx-auto mb-12">
    <!-- Replace with your content -->
      <div class="bg-white">
        <div class="w-full relative">
          <div class="relative">
            <a href={{ route('home') }}>
              <div class="absolute top-4 left-2 rounded-full h-8 w-8 bg-white text-center" style="box-shadow: 0 0 15px #999">
                <p class="leading-8 text-gray-400 text-lg">
                  <i class="fas fa-arrow-left"></i>
                </p>
              </div>
            </a>
            <a onclick="toggleSaved()">
              <div class="absolute top-4 right-2 rounded-full h-8 w-8 bg-white text-center" style="box-shadow: 0 0 15px #999">
                @if ($saved)
                  <p class="leading-8 text-gray-400" style="color: #0f97cd" id="saved">
                    <i class="fas fa-heart"></i>
                  </p>
                @else
                  <p class="leading-8 text-gray-400 text-lg" id="saved">
                    <i class="fas fa-heart"></i>
                  </p>
                @endif
              </div>
            </a>
            <img src={{ asset('img/profile-image/'.$profile->top_img.'.jpg') }} alt={{ $profile->top_img }} class="w-full">
            <div class="w-10/12 absolute px-2 pb-2 bottom-5 bg-white h-36 md:h-40" style="left: 50%; margin-left: -41%; bottom:60px">
              <div class="relative ml-2 h-8">
                <div class="absolute" style="width: 33%;bottom:0;">
                  <img src={{ asset('img/profile-image/'.$profile->round_img.'.jpg') }} alt={{ $profile->round_img }} class="rounded-full" style="border:3px solid white">
                </div>
                <div style="margin-left: 30%;" class="h-full text-gray-500">
                  <span><i class="fas fa-circle" style="font-size: 5px; line-height:2rem;"></i></span>
                  <span class="leading-8 text-xs md:text-sm" style="line-height: 2rem;" style="font-family: 'Poppins', sans-serif; font-weight: 500;">last seen 5 minutes ago</span>
                </div>
              </div>
              <div class="relative ml-2">
                <div class="float-left w-9/12" style="font-family: 'Poppins', sans-serif;">
                  <p class="text-md md:text-lg font-bold" style="font-weight:600">{{ $accountInfo->name }}</p>
                  <p class="text-xs md:text-sm text-gray-700" style="font-weight: 400;">{{ '@'.$accountInfo->username }}</p>
                  <div class="text-sm md:text-md">
                    <span class="px-1 rounded text-white font-bold rounded-lg mr-1 text-xs md:text-sm" style="padding: 1px 3px; line-height:20px; background:#f5a321;">{{ number_format($accountInfo->rating, 1) }}</span>
                    <span style="line-height:26px;">
                      @for ($i = 0; $i < 5 ; $i++)
                        @if ($accountInfo->rating > $i)
                          <i class="fas fa-star" style="color: #f5a321"></i>
                        @else
                          <i class="fas fa-star text-gray-400"></i>
                        @endif
                      @endfor
                    </span>
                  </div>
                  <p class="text-sm md:text-md text-gray-700 mt-1" style="font-weight: 400;"><i style="color: #119dab" class="fas fa-map-marker-alt"></i> {{ $accountInfo->state.', '.$accountInfo->country }}</p>
                </div>
                <div class="float-right w-3/12 pr-2 pt-3" style="font-family: 'Poppins', sans-serif;">
                  @if(count($categories) > 0)
                  <div class="mb-2 px-1 py-1 rounded-lg w-full" style="background:#f0effe">
                    <p class="text-sm text-center" style="color: #6f60fa; font-weight:500;">{{ $categories[0]->category_name }}</p>
                  </div>
                  <div class="mb-2 px-1 py-1 rounded-lg w-full" style="background: #fcefed">
                    <p class="text-sm text-center" style="color: #ea5e51; font-weight:500;">{{ $categories[1]->category_name }}</p>
                  </div>
                  @endif
                </div>
                <div id="social_links" class="w-3/5 float-right">
                  <div class="w-10 h-10 rounded-full float-right mx-1 bg-white text-center" style="box-shadow: 0 0  8px 0 #999">
                    <a href="{{ $profile->tiktok }}" class="text-center leading-10"><i class="fab fa-tiktok"></i></a>
                  </div>
                  <div class="w-10 h-10 rounded-full float-right mx-1 bg-white text-center" style="box-shadow: 0 0  8px 0 #999">
                    <a href="{{ $profile->youtube }}" class="text-center leading-10 text-red-700"><i class="fab fa-youtube"></i></a>
                  </div>
                  <div class="w-10 h-10 rounded-full float-right mx-1 bg-white text-center" style="box-shadow: 0 0  8px 0 #999">
                    <a href="{{ $profile->instagram }}" class="text-center leading-10"><i class="fab fa-instagram" style="background:-webkit-linear-gradient(#792ec5, #c62e71, #da8a40);-webkit-background-clip: text;-webkit-text-fill-color: transparent;"></i></a>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <div class="h-8 rounded-t-2xl bg-white w-full absolute -bottom-1"></div>
        </div>
        <div class="w-full pt-2 pb-8">
          <div class="w-11/12 mx-auto rounded-lg bg-gray-200" style="font-family: 'Poppins', sans-serif; font-weight: 600; ">
            <div class="w-full grid grid-cols-2">
              <div class="col-span-1 px-1 py-1">
                <button class="tablink py-1 rounded-lg w-full text-gray-500 font-bold" onclick="openTab('profile', this)" id="defaultOpen">Profile</button>
              </div>
              <div class="col-span-1 px-1 py-1">
                <button class="tablink py-1 rounded-lg w-full text-gray-500 font-bold" onclick="openTab('reviews', this)">Reviews</button>
              </div>
            </div>
          </div>
          <div id="profile" class="tabcontent w-11/12 mx-auto">
            <div id="introduction" class="w-full my-2 py-3">
              <p class="text-md md:text-lg">
                {{ $profile->introduction }}
              </p>
            </div>
            <hr class="my-4" />
            <div id="portfolio" class="w-full mx-auto">
              @if (count($portfolios) > 0)
              <div id="portfolio-slide" class="w-full overflow-hidden relative">
                <div style="visibility: hidden;" class="w-4/5 px-2">
                  <img src="{{asset('img/profile-image/'.$portfolios[0]->slide_img.'.jpg')}}" alt="hidden image" style="width: 100%;">
                </div>
                <div id="portfolio-img" class="absolute top-0" style="right: -80%">
                  @foreach ($portfolios as $portfolio)
                  <div class="slide_item float-left px-3">
                    <img src="{{asset('img/profile-image/'.$portfolio->slide_img.'.jpg')}}" alt="slide1" class="w-full rounded-lg">
                  </div>
                  @endforeach
                </div>
                <div class="clearfix"></div>
              </div>
              @else
              <div class="w-full my-2">
                Please update your profile.
              </div>                  
              @endif

            </div>

          @if ($accountInfo->accountType == 'influencer')
            <div id="partnership" class="w-full mx-auto pb-3">
              <p class="text-center text-gray-400 py-2 mt-3 text-md md:text-lg tracking-wide" style="font-family: 'Poppins', sans-serif; font-weight:500;">
                INFLUENCER PARTNERSHIPS
              </p>
              <div id="partnership_slide" class="py-1 px-1 rounded-xl" style="box-shadow: 0 0 3px 3px lightgray">
                <div class="w-full mx-auto">
                  <div id="partnerships" class="carousel slide rounded-xl relative z-10" data-ride="carousel">
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                      <li data-target="#partnerships" data-slide-to="0" class="active"></li>
                      @for ($i = 1; $i < count($partnerships); $i++)
                        <li data-target="#partnerships" data-slide-to={{ $i + 1 }}></li>
                      @endfor
                    </ul>
                    <!-- The slideshow -->
                    <div class="carousel-inner">
                      @if(count($partnerships) > 0)
                      <div class="carousel-item active">
                        <img src={{ asset('img/partnership-image/'.$partnerships[0]->partnership_img).'.jpg' }} alt={{ $partnerships[0]->partnership_img }} class="w-full rounded-xl">
                      </div>
                      
                      @for ($i = 1; $i < count($partnerships); $i++)
                      <div class="carousel-item">
                        <img src={{ asset('img/partnership-image/'.$partnerships[$i]->partnership_img).'.jpg' }} alt={{ $partnerships[$i]->partnership_img }} class="w-full rounded-xl">
                      </div>
                      @endfor
                      @else
                      <div class="text-center w-full">
                        <p class="text-sm md:text-md">Please complete you profile</p>
                      </div>
                      @endif
    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
              
        </div>
          <div id="reviews" class="tabcontent w-11/12 mx-auto pb-10">
            <div id="reviews" class="w-11/12 mx-auto my-8">
              @if (count($reviews) == 0)
                <p class="text-center text-md md:text-lg">
                  No review.
                </p>
              @else
                @foreach ($reviews as $review)
                  <div class="title my-2">
                    <p class="text-lg md:text-xl font-semibold">
                      {{ $review->title }}
                    </p>
                  </div>
                  <div class="rating my-2">
                    <span class="px-2 py-1 bg-yellow-400 rounded-md text-white text-xs md:text-sm font-bold">{{ number_format($review->star, 1) }}</span>
                    @for ($i = 0; $i < 5; $i++)
                      @if ($review->star > $i)
                        <i class="fas fa-star text-yellow-400"></i>
                      @else
                        <i class="fas fa-star text-gray-400"></i>
                      @endif
                    @endfor
                  </div>
                  <div class="review my-2">
                    <p class="text-sm md:text-md">{{ $review->review }}</p>
                  </div>
                  <div class="com my-2">
                    <p class="text-xs md:text-sm text-gray-500">by {{ $review->name }} - {{ $review->interval }} ago</p>
                  </div>
                  <hr class="mt-3">
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="w-full fixed bottom-0 z-50 bg-white">
      <div class="w-full md:max-w-7xl mx-auto py-3" style="border-top: 1px solid lightgray">
        <div class="w-8/12 mx-auto">
          <a href={{ route('collaborate', ['user_id' => $accountInfo->id]) }} class="block w-full py-2 text-center text-white font-bold text-lg md:text-xl rounded-lg" style="background: #0ac2c8; font-family:'Poppins', sans-serif; font-weight:500;">Collaborate</a> 
        </div>
      </div>
    </div>
</main>
<script>
  var firstPos, interval;
  var portfolio_count = "{{ count($portfolios) }}";
  $("#portfolio-img").css('width', (portfolio_count * 80) + '%');
  $(".slide_item").css('width', (100/portfolio_count) + '%');
  $(document).ready(function () {
    firstPos = $("div#portfolio-img").css('right').slice(0, -2);
    interval = setInterval(slideMove, 12);
  });

  function slideMove() {
    var pos = $("div#portfolio-img").css('right').slice(0, -2);
    if (pos == 0) {
      var element = $("#portfolio-img").children()[0];
      $("#portfolio-img").children()[0].remove();
      $("#portfolio-img").append(element);
      pos = firstPos;
    }
    $("div#portfolio-img").css('right', parseInt(pos) + 1);
  }
  function openTab(tabname, elmnt) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Remove the background color of all tablinks/buttons
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }

  // Show the specific tab content
  document.getElementById(tabname).style.display = "block";

  // Add the specific color to the button used to open the tab content
  elmnt.style.backgroundColor = 'white';
  }
    
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
    
    
  function toggleSaved() {
    const headers ={
        'Accept': 'application/json'
      };
      var api_token = $("meta[name=api-token]").attr('content');
      var url = "{{ url('/') }}/api/savedToggle/{{$accountInfo->id}}?api_token=";
      url = url + api_token;
      console.log(url);
      $.ajax({
        url: url,
        type: "GET",
        headers: headers,
        success: function(res) {
          if(res.data == 1) {
            $("p#saved").css('color', '#0f97cd');
          } else {
            $("p#saved").css('color', 'rgb(156, 163, 175)')
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log(XMLHttpRequest, textStatus, errorThrown);
        }
      });
  }

</script>
@endsection