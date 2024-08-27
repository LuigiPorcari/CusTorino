<x-layout documentTitle="Student Dashboard">
    <div class="container mt-5">
        <div class="row mt-5">
            <h1 class="mt-5">Student Dashboard</h1>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- Segna assenze --}}
            <h2 class="mt-2 mb-3">Gruppi dove ti alleni</h2>
            {{-- Filtra per data --}}
            <form method="GET" action="{{ route('student.dashboard') }}" class="mb-4">
                <div class="row justify-content-start">
                    <div class="col-md-3 col-12">
                        <label for="training_date" class="form-label">Data in cui sarai assente</label>
                        <select id="training_date" name="training_date" class="form-select">
                            <option value="">Seleziona una data</option>
                            @foreach ($availableTrainingDates as $date)
                                <option value="{{ $date['raw'] }}"
                                    {{ request('training_date') == $date['raw'] ? 'selected' : '' }}>
                                    {{ $date['formatted'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filtra</button>
                    </div>
                </div>
            </form>
            <div class="col-12 border rounded-4 shadow bg-white">
                <table class="table table-hover mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Pulsante Assenza</th>
                            <th scope="col">Gruppo</th>
                            <th scope="col">Data</th>
                            <th scope="col">Orario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trainingAliases as $alias)
                            <tr>
                                <th scope="row">
                                    <form action="{{ route('student.markAbsence', $alias->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Segna Assenza</button>
                                    </form>
                                </th>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td>{{ $alias->formatHours($alias->orario) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <h2>Non sei ancora iscritto ad un gruppo</h2>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Segna nuova presenza --}}
            <h2 class="mt-5 mb-3">Gruppi dove puoi recuperare</h2>
            <div class="col-12 border rounded-4 shadow bg-white">
                <table class="table table-hover mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Pulsante Recupero</th>
                            <th scope="col">Gruppo</th>
                            <th scope="col">Data</th>
                            <th scope="col">Orario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (Auth::user()->Nrecuperi > 0)
                            @forelse ($recoverableAliases as $alias)
                                <tr>
                                    <th scope="row">
                                        <form action="{{ route('student.recAbsence', $alias->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Recupera
                                                Assenza</button>
                                        </form>
                                    </th>
                                    <td>{{ $alias->nome }}</td>
                                    <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                    <td>{{ $alias->formatHours($alias->orario) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <h2>Non ci sono gruppi adatti al tuo recupero</h2>
                                    </td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="4">
                                    <h2>Non hai gettoni recupero</h2>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
