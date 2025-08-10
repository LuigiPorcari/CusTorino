<x-layout documentTitle="Admin Group Dashboard">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-5 pt-md-3" role="navigation" aria-label="Navigazione amministrazione">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student', session('student_filters', [])) }}">
                Corsisti
            </a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.week') }}">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
    </ul>

    <main class="container mt-md-5 admin-dashboard" role="main">
        <header class="pt-4 pt-md-0">
            <h1 class="visually-hidden">Dashboard Amministratore</h1>
            <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Gruppi</h2>
        </header>

        @if (session('success'))
            <div class="alert alert-dismissible custom-alert-success" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
            </div>
        @endif

        <section class="mb-4" aria-labelledby="filtroGruppi">
            <h2 id="filtroGruppi" class="visually-hidden">Filtra gruppi</h2>
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="search" name="group_name" class="custom-form-input shadow-lg"
                            placeholder="Nome Gruppo" value="{{ request('group_name') }}" onsearch="this.form.submit()">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn w-100 admin-btn-info py-2 mt-1 fs-6 shadow-lg">Filtra</button>
                    </div>
                </div>
            </form>
        </section>

        <section aria-labelledby="tabellaGruppi">
            <h2 id="tabellaGruppi" class="visually-hidden">Tabella dei gruppi</h2>
            <table class="table table-bordered admin-table">
                <thead>
                    <tr>
                        <th scope="col">Nome Gruppo</th>
                        <th scope="col">Dettagli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groups as $group)
                        <tr>
                            <td>{{ $group->nome }}</td>
                            <td>
                                <a href="{{ route('admin.group.details', $group) }}" class="btn admin-btn-info">
                                    Visualizza Dettagli
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">Non ci sono gruppi disponibili</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $groups->links('pagination::bootstrap-5') }}
        </section>
    </main>
</x-layout>
