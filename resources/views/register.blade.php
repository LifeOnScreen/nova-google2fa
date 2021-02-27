@extends('nova::auth.layout')

@section('content')

    @include('nova::auth.partials.header')

    <form id="register_form" class="bg-white shadow rounded-lg p-8 max-w-xl mx-auto" method="POST"
            action="/los/2fa/confirm">
            
        @component('nova::auth.partials.heading')
            {{ __('Two Factor Authentication') }}
        @endcomponent

        @csrf

        <p class="p-2">Two factor authentication (2FA) strengthens access security by requiring two methods (also
            referred
            to as factors) to verify your identity. Two factor authentication protects against phishing, social
            engineering and password brute force attacks and secures your logins from attackers exploiting weak
            or stolen credentials.</p>

        <p class="p-2">To Enable Two Factor Authentication on your Account, you need to do following steps</p>

        <div class="text-left p-2 text-bold">
            <ol>
                <li>Download The Google Authenticator Mobile app from the app store</li>
                <li>Verify the OTP from Google Authenticator Mobile App</li>
            </ol>
        </div>

        <div class="text-center" style="margin: 20px 0px 20px 0px">
            <img id="preload-qr" src="{{ $google2fa_url }}" alt="">
            
            <div id="preload-qr-loading" class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
            
            <p id="preload-qr-error" style="display:none"></p>
        </div>

        <div class="text-center">
            <div class="mb-6 w-1/2" style="display:inline-block">
                @if (isset($error))
                    <p class="text-center font-semibold text-danger my-3">
                        {{ $error }}
                    </p>
                @endif
                <label class="block font-bold mb-2" for="co">Secret</label>
                <input class="form-control form-input form-input-bordered w-full" id="secret" type="number"
                        name="one_time_password" value="" required="required" onkeyup="checkAutoSubmit(this)" autofocus="">
            </div>

            <button class="w-1/2 btn btn-default btn-primary hover:bg-primary-dark" type="submit">
                Confirm
            </button>
        </div>
    </form>
    

    <script type="text/javascript">
        function checkAutoSubmit(el) {
            if (el.value.length === 6) {
                document.getElementById('register_form').submit();
            }
        }
        
        document.getElementById("secret").focus();

        document.getElementById('preload-qr').style.display = 'none';
        document.getElementById('preload-qr-loading').style.display = 'block';
        const qrSrc = document.getElementById('preload-qr').getAttribute('src');

        const img = new Image();
        img.onload = (e) => {
            document.getElementById('preload-qr-loading').style.display = 'none';
            document.getElementById('preload-qr').src = qrSrc;
            document.getElementById('preload-qr').style.display = 'block';
        };
        img.onerror = (err) => {
            console.log(err);       
            document.getElementById('preload-qr-loading').style.display = 'block';
            document.getElementById('preload-qr-error').style.display = 'block';
            document.getElementById('preload-qr-error').innerHTML = 'Error loading Authenticator QR image.';
        };
        img.src = qrSrc;
    </script>

    
    <style type="text/css">
        #preload-qr{
            margin: auto;
            width: 200px;
            height: 200px;
            border: none;
            outline: none;
        }

        .lds-roller {
            display:none;
            position: relative;
            margin: auto;
            width: 200px;
            height: 200px;
            padding: 60px;
        }
        .lds-roller div {
            animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            transform-origin: 40px 40px;
        }
        .lds-roller div:after {
            content: " ";
            display: block;
            position: absolute;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--40);
            margin: -4px 0 0 -4px;
        }
        .lds-roller div:nth-child(1) {
            animation-delay: -0.036s;
        }
        .lds-roller div:nth-child(1):after {
            top: 63px;
            left: 63px;
        }
        .lds-roller div:nth-child(2) {
            animation-delay: -0.072s;
        }
        .lds-roller div:nth-child(2):after {
            top: 68px;
            left: 56px;
        }
        .lds-roller div:nth-child(3) {
            animation-delay: -0.108s;
        }
        .lds-roller div:nth-child(3):after {
            top: 71px;
            left: 48px;
        }
        .lds-roller div:nth-child(4) {
            animation-delay: -0.144s;
        }
        .lds-roller div:nth-child(4):after {
            top: 72px;
            left: 40px;
        }
        .lds-roller div:nth-child(5) {
            animation-delay: -0.18s;
        }
        .lds-roller div:nth-child(5):after {
            top: 71px;
            left: 32px;
        }
        .lds-roller div:nth-child(6) {
            animation-delay: -0.216s;
        }
        .lds-roller div:nth-child(6):after {
            top: 68px;
            left: 24px;
        }
        .lds-roller div:nth-child(7) {
            animation-delay: -0.252s;
        }
        .lds-roller div:nth-child(7):after {
            top: 63px;
            left: 17px;
        }
        .lds-roller div:nth-child(8) {
            animation-delay: -0.288s;
        }
        .lds-roller div:nth-child(8):after {
            top: 56px;
            left: 12px;
        }
        @keyframes lds-roller {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
    </style>

@endsection