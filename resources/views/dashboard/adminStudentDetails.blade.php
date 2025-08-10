<x-layout documentTitle="Admin Student Details">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0 pt-md-3" role="navigation" aria-label="Navigazione amministrativa">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.dashboard') }}"
                aria-label="Vai alla pagina gruppi">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}"
                aria-label="Vai alla pagina allenatori">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student', session('student_filters', [])) }}"
                aria-label="Vai alla pagina corsisti">Corsisti</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.week') }}"
                aria-label="Vai alla pagina settimana">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}" aria-label="Vai alla pagina log">Log</a>
        </li>
    </ul>
    <main>
        <h1 class="visually-hidden">Dettaglio corsista - area amministrativa</h1>

        <div class="container mt-5 pt-5 admin-student-details">
            <div class="row mt-5">
                <div class="col-12 mt-4">
                    <div class="card shadow-sm admin-student-card mt-5 mt-md-0">
                        <div class="admin-student-card-header">
                            <h2>Scheda del corsista {{ $student->name }} {{ $student->cognome }}</h2>
                        </div>
                        <form method="POST" action="{{ route('admin.update.student', $student) }}"
                            aria-label="Modifica dati corsista">
                            @csrf
                            <div class="card-body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12 col-md-3">
                                            <!-- Campo per Nome -->
                                            <div class="mb-3">
                                                <label for="name" class="form-label"><strong>Nome:</strong></label>
                                                <input type="text" id="name" name="name" class="form-control"
                                                    value="{{ $student->name }}">
                                            </div>
                                            <!-- Campo per Cognome -->
                                            <div class="mb-3">
                                                <label for="cognome"
                                                    class="form-label"><strong>Cognome:</strong></label>
                                                <input type="text" id="cognome" name="cognome" class="form-control"
                                                    value="{{ $student->cognome }}">
                                            </div>
                                            <!-- Campo per Mail (non modificabile) -->
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Mail:</strong></label>
                                                <p class="form-control" aria-readonly="true">{{ $student->email }}</p>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <!-- Campo per Livello -->
                                            <div class="mb-3">
                                                <label for="level"
                                                    class="form-label"><strong>Livello:</strong></label>
                                                <input type="number" id="level" name="level" class="form-control"
                                                    placeholder="@if ($student->livello == null) N.C. @endif"
                                                    value="{{ $student->livello }}" min="1" max="12">
                                            </div>

                                            <!-- Campo per Genere -->
                                            <div class="mb-3">
                                                <label for="genere"
                                                    class="form-label"><strong>Genere:</strong></label>
                                                @if ($student->genere != 'Misto')
                                                    <select id="genere" name="genere" class="form-control">
                                                        <option value="M"
                                                            @if ($student->genere == 'M') selected @endif>Maschio
                                                        </option>
                                                        <option value="F"
                                                            @if ($student->genere == 'F') selected @endif>Femmina
                                                        </option>
                                                        <option value="misto"
                                                            @if ($student->genere == 'misto') selected @endif>Misto
                                                        </option>
                                                    </select>
                                                @else
                                                    <p class="form-control" aria-readonly="true">{{ $student->genere }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Campo CUS Card -->
                                            <div class="mb-3">
                                                <label for="cus_card" class="form-label"><strong>CUS
                                                        Card:</strong></label>
                                                <select id="cus_card" name="cus_card" class="form-control">
                                                    <option value="1"
                                                        @if ($student->cus_card == 1) selected @endif>OK</option>
                                                    <option value="0"
                                                        @if ($student->cus_card == 0) selected @endif>NON OK
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <!-- Campo Visita Medica -->
                                            <div class="mb-3">
                                                <label for="visita_medica" class="form-label"><strong>Visita
                                                        Medica:</strong></label>
                                                <select id="visita_medica" name="visita_medica" class="form-control">
                                                    <option value="1"
                                                        @if ($student->visita_medica == 1) selected @endif>OK</option>
                                                    <option value="0"
                                                        @if ($student->visita_medica == 0) selected @endif>NON OK
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- Campo Pagamento -->
                                            <div class="mb-3">
                                                <label for="pagamento"
                                                    class="form-label"><strong>Pagamento:</strong></label>
                                                <select id="pagamento" name="pagamento" class="form-control">
                                                    <option value="1"
                                                        @if ($student->pagamento == 1) selected @endif>OK</option>
                                                    <option value="0"
                                                        @if ($student->pagamento == 0) selected @endif>NON OK
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- Campo Trimestrale -->
                                            <div class="mb-3">
                                                <label for="trimestrale"
                                                    class="form-label"><strong>Trimestrale:</strong></label>
                                                <select id="trimestrale" name="trimestrale" class="form-control">
                                                    <option value="1"
                                                        @if ($student->trimestrale == 1) selected @endif>Sì</option>
                                                    <option value="0"
                                                        @if ($student->trimestrale == 0) selected @endif>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <!-- Campo Universitario -->
                                            <div class="mb-3">
                                                <label for="universitario"
                                                    class="form-label"><strong>Universitario:</strong></label>
                                                <select id="universitario" name="universitario" class="form-control">
                                                    <option value="1"
                                                        @if ($student->universitario == 1) selected @endif>Sì</option>
                                                    <option value="0"
                                                        @if ($student->universitario == 0) selected @endif>No</option>
                                                </select>
                                            </div>

                                            <!-- Campo Anche Allenatore -->
                                            <div class="mb-3">
                                                <label for="is_trainer" class="form-label"><strong>È anche
                                                        Allenatore?</strong></label>
                                                <select id="is_trainer" name="is_trainer" class="form-control">
                                                    <option value="1"
                                                        @if ($student->is_trainer == 1) selected @endif>Sì</option>
                                                    <option value="0"
                                                        @if ($student->is_trainer == 0) selected @endif>No</option>
                                                </select>
                                            </div>

                                            <!-- Campo Recuperi -->
                                            <div class="mb-3">
                                                <label for="Nrecuperi"
                                                    class="form-label"><strong>Recuperi:</strong></label>
                                                <input type="number" id="Nrecuperi" name="Nrecuperi"
                                                    class="form-control" value="{{ $student->Nrecuperi }}"
                                                    min="0">
                                            </div>
                                        </div>

                                        <!-- Pulsanti di azione -->
                                        <div class="col-12">
                                            <div class="mb-3 d-flex justify-content-center" role="group"
                                                aria-label="Azioni sul corsista">
                                                <button type="submit" class="btn admin-btn-warning me-1">Salva
                                                    Modifiche</button>
                                                <button type="button" class="btn admin-btn-danger ms-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $student->id }}">
                                                    Elimina Corsista
                                                </button>
                                            </div>
                                        </div>
                                    </div> <!-- fine row -->
                                </div> <!-- fine container-fluid -->
                            </div> <!-- fine card-body -->
                        </form>
                        <div class="text-center">
                            <a class="btn admin-btn-info fs-6"
                                href="{{ route('admin.dashboard.student', session('student_filters', [])) }}">
                                Torna alla Lista Corsisti
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inizio nuove tabelle affiancate -->
            <div class="row mb-3 mt-3">
                <!-- Assenze -->
                <div class="col-md-6">
                    <h2 class="custom-subtitle h3" id="assenze-title">Assenze</h2>
                    <table id="table-assenze" class="table table-bordered admin-table"
                        aria-labelledby="assenze-title">
                        <thead>
                            <tr>
                                <th scope="col">Data Assenza</th>
                                <th scope="col">Gruppo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (Auth::user()->countAbsStudent($student) as $alias)
                                <tr>
                                    <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                    <td>
                                        <a href="{{ route('alias.details', $alias) }}">{{ $alias->nome }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Non ci sono assenze per questo corsista
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button id="toggle-assenze" class="btn btn-primary mt-2 fw-bold"
                        aria-controls="table-assenze">Mostra tutto</button>
                </div>

                <!-- Recuperi -->
                <div class="col-md-6">
                    <h2 class="custom-subtitle h3" id="recuperi-title">Recuperi</h2>
                    <table id="table-recuperi" class="table table-bordered admin-table"
                        aria-labelledby="recuperi-title">
                        <thead>
                            <tr>
                                <th scope="col">Data Recupero</th>
                                <th scope="col">Gruppo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (Auth::user()->countRecStudent($student) as $alias)
                                <tr>
                                    <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                    <td>
                                        <a href="{{ route('alias.details', $alias) }}">{{ $alias->nome }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Non ci sono recuperi per questo corsista
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button id="toggle-recuperi" class="btn btn-primary mt-2 fw-bold"
                        aria-controls="table-recuperi">Mostra tutto</button>
                </div>

                <!-- Log -->
                <div class="col-12">
                    <h2 class="custom-subtitle h3 mt-4" id="log-title">Log</h2>
                    <table id="corsistaLogsTable" class="table table-bordered admin-table"
                        aria-labelledby="log-title">
                        <thead>
                            <tr>
                                <th scope="col">Utente</th>
                                <th scope="col">Azione</th>
                                <th scope="col">Tipo elemento</th>
                                <th scope="col">Elemento Modificato</th>
                                <th scope="col">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                @if ($log->model_type != 'User' && $log->custom_action != null)
                                    <tr class="corsista-log-row">
                                        <td>{{ $log->user_name }} {{ $log->user_cognome }}</td>
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
                                                {{ $log->userModified->name }} {{ $log->userModified->cognome }}
                                            @else
                                                {{ $log->model_name }}
                                                @if ($log->model_cognome)
                                                    {{ $log->model_cognome }}
                                                @endif
                                                @if ($log->data_allenamento)
                                                    / {{ $log->formatDataMod($log->data_allenamento) }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $log->formatData($log->created_at) }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Nessun log disponibile per i corsisti.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button id="toggle-logs" class="btn btn-primary mt-2 fw-bold"
                        aria-controls="corsistaLogsTable">Mostra tutto</button>
                </div>
            </div>
        </div>

        <!-- Modale Eliminazione -->
        <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1"
            aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header admin-modal-header">
                        <h2 class="modal-title fs-5" id="deleteModalLabel{{ $student->id }}">
                            Sicuro di voler eliminare lo studente {{ $student->name }} {{ $student->cognome }}?
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center admin-modal-body">
                        <form action="{{ route('student.destroy', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn admin-btn-danger mx-2">Sì</button>
                        </form>
                        <button type="button" class="btn admin-modal-btn-secondary"
                            data-bs-dismiss="modal">No</button>
                    </div>
                    <div class="modal-footer admin-modal-footer">
                        <button type="button" class="btn admin-modal-btn-secondary"
                            data-bs-dismiss="modal">Chiudi</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script toggle tabelle -->
        <script>
            function toggleTableRows(tableId, buttonId) {
                const table = document.getElementById(tableId);
                const button = document.getElementById(buttonId);
                const rows = table.querySelectorAll('tbody tr');
                let expanded = false;

                const showLimitedRows = () => {
                    rows.forEach((row, index) => {
                        row.style.display = index < 3 ? '' : 'none';
                    });
                };

                const showAllRows = () => {
                    rows.forEach(row => row.style.display = '');
                };

                showLimitedRows();

                button.addEventListener('click', () => {
                    expanded = !expanded;
                    if (expanded) {
                        showAllRows();
                        button.textContent = 'Mostra meno';
                    } else {
                        showLimitedRows();
                        button.textContent = 'Mostra tutto';
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                toggleTableRows('table-assenze', 'toggle-assenze');
                toggleTableRows('table-recuperi', 'toggle-recuperi');
                toggleTableRows('corsistaLogsTable', 'toggle-logs');
            });
        </script>
</x-layout>
