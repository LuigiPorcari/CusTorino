<x-layout documentTitle="Admin Trainer Details">
    <div class="container mt-5 admin-trainer-details">
        <div class="row">
            <h1 class="mt-5">Dettagli pagamenti {{ $trainer->name }} {{ $trainer->cognome }}</h1>
            <div class="col-12">
                <h2 class="mt-5 mb-4">{{ $trainer->name }} {{ $trainer->cognome }}</h2>
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
                            <td colspan="3" class="text-right" style="background-color: white;"><strong>Stipendio Totale:</strong></td>
                            <td style="background-color: white;"><strong>{{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</strong></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <a href="{{ route('admin.dashboard.trainer') }}" class="btn admin-btn-info">Torna alla lista Trainer</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
