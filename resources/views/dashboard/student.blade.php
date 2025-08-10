<x-layout documentTitle="Student Dashboard">
    @if (Auth::check() && Auth::user()->is_trainer)
        <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0 pt-md-3" role="navigation" aria-label="Navigazione amministrativa">
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
    @endif

    <main class="container mt-5" id="main-content">
        <div class="row mt-5 justify-content-center">
            <header class="mt-4">
                <h1 class="custom-title mt-5 pt-5">{{ Auth::user()->name }} {{ Auth::user()->cognome }}</h1>
            </header>

            <section class="col-12 mb-5 mt-2" aria-labelledby="statistiche-personali">
                <h2 id="statistiche-personali" class="custom-subtitle mb-3">Statistiche Personali</h2>
                <table class="table table-bordered admin-trainer-table">
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
            </section>

            <div class="row">
                {{-- Sezione Assenze --}}
                <section class="col-12 col-md-6" aria-labelledby="gruppi-allenamento">
                    <h2 id="gruppi-allenamento" class="custom-subtitle mt-2 mb-3">Gruppi dove ti alleni</h2>

                    @if (session('success'))
                        <div class="alert alert-dismissible custom-alert-success" role="alert">
                            {!! session('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('student.dashboard') }}" class="mb-4"
                        aria-label="Filtro assenze">
                        <label for="training_date" class="custom-form-label text-black">Data in cui sarai
                            assente</label>
                        <select id="training_date" name="training_date" class="custom-form-input"
                            onchange="this.form.submit()">
                            <option value="">Tutte le date</option>
                            @foreach ($availableTrainingDates as $date)
                                <option value="{{ $date['raw'] }}"
                                    {{ request('training_date') == $date['raw'] ? 'selected' : '' }}>
                                    {{ $date['formatted'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <table class="table table-bordered admin-trainer-table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Data</th>
                                <th scope="col">Sede</th>
                                <th scope="col">Allenatore</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trainingAliases as $alias)
                                @php
                                    $inGroup = in_array($alias->group_id, $userGroupsId);
                                    $rowClass = $inGroup ? '' : 'bg-warning';
                                @endphp
                                <tr>
                                    <td class="{{ $rowClass }}">
                                        <button type="button" class="btn admin-btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#absenceConfirmationModal-{{ $alias->id }}">
                                            Segna Assenza
                                        </button>
                                    </td>
                                    <td class="{{ $rowClass }}">
                                        {{ $alias->formatDayStudent($alias->data_allenamento) }}<br>
                                        {{ $alias->formatDataStudent($alias->data_allenamento) }}<br>
                                        {{ $alias->formatHours($alias->orario) }}
                                    </td>
                                    <td class="text-uppercase {{ $rowClass }}">{{ $alias->location }}</td>
                                    <td class="{{ $rowClass }}">
                                        {{ $alias->primoAllenatore->name ?? 'Nessun Allenatore' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <h2 class="custom-subtitle text-black">Non sei ancora iscritto ad un gruppo</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $trainingAliases->links('pagination::bootstrap-5') }}
                </section>
                {{-- Sezione Recuperi --}}
                <section class="col-12 col-md-6" aria-labelledby="gruppi-recupero">
                    <h2 id="gruppi-recupero" class="custom-subtitle mt-2 mb-3">Gruppi dove puoi recuperare</h2>

                    @if (session('success1'))
                        <div class="alert alert-dismissible custom-alert-success" role="alert">
                            {!! session('success1') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('student.dashboard') }}" class="mb-4"
                        aria-label="Filtro recuperi">
                        <label for="recovery_date" class="custom-form-label text-black">Data per il recupero</label>
                        <select id="recovery_date" name="recovery_date" class="custom-form-input"
                            onchange="this.form.submit()">
                            <option value="">Tutte le date</option>
                            @foreach ($availableRecoveryDates as $date)
                                <option value="{{ $date['raw'] }}"
                                    {{ request('recovery_date') == $date['raw'] ? 'selected' : '' }}>
                                    {{ $date['formatted'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <table class="table table-bordered admin-trainer-table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Data</th>
                                <th scope="col">Sede</th>
                                <th scope="col">Allenatore</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::user()->pagamento)
                                @if (Auth::user()->Nrecuperi > 0)
                                    @forelse ($recoverableAliases as $alias)
                                        <tr>
                                            <td>
                                                <button type="button" class="btn admin-btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#recoveryConfirmationModal-{{ $alias->id }}">
                                                    Recupera Assenza
                                                </button>
                                            </td>
                                            <td>
                                                {{ $alias->formatDayStudent($alias->data_allenamento) }}<br>
                                                {{ $alias->formatDataStudent($alias->data_allenamento) }}<br>
                                                {{ $alias->formatHours($alias->orario) }}
                                            </td>
                                            <td class="text-uppercase">{{ $alias->location }}</td>
                                            <td>{{ $alias->primoAllenatore->name ?? 'Nessun Allenatore' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <h2 class="custom-subtitle text-black">
                                                    Non ci sono gruppi adatti al tuo recupero
                                                </h2>
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="4">
                                            <h2 class="fw-bold text-uppercase">
                                                Non puoi recuperare perché<br>
                                                @if (Auth::user()->countAbsences() > 0)
                                                    non hai rispettato i criteri opportuni quando hai segnato la tua
                                                    assenza
                                                    oppure hai già recuperato tutte le assenze.
                                                @else
                                                    non hai mai fatto un'assenza.
                                                @endif
                                            </h2>
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="4">
                                        <h2 class="fw-bold text-uppercase">
                                            Non puoi recuperare perché<br>
                                            non hai effettuato il pagamento per questo periodo
                                        </h2>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
        </div>
    </main>
    @foreach ($trainingAliases as $alias)
        <!-- Modale di conferma per Segna Assenza -->
        <div class="modal fade" id="absenceConfirmationModal-{{ $alias->id }}" tabindex="-1"
            aria-labelledby="absenceConfirmationModalLabel-{{ $alias->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header admin-modal-header">
                        <h5 class="modal-title" id="absenceConfirmationModalLabel-{{ $alias->id }}">
                            Conferma Segna Assenza
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        {!! Auth::user()->canMarkAbsence($alias) !!}
                        Sei sicuro di voler segnare l'assenza per il gruppo in data
                        {{ $alias->formatData($alias->data_allenamento) }} all'ora
                        {{ $alias->formatHours($alias->orario) }}?
                    </div>
                    <div class="modal-footer admin-modal-footer">
                        <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">
                            Annulla
                        </button>
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
                        <h5 class="modal-title" id="recoveryConfirmationModalLabel-{{ $alias->id }}">
                            Conferma Recupero Assenza
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        @if ($alias->data_allenamento == now()->toDateString() && now()->hour >= 12)
                            Non puoi recuperare l'assenza per il gruppo in data
                            {{ $alias->formatData($alias->data_allenamento) }} all'ora
                            {{ $alias->formatHours($alias->orario) }} perché sono passate le 12:00.
                        @else
                            Sei sicuro di voler recuperare l'assenza per il gruppo in data
                            {{ $alias->formatData($alias->data_allenamento) }} all'ora
                            {{ $alias->formatHours($alias->orario) }}?
                        @endif
                    </div>
                    <div class="modal-footer admin-modal-footer">
                        <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">
                            Annulla
                        </button>
                        @if (!($alias->data_allenamento == now()->toDateString() && now()->hour >= 12))
                            <form action="{{ route('student.recAbsence', $alias->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn admin-btn-danger">Conferma</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>
