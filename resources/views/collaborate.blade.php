@extends('layouts.app')
@section('content')
<header class="bg-white">
  <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">Collaborate</p>
  </div>
</header>

  <main class="md:max-w-7xl mx-auto">
    <div class="max-w-lg mx-auto sm:px-6 lg:px-8" id="collaborate" style="overflow: auto">
      <!-- Replace with your content -->
        <div class="px-4 sm:px-0 bg-white w-11/12 mx-auto my-3 md:max-w-lg">
          <img src={{ asset('img/avatar-image/'.$influencerInfo->avatar.'.jpg') }} alt="$influencerInfo->avatar" class="w-4/5 mx-auto rounded-lg">
          <p class="text-center text-black text-lg md:text-xl font-bold">
            {{ $influencerInfo->name }}
          </p>
          <p class="text-center text-gray-500 text-sm md:text-md">
            {{ $influencerInfo->country." ".$influencerInfo->state }}
          </p>
          <div class="w-full mt-10">
            <form action="" method="get">
              <input type="text" name="title" id="title" class="w-full rounded-lg bg-gray-200 border-none my-5" placeholder="Project Title">
              <textarea name="detail" id="detail" class="w-full rounded-lg bg-gray-200 border-none my-5" placeholder="Describe your project" rows='5'></textarea>
              <div class="attach w-full rounded-lg my-5">
                {{-- file upload --}}
                <div class="w-full h-xl sm:px-8 md:px-16 sm:py-8">
                  <main class="container mx-auto max-w-screen-lg h-full">
                    <!-- file upload modal -->
                    <article aria-label="File Upload Modal" class="relative h-full flex flex-col bg-white rounded-lg" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);" ondragenter="dragEnterHandler(event);">
           
                      <!-- scroll area -->
                      <section class="h-full w-full h-full flex flex-col">
                        <header class="border-dashed border-2 border-gray-200 py-12 flex flex-col justify-center items-center rounded-lg">
                          <input id="hidden-input" type="file" name="images" multiple class="hidden" />
                          <a id="button" class="mt-2 rounded-sm px-3 py-1 bg-gray-700 hover:bg-gray-500 focus:shadow-outline focus:outline-none rounded-xl text-white">
                            <i class="fas fa-plus-circle"></i>
                            Attach File
                          </a>
                          <p class="mb-3 font-semibold text-gray-900 flex flex-wrap justify-center underline text-xs md:text-sm text-gray-300">
                            Max size is 20MB.
                          </p>
                        </header>
            
                        <h1 class="pt-8 pb-3 font-semibold sm:text-lg text-gray-900">
                          To Upload
                        </h1>
            
                        <ul id="gallery" class="flex flex-1 flex-wrap -m-1">
                          <li id="empty" class="h-full w-full text-center flex flex-col items-center justify-center items-center">
                            <img class="mx-auto w-32" src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png" alt="no data" />
                            <span class="text-small text-gray-500">No files selected</span>
                          </li>
                        </ul>
                      </section>
            
                      <!-- sticky footer -->
                      <footer class="flex justify-end px-8 pb-8 pt-4">
                      </footer>
                    </article>
                  </main>
                </div>
            
                <!-- using two similar templates for simplicity in js code -->
                <template id="file-template">
                  <li class="block p-1 w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 xl:w-1/8 h-24">
                    <article tabindex="0" class="group w-full h-full rounded-md focus:outline-none focus:shadow-outline elative bg-gray-100 cursor-pointer relative shadow-sm">
                      <img alt="upload preview" class="img-preview hidden w-full h-full sticky object-cover rounded-md bg-fixed" />
            
                      <section class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3">
                        <h1 class="flex-1 group-hover:text-blue-800"></h1>
                        <div class="flex">
                          <span class="p-1 text-blue-800">
                            <i>
                              <svg class="fill-current w-4 h-4 ml-auto pt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M15 2v5h5v15h-16v-20h11zm1-2h-14v24h20v-18l-6-6z" />
                              </svg>
                            </i>
                          </span>
                          <p class="p-1 size text-xs text-gray-700"></p>
                          <button class="delete ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md text-gray-800">
                            <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                              <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                            </svg>
                          </button>
                        </div>
                      </section>
                    </article>
                  </li>
                </template>
            
                <template id="image-template">
                  <li class="block p-1 w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 xl:w-1/8 h-24">
                    <article tabindex="0" class="group hasImage w-full h-full rounded-md focus:outline-none focus:shadow-outline bg-gray-100 cursor-pointer relative text-transparent hover:text-white shadow-sm">
                      <img alt="upload preview" class="img-preview w-full h-full sticky object-cover rounded-md bg-fixed" />
            
                      <section class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3">
                        <h1 class="flex-1"></h1>
                        <div class="flex">
                          <span class="p-1">
                            <i>
                              <svg class="fill-current w-4 h-4 ml-auto pt-" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M5 8.5c0-.828.672-1.5 1.5-1.5s1.5.672 1.5 1.5c0 .829-.672 1.5-1.5 1.5s-1.5-.671-1.5-1.5zm9 .5l-2.519 4-2.481-1.96-4 5.96h14l-5-8zm8-4v14h-20v-14h20zm2-2h-24v18h24v-18z" />
                              </svg>
                            </i>
                          </span>
            
                          <p class="p-1 size text-xs"></p>
                          <button class="delete ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md">
                            <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                              <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                            </svg>
                          </button>
                        </div>
                      </section>
                    </article>
                  </li>
                </template>
                <div class="w-full mx-auto my-5">
                  <p class="text-center text-gray-500 text-sm md:text-md mb-5">
                    How will you compensate the influencers?
                  </p>
                  <div class="float-left active" style="width: 90px; height: 90px; border-radius:50%; background:white; border: 1px solid lightgray; padding:15px">
                    <p class="text-3xl text-gray-500 text-center" style="line-height: 35px"><i class="fas fa-dollar-sign"></i></p>
                    <p class="text-center text-sm text-gray-500" style="line-height: 25px">
                      Money
                    </p>
                  </div>
                  <div class="float-right mx-auto" style="width: 90px; height: 90px; border-radius:50%; background:white; border: 1px solid lightgray; padding:15px">
                    <p class="text-3xl text-white text-center text-gray-500" style="line-height: 35px"><i class="fas fa-shopping-bag"></i></p>
                    <p class="text-center text-sm  text-gray-500" style="line-height: 25px">
                      Both
                    </p>
                  </div>
                  <div class="mx-auto" style="width: 90px; height: 90px; border-radius:50%; background:white; border: 1px solid lightgray; padding:15px">
                    <p class="text-3xl text-white text-center text-gray-500" style="line-height: 35px"><i class="fas fa-shopping-bag"></i></p>
                    <p class="text-center text-sm  text-gray-500" style="line-height: 25px">
                      Product
                    </p>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="w-full">
                  <label for="price" class="block text-sm font-medium text-gray-700">Budget</label>
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">
                        $
                      </span>
                    </div>
                    <input type="text" name="price" id="price" class="block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" style="height: 38px">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                      <label for="currency" class="sr-only">Currency</label>
                      <select id="currency" name="currency" class="h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md bg-gray-300" style="height: 34px; margin-right:2px;">
                        <option value="gbp">GBP</option>
                        <option value="usd">USD</option>
                        <option value="eur">EUR</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="w-5/12 mx-auto mt-3 mb-5">
                  <button type="submit" class="w-full py-1 text-white rounded-md text-sm md:text-md" style="background: #119dac">Send</button>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
  </main>
@endsection