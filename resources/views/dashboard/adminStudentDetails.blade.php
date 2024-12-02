<x-layout documentTitle="Admin Student Details">
    <div class="container mt-5 pt-5 admin-student-details">
        <!-- Inizio nuove tabelle affiancate -->
        <div class="row mb-3 mt-3">
            <div class="col-md-6">
                <h3 class=" custom-subtitle">Assenze</h3>
                <table id="table-assenze" class="table table-bordered admin-table">
                    <thead>
                        <tr>
                            <th>Data Assenza</th>
                            <th>Gruppo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (Auth::user()->countAbsStudent($student) as $alias)
                            <tr>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td><a href="{{ route('alias.details', $alias) }}">{{ $alias->nome }}</a></td>
                            </tr>
                        @empty
                            <tr id="no_students_row">
                                <td colspan="4" class="text-center">Non ci sono assenze per questo corsista</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <button id="toggle-assenze" class="btn btn-primary mt-2 fw-bold">Mostra tutto</button>
            </div>
            <div class="col-md-6">
                <h3 class=" custom-subtitle">Recuperi</h3>
                <table id="table-recuperi" class="table table-bordered admin-table">
                    <thead>
                        <tr>
                            <th>Data Recupero</th>
                            <th>Gruppo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (Auth::user()->countRecStudent($student) as $alias)
                            <tr>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td><a href="{{ route('alias.details', $alias) }}">{{ $alias->nome }}</a></td>
                            </tr>
                        @empty
                            <tr id="no_students_row">
                                <td colspan="4" class="text-center">Non ci sono recuperi per questo corsista</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <button id="toggle-recuperi" class="btn btn-primary mt-2 fw-bold">Mostra tutto</button>
            </div>
            <div class="col-12">
                <!-- Log dei Corsisti -->
                <h3 class="mb-3 mt-3 custom-subtitle">Log</h3>
                <table id="corsistaLogsTable" class="table table-bordered admin-table">
                    <thead>
                        <tr>
                            <th>Utente</th>
                            <th>Azione</th>
                            <th>Tipo elemento</th>
                            <th>Elemento Modificato</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            @if (($log->model_type != 'User' && $log->custom_action != null))
                                <tr class="corsista-log-row">
                                    <td>
                                        {{ $log->user_name }}
                                        {{ $log->user_cognome }}
                                    </td>
                                    <td>
                                        @if ($log->custom_action)
                                            {{ $log->custom_action }}
                                        @elseif($log->action == 'creating')
                                            Elemento creato
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if ($log->alias)
                                            Alias
                                        @elseif($log->group)
                                            Group
                                        @elseif($log->userModified)
                                            Utente
                                        @else
                                            {{ $log->model_type }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($log->alias)
                                            <a href="{{ route('alias.details', $log->alias) }}">{{ $log->alias->nome }}
                                                /
                                                {{ $log->alias->formatData($log->alias->data_allenamento) }}</a>
                                        @elseif($log->group)
                                            <a
                                                href="{{ route('admin.group.details', $log->group) }}">{{ $log->group->nome }}</a>
                                        @elseif($log->userModified)
                                            @if ($log->userModified->is_corsista)
                                                <a href="{{ route('admin.student.details', $log->userModified) }}">{{ $log->userModified->name }}
                                                    {{ $log->userModified->cognome }}</a>
                                            @elseif($log->userModified->is_trainer)
                                                <a href="{{ route('admin.trainer.details', $log->userModified) }}">{{ $log->userModified->name }}
                                                    {{ $log->userModified->cognome }}</a>
                                            @else
                                                {{ $log->userModified->name }}
                                                {{ $log->userModified->cognome }}
                                            @endif
                                        @else
                                            {{ $log->model_name }}
                                            @if ($log->model_cognome != null)
                                                {{ $log->model_cognome }}
                                            @endif
                                            @if ($log->data_allenamento != null)
                                                / {{ $log->formatDataMod($log->data_allenamento) }}
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $log->formatData($log->created_at) }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nessun log disponibile per i corsisti.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <button id="toggle-logs" class="btn btn-primary mt-2 fw-bold">Mostra tutto</button>
            </div>
        </div>
        <!-- Fine nuove tabelle affiancate -->
        <div class="row justify-content-center">
            <div class="col-md-8 mt-1">
                <div class="card shadow-sm admin-student-card">
                    <div class="admin-student-card-header">
                        <h3>Scheda del corsista {{ $student->name }} {{ $student->cognome }}</h3>
                    </div>
                    <form method="POST" action="{{ route('admin.update.student', $student) }}">
                        @csrf
                        <div class="card-body">
                            <!-- Campo per Nome -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Nome:</strong></label>
                                <input type="text" class="form-control" name="name" value="{{ $student->name }}">
                            </div>
                            <!-- Campo per Cognome -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Cognome:</strong></label>
                                <input type="text" class="form-control" name="cognome"
                                    value="{{ $student->cognome }}">
                            </div>
                            <!-- Campo per Mail -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Mail:</strong></label>
                                <p class="form-control">{{ $student->email }}</p>
                            </div>
                            <!-- Campo per Livello -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Livello:</strong></label>
                                <input placeholder="@if ($student->livello == null) N.C. @endif"
                                    value="{{ $student->livello }}" type="number" class="form-control" name="level"
                                    min="1" max="12">
                            </div>
                            <!-- Campo per Genere -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Genere:</strong></label>
                                @if ($student->genere != 'Misto')
                                    <select class="form-control" name="genere">
                                        <option @if ($student->genere == 'M') selected @endif value="M">
                                            Maschio
                                        </option>
                                        <option @if ($student->genere == 'F') selected @endif value="F">
                                            Femmina
                                        </option>
                                        <option @if ($student->genere == 'misto') selected @endif value="misto">
                                            Misto
                                        </option>
                                    </select>
                                @else
                                    <p class="form-control">{{ $student->genere }}</p>
                                @endif
                            </div>
                            <!-- CUS Card -->
                            <div class="mb-3">
                                <label class="form-label"><strong>CUS Card:</strong></label>
                                <select class="form-control" name="cus_card">
                                    <option @if ($student->cus_card == 1) selected @endif value="1">OK
                                    </option>
                                    <option @if ($student->cus_card == 0) selected @endif value="0">NON OK
                                    </option>
                                </select>
                            </div>
                            <!-- Visita Medica -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Visita Medica:</strong></label>
                                <select class="form-control" name="visita_medica">
                                    <option @if ($student->visita_medica == 1) selected @endif value="1">OK
                                    </option>
                                    <option @if ($student->visita_medica == 0) selected @endif value="0">NON OK
                                    </option>
                                </select>
                            </div>
                            <!-- Pagamento -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Pagamento:</strong></label>
                                <select class="form-control" name="pagamento">
                                    <option @if ($student->pagamento == 1) selected @endif value="1">OK
                                    </option>
                                    <option @if ($student->pagamento == 0) selected @endif value="0">NON OK
                                    </option>
                                </select>
                            </div>
                            <!-- Trimestrale -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Trimestrale:</strong></label>
                                <select class="form-control" name="trimestrale">
                                    <option @if ($student->trimestrale == 1) selected @endif value="1">Si
                                    </option>
                                    <option @if ($student->trimestrale == 0) selected @endif value="0">No
                                    </option>
                                </select>
                            </div>
                            <!-- Universitario -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Universitario:</strong></label>
                                <select class="form-control" name="universitario">
                                    <option @if ($student->universitario == 1) selected @endif value="1">SI
                                    </option>
                                    <option @if ($student->universitario == 0) selected @endif value="0">NO
                                    </option>
                                </select>
                            </div>
                            <!-- Questo Corsista è anche un Allenatore -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Questo Corsista è anche un
                                        Allenatore?</strong></label>
                                <select class="form-control" name="is_trainer">
                                    <option @if ($student->is_trainer == 1) selected @endif value="1">SI
                                    </option>
                                    <option @if ($student->is_trainer == 0) selected @endif value="0">NO
                                    </option>
                                </select>
                            </div>
                            <!-- Recuperi -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Recuperi:</strong></label>
                                <input value="{{ $student->Nrecuperi }}" type="number" class="form-control"
                                    name="Nrecuperi" min="0">
                            </div>
                            <!-- Pulsanti Salva ed Elimina -->
                            <div class="mb-3 d-flex justify-content-center">
                                <button type="submit" class="btn admin-btn-warning me-1">Salva Modifiche</button>
                                <button type="button" class="btn admin-btn-danger ms-1" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $student->id }}">Elimina Corsista</button>
                            </div>
                    </form>
                    <div class="text-center">
                        <a href="{{ route('admin.dashboard.student') }}" class="btn admin-btn-info fs-6">Torna alla
                            Lista
                            Corsisti</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1"
        aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header admin-modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel{{ $student->id }}">Sicuro di voler eliminare
                        lo studente {{ $student->name }} {{ $student->cognome }}?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center admin-modal-body">
                    <form action="{{ route('student.destroy', $student->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn admin-btn-danger mx-2">Si</button>
                    </form>
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
                <div class="modal-footer admin-modal-footer">
                    <button type="button" class="btn admin-modal-btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleTableRows(tableId, buttonId) {
            const table = document.getElementById(tableId);
            const button = document.getElementById(buttonId);
            const rows = table.querySelectorAll('tbody tr');
            let isExpanded = false;

            // Mostra solo le prime 3 righe all'inizio
            rows.forEach((row, index) => {
                if (index >= 3) row.style.display = 'none';
            });

            // Gestione click sul pulsante
            button.addEventListener('click', () => {
                isExpanded = !isExpanded;
                rows.forEach((row, index) => {
                    row.style.display = isExpanded || index < 3 ? '' : 'none';
                });
                button.textContent = isExpanded ? 'Mostra meno' : 'Mostra tutto';
            });
        }

        // Gestione delle altre tabelle
        toggleTableRows('table-assenze', 'toggle-assenze');
        toggleTableRows('table-recuperi', 'toggle-recuperi');
        toggleTableRows('corsistaLogsTable', 'toggle-logs');
    </script>
</x-layout>
