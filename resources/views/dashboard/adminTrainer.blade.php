<x-layout documentTitle="Admin Trainer Dashboard">
    <ul class="nav nav-tabs admin-nav-tabs mt-5 pt-5 pt-md-0">
        <li class="nav-item admin-nav-item">
            <a class="nav-link mt-3" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.trainer') }}">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student') }}">Corsisti</a>
        </li>
    </ul>
    @if (session('success'))
        <div class="alert alert-dismissible custom-alert-success">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container mt-md-5 admin-trainer-dashboard">
        <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Allenatori</h2>

        <!-- Filtro Allenatori -->
        <div class="mb-4 admin-student-filter">
            <div class="row">
                <!-- Filtro per Nome e Cognome -->
                <div class="col-md-4 my-auto">
                    <input type="search" id="trainer_name" class="custom-form-input shadow-lg" placeholder="Nome o Cognome">
                    <input type="search" id="group_filter" class="custom-form-input shadow-lg" placeholder="Gruppi">
                </div>

                <!-- Checkbox per Gruppi -->
                <div class="col-6 col-md-2">
                    <div class="filter-box shadow-lg">
                        <p class="fw-bold">Gruppi</p>
                        <label for="no_group_filter" class="mt-2 fw-bold">
                            <input type="checkbox" id="no_group_filter" value="1"> Non allena nessun gruppo
                        </label>
                    </div>
                </div>
            </div>
        </div>

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
                                @forelse ($trainer->groups as $group)
                                    <p class="m-1">{{ $group->nome }}</p>
                                @empty
                                    <p>Non allena nessun gruppo</p>
                                @endforelse
                            </td>
                            <td class="d-none d-md-table-cell">{{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</td>
                            <td>
                                <a href="{{ route('admin.trainer.details', $trainer) }}" class="btn admin-btn-info">Visualizza Dettagli</a>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.user.make-trainer-student', $trainer) }}">
                                    @csrf
                                    <div class="d-flex">
                                        <select class="form-control mb-2 mb-md-0" name="is_corsista">
                                            <option @if ($trainer->is_corsista == 1) selected @endif value="1">SI</option>
                                            <option @if ($trainer->is_corsista == 0) selected @endif value="0">NO</option>
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
        document.querySelectorAll('#trainer_name, #group_filter, #no_group_filter').forEach(function(input) {
            input.addEventListener('input', filterTrainers);
        });

        function filterTrainers() {
            const name = document.getElementById('trainer_name').value.toLowerCase();
            const group = document.getElementById('group_filter').value.toLowerCase();
            const noGroup = document.getElementById('no_group_filter').checked;

            let visibleRows = 0; // Contatore per righe visibili

            // Nascondi la riga "Non ci sono allenatori disponibili"
            const noResultsRow = document.getElementById('no_trainers_row');
            if (noResultsRow) {
                noResultsRow.remove();
            }

            // Filtro delle righe visibili
            document.querySelectorAll('#trainer_table_body tr').forEach(function(row) {
                const rowName = row.dataset.name.toLowerCase();
                const rowCognome = row.dataset.cognome.toLowerCase();
                const rowGroups = row.dataset.groups.toLowerCase();

                const matchesName = !name || rowName.includes(name) || rowCognome.includes(name) || (rowName + ' ' +
                    rowCognome).includes(name);

                const matchesGroup = (!group && !noGroup) ||
                    (group && rowGroups.includes(group)) ||
                    (noGroup && rowGroups.trim() === '');

                if (matchesName && matchesGroup) {
                    row.style.display = '';
                    visibleRows++; // Incrementa il contatore se la riga è visibile
                } else {
                    row.style.display = 'none';
                }
            });

            // Se nessuna riga è visibile, aggiungi il messaggio "Non ci sono allenatori disponibili"
            if (visibleRows === 0) {
                const noResults = document.createElement('tr');
                noResults.id = 'no_trainers_row';
                noResults.innerHTML = '<td colspan="7" class="text-center">Non ci sono allenatori disponibili</td>';
                document.getElementById('trainer_table_body').appendChild(noResults);
            }
        }
    </script>
</x-layout>
