@extends('layouts.app')

@section('content')
{{-- <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 text-center">
        {{ __('My account') }}
      </h1>
    </div>
</header> --}}
<main>
    <div class="max-w-md mx-auto py-6 sm:px-6 lg:px-8 mt-10">
      <!-- Replace with your content -->
      <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">My Account</p>
        <div class="px-4 sm:px-0 bg-white pt-12">
            <div class="mt-6">
                <div class="w-10/12 mx-auto">
                    <div class="w-full mx-auto relative" style="margin-bottom: 20%;">
                        <img class="w-full mx-auto" src="{{ asset('img/back-image/').'/'.$accountInfo->back_img.'.jpg' }}" alt="$accountInfo->avatar">
                        <div class="w-3/12 mx-auto absolute" style="bottom: -23%;left:50%;margin-left:-12.5%;">
                            <img class="rounded-full w-full" src="{{ asset('img/avatar-image/').'/'.$accountInfo->avatar.'.jpg' }}" alt="$accountInfo->avatar" style="border:solid 8px white;box-shadow: 0 2px 1px 1px lightgrey">
                        </div>
                    </div>
                    <div class="mt-6 text-gray-500">
                        <h3 class="text-center text-xl">{{ $accountInfo->name }}</h3>
                        <p class="text-center text-sm">{{ $accountInfo->state.', '.$accountInfo->country }}</p>
                    </div>
                    <hr class="my-6">
                    @if ($accountType == 'influencer')
                        <div class="text-black pb-20" style="font-family: 'Josefin Sans', sans-serif;">
                            <h2 class="font-bold text-xl text-center mt-12">{{ __('Social Activity Status') }}</h2>
                            <p class="text-lg text-center mt-10">{{ __('Engagement Rate') }}</p>
                            <h2 class="text-3xl text-center mt-3">{{ $accountInfo->arg_rate.'%' }}</h2>
                            <p class="text-lg text-center mt-10">{{ __('Audience Interests') }}</p>
                            <div>
                                <p class="text-lg mt-3">{{ __('Beauty & Fashion: ').$accountInfo->bf_rate.'%' }}</p>
                                <div class="w-full h-5 bg-white border-solid border-2 border-gray-100 mt-1 rounded">
                                    <div class="h-4" style="width: {{ $accountInfo->bf_rate }}%;background:rgb(23,165,52);;border-radius:3px 0 0 3px;"></div>
                                </div>
                            </div>
                            <div>
                                <p class="text-lg mt-3">{{ __('TV & Media: ').$accountInfo->tm_rate.'%' }}</p>
                                <div class="w-full h-5 bg-white border-solid border-2 border-gray-100 mt-1 rounded">
                                    <div class="h-4 bg-blue-500 " style="width: {{ $accountInfo->tm_rate }}%;background:rgb(3,159,184);;border-radius:3px 0 0 3px;"></div>
                                </div>
                            </div>
                            <div>
                                <p class="text-lg mt-3">{{ __('Music: ').$accountInfo->m_rate.'%' }}</p>
                                <div class="w-full h-5 bg-white border-solid border-2 border-gray-100 mt-1 rounded">
                                    <div class="h-4 bg-red-500 " style="width: {{ $accountInfo->m_rate }}%;background:rgb(238,35,52);;border-radius:3px 0 0 3px;"></div>
                                </div>
                            </div>
                        </div>                        
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
