@extends('vendor.google2fa.partials.layout')

@section('content')
    @include('nova::auth.partials.header')
        <form id="register_form" class="bg-white shadow rounded-lg p-8 max-w-login mx-auto" method="POST"
              action="confirm">
            @csrf
            @component('nova::auth.partials.heading')
                {{ __('Add to Google Authenticator') }}
            @endcomponent
            <strong>
                <div class="text-center">
                    Scan the Barcode From Your Google Authenticator Mobile App
                </div>
            </strong>
            <div class="text-center">
                <img src="{{ $qrcode_image }}" alt="">
            </div>

            <div class="text-center">
                @if ($displaySecretCode)
                    <div class="mb-6" style="display:inline-block">
                        Alternatively you can type the secret key.
                        <br>
                        <input id="secret_code" style="background-color:lightyellow;" value ="{{ $secret_code }}"/>
                        <svg aria-hidden="true" viewBox="0 0 16 16" version="1.1" height="16" width="16" onclick="copyCode()">
                            <title id="copySecretTitle">Copy</title>
                            <path fill-rule="evenodd" d="M5.75 1a.75.75 0 00-.75.75v3c0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75v-3a.75.75 0 00-.75-.75h-4.5zm.75 3V2.5h3V4h-3zm-2.874-.467a.75.75 0 00-.752-1.298A1.75 1.75 0 002 3.75v9.5c0 .966.784 1.75 1.75 1.75h8.5A1.75 1.75 0 0014 13.25v-9.5a1.75 1.75 0 00-.874-1.515.75.75 0 10-.752 1.298.25.25 0 01.126.217v9.5a.25.25 0 01-.25.25h-8.5a.25.25 0 01-.25-.25v-9.5a.25.25 0 01.126-.217z"></path>
                        </svg>
                    </div>
                @endif
                <div class="mb-6 w-1/2" style="display:inline-block">
                    @if (isset($error))
                        <p class="text-center font-semibold text-danger my-3">
                            {{  $error }}
                        </p>
                    @endif
                    <label class="block font-bold mb-2" for="co">Secret</label>
                    <input class="form-control form-input form-input-bordered w-full" id="secret" type="text"
                           name="secret" value="" required="required" autofocus="">
                </div>
                <button class="no-print m-2 w-1/2 btn btn-default btn-primary hover:bg-primary-dark" type="submit">
                    Confirm
                </button><br><br>
                <a href="{{ route('nova.logout') }}">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
