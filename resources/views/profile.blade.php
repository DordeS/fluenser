@extends('layouts.app')
@section('content')
<main>
  <div class="w-full md:max-w-7xl mx-auto mb-12">
    <!-- Replace with your content -->
      <div class="bg-white">
        <div class="w-full">
          <div class="relative overflow-hidden">
            <a href={{ route('search') }}>
              <div class="absolute top-4 left-2 rounded-full h-8 w-8 bg-white text-center" style="box-shadow: 0 0 15px #999">
                <p class="leading-8 text-gray-400 text-lg">
                  <i class="fas fa-arrow-left"></i>
                </p>
              </div>
            </a>
            <a href={{ route('search') }}>
              <div class="absolute top-4 right-2 rounded-full h-8 w-8 bg-white text-center" style="box-shadow: 0 0 15px #999">
                <p class="leading-8 text-gray-400 text-lg">
                  <i class="fas fa-heart"></i>
                </p>
              </div>
            </a>
            <img src={{ asset('img/profile-image/'.$profile->top_img.'.jpg') }} alt={{ $profile->top_img }} class="w-full">
            <img src="{{ asset('img/gradient.png') }}" alt="gradient" style="position: absolute; bottom:-50%;" class="w-full">
            <div class="w-10/12 absolute px-2 pb-2 bottom-5 bg-white h-36 md:h-40" style="left: 50%; margin-left: -41%; bottom:30px">
              <div class="relative ml-2 h-8">
                <div class="absolute" style="width: 33%;bottom:0;">
                  <img src={{ asset('img/profile-image/'.$profile->round_img.'.jpg') }} alt={{ $profile->round_img }} class="rounded-full" style="border:3px solid white">
                </div>
                <div style="margin-left: 30%;" class="h-full text-gray-500">
                  <span><i class="fas fa-circle" style="font-size: 5px; line-height:2rem;"></i></span>
                  <span class="leading-8 text-xs md:text-sm" style="line-height: 2rem;">last seen 5 minutes ago</span>
                </div>
              </div>
              <div class="relative ml-2">
                <div class="float-left w-9/12">
                  <p class="text-md md:text-lg font-bold">{{ $influencerInfo->name }}</p>
                  <p class="text-xs md:text-sm text-gray-700 mt-1">{{ '@'.$influencerInfo->username }}</p>
                  <div class="text-xs md:text-sm">
                    <span style="line-height:26px;">
                      @for ($i = 0; $i < 5; $i++)
                        @if ($influencerInfo->rating > $i)
                          <i class="fas fa-star text-yellow-400"></i>
                        @else
                          <i class="fas fa-star text-gray-400"></i>
                        @endif
                      @endfor
                    </span>
                    <span class="px-2 rounded text-yellow-400 mr-1" style="padding: 3px; line-height:20px;">{{ number_format($influencerInfo->rating, 1) }}</span>
                  </div>
                  <p class="text-sm md:text-md text-gray-700 mt-1"><i style="color: #119dab" class="fas fa-map-marker-alt"></i> {{ $influencerInfo->country.' '.$influencerInfo->state }}</p>
                </div>
                <div class="float-right w-3/12 pr-2 pt-3">
                  <div class="mb-2 px-1 py-1 rounded-lg w-full" style="background:#f0effe">
                    <p class="text-sm text-center" style="color: #6f60fa">{{ $categories[0]->category_name }}</p>
                  </div>
                  <div class="mb-2 px-1 py-1 rounded-lg w-full" style="background: #fcefed">
                    <p class="text-sm text-center" style="color: #ea5e51">{{ $categories[1]->category_name }}</p>
                  </div>
                </div>
                <div id="social_links" class="w-3/5 float-right">
                  <div class="w-10 h-10 rounded-full float-right mx-1 bg-white text-center" style="box-shadow: 0 0  8px 0 #999">
                    <a href="#" class="text-center leading-10"><i class="fab fa-tiktok"></i></a>
                  </div>
                  <div class="w-10 h-10 rounded-full float-right mx-1 bg-white text-center" style="box-shadow: 0 0  8px 0 #999">
                    <a href="#" class="text-center leading-10 text-red-700"><i class="fab fa-youtube"></i></a>
                  </div>
                  <div class="w-10 h-10 rounded-full float-right mx-1 bg-white text-center" style="box-shadow: 0 0  8px 0 #999">
                    <a href="#" class="text-center leading-10"><i class="fab fa-instagram" style="background:-webkit-linear-gradient(#792ec5, #c62e71, #da8a40);-webkit-background-clip: text;-webkit-text-fill-color: transparent;"></i></a>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full mt-10 mb-16">
          <div class="w-11/12 mx-auto rounded-lg bg-gray-200">
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
            <div id="introduction" class="w-11/12 mx-auto my-8">
              <p class="text-center text-md md:text-lg">
                {{ $profile->introduction }}
              </p>
            </div>
          </div>
          <div id="reviews" class="tabcontent w-11/12 mx-auto">
            <div id="reviews" class="w-11/12 mx-auto my-8">
              @if (count($reviews) == 0)
                <p class="text-center text-md md:text-lg">
                  No review.
                </p>
              @else
                @foreach ($reviews as $review)
                    <p class="text-center text-md md:text-lg">
                      {{ $review }}
                    </p>
                @endforeach
              @endif
            </div>
          </div>
          <div id="portfolio" class="w-11/12 mx-auto">
            <div class="portfolio_slides relative">
              <div id="demo" class="carousel slide rounded-xl relative z-10" data-ride="carousel">
                {{-- <!-- Indicators -->
                <ul class="carousel-indicators">
                  <li data-target="#demo" data-slide-to="0" class="active"></li>
                  @for ($i = 1; $i < count($portfolios); $i++)
                    <li data-target="#demo" data-slide-to={{ $i + 1 }}></li>
                  @endfor
                </ul> --}}
                <!-- The slideshow -->
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <div id="count" class="bg-gray-900 bg-opacity-70 absolute bottom-5 right-5 px-4 py-2 rounded-lg z-30">
                      <p class="text-center text-white">
                        {{ '1/'.count($portfolios) }}
                      </p>
                    </div>
                    <img src={{ asset('img/profile-image/'.$portfolios[0]->slide_img).'.jpg' }} alt={{ $portfolios[0]->slide_img }} class="w-full rounded-xl">
                  </div>
                  
                  @for ($i = 1; $i < count($portfolios); $i++)
                  <div class="carousel-item">
                    <div id="count" class="bg-gray-900 bg-opacity-70 absolute bottom-5 right-5 px-4 py-2 rounded-lg z-30">
                      <p class="text-center text-white">
                        {{ ($i + 1).'/'.count($portfolios) }}
                      </p>
                    </div>
                    <img src={{ asset('img/profile-image/'.$portfolios[$i]->slide_img).'.jpg' }} alt={{ $portfolios[$i]->slide_img }} class="w-full rounded-xl">
                  </div>
                  @endfor

                </div>
              </div>
              <div>
                <div class="w-11/12 bg-gray-300 h-20 rounded-xl absolute" style="left: 50%; margin-left: -46%; bottom:-1rem; z-index:2"></div>
                <div class="w-10/12 bg-gray-200 h-20 rounded-xl absolute" style="left: 50%;margin-left: -42%; bottom:-2rem; z-index:1"></div>
              </div>
            </div>
          </div>
        </div>
        <div id="partnership" class="w-11/12 mx-auto rounded pb-14" style="box-shadow: 0 0 3px 3px #eee;">
          <p class="text-center text-gray-500 py-5 text-lg md:text-xl font-bold">
            Influencer Partnerships
          </p>
          <div id="partnership_slide">
            <div class="w-11/12 mx-auto">
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
                  <div class="carousel-item active">
                    <img src={{ asset('img/partnership-image/'.$partnerships[0]->partnership_img).'.jpg' }} alt={{ $partnerships[0]->partnership_img }} class="w-full rounded-xl">
                  </div>
                  
                  @for ($i = 1; $i < count($partnerships); $i++)
                  <div class="carousel-item">
                    <img src={{ asset('img/partnership-image/'.$partnerships[$i]->partnership_img).'.jpg' }} alt={{ $partnerships[$i]->partnership_img }} class="w-full rounded-xl">
                  </div>
                  @endfor

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="w-full fixed bottom-0 z-50 bg-white">
      <div class="w-full md:max-w-7xl mx-auto py-2" style="box-shadow: 0 0 10px 0 #333">
        <div class="w-8/12 mx-auto">
          <a href={{ route('collaborate', ['user_id' => $influencerInfo->id]) }} class="block w-full py-2 text-center text-white font-bold text-lg md:text-xl rounded-lg" style="background: linear-gradient(to right, #1277d3, #06ebbe)">Collaborate</a> 
        </div>
      </div>
    </div>
</main>
<script>
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
</script>
@endsection