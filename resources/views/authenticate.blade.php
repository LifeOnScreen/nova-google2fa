@extends('nova::auth.layout')

@section('content')

    @include('nova::auth.partials.header')

    <form id="authenticate_form" class="bg-white shadow rounded-lg p-8 max-w-xl mx-auto" method="POST"
            action="/los/2fa/authenticate">
            
        @component('nova::auth.partials.heading')
            {{ __('Two Factor Authentication') }}
        @endcomponent

        @csrf

        <p class="p-2">Two factor authentication (2FA) strengthens access security by requiring two methods (also
                referred to as factors) to
                verify your identity.
                Two factor authentication protects against phishing, social engineering and password brute force attacks
                and secures your logins from attackers
                exploiting weak or stolen credentials.</p>
        <p class="p-2"><strong>Enter the pin from Google Authenticator Enable 2FA</strong></p>

        <div class="text-center pt-3">
            <div class="mb-6 w-1/2" style="display:inline-block">
                @if (isset($error))
                    <p id="error_text" class="text-center font-semibold text-danger my-3">
                        {{  $error }}
                        <button
                                onclick="
                                    document.getElementById('secret_div').style.display = 'none';
                                    document.getElementById('error_text').style.display = 'none';
                                    document.getElementById('recover_div').style.display = 'block';
                                "
                                class="w-1/2 mt-2 btn btn-default btn-primary hover:bg-primary-dark" type="button">
                            Recover
                        </button>
                    </p>
                @endif
                <div id="secret_div">
                    <label class="block font-bold mb-2" for="co">One Time Password</label>
                    <input class="form-control form-input form-input-bordered w-full" id="secret" type="number"
                            name="one_time_password" value="" onkeyup="checkAutoSubmit(this)" autofocus="">
                </div>
                <div id="recover_div" style="display: none;">
                    <label class="block font-bold mb-2" for="co">Recovery code</label>
                    <input class="form-control form-input form-input-bordered w-full" id="recover" type="text"
                            name="recover" value="" autofocus="">
                </div>
            </div>
            <button class="w-1/2 btn btn-default btn-primary hover:bg-primary-dark" type="submit">
                Authenticate
            </button>
        </div>
    </form>

    <script type="text/javascript">
        function checkAutoSubmit(el) {
            if (el.value.length === 6) {
                document.getElementById('authenticate_form').submit();
            }
        }
        
        document.getElementById("secret").focus();
    </script>

@endsection