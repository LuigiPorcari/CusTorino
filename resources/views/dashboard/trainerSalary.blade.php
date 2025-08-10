<x-layout documentTitle="Admin Trainer Details">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0" role="navigation" aria-label="Navigazione amministrativa">
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('trainer.dashboard') }}">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('trainer.group') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('trainer.salary') }}">Compensi</a>
        </li>
    </ul>

    <main class="container mt-5" id="main-content">
        <div class="row mt-5 pt-5">
            <header>
                <h1 class="custom-title my-5 pt-5">Dettagli pagamenti</h1>
            </header>

            <div class="col-12">
                <section aria-labelledby="presenzeAssenze">
                    <h2 id="presenzeAssenze" class="visually-hidden">Tabella presenze e assenze</h2>
                    <table class="table table-bordered admin-trainer-table mb-5">
                        <thead class="custom-table-header">
                            <tr>
                                <th scope="col">Totale Presenze</th>
                                <th scope="col">Totale Assenze</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ Auth::user()->countAttendanceTrainer(Auth::user()) }}</td>
                                <td>{{ Auth::user()->countAbsenceTrainer(Auth::user()) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section aria-labelledby="dettagliStipendi">
                    <h2 id="dettagliStipendi" class="visually-hidden">Dettaglio compensi per ogni gruppo</h2>
                    <table class="table table-bordered admin-trainer-table">
                        <thead class="custom-table-header">
                            <tr>
                                <th scope="col">Nome Gruppo Alias</th>
                                <th scope="col">Data Gruppo Alias</th>
                                <th scope="col">Tipo allenatore / Condiviso</th>
                                <th scope="col">Stipendio relativo alla prestazione (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aliasesTrainer as $alias)
                                @php
                                    $isPast = \Carbon\Carbon::parse($alias->data_allenamento)->lt(
                                        \Carbon\Carbon::today(),
                                    );
                                    $rowClass = $isPast ? 'bg-green-salary text-white' : '';
                                @endphp
                                <tr>
                                    <td class="{{ $rowClass }}">{{ $alias->nome }}</td>
                                    <td class="{{ $rowClass }}">{{ $alias->formatData($alias->data_allenamento) }}
                                    </td>
                                    <td class="{{ $rowClass }}">
                                        {{ $alias->primo_allenatore_id == Auth::user()->id ? 'Primo Allenatore' : 'Secondo Allenatore' }}
                                        / {{ $alias->condiviso == 'true' ? 'Si' : 'No' }}
                                    </td>
                                    <td class="{{ $rowClass }}">
                                        @if ($alias->primo_allenatore_id == Auth::user()->id)
                                            {{ $alias->condiviso == 'true' ? '15.00 €' : '22.50 €' }}
                                        @else
                                            {{ $alias->condiviso == 'true' ? '15.00 €' : '7.50 €' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end" style="background-color: white;">
                                    <strong>Stipendio Totale:</strong>
                                </td>
                                <td style="background-color: white;">
                                    <strong>{{ Auth::user()->calcolaStipendioAllenatore(Auth::user()->id) }} €</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </main>
</x-layout>
