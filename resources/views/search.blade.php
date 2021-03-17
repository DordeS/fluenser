@extends('layouts.app')

@section('content')
<header class="bg-white">
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">Search Influencers</p>
  </div>
</header>

  <main class="md:max-w-7xl mx-auto">
    <div class="w-full md:max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8 mb-20">
      <!-- Replace with your content -->
        <div class="px-4 sm:px-0 bg-white">
          <div class="w-11/12 mx-auto">
            <div class="w-full grid grid-cols-2">
              <div class="col-span-1 text-center px-1 py-1">
                <a class="tablink py-1 text-gray-500" style="color: #42aeb7" onclick="openTab('name', this)" id="defaultOpen">By Name</a>
              </div>
              <div class="col-span-1 text-center px-1 py-1">
                <a class="tablink py-1 text-gray-500" style="color: #42aeb7" onclick="openTab('category', this)">By Category</a>
              </div>
            </div>
          </div>
          <div id="name" class="tabcontent w-full mx-auto">
            <form action={{ route('search') }} method="get">
              @csrf
              <div id="searchName" class="w-full mx-auto my-8 relative">
                <input type="text" name="name" id="name" class="block w-full mx-auto rounded-full bg-gray-100 text-gray-700 font-semibold border-none h-12 px-4 py-2 text-sm md:text-lg @error('name') is-invalid @enderror" placeholder="Search influencer by name or username" value={{old('name')}}>
                <button class="absolute text-sm md:text-lg right-5 top-0 h-12 leading-12 text-gray-700"><i class="fas fa-search"></i></button>
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </form>
          </div>
          <div id="category" class="tabcontent w-full">
            <form action={{ route('search') }} method="get">
              @csrf
              <div id="searchCategory" class="w-full my-8">
                <div class="w-full md:max-w-7xl mt-3 mb-5">
                  <input name="category" id="category" class="block w-full mx-auto rounded-full bg-gray-100 text-gray-700 font-semibold border-none h-12 px-4 py-2 text-sm md:text-lg" placeholder="Search influencer by category"  value = {{old('category')}}>
                  <div class="w-full rounded-lg mt-8 py-3" style="box-shadow: 0 0 3px 3px #eee">
                    <div class="w-11/12 mx-auto h-28 overflow-auto" id="categories">
                      @foreach ($categories as $category)
                          <input type="checkbox" class="pb-2 rounded border-1 border-gray-500" value={{ $category->category_name }}> <label class="text-xs md:text-sm" for={{$category->category_name}}>{{ $category->category_name }}</label> <hr class="pb-2">
                      @endforeach
                    </div>
                    <div class="w-7/12 mx-auto">
                      <button type="submit" class="w-full mx-auto rounded-lg py-2 text-white mt-1" style="background: linear-gradient(to right, #06ebbe, #1277d3)">Search</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div>
          <div class="w-11/12 mx-auto">
          @if (count($influencers) == 0)
              <p class="text-center">No matching influencers.</p>
          @else
          @foreach ($influencers as $influencer)
          <a href={{ route('profile', ['user_id' => $influencer->id]) }}>
            <div class="w-1/2 md:w-4/12 float-left rounded-lg pb-8" style="box-shadow: 0 0 3px 3px #eee">
              <div class="w-11/12 mx-auto relative">
                <div class="w-8/12 mx-auto rounded-full px-1 py-1 mt-8" style="background: linear-gradient(to right, #06ebbe, #1277d3)" >
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
                  <div class="w-1/3 text-center py-1 rounded mx-1" style="background: #f0f0fd;color:#8c82df;">
                    <p>{{ $influencer->category[0]->category_name }}</p>
                  </div>
                  <div class="w-1/3 text-center py-1 rounded mx-1" style="background: #fcefed;color:#dc8179">
                    <p>{{ $influencer->category[1]->category_name }}</p>
                  </div>
                </div>
              </div>
              <div class="mt-3 w-10/12 mx-auto" style="border-top: 1px solid lightgray">
                <div class="text-lg md:text-xl text-center flex justify-between pt-1">
                  <div class="w-1/3 text-center">
                    <p><i class="fab fa-instagram" style="background:-webkit-linear-gradient(#792ec5, #c62e71, #da8a40);-webkit-background-clip: text;-webkit-text-fill-color: transparent;"></i></p>
                    <p class="text-xs md:text-sm mt-1 text-gray-700">1k-10k</p>
                  </div>
                  <div class="w-1/3 text-center" style="border-right: 1px solid lightgray; border-left:1px solid lightgray">
                    <p><i class="fab fa-youtube text-red-400"></i></p>
                    <p class="text-xs md:text-sm mt-1 text-gray-700">10k-50k</p>
                  </div>
                  <div class="w-1/3 text-center">
                    <p><i class="fab fa-tiktok text-gray-700"></i></p>
                    <p class="text-xs md:text-sm mt-1 text-gray-700">100k-500k</p>
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
      tablinks[i].style.fontWeight = "normal";
      tablinks[i].style.border = "none";
    }
  
    // Show the specific tab content
    document.getElementById(tabname).style.display = "block";
  
    // Add the specific color to the button used to open the tab content
    elmnt.style.fontWeight = 'bold';
    elmnt.style.borderBottom = '2px solid #42aeb7';

    }
  
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();

    var categoryInputValue = '';
    $(document).ready(function() {
      $("#categories input[type=checkbox]").click(function() {
        var clickedValue = $(this).val();
        var input = $("input#category");
        if($(this).is(':checked')) {
          categoryInputValue = categoryInputValue + ',' + clickedValue;
        } else {
          categoryInputValue = categoryInputValue.split(',' + clickedValue)[0] + categoryInputValue.split(', ' + clickedValue)[1];
        }
        input.val(categoryInputValue.slice(1));
      });
    });
  </script>
  @endsection