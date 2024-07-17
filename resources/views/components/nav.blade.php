<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('homepage') }}">CusTorino</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('homepage') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    @if (Auth::guard('admin')->check())
                        {{-- Logica Admin --}}
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Benvenuto, {{ Auth::guard('admin')->user()->nome }}
                        </a>
                    @elseif(Auth::guard('trainer')->check())
                        {{-- Logica Trainer --}}
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Benvenuto, {{ Auth::guard('trainer')->user()->nome }}
                        </a>
                    @elseif(Auth::guard('student')->check())
                        {{-- Logica Student --}}
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Benvenuto, {{ Auth::guard('student')->user()->nome }}
                        </a>
                    @endif
                    <ul class="dropdown-menu">
                        @if (Auth::guard('admin')->check())
                            {{-- Logica Admin --}}
                            <li class="d-flex justify-content-center">
                                <a class="btn btn-primary mb-2" href="{{ route('groups.create') }}">Crea gruppi</a>
                            </li>
                            <li class="d-flex justify-content-center">
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Logout</button>
                                </form>
                            </li>
                        @elseif(Auth::guard('trainer')->check())
                            {{-- Logica Trainer --}}
                            <li class="d-flex justify-content-center">
                                <form action="{{ route('trainer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Logout</button>
                                </form>
                            </li>
                        @elseif(Auth::guard('student')->check())
                            {{-- Logica Student --}}
                            <li class="d-flex justify-content-center">
                                <form action="{{ route('student.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Logout</button>
                                </form>
                            </li>
                        @endif
                    </ul>
                </li>
                @if (Auth::guard('admin')->check())
                    {{-- Logica Admin --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                @elseif(Auth::guard('trainer')->check())
                    {{-- Logica Trainer --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('trainer.dashboard') }}">Dashboard</a>
                    </li>
                @elseif(Auth::guard('student')->check())
                    {{-- Logica Student --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard</a>
                    </li>
                @endif
            </ul>
            @if (!Auth::guard('admin')->check() && !Auth::guard('trainer')->check() && !Auth::guard('student')->check())
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Accedi
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#registerModal">
                        Registrati
                    </button>
                </div>
            @endif
            @if (Auth::guard('admin')->check())
                {{-- Logica Admin --}}
                    <a class="btn btn-primary" href="{{ route('admin.register') }}">Registra nuovo Admin</a>
            @endif
        </div>
</nav>
