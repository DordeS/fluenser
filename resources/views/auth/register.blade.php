@extends('layouts.app')

@section('content')
<div style="visibility: hidden">{{$page = 0}}</div>
<main>
    <div class="max-w-md mx-auto pb-6 sm:px-6 lg:px-8 mt-5">
      <!-- Replace with your content -->
      <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">New User Registration</p>
      <div class="px-4 py-6 sm:px-0 w-4/5 mx-auto">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div id="togglebar" class="relative w-3/5 mx-auto">
                <ul>
                    <li style="float: left;width:50%"><a style="display: block;text-align:center;height:35px;line-height:35px;" class="selected" id="influencer">Influencer</a></li>
                    <li style="float: left;width:50%"><a style="display: block;text-align:center;height:35px;line-height:35px;" class="unselected" id="brand">Brand</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div id="accountAlert" class="text-center mt-2"><p class="font-bold" style="font-family: 'Josefin Sans', sans-serif;">You are registering as an Influencer</p></div>
            <div id="radio-btn">
                <div style="visibility: hidden">
                    <input type="radio" class="form-radio" name="accountType" value="influencer">
                    <input type="radio" class="form-radio" name="accountType" value="brand">
                </div>
                @error('accountType')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>


            <label class="block mb-5">
                <input id="name" type="name" class="h-10 p-2 rounded form-input mt-2 block w-full @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name" style="border:1px solid rgba(220,220,220,0.5)">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <label class="block mb-5">
                <input id="email" type="email" class="h-10 p-2 rounded form-input mt-2 block w-full @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail" style="border:1px solid rgba(220,220,220,0.5)">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <label class="block mb-5">
                <input id="password" type="password" class="h-10 p-2 rounded form-input mt-2 block w-full @error('password') is-invalid @enderror" name="password" required placeholder="Password" style="border:1px solid rgba(220,220,220,0.5)">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <label class="block mb-5">
                <input id="password-confirm" type="password" class="h-10 p-2 rounded form-input mt-2 block w-full @error('password') is-invalid @enderror" name="password_confirmation" required placeholder="Confirm Password" style="border:1px solid rgba(220,220,220,0.5)">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <div class="flex mt-6">
                <label class="flex items-center font-bold" style="font-family: 'Josefin Sans', sans-serif;">
                  <input type="checkbox" class="form-checkbox rounded" name="agreement" >
                  <span class="ml-2">I agree to the <span class="underline text-indigo-700">Terms & Conditions</span></span>
                </label>
                <div></div>
                @error('agreement')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>
            <div class="flex mt-6 w-4/5 mx-auto">
                <button type="submit" class="w-full appearance-none text-white text-base font-semibold tracking-wide p-2 rounded hover:bg-blue-900" style="background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));"> {{ __('Register') }} </button>
            </div>

        </form>
      </div>
    </div>
</main>
@endsection
