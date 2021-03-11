@extends('layouts.app')

@section('content')
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 text-center">
        {{ __('My account') }}
      </h1>
    </div>
</header>
<main class="bg-gray-100">
    <div class="max-w-5xl mx-auto py-16 sm:px-6 lg:px-8">
      <!-- Replace with your content -->
        <div class="px-4 sm:px-0 bg-white pt-12 shadow-lg">
            <div class="mt-6">
                <div class="max-w-lg mx-auto">
                    <div class="max-w-xs mx-auto">
                        <img class="rounded-full shadow-lg w-3/4 mx-auto" src="{{ asset('img/avatar-image/').'/'.$accountInfo->avatar.'.jpg' }}" alt="$accountInfo->avatar">
                    </div>
                    <div class="mt-6 text-gray-500">
                        <h3 class="text-center text-3xl">{{ $accountInfo->name }}</h3>
                        <p class="text-center text-lg">{{ $accountInfo->state.', '.$accountInfo->country }}</p>
                    </div>
                    <hr class="my-6">
                    <div class="text-black pb-20">
                        <h2 class="text-3xl text-center mt-6">{{ __('Social Activity Status') }}</h2>
                        <p class="text-xl text-center mt-10">{{ __('Engagement Rate') }}</p>
                        <h2 class="text-3xl text-center mt-3">{{ $accountInfo->arg_rate.'%' }}</h2>
                        <p class="text-xl text-center mt-10">{{ __('Audience Interests') }}</p>
                        <div>
                            <p class="text-xl mt-3">{{ __('Beauty & Fashion: ').$accountInfo->bf_rate.'%' }}</p>
                            <div class="w-full h-7 bg-white border-solid border-2 border-gray-300 mt-1 rounded">
                                <div class="h-6 bg-green-500 rounded" style="width: {{ $accountInfo->bf_rate }}%"></div>
                            </div>
                        </div>
                        <div>
                            <p class="text-xl mt-3">{{ __('TV & Media: ').$accountInfo->tm_rate.'%' }}</p>
                            <div class="w-full h-7 bg-white border-solid border-2 border-gray-300 mt-1 rounded">
                                <div class="h-6 bg-blue-500 rounded" style="width: {{ $accountInfo->tm_rate }}%"></div>
                            </div>
                        </div>
                        <div>
                            <p class="text-xl mt-3">{{ __('Music: ').$accountInfo->m_rate.'%' }}</p>
                            <div class="w-full h-7 bg-white border-solid border-2 border-gray-300 mt-1 rounded">
                                <div class="h-6 bg-red-500 rounded" style="width: {{ $accountInfo->m_rate }}%"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
