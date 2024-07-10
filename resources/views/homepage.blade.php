<x-layout documentTitle="Homepage">
    <h1>Homepage</h1>

    @if (!Auth::guard('admin')->check() && !Auth::guard('trainer')->check() && !Auth::guard('student')->check())
        <a href="{{ route('admin.register') }}">registrati admin</a>
        <a href="{{ route('admin.login') }}">accedi admin</a>
        <a href="{{ route('trainer.register') }}">registrati trainer</a>
        <a href="{{ route('trainer.login') }}">accedi trainer</a>
        <a href="{{ route('student.register') }}">registrati student</a>
        <a href="{{ route('student.login') }}">accedi student</a>
    @endif
    {{-- Logica Admin --}}
    @if (Auth::guard('admin')->check())
        <p>Benvenuto, {{ Auth::guard('admin')->user()->nome }}</p>
        <a href="{{route('groups.create')}}">Crea gruppi</a>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    @endif

    {{-- Logica Trainer --}}
    @if (Auth::guard('trainer')->check())
        <p>Benvenuto, {{ Auth::guard('trainer')->user()->nome }}</p>
        <form action="{{ route('trainer.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    @endif

    {{-- Logica Student --}}
    @if (Auth::guard('student')->check())
        <p>Benvenuto, {{ Auth::guard('student')->user()->nome }}</p>
        <a href="{{route('student.dashboard')}}">Dashboard</a>
        <form action="{{ route('student.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    @endif




</x-layout>
