@extends('layouts.app')

@section('content')
<div class="w-full md:max-w-7xl mx-auto py-1 px-3 sm:px-6 lg:px-8 bg-gray-800 h-10">
  <p class="italic text-lg md:text-xl text-white font-bold leading-8" style="font-family: 'Josefin Sans', sans-serif;">{{ __('COLLABORATIONS') }}</p>
</div>
<div class="w-full md:max-w-7xl mx-auto px-2 h-8" style="border-bottom: 1px solid lightgray" id="collTab">
    <a class="active mx-4 px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 collTab" onclick="openTab('accepted')" id="accepted">{{ __('ACCEPTED') }}</a>
    <a class="mx-4 px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 collTab" onclick="openTab('completed')" id="completed">{{ __('COMPLETED') }}</a>
    <a class="mx-4 px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 collTab" onclick="openTab('disputed')" id="disputed">{{ __('DISPUTED') }}</a>
</div>
<main>
  <div class="max-w-md mx-auto sm:px-6 lg:px-8">
    <div class="tabContent" id="accepted" >
      accepted
    </div>
    <div class="tabContent" id="completed">
      completed
    </div>
    <div class="tabContent" id="disputed">
      disputed
    </div>
  </div>
</main>
<script>
  openTab('accepted');
  function openTab(id) {
    $("div#collTab a.collTab").removeClass('active');
    $("div#collTab a.collTab#" + id).addClass('active');
    $("div.tabContent").hide();
    $("div#" + id).show();
  }
</script>
@endsection