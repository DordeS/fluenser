@extends('layouts.app')

@section('content')
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 text-center">
        {{ __('Login') }}
      </h1>
    </div>
</header>
<main>
    <div class="max-w-md mx-auto py-6 sm:px-6 lg:px-8 bg-gray-100 border-solid border-black border-1 shadow-lg mt-20 rounded">
      <!-- Replace with your content -->
      <div class="px-4 py-6 sm:px-0">
        <form method="POST" action="{{ route('login') }}" class="mx-auto">
            @csrf

            <label class="block mb-5">
                <span class="text-gray-700">{{ __('E-Mail Address') }}</span>
                <input id="email" type="email" class="h-12 p-4 rounded border-2 border-grey-700 form-input mt-2 block w-full @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <label class="block mb-5">
                <span class="text-gray-700">{{ __('Password') }}</span>
                <input id="password" type="password" class="h-12 p-4 rounded border-2 border-grey-700 form-input mt-2 block w-full @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <div class="flex mt-6">
                <label class="flex items-center">
                  <input class="form-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <span class="ml-2">{{ __('Remember Me') }}</span>
                </label>
            </div>
            <div class="flex mt-6 w-4/5 mx-auto">
                <button type="submit" class="w-full appearance-none bg-blue-700 text-white text-base font-semibold tracking-wide uppercase p-3 rounded shadow hover:bg-blue-900"> {{ __('Login') }} </button>
            </div>
            <div class="flex mt-4  text-right">
                @if (Route::has('password.request'))
                    <a class="text-black" href="{{ route('password.request') }}">
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
