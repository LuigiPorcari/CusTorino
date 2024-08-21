<x-layout documentTitle="Admin Trainer Details">
    <div class="container mt-5">
        <div class="row">
            <h1 class="mt-5">Dettagli pagamenti {{ $trainer->name }} {{ $trainer->cognome }}</h1>
            <div class="col-12">
                <h2 class="mt-5 mb-4">{{ $trainer->name }} {{ $trainer->cognome }}</h2>
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
                        @foreach ($trainer->primoAllenatoreAliases as $alias)
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
                        @foreach ($trainer->secondoAllenatoreAliases as $alias)
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
                            <td><strong>{{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
