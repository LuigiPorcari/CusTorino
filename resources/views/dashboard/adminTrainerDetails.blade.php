<x-layout documentTitle="Admin Trainer Details">
    <div class="container mt-5 admin-trainer-details">
        <div class="row">
            <h1 class="mt-5 custom-title">Dettagli pagamenti {{ $trainer->name }} {{ $trainer->cognome }}</h1>
            <div class="col-12">
                <div class="d-flex">
                    <h2 class="mt-5 mb-4 me-5 fw-bolder fs-1">{{ $trainer->name }} {{ $trainer->cognome }}</h2>
                    <div>
                        <button type="button" class="btn admin-btn-danger ms-md-5 mt-5" data-bs-toggle="modal"
                        data-bs-target="#deleteModalTrainerAdmin">Elimina Trainer</button>
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
                    <thead>
                        <tr>
                            <th>Nome Gruppo Alias</th>
                            <th>Data Gruppo Alias</th>
                            <th>Tipo allenatore / Condiviso</th>
                            <th>Stipendio (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trainer->primoAllenatoreAliases as $alias)
                            <tr>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td>Primo Allenatore / {{ $alias->condiviso == 'true' ? 'Si' : 'No' }}</td>
                                <td>{{ $alias->condiviso == 'true' ? '15.00 €' : '22.50 €' }}</td>
                            </tr>
                        @endforeach
                        @foreach ($trainer->secondoAllenatoreAliases as $alias)
                            <tr>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td>Secondo Allenatore / {{ $alias->condiviso == 'true' ? 'Si' : 'No' }}</td>
                                <td>{{ $alias->condiviso == 'true' ? '15.00 €' : '7.50 €' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right" style="background-color: white;"><strong>Stipendio
                                    Totale:</strong></td>
                            <td style="background-color: white;">
                                <strong>{{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <a href="{{ route('admin.dashboard.trainer') }}" class="btn admin-btn-info fs-6">Torna alla lista
                        Trainer</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModalTrainerAdmin" tabindex="-1" aria-labelledby="deleteModalTrainerLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header admin-modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalTrainerLabel">Sicuro di voler eliminare il Trainer {{$trainer->name}} {{$trainer->cognome}}?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center admin-modal-body">
                    <form action="{{ route('trainer.destroy', $trainer->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn admin-btn-danger mx-2">Si</button>
                    </form>
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
                <div class="modal-footer admin-modal-footer">
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
