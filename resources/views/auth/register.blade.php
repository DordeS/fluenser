@extends('layouts.app')

@section('content')
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 text-center">
        {{ __('Register') }}
      </h1>
    </div>
</header>
<main>
    <div class="max-w-md mx-auto py-6 sm:px-6 lg:px-8 bg-gray-100 mt-20 shadow-lg rounded">
      <!-- Replace with your content -->
      <div class="px-4 py-6 sm:px-0">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mt-4 mb-5">
                <span class="text-gray-700">Account Type</span>
                <div class="mt-2">
                  <label class="inline-flex items-center">
                    <input type="radio" class="form-radio" name="accountType" value="influencer">
                    <span class="ml-2">{{ __('Influencer') }}</span>
                  </label>
                  <label class="inline-flex items-center ml-6">
                    <input type="radio" class="form-radio" name="accountType" value="brand">
                    <span class="ml-2">{{ __('Brand') }}</span>
                  </label>
                </div>
                @error('accountType')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <label class="block mb-5">
                <span class="text-gray-700">{{ __('Name') }}</span>
                <input id="name" type="name" class="h-12 p-4 rounded border-2 border-grey-700 form-input mt-2 block w-full @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
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
            <label class="block mb-5">
                <span class="text-gray-700">{{ __('Confirm Password') }}</span>
                <input id="password-confirm" type="password" class="h-12 p-4 rounded border-2 border-grey-700 form-input mt-2 block w-full @error('password') is-invalid @enderror" name="password_confirmation" required placeholder="Confirm Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </label>
            <div class="flex mt-6">
                <label class="flex items-center">
                  <input type="checkbox" class="form-checkbox" name="agreement">
                  <span class="ml-2">I agree to the <span class="underline">Terms & Conditions</span></span>
                </label>
                <div></div>
                @error('agreement')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>
            <div class="flex mt-6 w-4/5 mx-auto">
                <button type="submit" class="w-full appearance-none bg-blue-700 text-white text-base font-semibold tracking-wide uppercase p-3 rounded shadow hover:bg-blue-900"> {{ __('Register') }} </button>
            </div>

        </form>
      </div>
    </div>
</main>
@endsection
