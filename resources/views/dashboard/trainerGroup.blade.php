<x-layout documentTitle="Trainer Dashboard">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0" role="navigation" aria-label="Navigazione amministrativa">
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('trainer.dashboard') }}">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link active" href="{{ route('trainer.group') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('trainer.salary') }}">Compensi</a>
        </li>
    </ul>

    <main class="container mt-5" id="main-content">
        <header>
            <h1 class="custom-title mt-md-5 pt-md-5">{{ Auth::user()->name }} {{ Auth::user()->cognome }}</h1>
            <h2 class="custom-title mt-5 mb-4 pt-4 pt-md-0">Gruppi in cui alleni</h2>
        </header>

        @if (session('success'))
            <div class="alert custom-alert-success alert-dismissible" role="status">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
            </div>
        @endif

        <section class="mb-4" aria-labelledby="filtroGruppi">
            <h2 id="filtroGruppi" class="visually-hidden">Filtri ricerca gruppi</h2>
            <form method="GET" action="{{ route('trainer.group') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="alias_name" class="visually-hidden">Nome gruppo</label>
                        <input type="search" id="alias_name" name="alias_name" class="custom-form-input" placeholder="Gruppo"
                            value="{{ request('alias_name') }}" onsearch="this.form.submit()">
                    </div>
                    <div class="col-md-4">
                        <label for="alias_date" class="visually-hidden">Data gruppo</label>
                        <select name="alias_date" id="alias_date" class="custom-form-input" onchange="this.form.submit()">
                            <option value="">Tutte le date</option>
                            @foreach ($availableDates as $date)
                                <option value="{{ $date['original'] }}"
                                    {{ request('alias_date') == $date['original'] ? 'selected' : '' }}>
                                    {{ $date['formatted'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 admin-btn-info py-2 my-1 fs-6">
                            Filtra
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <section aria-labelledby="tabellaGruppiAllenatore">
            <h2 id="tabellaGruppiAllenatore" class="visually-hidden">Elenco gruppi allenati</h2>
            <table class="table table-bordered admin-trainer-table">
                <thead>
                    <tr>
                        <th scope="col">Nome Alias</th>
                        <th scope="col">Data Alias / Sede Alias</th>
                        <th scope="col">Dettagli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aliasesTrainer as $alias)
                        <tr>
                            <td>{{ $alias->nome }}</td>
                            <td>
                                {{ $alias->formatData($alias->data_allenamento) }}
                                / <span class="text-uppercase">{{ $alias->location }}</span>
                            </td>
                            <td>
                                <a href="{{ route('alias.details', $alias) }}"
                                    class="btn admin-btn-info" aria-label="Visualizza dettagli gruppo {{ $alias->nome }}">
                                    Visualizza Dettagli
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Non ci sono gruppi disponibili</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <nav aria-label="Paginazione gruppi allenatore">
                {{ $aliasesTrainer->links('pagination::bootstrap-5') }}
            </nav>
        </section>
    </main>
</x-layout>
