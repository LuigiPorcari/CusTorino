<h3>{{ $trainerName ?? 'Allenatore' }}</h3>
<table>
    <thead>
        <tr>
            <th colspan="2">Dettagli Presenze</th>
        </tr>
        <tr>
            <th>Totale Presenze</th>
            <th>Totale Assenze</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $totalPresence }}</td>
            <td>{{ $totalAbsence }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>Nome Gruppo Alias</th>
            <th>Data Gruppo Alias</th>
            <th>Tipo allenatore / Condiviso</th>
            <th>Stipendio (€)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($aliasesTrainer as $alias)
            <tr>
                <td>{{ $alias->nome }}</td>
                <td>{{ \Carbon\Carbon::parse($alias->data_allenamento)->format('d/m/Y') }}</td>
                <td>
                    {{ $alias->primo_allenatore_id == Auth::id() ? 'Primo Allenatore' : 'Secondo Allenatore' }} /
                    {{ $alias->condiviso == 'true' ? 'Sì' : 'No' }}
                </td>
                <td>
                    @if ($alias->primo_allenatore_id == Auth::id())
                        {{ $alias->condiviso == 'true' ? '15.00' : '22.50' }}
                    @else
                        {{ $alias->condiviso == 'true' ? '15.00' : '7.50' }}
                    @endif
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3"><strong>Totale</strong></td>
            <td><strong>{{ $totalSalary }}</strong></td>
        </tr>
    </tbody>
</table>
