@extends('layout.auth')

@section('title')
    @include('layout.header', ['title' => 'Login'])
@endsection

@section('content')
    <div class="login-box container mt-5">
        <div class="login-logo">
            <a href="/"><b>{{ env('APP_NAME') }}</b></em></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('Login to start your session')}}</p>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <center>{{ session('error') }}</center>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @if (isset($_GET['next']))
                    <input type="hidden" name="next" value="@php echo $_GET['next']  @endphp">
                    @endif
                    <div class="input-group">
                        <input id="email" placeholder="Email" type="email"
                            class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                        <input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror"
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
                        <div class="col-8 mt-2">
                            <input  type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>


                @if (Route::has('password.request'))
                    <p class="mb-1 mt-2">
                        <a href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </p>
                @endif
                <p class="mb-0">
                    <a href="{{ route('register') }}" class="text-center">{{ __('Register a new membership')}}</a>
                </p>

            </div>
        </div>
    </div>
@endsection
