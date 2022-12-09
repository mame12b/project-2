<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        @if (request()->is('department/*') || request()->is('user/*'))
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route(auth()->user()->type.'.message') }}" target="_blank">
                <i class="far fa-comments"></i>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link text-danger" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                {{ __('Log out') }} <i class="fas fa-sign-out-alt ml-1"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
