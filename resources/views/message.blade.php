@extends('layouts.app')
@section('content')
{{-- <header class="bg-white shadow">
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 text-center">
      {{ __('Mail') }}
    </h1>
  </div>
</header> --}}

  <main class="md:max-w-7xl mx-auto">
    <div class="w-full">
      <!-- Replace with your content -->
        <div class="sm:px-0 bg-white">
          <div id="mail-component"></div>
        </div>
    </div>
  </main>
  <script src="{{ asset('js/app.js') }}" ></script>
@endsection