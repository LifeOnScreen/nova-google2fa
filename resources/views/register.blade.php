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
