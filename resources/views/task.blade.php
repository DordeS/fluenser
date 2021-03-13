@extends('layouts.app')

@section('content')
<main>
  <div class="max-w-md mx-auto py-6 sm:px-6 lg:px-8 mt-10">
    <!-- Replace with your content -->
    <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">Tasks</p>
      <div class="px-4 sm:px-0 bg-white pt-5">
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
                        {{ $task->status }}
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
              @endforeach
            </div>
          </div>
      </div>
  </div>
</main>
@endsection