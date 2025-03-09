<x-layout documentTitle="Admin Log">
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
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
</ul>
    <div class="container mt-md-5 admin-dashboard">
        <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Log delle Operazioni</h2>
        <!-- Log degli Admin -->
        <h3 class="mb-3 custom-subtitle">Admin</h3>
        <table id="adminLogsTable" class="table table-bordered admin-table">
            <thead>
                <tr>
                    <th>Utente</th>
                    <th>Azione</th>
                    {{-- <th>Tipo elemento</th> --}}
                    <th>Elemento Modificato</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($finalFilteredLogs as $log)
                    <tr class="admin-log-row">
                        <td>{{ $log->user->name }} {{ $log->user->cognome }}</td>
                        <td>
                            @if ($log->action == 'deleting')
                                Elemento eliminato
                            @elseif($log->action == 'creating')
                                Elemento creato
                            @elseif($log->action == 'updating')
                                Elemento modificato
                            @else
                                N/A
                            @endif
                        </td>
                        {{-- <td>
                            @if ($log->alias)
                                Alias
                            @elseif($log->group)
                                Group
                            @elseif($log->userModified)
                                Utente
                            @else
                                {{ $log->model_type }}
                            @endif
                        </td> --}}
                        <td>
                            @if ($log->alias)
                                <a href="{{ route('alias.details', $log->alias) }}">{{ $log->alias->nome }} /
                                    {{ $log->alias->formatData($log->alias->data_allenamento) }}</a>
                            @elseif($log->group)
                                <a href="{{ route('admin.group.details', $log->group) }}">{{ $log->group->nome }}</a>
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
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Nessun log disponibile per gli admin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex mb-4">
            <button id="loadMoreAdminLogs" class="btn btn-primary me-2" onclick="loadMoreRows('adminLogsTable')">Mostra
                altri</button>
            <button id="loadLessAdminLogs" class="btn btn-secondary" onclick="loadLessRows('adminLogsTable')">Mostra
                meno</button>
        </div>

        <!-- Log dei Trainer -->
        <h3 class="mb-3 custom-subtitle">Allenatori</h3>
        <table id="trainerLogsTable" class="table table-bordered admin-table">
            <thead>
                <tr>
                    <th>Utente</th>
                    <th>Azione</th>
                    {{-- <th>Tipo elemento</th> --}}
                    <th>Elemento Modificato</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainerLogs as $log)
                    @if ($log->model_type != 'User')
                        <tr class="trainer-log-row">
                            <td>
                                @if ($log->user)
                                    <a href="{{ route('admin.trainer.details', $log->user) }}">{{ $log->user->name }}
                                        {{ $log->user->cognome }}</a>
                                @else
                                    {{ $log->user_name }}
                                    {{ $log->user_cognome }}
                                @endif
                            </td>
                            <td>
                                @if ($log->custom_action)
                                    {{ $log->custom_action }}
                                @elseif ($log->action == 'deleting')
                                    Elemento eliminato
                                @elseif($log->action == 'creating')
                                    Elemento creato
                                @elseif($log->action == 'updating')
                                    Elemento modificato
                                @else
                                    N/A
                                @endif

                            </td>
                            {{-- <td>
                                @if ($log->alias)
                                    Alias
                                @elseif($log->group)
                                    Group
                                @elseif($log->userModified)
                                    Utente
                                @else
                                    {{ $log->model_type }}
                                @endif
                            </td> --}}
                            <td>
                                @if ($log->alias)
                                    <a href="{{ route('alias.details', $log->alias) }}">{{ $log->alias->nome }} /
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
                        <td colspan="7" class="text-center">Nessun log disponibile per i trainer.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        <div class="d-flex mb-4">
            <button id="loadMoreTrainerLogs" class="btn btn-primary me-2"
                onclick="loadMoreRows('trainerLogsTable')">Mostra altri</button>
            <button id="loadLessTrainerLogs" class="btn btn-secondary" onclick="loadLessRows('trainerLogsTable')">Mostra
                meno</button>
        </div>

        <!-- Log dei Corsisti -->
        <h3 class="mb-3 custom-subtitle">Corsisti</h3>
        <table id="corsistaLogsTable" class="table table-bordered admin-table">
            <thead>
                <tr>
                    <th>Utente</th>
                    <th>Azione</th>
                    {{-- <th>Tipo elemento</th> --}}
                    <th>Elemento Modificato</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($corsistaLogs as $log)
                    @if (
                        ($log->model_type != 'User' && $log->custom_action != null && $log->custom_action != 'Elemento confermato') ||
                            $log->user_name == null)
                        <tr class="corsista-log-row">
                            <td>
                                @if ($log->user)
                                    <a href="{{ route('admin.student.details', $log->user) }}">{{ $log->user->name }}
                                        {{ $log->user->cognome }}</a>
                                @elseif($log->user_name != null)
                                    {{ $log->user_name }}
                                    {{ $log->user_cognome }}
                                @else
                                    Utente non registrato
                                @endif
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
                            {{-- <td>
                                @if ($log->alias)
                                    Alias
                                @elseif($log->group)
                                    Group
                                @elseif($log->userModified)
                                    Utente
                                @else
                                    {{ $log->model_type }}
                                @endif
                            </td> --}}
                            <td>
                                @if ($log->alias)
                                    <a href="{{ route('alias.details', $log->alias) }}">{{ $log->alias->nome }} /
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
        <div class="d-flex mb-4">
            <button id="loadMoreCorsistaLogs" class="btn btn-primary me-2"
                onclick="loadMoreRows('corsistaLogsTable')">Mostra altri</button>
            <button id="loadLessCorsistaLogs" class="btn btn-secondary"
                onclick="loadLessRows('corsistaLogsTable')">Mostra meno</button>
        </div>
    </div>

    <script>
        function loadMoreRows(tableId) {
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll('tbody tr');
            let hiddenRows = Array.from(rows).filter(row => row.style.display === 'none');

            for (let i = 0; i < 10 && hiddenRows.length > 0; i++) {
                hiddenRows[i].style.display = '';
            }

            if (hiddenRows.length <= 10) {
                document.getElementById('loadMore' + tableId.charAt(0).toUpperCase() + tableId.slice(1)).style.display =
                    'none';
            }
        }

        function loadLessRows(tableId) {
            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll('tbody tr');
            let visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');

            if (visibleRows.length > 10) {
                for (let i = visibleRows.length - 1; i >= visibleRows.length - 10 && i >= 10; i--) {
                    visibleRows[i].style.display = 'none';
                }
            }

            if (visibleRows.length <= 20) {
                document.getElementById('loadLess' + tableId.charAt(0).toUpperCase() + tableId.slice(1)).style.display =
                    'none';
            } else {
                document.getElementById('loadMore' + tableId.charAt(0).toUpperCase() + tableId.slice(1)).style.display = '';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const tables = ['adminLogsTable', 'trainerLogsTable', 'corsistaLogsTable'];
            tables.forEach(tableId => {
                const rows = document.querySelectorAll(`#${tableId} tbody tr`);
                rows.forEach((row, index) => {
                    if (index >= 10) {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-layout>
