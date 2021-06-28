@extends('nova::auth.layout')

@section('content')

    @include('nova::auth.partials.header')

    <form class="bg-white shadow rounded-lg p-8 max-w-xl mx-auto" method="POST" action="/los/2fa/register">
        
        @component('nova::auth.partials.heading')
            {{ __('Recovery codes') }}
        @endcomponent
        
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

        <button class="no-print m-2 w-full btn btn-default btn-primary hover:bg-primary-dark" type="submit">
            Continue
        </button>
    </form>

@endsection