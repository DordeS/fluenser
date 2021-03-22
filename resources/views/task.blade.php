@extends('layouts.app')

@section('content')
<header class="bg-white">
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">Tasks</p>
  </div>
</header>
<main>
  <div class="max-w-md mx-auto sm:px-6 lg:px-8">
    <!-- Replace with your content -->
      <div class="px-4 sm:px-0 bg-white">
          <div class="mt-6">
            <div class="w-10/12 mx-auto">
              @foreach ($tasks as $task)
                  <div class="w-full grid grid-rows-6">
                    <div class="w-full grid grid-cols-3" style="overflow: hidden;height:28px;">
                      <div class="col-span-1 font-bold">
                        TaskID
                      </div>
                      <div class="col-span-2">
                        {{ $task->id }}
                      </div>
                    </div>
                    <div class="w-full grid grid-cols-3" style="overflow: hidden;height:28px;">
                      <div class="col-span-1 font-bold">
                        Brand
                      </div>
                      <div class="col-span-2">
                        {{ $task->brand }}
                      </div>
                    </div>
                    <div class="w-full grid grid-cols-3" style="overflow: hidden;height:28px;">
                      <div class="col-span-1 font-bold">
                        Amount
                      </div>
                      <div class="col-span-2">
                        {{ $task->amount }} {{ $task->unit }}
                      </div>
                    </div>
                    <div class="w-full grid grid-cols-3" style="overflow: hidden;height:28px;">
                      <div class="col-span-1 font-bold">
                        Task
                      </div>
                      <div class="col-span-2">
                        {{ $task->content }}
                      </div>
                    </div>
                    <div class="w-full grid grid-cols-3" style="overflow: hidden;height:28px;">
                      <div class="col-span-1 font-bold">
                        Status
                      </div>
                      <div class="col-span-2">
                        @switch($task->status)
                            @case(1)
                                Awaiting for Deposit
                                @break
                            @case(2)
                                Deposit Made
                                @break
                            @case(3)
                                Deposit Released
                            @default
                                
                        @endswitch
                      </div>
                    </div>
                    <div class="w-full grid grid-cols-3" style="overflow: hidden;height:28px;">
                      <div class="col-span-1 font-bold">
                        Added Time
                      </div>
                      <div class="col-span-2">
                        {{ $task->created_at }}
                      </div>
                    </div>
                    <hr class="mt-3 mb-8">
                  </div>
                  @if ($accountInfo->accountType == 'brand')
                  <button id="action_btn" class="float-right px-2 py-1 rounded text-white" style="background: #119dab">
                    @if ($task->status == 1)
                        Deposit funds
                    @endif
                  </button>
                  @endif
              @endforeach
            </div>
          </div>
      </div>
  </div>
</main>
@endsection