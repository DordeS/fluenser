@extends('layouts.app')

@section('content')
<header>
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 text-center">
      <div class="max-w-md grid grid-cols-2 gap-y-1 mx-auto">
        <div class="col-span-1">
          <a href="{{ route('inbox') }}" class="text-center text-black text-lg">Inbox</a>
        </div>
        <div class="col-span-1">
          <a href="{{ route('inbox') }}" class="text-center text-black text-lg">Request</a>
        </div>
      </div>
    </h1>
  </div>
</header>
@endsection
@yield('inbox-content')