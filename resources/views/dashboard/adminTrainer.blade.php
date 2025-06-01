<x-layout documentTitle="Admin Trainer Dashboard">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-5 pt-md-0">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.dashboard') }}">Gruppi</a>
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
            <a class="nav-link" aria-current="page" href="{{ route('admin.week') }}">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
    </ul>
    @if (session('success'))
        <div class="alert alert-dismissible custom-alert-success">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container mt-md-5 admin-trainer-dashboard">
        <div class="pt-5 pt-md-0">
            <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Allenatori</h2>
        </div>
        <!-- Form per inviare i filtri al server -->
        <form id="filterForm" method="GET" action="{{ route('admin.dashboard.trainer') }}">
            <div class="mb-4 admin-student-filter">
                <div class="row">
                    <!-- Filtro per Nome e Cognome -->
                    <div class="col-md-4 my-auto">
                        <input type="search" name="trainer_name" id="trainer_name" class="custom-form-input shadow-lg"
                            placeholder="Nome o Cognome" value="{{ request('trainer_name') }}">
                        <input type="search" name="group_filter" id="group_filter" class="custom-form-input shadow-lg"
                            placeholder="Gruppi" value="{{ request('group_filter') }}">
                    </div>

                    <!-- Checkbox per "Non allena nessun gruppo" -->
                    <div class="col-6 col-md-2">
                        <div class="filter-box shadow-lg">
                            <p class="fw-bold">Gruppi</p>
                            <input type="hidden" name="no_group" value="0">
                            <label for="no_group_filter" class="mt-2">
                                <input type="checkbox" name="no_group" id="no_group_filter" value="1"
                                    {{ request('no_group') == '1' ? 'checked' : '' }}> Non allena nessun gruppo
                            </label>
                        </div>
                    </div>

                    <div class="col-6 col-md-2 text-center d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn admin-btn-info">Applica Filtri</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive admin-table-responsive">
            <table class="table table-bordered admin-trainer-table">
                <thead>
                    <tr>
                        <th class="d-table-cell d-md-none">Nome e Cognome</th>
                        <th class="d-none d-md-table-cell">Nome</th>
                        <th class="d-none d-md-table-cell">Cognome</th>
                        <th class="d-none d-md-table-cell">Gruppi</th>
                        <th class="d-none d-md-table-cell">Stipendio Tot:</th>
                        <th>Dettagli</th>
                        <th>Questo Allenatore è anche un Corsista?</th>
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
                            <td class="d-none d-md-table-cell">{{ $trainer->calcolaStipendioAllenatore($trainer->id) }}
                                €</td>
                            <td>
                                <a href="{{ route('admin.trainer.details', $trainer) }}"
                                    class="btn admin-btn-info">Visualizza Dettagli</a>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.user.make-trainer-student', $trainer) }}">
                                    @csrf
                                    <div class="d-flex">
                                        <select class="form-control mb-2 mb-md-0" name="is_corsista">
                                            <option @if ($trainer->is_corsista == 1) selected @endif value="1">SI
                                            </option>
                                            <option @if ($trainer->is_corsista == 0) selected @endif value="0">NO
                                            </option>
                                        </select>
                                        <button type="submit" class="btn admin-btn-info">Modifica</button>
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
        // Aggiungi evento di aggiornamento automatico quando si cancella il contenuto degli input
        document.querySelectorAll('#trainer_name, #group_filter').forEach(function(input) {
            input.addEventListener('input', function() {
                if (this.value === '') {
                    document.getElementById('filterForm').submit(); // Invia il form automaticamente
                }
            });
        });
    </script>
</x-layout>
