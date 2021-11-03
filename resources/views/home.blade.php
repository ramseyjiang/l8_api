@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (! auth()->user()->two_factor_secret)
                    You have not enabled 2fa.
                    <form method="POST" action="/user/two-factor-authentication">
                        @csrf
                        <button class="btn btn-primary">Enable</button>
                    </form>
                    @else
                    <div>{!! auth()->user()->twoFactorQrCodeSvg() !!}</div>
                    You have 2fa enabled.
                    <form method="POST" action="/user/two-factor-authentication">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-primary">Disabled</button>
                    </form>
                    @endif
                    {{ __('You are logged in!') }}<br>

                    {{auth()->user()->email}} <br>


                    <!-- @if(session('status') == 'two-factor-authenication-enabled') -->
                    Two factor auth is enabled.
                    @foreach((array) $request->user()->recoveryCodes() as $code)
                    {{ trim($code) }} <br>
                    @endforeach
                    <!-- @endif -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection