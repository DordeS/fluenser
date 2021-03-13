@extends('layouts.app')

@section('content')

<div style="visibility: hidden">{{$page = 0}}</div>
<main>
    <div class="max-w-md mx-auto py-6 sm:px-6 lg:px-8 mt-20">
      <!-- Replace with your content -->
      <p class="text-center text-3xl" style="font-family: 'Josefin Sans', sans-serif;">Log In</p>
      <div class="px-4 py-6 sm:px-0 w-4/5 mx-auto">
        <form method="POST" action="{{ route('login') }}" class="mx-auto">
            @csrf

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
            <div class="flex mt-6">
                <label class="flex items-center">
                  <input class="form-checkbox rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <span class="ml-2 text-sm">{{ __('Remember Me') }}</span>
                </label>
            </div>
            <div class="flex mt-6 w-4/5 mx-auto">
                <button type="submit" class="w-full appearance-none text-white text-base font-semibold tracking-wide p-2 rounded hover:bg-blue-900" style="background:linear-gradient(to right, RGB(5,235,189), RGB(19,120,212));"> {{ __('Login') }} </button>
            </div>
            <div class="flex mt-8">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="mx-auto" style="color: rgb(48, 180, 167)">{{ __('New User? Click to Register') }}</a>
                @endif

            </div>
            <div class="flex mt-3">
                @if (Route::has('password.request'))
                    <a class="mx-auto" href="{{ route('password.request') }}" style="color: rgb(48, 180, 167)">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                       

                </div>
            </div>
        </form>
      </div>
      <!-- /End replace -->
    </div>
</main>
@endsection
