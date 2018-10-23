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
        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-40 text-black h-full">
<div class="h-full">
    <div class="px-view py-view mx-auto">
        <div class="mx-auto py-8 max-w-sm text-center text-90">
            @include('nova::partials.logo')
        </div>

        <form class="bg-white shadow rounded-lg p-8 max-w-xl mx-auto" method="POST" action="/los/2fa/register">
            <h2 class="p-2">Recovery codes</h2>
            @csrf
            <p class="p-2">
                Recovery codes are used to access your account in the event you cannot recive two-factor
                authentication codes.
            </p>
            <p class="p-2 no-print">
                <strong>
                    Download, print or copy your codes before continuing two-factor authentication setup.
                </strong>
            </p>
            <div class="p-3">
                <label class="block font-bold mb-2" for="co">Recovery codes
                    <button class="no-print m-1  btn btn-default btn-primary hover:bg-primary-dark" type="button"
                            onclick="window.print();return false;">
                        Print
                    </button>
                </label>

                <div>
                    @foreach ($recovery as $recoveryCode)
                        <ul>
                            <li class="p-2">{{ $recoveryCode }}</li>
                        </ul>
                    @endforeach
                </div>
            </div>

            <button class="no-print m-2 w-1/2 btn btn-default btn-primary hover:bg-primary-dark" type="submit">
                Continue
            </button>
        </form>
    </div>
</div>
</body>
</html>
