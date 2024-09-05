<x-layout documentTitle="Student Dashboard">
    <div class="container mt-5">
        <div class="row mt-5">
            <h1 class="mt-5">Student Dashboard</h1>
            {{-- Tabella con il conteggio delle assenze e dei gettoni --}}
            <div class="col-12 mb-5">
                <h2 class="mb-3">Statistiche Personali</h2>
                <div class="col-12 border rounded-4 shadow bg-white">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Assenze Programmate</th>
                                <th scope="col">Possibili Recuperi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ Auth::user()->countAbsences() }}</td>
                                <td>{{ Auth::user()->Nrecuperi }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                {{-- Segna assenze --}}
                <div class="col-12 col-md-6">
                    <h2 class="mt-2 mb-3">Gruppi dove ti alleni</h2>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            {!! session('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- Filtra per data --}}
                    <form method="GET" action="{{ route('student.dashboard') }}" class="mb-4">
                        <div class="row justify-content-start">
                            <div class="col-12">
                                <label for="training_date" class="form-label">Data in cui sarai assente</label>
                                <select id="training_date" name="training_date" class="form-select"
                                    onchange="this.form.submit()">
                                    <option value="">Tutte le date</option>
                                    @foreach ($availableTrainingDates as $date)
                                        <option value="{{ $date['raw'] }}"
                                            {{ request('training_date') == $date['raw'] ? 'selected' : '' }}>
                                            {{ $date['formatted'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="col-12 border rounded-4 shadow bg-white">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">Pulsante Assenza</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Orario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($trainingAliases as $alias)
                                    <tr>
                                        <th scope="row">
                                            <!-- Bottone che apre la modale per Segna Assenza -->
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#absenceConfirmationModal-{{ $alias->id }}">
                                                Segna Assenza
                                            </button>
                                        </th>
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
                </div>

                {{-- Segna nuova presenza --}}
                <div class="col-12 col-md-6">
                    <h2 class="mt-2 mb-3">Gruppi dove puoi recuperare</h2>
                    @if (session('success1'))
                        <div class="alert alert-success alert-dismissible">
                            {!! session('success1') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- Filtro per data di recupero --}}
                    <form method="GET" action="{{ route('student.dashboard') }}" class="mb-4">
                        <div class="row justify-content-start">
                            <div class="col-12">
                                <label for="recovery_date" class="form-label">Data per il recupero</label>
                                <select id="recovery_date" name="recovery_date" class="form-select"
                                    onchange="this.form.submit()">
                                    <option value="">Tutte le date</option>
                                    @foreach ($availableRecoveryDates as $date)
                                        <option value="{{ $date['raw'] }}"
                                            {{ request('recovery_date') == $date['raw'] ? 'selected' : '' }}>
                                            {{ $date['formatted'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="col-12 border rounded-4 shadow bg-white">
                        <table class="table table-hover mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">Pulsante Recupero</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Orario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (Auth::user()->Nrecuperi > 0)
                                    @forelse ($recoverableAliases as $alias)
                                        <tr>
                                            <th scope="row">
                                                <!-- Bottone che apre la modale per Recupera Assenza -->
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#recoveryConfirmationModal-{{ $alias->id }}">
                                                    Recupera Assenza
                                                </button>
                                            </th>
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
                                            <h2>
                                                Non puoi recuperare perchè <br>
                                                @if (Auth::user()->countAbsences() > 0)
                                                    non hai rispettato i criteri opportuni quando hai segnato la tua
                                                    assenza <br> o hai usato tutti i tuoi gettoni
                                                @else
                                                    non hai mai fatto un'assenza
                                                @endif
                                            </h2>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($trainingAliases as $alias)
        <!-- Modale di conferma per Segna Assenza -->
        <div class="modal fade" id="absenceConfirmationModal-{{ $alias->id }}" tabindex="-1"
            aria-labelledby="absenceConfirmationModalLabel-{{ $alias->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="absenceConfirmationModalLabel-{{ $alias->id }}">Conferma Segna
                            Assenza</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {!! Auth::user()->canMarkAbsence($alias) !!}
                        Sei sicuro di voler segnare l'assenza per il gruppo in data
                        {{ $alias->formatData($alias->data_allenamento) }} all'ora
                        {{ $alias->formatHours($alias->orario) }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <form action="{{ route('student.markAbsence', $alias->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Conferma</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach ($recoverableAliases as $alias)
        <!-- Modale di conferma per Recupera Assenza -->
        <div class="modal fade" id="recoveryConfirmationModal-{{ $alias->id }}" tabindex="-1"
            aria-labelledby="recoveryConfirmationModalLabel-{{ $alias->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="recoveryConfirmationModalLabel-{{ $alias->id }}">Conferma
                            Recupero Assenza</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Ti verrà sottratto un gettone.<br>
                        Sei sicuro di voler recuperare l'assenza per il gruppo in data
                        {{ $alias->formatData($alias->data_allenamento) }} all'ora
                        {{ $alias->formatHours($alias->orario) }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <form action="{{ route('student.recAbsence', $alias->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Conferma</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>
