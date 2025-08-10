<x-layout documentTitle="Admin Trainer Dashboard">
        <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0" role="navigation" aria-label="Navigazione amministrativa">
            <li class="nav-item admin-nav-item mt-3" role="presentation">
                <a class="nav-link" href="{{ route('admin.dashboard') }}" role="tab">Gruppi</a>
            </li>
            <li class="nav-item admin-nav-item mt-3" role="presentation">
                <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.trainer') }}"
                    role="tab">Allenatori</a>
            </li>
            <li class="nav-item admin-nav-item mt-3" role="presentation">
                <a class="nav-link" href="{{ route('admin.dashboard.student', session('student_filters', [])) }}"
                    role="tab">
                    Corsisti
                </a>
            </li>
            <li class="nav-item admin-nav-item mt-3" role="presentation">
                <a class="nav-link" href="{{ route('admin.week') }}" role="tab">Settimana</a>
            </li>
            <li class="nav-item admin-nav-item mt-3" role="presentation">
                <a class="nav-link" href="{{ route('logs.index') }}" role="tab">Log</a>
            </li>
        </ul>

    @if (session('success'))
        <div class="alert alert-dismissible custom-alert-success" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi notifica"></button>
        </div>
    @endif

    <main id="main-content" class="container mt-md-5 admin-trainer-dashboard" tabindex="-1">
        <header class="pt-5 pt-md-0">
            <h1 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Allenatori</h1>
        </header>
        <!-- Form di filtro allenatori -->
        <form id="filterForm" method="GET" action="{{ route('admin.dashboard.trainer') }}"
            aria-label="Filtra allenatori">
            <fieldset class="mb-4 admin-student-filter">
                <legend class="visually-hidden">Filtri Allenatori</legend>
                <div class="row">
                    <!-- Filtro nome e gruppo -->
                    <div class="col-md-4 my-auto">
                        <label for="trainer_name" class="form-label visually-hidden">Nome o Cognome</label>
                        <input type="search" name="trainer_name" id="trainer_name" class="custom-form-input shadow-lg"
                            placeholder="Nome o Cognome" value="{{ request('trainer_name') }}">

                        <label for="group_filter" class="form-label visually-hidden">Gruppo</label>
                        <input type="search" name="group_filter" id="group_filter" class="custom-form-input shadow-lg"
                            placeholder="Gruppi" value="{{ request('group_filter') }}">
                    </div>

                    <!-- Checkbox "non allena" -->
                    <div class="col-6 col-md-2">
                        <fieldset class="filter-box shadow-lg" role="group" aria-labelledby="gruppiLabel">
                            <legend id="gruppiLabel" class="fw-bold">Gruppi</legend>
                            <input type="hidden" name="no_group" value="0">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="no_group" id="no_group_filter"
                                    value="1" {{ request('no_group') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="no_group_filter">Non allena nessun gruppo</label>
                            </div>
                        </fieldset>
                    </div>

                    <!-- Bottone filtra -->
                    <div class="col-6 col-md-2 text-center d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn admin-btn-info" aria-label="Applica i filtri">Applica
                            Filtri</button>
                    </div>
                </div>
            </fieldset>
        </form>

        <!-- Tabella allenatori -->
        <div class="table-responsive admin-table-responsive" aria-live="polite">
            <table class="table table-bordered admin-trainer-table" aria-describedby="trainerTableCaption">
                <caption id="trainerTableCaption" class="visually-hidden">Tabella degli allenatori</caption>
                <thead>
                    <tr>
                        <th scope="col" class="d-table-cell d-md-none">Nome e Cognome</th>
                        <th scope="col" class="d-none d-md-table-cell">Nome</th>
                        <th scope="col" class="d-none d-md-table-cell">Cognome</th>
                        <th scope="col" class="d-none d-md-table-cell">Gruppi</th>
                        <th scope="col" class="d-none d-md-table-cell">Stipendio Tot:</th>
                        <th scope="col">Dettagli</th>
                        <th scope="col">Anche corsista?</th>
                    </tr>
                </thead>
                <tbody id="trainer_table_body">
                    @forelse ($trainers as $trainer)
                        <tr data-name="{{ $trainer->name }}" data-cognome="{{ $trainer->cognome }}"
                            data-groups="@foreach ($trainer->groups as $group){{ $group->nome }}@if (!$loop->last), @endif @endforeach">
                            <td class="d-table-cell d-md-none">{{ $trainer->name }} {{ $trainer->cognome }}</td>
                            <td class="d-none d-md-table-cell">{{ $trainer->name }}</td>
                            <td class="d-none d-md-table-cell">{{ $trainer->cognome }}</td>
                            <td class="d-none d-md-table-cell">
                                @if ($trainer->primoAllenatoreGroups->isEmpty() && $trainer->secondoAllenatoreGroups->isEmpty())
                                    <p>Non allena nessun gruppo</p>
                                @else
                                    @foreach ($trainer->primoAllenatoreGroups as $group)
                                        <p class="m-1">{{ $group->nome }}</p>
                                    @endforeach
                                    @foreach ($trainer->secondoAllenatoreGroups as $group)
                                        <p class="m-1">{{ $group->nome }}</p>
                                    @endforeach
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                {{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</td>
                            <td>
                                <a href="{{ route('admin.trainer.details', $trainer) }}" class="btn admin-btn-info"
                                    aria-label="Visualizza dettagli di {{ $trainer->name }} {{ $trainer->cognome }}">
                                    Visualizza Dettagli
                                </a>
                            </td>
                            <td>
                                <form method="POST"
                                    action="{{ route('admin.user.make-trainer-student', $trainer) }}">
                                    @csrf
                                    <div class="d-flex">
                                        <label for="is_corsista_{{ $trainer->id }}"
                                            class="visually-hidden">Corsista?</label>
                                        <select class="form-control mb-2 mb-md-0" name="is_corsista"
                                            id="is_corsista_{{ $trainer->id }}">
                                            <option @if ($trainer->is_corsista == 1) selected @endif value="1">SI
                                            </option>
                                            <option @if ($trainer->is_corsista == 0) selected @endif value="0">NO
                                            </option>
                                        </select>
                                        <button type="submit" class="btn admin-btn-info ms-2"
                                            aria-label="Salva stato corsista">Modifica</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="no_trainers_row">
                            <td colspan="7" class="text-center">Non ci sono allenatori disponibili</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $trainers->links('pagination::bootstrap-5') }}
        </div>

        <script>
            document.querySelectorAll('#trainer_name, #group_filter').forEach(function(input) {
                input.addEventListener('input', function() {
                    if (this.value === '') {
                        document.getElementById('filterForm').submit();
                    }
                });
            });
        </script>
    </main>
</x-layout>
