@extends('layout.auth')
@section('title')
    @include('layout.header', ['title' => 'Confirm Password'])
@endsection
@section('content')
    <div class="login-box container mt-5">
        <div class="login-logo">
            <a href="/"><b>{{ env('APP_NAME') }}</b></em></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('Please confirm your password before continuing.') }}</p>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    @error('password')
                        <span class="text-danger" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                    <div class="row mt-3">
                        <div class="col-4"></div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Confirm Password') }}
                            </button>
                        </div>
                    </div>
                </form>

                @if (Route::has('password.request'))
                    <p class="mb-0">
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
