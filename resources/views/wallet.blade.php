@extends('layouts.app')
@section('content')
<header class="bg-white">
  <div class="w-full md:max-w-7xl mx-auto py-1 px-3 sm:px-6 lg:px-8 bg-gray-800 h-10">
    <p class="italic text-lg md:text-xl text-white font-bold leading-8" style="font-family: 'Josefin Sans', sans-serif;">{{ __('BALANCE') }}</p>
  </div>
</header>

  <main class="w-full md:max-w-7xl mx-auto">
    <div class="w-full md:max-w-7xl mx-auto sm:px-6 lg:px-8" id="collaborate" style="overflow: auto">
      <div class="w-11/12 mx-auto">
        <div class="w-full rounded-xl mt-3 relative" style="background: #119dab">
          <img src={{ asset('img/wallet.png') }} alt="wallet" class="w-full rounded-xl" style="opacity: 0.36">
          <div class="w-full absolute left-0 text-center" style="top: 50%; transform:translateY(-50%);">
            <div class="balance text-white">
              <p class="text-lg md:text-xl">BALANCE</p>
              <p class="text-2xl md:text-3xl font-bold my-3">{{ number_format($wallet->usd_balance, 2).' USD' }}</p>
              <p class="text-2xl md:text-3xl font-bold my-3">{{ number_format($wallet->gbp_balance, 2).' GBP' }}</p>
              <p class="text-2xl md:text-3xl font-bold my-3">{{ number_format($wallet->eur_balance, 2).' EUR' }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full bg-gray-200 px-2 py-3 mt-3">
        <div class="flex justify-center md:justify-around">
          <div class="mx-2">
            <button class="w-full rounded-lg py-2 px-3 text-white" style="background: #119dab;font-size:12px;"><img src={{ asset('img/deposit.png') }} alt="deposit" style="width: 19px; margin-top:3px; float:left">&nbsp;&nbsp;&nbsp;Deposit Funds</button>
          </div>
          <div class="mx-2">
            <button class="w-full rounded-lg text-white px-3 py-2 bg-gray-500" style="font-size: 12px;"><img src={{ asset('img/withdraw.png') }} alt="deposit" style="width: 19px; margin-top:3px; float:left">&nbsp;&nbsp;&nbsp;Withdraw Money</button>
          </div>
        </div>
      </div>
      <div class="w-11/12 mx-auto">
        <div class="h-10 mt-3">
          <p class="text-md md:text-lg leading-10 font-bold">Transactions</p>
        </div>
        <div class="w-full mt-3">
          @foreach ($walletActions as $action)
              <div class="w-full py-2 h-16" style="border-bottom: 1px solid #999">
                <div class="flex justify-between">
                  <div>
                    <p class="text-md md:text-lg font-semibold leading-8">{{ ucwords($action->action) }}</p>
                    <p class="text-xs md:text-sm leading-6">{{ $action->created_at }}</p>
                  </div>
                  <div>
                    <p class="text-xs md:text-sm leading-10 py-2">
                      @if ($action->aaa == '+')
                        <span class="text-green-500">{{ $action->aaa }}</span>
                      @else
                        <span class="text-red-500">{{ $action->aaa }}</span>
                      @endif
                      <span>{{ number_format($action->amount, 2).' '.strtoupper($action->currency) }}</span>
                    </p>
                  </div>
                </div>
              </div>
          @endforeach
        </div>
      </div>
    </div>
  </main>
