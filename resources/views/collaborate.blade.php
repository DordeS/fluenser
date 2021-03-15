@extends('layouts.app')
@section('content')
<header class="bg-white">
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">My Account</p>
  </div>
</header>

  <main class="md:max-w-7xl mx-auto">
    <div class="max-w-md mx-auto sm:px-6 lg:px-8">
      <!-- Replace with your content -->
        <div class="px-4 sm:px-0 bg-white w-10/12 mx-auto my-3 md:max-w-lg">
          <img src={{ asset('img/avatar-image/'.$influencerInfo->avatar.'.jpg') }} alt="$influencerInfo->avatar" class="w-4/5 mx-auto rounded-lg">
          <p class="text-center text-black text-lg md:text-xl font-bold">
            {{ $influencerInfo->name }}
          </p>
        </div>
    </div>
  </main>
@endsection