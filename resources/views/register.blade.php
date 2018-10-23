<!DOCTYPE html>
<html lang="en" class="h-full font-sans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ Nova::name() }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('app.css', 'vendor/nova') }}">

    <style>
        body {
            font-family: "Montserrat", sans-serif !important;
        }

        .btn,
        .form-input,
        .rounded-lg {
            border-radius: 0 !important;
        }
    </style>
    <script>
        function checkAutoSubmit(el) {
            if (el.value.length === 6) {
                document.getElementById('register_form').submit();
            }
        }
    </script>
</head>
<body class="bg-40 text-black h-full">
<div class="h-full">
    <div class="px-view py-view mx-auto">
        <div class="mx-auto py-8 max-w-sm text-center text-90">
            @include('nova::partials.logo')
        </div>

        <form id="register_form" class="bg-white shadow rounded-lg p-8 max-w-xl mx-auto" method="POST"
              action="/los/2fa/confirm">
            @csrf
            <h2 class="p-2">Two Factor Authentication</h2>

            <p class="p-2">Two factor authentication (2FA) strengthens access security by requiring two methods (also
                referred
                to as factors) to verify your identity. Two factor authentication protects against phishing, social
                engineering and password brute force attacks and secures your logins from attackers exploiting weak
                or stolen credentials.</p>
            <p class="p-2">To Enable Two Factor Authentication on your Account, you need to do following steps</p>
            <strong>
                <ol>
                    <li>Verify the OTP from Google Authenticator Mobile App</li>
                </ol>
            </strong>
            <div class="text-center">
                <img src="{{ $google2fa_url }}" alt="">
            </div>

            <div class="text-center">
                <div class="mb-6 w-1/2" style="display:inline-block">
                    @if (isset($error))
                        <p class="text-center font-semibold text-danger my-3">
                            {{  $error }}
                        </p>
                    @endif
                    <label class="block font-bold mb-2" for="co">Secret</label>
                    <input class="form-control form-input form-input-bordered w-full" id="secret" type="number"
                           name="secret" value="" required="required" onkeyup="checkAutoSubmit(this)" autofocus="">
                </div>
                <button class="w-1/2 btn btn-default btn-primary hover:bg-primary-dark" type="submit">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
