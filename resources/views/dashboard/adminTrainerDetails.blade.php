<x-layout documentTitle="Admin Trainer Details">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0 pt-md-3" role="navigation" aria-label="Navigazione amministrativa">
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
            <a class="nav-link" href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
    </ul>

    <main class="container mt-5 admin-trainer-details" id="main-content">
        <div class="row">
            <header class="pt-5 pt-md-0">
                <h1 class="mt-5 custom-title">Dettagli pagamenti {{ $trainer->name }} {{ $trainer->cognome }}</h1>
            </header>
            <div class="col-12">
                <div class="d-flex">
                    <h2 class="mt-5 mb-4 me-5 fw-bolder fs-1">{{ $trainer->name }} {{ $trainer->cognome }}</h2>
                    <div>
                        <button type="button" class="btn admin-btn-danger ms-md-5 mt-5" data-bs-toggle="modal"
                            data-bs-target="#deleteModalTrainerAdmin">Elimina Allenatore</button>
                    </div>
                </div>

                <table class="table table-bordered admin-trainer-table">
                    <thead>
                        <tr>
                            <th>Totale Presenze</th>
                            <th>Totale Assenze</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ Auth::user()->countAttendanceTrainer($trainer) }}</td>
                            <td>{{ Auth::user()->countAbsenceTrainer($trainer) }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered admin-trainer-table">
                    <thead class="custom-table-header">
                        <tr>
                            <th>Nome Gruppo Alias</th>
                            <th>Data Gruppo Alias</th>
                            <th>Tipo allenatore / Condiviso</th>
                            <th>Stipendio relativo alla prestazione (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aliasesTrainer as $alias)
                            <tr>
                                <td
                                    class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">
                                    {{ $alias->nome }}</td>
                                <td
                                    class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">
                                    {{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td
                                    class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">
                                    {{ $alias->primo_allenatore_id == $trainer->id ? 'Primo Allenatore' : 'Secondo Allenatore' }}
                                    / {{ $alias->condiviso == 'true' ? 'Si' : 'No' }}
                                </td>
                                <td
                                    class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">
                                    @if ($alias->primo_allenatore_id == $trainer->id)
                                        {{ $alias->condiviso == 'true' ? '15.00 €' : '22.50 €' }}
                                    @else
                                        {{ $alias->condiviso == 'true' ? '15.00 €' : '7.50 €' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end"><strong>Stipendio Totale:</strong></td>
                            <td><strong>{{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-center">
                    <a href="{{ route('admin.dashboard.trainer') }}" class="btn admin-btn-info fs-6">Torna alla lista
                        Allenatori</a>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="deleteModalTrainerAdmin" tabindex="-1" aria-labelledby="deleteModalTrainerLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header admin-modal-header">
                    <h2 class="modal-title fs-5" id="deleteModalTrainerLabel">Sicuro di voler eliminare l'Allenatore
                        {{ $trainer->name }} {{ $trainer->cognome }}?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body d-flex justify-content-center admin-modal-body">
                    <form action="{{ route('trainer.destroy', $trainer->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn admin-btn-danger mx-2">Sì</button>
                    </form>
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
                <div class="modal-footer admin-modal-footer">
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
