
@extends('vendor.google2fa.partials.layout')

@section('content')
    @include('nova::auth.partials.header')
        <form class="bg-white shadow rounded-lg p-8 max-w-xl mx-auto" method="POST" action="/los/2fa/register">
            @component('nova::auth.partials.heading')
                {{ __('Recovery Codes') }}
            @endcomponent

            @csrf
            <div class="text-center">
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
                                <div class="p-2">{{ $recoveryCode }}</div>
                        @endforeach
                    </div>
                </div>

                <button class="no-print m-2 w-1/2 btn btn-default btn-primary hover:bg-primary-dark" type="submit">
                    Continue
                </button><br><br>
                <a href="{{ route('nova.logout') }}">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
