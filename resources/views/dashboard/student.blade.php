<x-layout documentTitle="Student Dashboard">
    <div class="container mt-5">
        <div class="row mt-5 justify-content-center">
            <h1 class="custom-title mt-5 pt-5">{{ Auth::user()->name }} {{ Auth::user()->cognome }}</h1>
            {{-- Tabella con il conteggio delle assenze e dei gettoni --}}
            <div class="col-12 mb-5 mt-2">
                <h2 class="custom-subtitle mb-3">Statistiche Personali</h2>
                <table class="table table-bordered admin-trainer-table">
                    <thead>
                        <tr>
                            <th>Assenze Programmate</th>
                            <th>Possibili Recuperi</th>
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

            <div class="row">
                {{-- Segna assenze --}}
                <div class="col-12 col-md-6">
                    <h2 class="custom-subtitle mt-2 mb-3">Gruppi dove ti alleni</h2>
                    @if (session('success'))
                        <div class="alert alert-dismissible custom-alert-success">
                            {!! session('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- Filtra per data --}}
                    <form method="GET" action="{{ route('student.dashboard') }}" class="mb-4">
                        <div class="row justify-content-start">
                            <div class="col-12">
                                <label for="training_date" class="custom-form-label text-black">Data in cui sarai assente</label>
                                <select id="training_date" name="training_date" class="custom-form-input" onchange="this.form.submit()">
                                    <option value="">Tutte le date</option>
                                    @foreach ($availableTrainingDates as $date)
                                        <option value="{{ $date['raw'] }}" {{ request('training_date') == $date['raw'] ? 'selected' : '' }}>
                                            {{ $date['formatted'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered admin-trainer-table">
                        <thead>
                            <tr>
                                <th>Pulsante Assenza</th>
                                <th>Data</th>
                                <th>Orario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trainingAliases as $alias)
                                <tr>
                                    <td>
                                        <button type="button" class="btn admin-btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#absenceConfirmationModal-{{ $alias->id }}">
                                            Segna Assenza
                                        </button>
                                    </td>
                                    <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                    <td>{{ $alias->formatHours($alias->orario) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <h2 class="custom-subtitle text-black">Non sei ancora iscritto ad un gruppo</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Segna nuova presenza --}}
                <div class="col-12 col-md-6">
                    <h2 class="custom-subtitle mt-2 mb-3">Gruppi dove puoi recuperare</h2>
                    @if (session('success1'))
                        <div class="alert alert-dismissible custom-alert-success">
                            {!! session('success1') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- Filtro per data di recupero --}}
                    <form method="GET" action="{{ route('student.dashboard') }}" class="mb-4">
                        <div class="row justify-content-start">
                            <div class="col-12">
                                <label for="recovery_date" class="custom-form-label text-black">Data per il recupero</label>
                                <select id="recovery_date" name="recovery_date" class="custom-form-input" onchange="this.form.submit()">
                                    <option value="">Tutte le date</option>
                                    @foreach ($availableRecoveryDates as $date)
                                        <option value="{{ $date['raw'] }}" {{ request('recovery_date') == $date['raw'] ? 'selected' : '' }}>
                                            {{ $date['formatted'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered admin-trainer-table">
                        <thead>
                            <tr>
                                <th>Pulsante Recupero</th>
                                <th>Data</th>
                                <th>Orario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::user()->Nrecuperi > 0)
                                @forelse ($recoverableAliases as $alias)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn admin-btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#recoveryConfirmationModal-{{ $alias->id }}">
                                                Recupera Assenza
                                            </button>
                                        </td>
                                        <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                        <td>{{ $alias->formatHours($alias->orario) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            <h2 class="custom-subtitle text-black">Non ci sono gruppi adatti al tuo recupero</h2>
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="3">
                                        <h2 class="fw-bold text-uppercase">
                                            Non puoi recuperare perchè <br>
                                            @if (Auth::user()->countAbsences() > 0)
                                                non hai rispettato i criteri opportuni quando hai segnato la tua
                                                assenza <br> o hai recuperato tutte le tue assenze
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

    @foreach ($trainingAliases as $alias)
        <!-- Modale di conferma per Segna Assenza -->
        <div class="modal fade" id="absenceConfirmationModal-{{ $alias->id }}" tabindex="-1"
            aria-labelledby="absenceConfirmationModalLabel-{{ $alias->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header admin-modal-header">
                        <h5 class="modal-title" id="absenceConfirmationModalLabel-{{ $alias->id }}">Conferma Segna Assenza</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {!! Auth::user()->canMarkAbsence($alias) !!}
                        Sei sicuro di voler segnare l'assenza per il gruppo in data {{ $alias->formatData($alias->data_allenamento) }} all'ora
                        {{ $alias->formatHours($alias->orario) }}?
                    </div>
                    <div class="modal-footer admin-modal-footer">
                        <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <form action="{{ route('student.markAbsence', $alias->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn admin-btn-danger">Conferma</button>
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
                    <div class="modal-header admin-modal-header">
                        <h5 class="modal-title" id="recoveryConfirmationModalLabel-{{ $alias->id }}">Conferma Recupero Assenza</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Sei sicuro di voler recuperare l'assenza per il gruppo in data {{ $alias->formatData($alias->data_allenamento) }} all'ora
                        {{ $alias->formatHours($alias->orario) }}?
                    </div>
                    <div class="modal-footer admin-modal-footer">
                        <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">Annulla</button>
                        <form action="{{ route('student.recAbsence', $alias->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn admin-btn-danger">Conferma</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>
