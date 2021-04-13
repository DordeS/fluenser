@extends('layouts.app')

@section('content')
<main>
    <div class="max-full md:max-w-xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="w-full">
        <a href="{{route('welcome')}}" class="block mx-auto mb-6" style="max-width: 150px;">
            <img class="w-full" src="{{ asset('img/logo.jpg') }}" alt="logo">
        </a>
    </div>
    <div class="w-11/12 mx-auto bg-gray-100 rounded-2xl py-3" style="font-family:'Poppins', sans-serif">
        <div class="w-11/12 mx-auto">
            <p class="text-center text-xl font-semibold" style="font-weight: 600;">New User Registration</p>
            <hr style="height: 1px; background:gray;margin-top: 5px;" />
            <div class="py-6 sm:px-0 w-full mx-auto">
              <form method="POST" action="{{ route('register') }}">
                  @csrf
                  <div id="togglebar" class="relative w-full mx-auto rounded bg-gray-300">
                      <div class="flex justify-between">
                          <div class="px-2 py-2 w-1/2">
                              <a style="display: block;text-align:center;height:30px;line-height:30px;" class="selected text-sm md:text-md font-semibold" id="influencer">Influencer</a>
                          </div>
                          <div class="px-2 py-2 w-1/2">
                              <a style="display: block;text-align:center;height:30px;line-height:30px;" class="unselected text-sm md:text-md font-semibold" id="brand">Brand</a>
                          </div>
                      </div>
                      <div class="clearfix"></div>
                  </div>
                  <div id="accountAlert" class="text-xs mt-2"><p style="font-weight: 500;">You are registering as an Influencer</p></div>
                  <div id="radio-btn">
                    <input type="text" name="accountType" id="accountType" value="influencer" hidden>
                    @error('accountType')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
      
                  <label class="block mb-8">
                      <input id="first_name" type="name" class="h-10 form-input mt-2 block w-full @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus placeholder="First Name" style="border:1px solid #999; padding:25px 15px;">
                      @error('first_name')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </label>
                  <label class="block mb-8">
                      <input id="last_name" type="name" class="h-10 form-input mt-2 block w-full @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" autocomplete="last_name" autofocus placeholder="Last Name" style="border:1px solid #999; padding:25px 15px;">
                      @error('last_name')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </label>
                  <label class="block mb-8">
                      <input id="email" type="email" class="h-10 form-input mt-2 block w-full @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="E-mail" style="border:1px solid #999; padding:25px 15px;">
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </label>
                  <label class="block mb-8">
                      <input id="password" type="password" class="h-10 form-input mt-2 block w-full @error('password') is-invalid @enderror" name="password" placeholder="Password" style="border:1px solid #999; padding:25px 15px;">
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </label>
                  <label class="block mb-2">
                      <input id="password-confirm" type="password" class="h-10 form-input mt-2 block w-full @error('password') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password" style="border:1px solid #999; padding:25px 15px;">
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </label>
                  <div>
                      <label class="flex items-center font-bold text-xs">
                        <input type="checkbox" class="form-checkbox" name="agreement" >
                        <span class="ml-2" style="color: #999;">I agree to the <span class="text-blue-500">Terms & Conditions</span></span>
                      </label>
                      <div></div>
                      @error('agreement')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
                  <div class="flex mt-6 w-4/5 mx-auto">
                      <button type="submit" class="w-full appearance-none text-white text-base font-semibold tracking-wide p-2 rounded hover:bg-blue-900" style="background:#0ac2c8;"> {{ __('Register') }} </button>
                  </div>
              </form>
            </div>
        </div>
    </div>
    </div>
</main>
@endsection
