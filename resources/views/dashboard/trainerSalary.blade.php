<x-layout documentTitle="Admin Trainer Details">
    <ul class="nav nav-tabs mt-5 pt-3">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="{{ route('trainer.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('trainer.salary') }}">Stipendio</a>
        </li>
    </ul>
    <div class="container mt-5">
        <div class="row">
            <h1 class="mt-1 mb-5">Dettagli pagamenti</h1>
            <div class="col-12">
                <!-- Tabella per mostrare assenze e presenze -->
                <table class="table table-bordered mb-5">
                    <thead>
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <p>Nome Gruppo Alias</p>
                            </th>
                            <th>
                                <p>Data Gruppo Alias</p>
                            </th>
                            <th class="d-flex justify-content-between">
                                <p>Tipo allenatore</p>
                                <p>Condiviso</p>
                            </th>
                            <th>
                                <p>Stipendio relativo alla prestazione (€)</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Auth::user()->primoAllenatoreAliases as $alias)
                            <tr>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td class="d-flex justify-content-between">
                                    <p>Primo Allenatore</p>
                                    <p>
                                        @if ($alias->condiviso == 'true')
                                            Si
                                        @else
                                            No
                                        @endif
                                    </p>
                                </td>
                                @if ($alias->condiviso == 'true')
                                    <td>15.00 €</td>
                                @else
                                    <td>22.50 €</td>
                                @endif
                            </tr>
                        @endforeach
                        @foreach (Auth::user()->secondoAllenatoreAliases as $alias)
                            <tr>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td class="d-flex justify-content-between">
                                    <p>Secondo Allenatore</p>
                                    <p>
                                        @if ($alias->condiviso == 'true')
                                            Si
                                        @else
                                            No
                                        @endif
                                    </p>
                                </td>
                                @if ($alias->condiviso == 'true')
                                    <td>15.00 €</td>
                                @else
                                    <td>7.50 €</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right"><strong>Stipendio Totale:</strong></td>
                            <td><strong>{{ Auth::user()->calcolaStipendioAllenatore(Auth::user()->id) }} €</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
