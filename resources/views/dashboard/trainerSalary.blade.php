<x-layout documentTitle="Admin Trainer Details">
    <ul class="nav nav-tabs mt-5 pt-5 admin-nav-tabs">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('trainer.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" href="{{ route('trainer.salary') }}">Stipendio</a>
        </li>
    </ul>

    <div class="container mt-5">
        <div class="row">
            <h1 class="custom-title mt-1 mb-5">Dettagli pagamenti</h1>
            <div class="col-12">
                <table class="table table-bordered admin-trainer-table mb-5">
                    <thead class="custom-table-header">
                        <tr>
                            <th>Totale Presenze</th>
                            <th>Totale Assenze</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ Auth::user()->countAttendanceTrainer(Auth::user()) }}</td>
                            <td>{{ Auth::user()->countAbsenceTrainer(Auth::user()) }}</td>
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
                        @foreach (Auth::user()->primoAllenatoreAliases as $alias)
                            <tr>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td>Primo Allenatore / {{ $alias->condiviso == 'true' ? 'Si' : 'No' }}</td>
                                <td>{{ $alias->condiviso == 'true' ? '15.00 €' : '22.50 €' }}</td>
                            </tr>
                        @endforeach

                        @foreach (Auth::user()->secondoAllenatoreAliases as $alias)
                            <tr>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td>Secondo Allenatore / {{ $alias->condiviso == 'true' ? 'Si' : 'No' }}</td>
                                <td>{{ $alias->condiviso == 'true' ? '15.00 €' : '7.50 €' }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right" style="background-color: white;"><strong>Stipendio Totale:</strong></td>
                            <td style="background-color: white;"><strong>{{ Auth::user()->calcolaStipendioAllenatore(Auth::user()->id) }} €</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
