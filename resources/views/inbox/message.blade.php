@extends('layouts.inbox')
@section('inbox-content')
  <main class="bg-gray-100">
    <div class="max-w-5xl mx-auto py-16 sm:px-6 lg:px-8">
      <!-- Replace with your content -->
        <div class="px-4 sm:px-0 bg-white pt-12 shadow-lg">
          
        </div>
    </div>
  </main>
  <div id="app">
    <div id="wrap">
      <div class="container">
        <div class="page-header">
          <h1>Laravel + Websockets + React.js</h1>
        </div>
        <h3> Message </h3>
        <!-- component react -->
        <div id="message-component"></div>
      </div>
    </div>
  </div>
  <!-- importar component vue -->
  <script src="{{ asset('js/app.js') }}" ></script>
@endsection