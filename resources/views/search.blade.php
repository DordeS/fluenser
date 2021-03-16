@extends('layouts.app')

@section('content')
<header class="bg-white">
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">Search Influencers</p>
  </div>
</header>

  <main class="md:max-w-7xl mx-auto"  id="influencers" style="overflow: auto">
    <div class="max-w-md mx-auto sm:px-6 lg:px-8">
      <!-- Replace with your content -->
        <div class="px-4 sm:px-0 bg-white">
          <form action="/search" method="get">
            @csrf
            <div class="max-w-lg mt-3 mb-5">
              <select name="category" id="category" class="block w-6/12 mx-auto rounded" style="height: 40px; padding: 5px 10px; border:1px solid lightgrey;" value = {{old('category')}}>
                <option value="Category">Category</option>
                @foreach ($categories as $category)
                    <option value={{ $category->category_name }}>{{ $category->category_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="max-w-lg my-5">
              <select name="country" id="country" class="block w-6/12 mx-auto rounded" style="height: 40px; padding: 5px 10px; border:1px solid lightgrey;" value={{old('country')}}>
                <option value='Location'>Location</option>
                @foreach ($countries as $country)
                    <option value={{ $country->name }}>{{ $country->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="max-w-lg my-5">
              <input type="text" name="name" id="name" class="block w-6/12 mx-auto rounded" style="height: 40px; padding: 5px 10px; border:1px solid lightgrey;" placeholder="Name" value={{old('name')}}>
              @error('name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="max-w-lg my-5">
              <div class="w-7/12 float-left">
                <input type="text" name="keyword" id="keyword" class="w-full mx-auto rounded" style="height: 40px; padding: 5px 10px; border:1px solid lightgrey;" placeholder="keyword" value={{old('keyword')}}>
                @error('keyword')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <button type="submit" class="w-4/12 float-right font-bold text-white rounded bg-yellow-400 text-md md:text-ld" style="line-height:40px;"><li class="fas fa-search"></li> Search</button>
              <div class="clearfix"></div>
            </div>
          </form>
        </div>
        <hr class="my-4">
        <div>
          <div class="w-10/12 mx-auto">
          @if (count($influencers) == 0)
              <p class="text-center">No matching influencers.</p>
          @else
            @foreach ($influencers as $influencer)
              <div class="w-full mx-auto relative" style="margin-bottom: 20%;">
                  <img class="w-full mx-auto" src="{{ asset('img/back-image/').'/'.$influencer->back_img.'.jpg' }}" alt="$influencer->avatar">
                  <div class="w-3/12 mx-auto absolute" style="bottom: -23%;left:50%;margin-left:-12.5%;">
                      <img class="rounded-full w-full" src="{{ asset('img/avatar-image/').'/'.$influencer->avatar.'.jpg' }}" alt="$influencer->avatar" style="border:solid 8px white;box-shadow: 0 2px 1px 1px lightgrey">
                  </div>
              </div>
              <div class="mt-6 text-gray-500">
                  <h3 class="text-center text-xl">{{ $influencer->name }}</h3>
                  <p class="text-center text-sm">{{ $influencer->state.', '.$influencer->country }}</p>
              </div>
              <div class="w-9/12 mx-auto my-5">
                <div class="float-left w-1/2">
                  <a href={{ route('profile', ['user_id' => $influencer->id]) }} class="block w-full py-2 text-center text-white rounded-l-lg" style="background: #119dac"><i class="fas fa-user"></i> Profile</a>
                </div>
                <div class="float-right w-1/2">
                  <a href={{ route('collaborate', ['user_id' => $influencer->id]) }} class="block w-full py-2 text-center text-white bg-yellow-400 rounded-r-lg"><i class="fas fa-comment-alt"></i> Collaborate</a>
                </div>
              </div>
            @endforeach
          @endif
          </div>
        </div>
    </div>
  </main>
@endsection