@extends('layout.auth')

@section('title')
    @include('layout.header', ['title' => 'Register'])
@endsection

@section('content')
    <div class="login-box container mt-5">
        <div class="login-logo">
            <a href="/"><b>{{ env('APP_NAME') }}</b></em></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('Register to start your session')}}</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="input-group">
                        <input id="email" placeholder="Email" type="email"
                            class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <span class="text-danger" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                    <div class="input-group mt-3">
                        <input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
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

                    <div class="input-group mt-3">
                        <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6"></div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mb-1">
                    <a href="{{ route('login') }}">I have account</a>
                </p>

            </div>
        </div>
    </div>
@endsection
