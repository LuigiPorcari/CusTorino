<x-layout documentTitle="Admin Trainer Details">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('trainer.dashboard') }}">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('trainer.group') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('trainer.salary') }}">Compensi</a>
        </li>
</ul>
    <div class="container mt-5">
        <div class="row mt-5 pt-5">
            <h1 class="custom-title my-5 pt-5">Dettagli pagamenti</h1>
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
                        @foreach ($aliasesTrainer as $alias)
                            <tr>
                                <td class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">{{ $alias->nome }}</td>
                                <td class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">{{ $alias->primo_allenatore_id == Auth::user()->id ? 'Primo Allenatore' : 'Secondo Allenatore' }}
                                    / {{ $alias->condiviso == 'true' ? 'Si' : 'No' }}</td>
                                <td class="{{ \Carbon\Carbon::parse($alias->data_allenamento)->lt(\Carbon\Carbon::today()) ? 'bg-green-salary text-white' : '' }}">
                                    @if ($alias->primo_allenatore_id == Auth::user()->id)
                                        {{ $alias->condiviso == 'true' ? '15.00 €' : '22.50 €' }}
                                    @else
                                        {{ $alias->condiviso == 'true' ? '15.00 €' : '7.50 €' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right" style="background-color: white;"><strong>Stipendio
                                    Totale:</strong></td>
                            <td style="background-color: white;">
                                <strong>{{ Auth::user()->calcolaStipendioAllenatore(Auth::user()->id) }} €</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-layout>
