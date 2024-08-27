<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top shadow-sm">
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
                    @if (Auth::check())
                        {{-- Logica Utente Autenticato --}}
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Benvenuto, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            @if (Auth::user()->is_admin)
                                <li class="d-flex justify-content-center">
                                    <a class="btn btn-primary mb-2" href="{{ route('groups.create') }}">Crea gruppi</a>
                                </li>
                            @endif
                            <li class="d-flex justify-content-center">
                                <a class="btn btn-primary mb-2" href="{{ route('password.change') }}">Cambia
                                    Password</a>
                            </li>
                            <li class="d-flex justify-content-center">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning mb-2">Logout</button>
                                </form>
                            </li>
                            @if (Auth::user()->is_admin)
                                <li class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModalAdmin">
                                        Elimina utente
                                    </button>
                                </li>
                            @endif
                            @if (Auth::user()->is_trainer)
                                <li class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModalTrainer">
                                        Elimina utente
                                    </button>
                                </li>
                            @endif
                        </ul>
                    @endif
                </li>
                @if (Auth::check())
                    {{-- Mostra la dashboard basata sul ruolo dell'utente --}}
                    @if (Auth::user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                        </li>
                    @endif
                    @if (Auth::user()->is_trainer)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('trainer.dashboard') }}">Dashboard Trainer</a>
                        </li>
                    @endif
                    @if (Auth::user()->is_corsista)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('student.dashboard') }}">Dashboard Corsista</a>
                        </li>
                    @endif
                @endif
            </ul>
            @if (!Auth::check())
                <div>
                    <a class="btn btn-primary" href="{{ route('login') }}">Accedi</a>
                    <a class="btn btn-primary mx-2" href="{{ route('corsista.register') }}">Registrati come
                        Corsista</a>
                </div>
            @endif
            @if (Auth::check() && Auth::user()->is_admin)
                <div class="d-flex">
                    <a class="btn btn-primary me-2" href="{{ route('corsista.register') }}">Registra nuovo Corsista</a>
                    <a class="btn btn-primary me-2" href="{{ route('trainer.register') }}">Registra nuovo Trainer</a>
                    <a class="btn btn-primary" href="{{ route('admin.register') }}">Registra nuovo Admin</a>
                </div>
            @endif
        </div>
    </div>
</nav>
