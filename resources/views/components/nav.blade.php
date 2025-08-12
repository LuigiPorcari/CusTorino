<nav class="navbar navbar-expand-lg custom-navbar fixed-top shadow-sm ps-1" id="neubar" role="navigation"
    aria-label="Navigazione principale">
    <div class="container-fluid">
        <a class="navbar-brand custom-navbar-brand mb-2 d-flex align-items-center" href="{{ route('homepage') }}">
            <img src="{{ asset('img/CUS_Torino_Logo.png') }}" alt="Logo Cus Torino" class="img-fluid img-nav me-3 ms-0" />
            <span>CusTorino</span>
        </a>

        <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Apri menu di navigazione">
            <i class="fa-solid fa-bars text-white"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if (!Auth::check())
                    <li class="nav-item">
                        <a class="nav-link custom-nav-link" href="{{ route('homepage') }}" aria-current="page">Home</a>
                    </li>
                @endif

                <li class="nav-item dropdown">
                    @if (Auth::check())
                        <a class="nav-link dropdown-toggle custom-nav-link" href="#" id="utenteDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            Benvenuto, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu custom-dropdown-menu" aria-labelledby="utenteDropdown">
                            @if (Auth::user()->is_admin)
                                <li class="d-flex justify-content-center">
                                    <a class="btn custom-btn-primary-nav-drop mb-2" href="{{ route('groups.create') }}">
                                        Crea gruppi
                                    </a>
                                </li>
                            @endif
                            <li class="d-flex justify-content-center">
                                <a class="btn custom-btn-primary-nav-drop mb-2" href="{{ route('password.change') }}">
                                    Cambia Password
                                </a>
                            </li>
                            <li class="d-flex justify-content-center">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn custom-btn-warning-nav mb-2"
                                        aria-label="Esci dal tuo account">Logout</button>
                                </form>
                            </li>
                        </ul>
                    @endif
                </li>

                @if (Auth::check())
                    @if (Auth::user()->is_corsista)
                        <li class="nav-item">
                            <a class="nav-link custom-nav-link" href="{{ route('student.dashboard') }}">Home</a>
                        </li>
                    @endif
                    @if (Auth::user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link custom-nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                    @endif
                    @if (Auth::user()->is_trainer)
                        <li class="nav-item">
                            <a class="nav-link custom-nav-link" href="{{ route('trainer.dashboard') }}">Allenatore</a>
                        </li>
                    @endif
                @endif
            </ul>

            @if (Auth::check() && Auth::user()->is_admin)
                {{-- !TASTO ELIMINA MASSIVO DEI LOG PRIMA DI MESI --}}
                <form method="POST" action="{{ route('admin.logs.purge_old') }}" class="d-inline"
                    onsubmit="return confirm('Eliminare TUTTI i log più vecchi di 4 mesi? Operazione irreversibile.');">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        Pulisci log (> 4 mesi)
                    </button>
                </form>
                {{-- !FINE TASTO ELIMINA MASSIVO DEI LOG PRIMA DI MESI --}}
                <div class="d-flex flex-wrap justify-content-end" role="group" aria-label="Azioni rapide Admin">
                    <a class="btn custom-btn-primary-nav me-2 mb-2" href="{{ route('corsista.register') }}">
                        Registra Corsista
                    </a>
                    <a class="btn custom-btn-primary-nav me-2 mb-2" href="{{ route('trainer.register') }}">
                        Registra Allenatore
                    </a>
                    <a class="btn custom-btn-primary-nav mb-2" href="{{ route('admin.register') }}">
                        Registra Admin
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>
