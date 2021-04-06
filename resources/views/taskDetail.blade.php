@extends('layouts.app')

@section('content')
<header class="bg-white">
  <div class="w-full md:max-w-7xl mx-auto py-1 px-3 sm:px-6 lg:px-8 bg-gray-800 h-10">
    <span><a href="{{ route('task') }}" class="text-white"><i class="fas fa-chevron-left"></i></a></span>
    <span class="italic text-lg md:text-xl text-white font-bold leading-8" style="font-family: 'Josefin Sans', sans-serif;">{{ __('COLLABORATIONS') }}</span>
  </div>
</header>
  <main class="md:max-w-7xl mx-auto">
    <div class="w-full md:max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3 mb-20">
      <div class="w-11/12 mx-auto">
        <div class="text-center">
          @if ($requests->status < 3)
            <p class="text-lg md:text-xl font-bold" id="status">Accepted</p>
          @else
            <p class="text-lg md:text-xl font-bold">Completed</p>
          @endif
          <p class="text-sm md:text-md">{{ $requests->title }}</p>
        </div>
        <hr class="my-3">
        <div class="text-center">
          <div class="w-1/3 px-1 py-1 mx-auto rounded-full my-3" style="background: linear-gradient(to right, #15ecc2, #1278d3)">
            <img class="w-full mx-auto rounded-full" src={{ asset('img/profile-image/'.$accountInfo->avatar.'.jpg') }} alt={{$accountInfo->avatar}}>
          </div>
          <div class="mt-3 mb-4">
            <p class="text-md md:text-lg font-bold">{{$accountInfo->name}}</p>
            <p class="text-xs md:text-sm">{{'@' . $accountInfo->username}}</p>
          </div>
          <a href={{ route('inbox') }} class="px-4 py-2 rounded-md border border-gray-500 text-sm md:text-md">Chat</a>
        </div>
        <hr class="my-4">
        <div class="mt-4 mb-3 text-center">
          <p class="text-md md:text-lg text-bold">Payment</p>
        </div>
          <div class="w-full grid grid-cols-2 gap-x-5">
            <div class="col-span-1">
              <div class="w-8/12 float-right rounded-xl px-3 py-3" style="box-shadow: 0 0 10px 0 #999">
                <p class="text-lg md:text-xl font-bold">
                  @if ($requests->status < 3)
                    <span id="progress">{{ number_format($requests->amount, 2)}}</span>
                  @else
                    <span id="progress">0.00</span>
                  @endif
                  {{' ' . strtoupper($requests->unit) }}</p>
                <p class="text-xs md:text-sm">in progress</p>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="col-span-1">
              <div class="w-8/12 float-left rounded-xl px-3 py-3" style="box-shadow: 0 0 10px 0 #999" >
                <p class="text-lg md:text-xl font-bold">
                  @if ($requests->status < 3)
                    <span id="released">0.00</span>                    
                  @else
                    <span id="released">{{ number_format($requests->amount, 2)}}</span>
                  @endif
                  {{" " . strtoupper($requests->unit) }}</p>
                <p class="text-xs md:text-sm">Released</p>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
      </div>
      <div class="w-10/12 mx-auto">
        @if ($requests->status < 3)
          @if ($accountInfo->accountType == 'influencer')
            <button class="rounded mt-4 block w-full py-2 text-center text-md md:text-lg font-bold text-white" style="background: #0ac2c8" id="release" onclick="$('div#confirmModal').show()">Release Deposit</button>            
          @else
            <button class="rounded mt-4 block w-full py-2 text-center text-md md:text-lg font-bold text-white" style="background: #0ac2c8" id="release" onclick="$('div#confirmModal').show()">Request to Release</button>
          @endif
        @else
          @if ($accountInfo->accountType == 'influencer')
            @if ($requests->rs_review == 0)
              <a class="rounded mt-4 block w-full py-2 text-center text-md md:text-lg font-bold text-white" style="background: #0ac2c8" id="release" href="{{ route('leaveReview', ['request_id' => $requests->id]) }}">Leave a Review</a>
            @else
              <a class="rounded mt-4 block w-full py-2 text-center text-md md:text-lg font-bold text-white" style="background: #0ac2c8" id="release" href="#">Completed</a>
            @endif
          @else
            @if ($requests->sr_review == 0)
             <a class="rounded mt-4 block w-full py-2 text-center text-md md:text-lg font-bold text-white" style="background: #0ac2c8" id="release" href="{{ route('leaveReview', ['request_id' => $requests->id]) }}">Leave a Review</a>
            @else
             <a class="rounded mt-4 block w-full py-2 text-center text-md md:text-lg font-bold text-white" style="background: #0ac2c8" id="release" href="#">Completed</a>
            @endif
          @endif
        @endif
      </div>
    </div>
  </main>

  <script>
    function onReleaseClick() {
      $("div#confirmModal #modalBody").html('');
      var element = $("<img src={{asset('img/loading.gif')}} class='mx-auto' />");
      $("div#confirmModal #modalBody").append(element);

      const headers ={
        'Accept': 'application/json'
      };
      var api_token = $("meta[name=api-token]").attr('content');
      var url = "{{ url('/') }}/api/releaseDeposit/{{$requests->id}}?api_token=";
      url = url + api_token;
      console.log(url);
      $.ajax({
        url: url,
        type: "GET",
        headers: headers,
        success: function(res) {
          console.log(res);
          $("span#released").text("{{number_format($requests->amount, 2)}}");
          $("span#progress").text('0.00');
          $("div#confirmModal").hide();
          $("div#reviewModal").show();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log(XMLHttpRequest, textStatus, errorThrown);
        }
      });
    }
  </script>