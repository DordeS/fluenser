@extends('layouts.app')

@section('content')
<header class="bg-white">
  <div class="w-full md:max-w-7xl mx-auto py-1 px-3 sm:px-6 lg:px-8 bg-gray-800 h-10">
    <p class="italic text-lg md:text-xl text-white font-bold leading-8" style="font-family: 'Josefin Sans', sans-serif;">{{ __('SEARCH') }}</p>
  </div>  
</header>
  <main class="md:max-w-7xl mx-auto">
    <div class="w-full md:max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3 mb-20">
      <!-- Replace with your content -->
        <div class="bg-white">
          <div id="name" class="tabcontent w-11/12 mx-auto">
            <form action={{ route('search') }} method="get" id="nameForm">
              @csrf

              <select name="category" id="category" class="w-full border-none rounded-md my-3" style="box-shadow: 0 0 3px 0 #999999">
                <option value="Any"><label class="text-xs md:text-sm"  for="category">Any</label></option>
                @foreach ($categories as $category)
                  @if ($category->category_name == $selectedCategory)
                    <option value={{ $category->category_name }} selected> <label class="text-xs md:text-sm" for='category'>{{ $category->category_name }}</label> </option>
                  @else
                    <option value={{ $category->category_name }}> <label class="text-xs md:text-sm" for='category'>{{ $category->category_name }}</label> </option>
                  @endif
                @endforeach
              </select>

              <select name="country" id="country" class="w-full border-none rounded-md my-2 text-black" style="box-shadow: 0 0 3px 0 #999999">
                <option value="Any"><label class="text-xs md:text-sm" for="location">Any</label></option>
                @foreach ($countries as $country)
                  @if ($country->name == $selectedLocation)
                    <option value={{ $country->name }} selected> <label class="text-xs md:text-sm" for='location'>{{ $country->name }}</label> </option>
                  @else
                    <option value={{ $country->name }}> <label class="text-xs md:text-sm" for='location'>{{ $country->name }}</label> </option>
                  @endif
                @endforeach
              </select>

                <input type="text" name="name" id="name" style="border: 1px solid lightgray" class="block w-full mx-auto rounded text-gray-700 font-semibold my-2 h-10 shadow-inner @error('name') is-invalid @enderror" placeholder="Name" value={{$selectedName}} >

                <input type="text" name="keyword" id="keyword" style="border: 1px solid lightgray" class="block w-full mx-auto rounded text-gray-700 font-semibold my-2 h-10 shadow-inner" placeholder="keyword"  value = {{$selectedKeyword}}>

                <button type="submit" class="font-semibold block w-1/3 mx-auto rounded-lg py-2 text-white mt-2" style="background: linear-gradient(to right, #47afbe, #4addc4)"  id="category_search_btn">Search</button>
            </form>
          </div>
          <div class="w-full">
            <div class="my-3" id="searchTab" style="border-top: solid 1px lightgray; border-bottom: solid 1px lightgray">
              <div class="w-11/12 mx-auto grid grid-cols-12 h-10">
                <a class="block col-span-3 w-full h-full text-gray-500">
                  <button class="block mx-auto w-12 h-full">
                    <i class="fas fa-bars leading-10"></i>
                  </button>
                </a>
                <a class="block col-span-3 w-full h-full text-gray-500 active">
                  <button class="block mx-auto w-12 h-full">
                    <i class="fas fa-border-all leading-10"></i>
                  </button>
                </a>
                <div class="col-span-6 w-full h-10">
                  <select name="perPage" id="perPage" class="w-10/12 float-right rounded-md text-xs md:text-sm h-8 my-1 py-1" style="border: solid 1px lightgray">
                    <option value="10">10 per page</option>
                    <option value="20">20 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div class="w-11/12 mx-auto">
          @if (count($influencers) == 0)
              <p class="text-center text-sm md:text-md text-gray-500">No matching influencers.</p>
          @else
          @foreach ($influencers as $influencer)
          <a href={{ route('profile', ['username' => $influencer->username]) }}>
            <div class="w-1/2 md:w-4/12 float-left rounded-lg pb-8" style="box-shadow: 0 0 3px 3px #eee">
              <div class="w-11/12 mx-auto relative">
                <div class="w-8/12 mx-auto rounded-full px-1 py-1 mt-3" style="background: linear-gradient(to right, #06ebbe, #1277d3)" >
                  <img class="rounded-full w-full" src="{{ asset('img/avatar-image/').'/'.$influencer->avatar.'.jpg' }}" alt="$influencer->avatar" style="border:solid 2px white">
                </div>
              </div>
              <div class="mt-2">
                  <h3 class="text-center text-sm md:text-md font-bold text-gray-700">{{ $influencer->name }}</h3>
                  <p class="text-center text-xs md:text-sm text-gray-500">{{ '@'.$influencer->username }}</p>
                  <p class="text-center text-xs md:text-sm text-gray-700"><i class="fas fa-map-marker-alt" style="color: #119dab"></i> {{ $influencer->state.', '.$influencer->country }}</p>
              </div>
              <div class="mt-1 w-full">
                <div class="text-xs md:text-sm flex justify-center">
                  <span class="px-2 bg-yellow-400 rounded text-white mr-1" style="padding: 3px; line-height:20px;">{{ number_format($influencer->rating, 1) }}</span>
                  <span style="line-height:26px;">
                    @for ($i = 0; $i < 5; $i++)
                      @if ($influencer->rating > $i)
                        <i class="fas fa-star text-yellow-400"></i>
                      @else
                        <i class="fas fa-star text-gray-400"></i>
                      @endif
                    @endfor
                  </span>
                  <span class="ml-1 text-gray-700" style="line-height: 26px;">({{ $influencer->reviews }})</span>
                </div>
              </div>
              <div class="mt-2 w-full">
                <div class="text-xs md:text-sm flex justify-center">
                  @if (count($influencer->category) > 0)
                  <div class="w-1/3 text-center py-1 rounded mx-1" style="background: #f0f0fd;color:#8c82df;">
                    <p>{{ $influencer->category[0]->category_name }}</p>
                  </div>
                  <div class="w-1/3 text-center py-1 rounded mx-1" style="background: #fcefed;color:#dc8179">
                    <p>{{ $influencer->category[1]->category_name }}</p>
                  </div>
                  @else
                  <div style="height: 24px;"></div>
                  @endif
                </div>
              </div>
              <div class="mt-3 w-10/12 mx-auto" style="border-top: 1px solid lightgray">
                <div class="text-lg md:text-xl text-center flex justify-between pt-1">
                  <div class="w-1/3 text-center">
                    <p><i class="fab fa-instagram" style="background:-webkit-linear-gradient(#792ec5, #c62e71, #da8a40);-webkit-background-clip: text;-webkit-text-fill-color: transparent;"></i></p>
                    <p class="text-xs md:text-sm mt-1 text-gray-700 tracking-tighter">1k-10k</p>
                  </div>
                  <div class="w-1/3 text-center" style="border-right: 1px solid lightgray; border-left:1px solid lightgray">
                    <p><i class="fab fa-youtube text-red-400"></i></p>
                    <p class="text-xs md:text-sm mt-1 text-gray-700 tracking-tighter">10k-50k</p>
                  </div>
                  <div class="w-1/3 text-center">
                    <p><i class="fab fa-tiktok text-gray-700"></i></p>
                    <p class="text-xs md:text-sm mt-1 text-gray-700 tracking-tighter">100k-500k</p>
                  </div>
                </div>
              </div>
            </div>
          </a>
            @endforeach
            <div class="clearfix"></div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </main>
  @endsection