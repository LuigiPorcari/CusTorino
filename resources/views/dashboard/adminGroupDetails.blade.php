<x-layout documentTitle="Admin Group Details">
    <div class="container mt-5 pt-5 admin-group-details">
        <div class="row mt-4">
            <div class="col-12 col-md-4">
                @if (session('success'))
                    <div class="alert alert-dismissible custom-alert-success">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card admin-card">
                    <div class="admin-card-body">
                        <h5 class="card-title">Dettagli del {{ $group->nome }}</h5>
                        <p class="card-text">Sede: <span class="text-uppercase">{{ $group->location }}</span></p>
                        <p class="card-text">Giorno: {{ $group->giorno_settimana }}</p>
                        <p class="card-text">Orario: {{ $group->formatHours($group->orario) }}</p>
                        <p class="card-text">Tipologia: {{ $group->tipo }}</p>
                        @if ($group->primo_allenatore_id != null)
                            <p class="card-text">Primo allenatore: <br> {{ $group->primoAllenatore->name }}
                                {{ $group->primoAllenatore->cognome }}</p>
                        @endif
                        @if ($group->secondo_allenatore_id != null)
                            <p class="card-text">Secondo allenatore: <br> {{ $group->secondoAllenatore->name }}
                                {{ $group->secondoAllenatore->cognome }}</p>
                            @if ($group->condiviso == 'true')
                                <p class="card-text">Condiviso</p>
                            @endif
                        @endif
                        <p class="card-text">Numero massimo: {{ $group->numero_massimo_partecipanti }}</p>
                        <p class="card-text">Livello: {{ $group->livello }}</p>
                        <p class="card-text mb-0">Corsisti:</p>
                        @foreach ($group->users as $student)
                            <div class="d-flex">
                                <p class="mt-3 fw-bold">{{ $student->name }} {{ $student->cognome }} - </p>
                                <div class="btn-group dropend">
                                    <button type="button" class="btn btn-sm dropdown-toggle text-white"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Documentazione
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <p class="dropdown-item">Cus Card --
                                                @if ($student->cus_card)
                                                    OK
                                                @else
                                                    NON OK
                                                @endif
                                            </p>
                                            <p class="dropdown-item">Visita Medica --
                                                @if ($student->visita_medica)
                                                    OK
                                                @else
                                                    NON OK
                                                @endif
                                            </p>
                                            <p class="dropdown-item">Pagamento --
                                                @if ($student->pagamento)
                                                    OK
                                                @else
                                                    NON OK
                                                @endif
                                            </p>
                                            <p class="dropdown-item">Universitario --
                                                @if ($student->universitario)
                                                    SI
                                                @else
                                                    NO
                                                @endif
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex flex-column align-items-center mt-5">
                            <a class="btn admin-btn-warning mb-2"
                                href="{{ route('groups.edit', $group) }}">Modifica</a>
                            <a class="btn admin-btn-warning mb-2"
                                href="{{ route('edit.student', $group) }}">Inserisci-Modifica Corsisti</a>
                            <button type="button" class="btn admin-btn-warning mb-2" data-bs-toggle="modal"
                                data-bs-target="#addModalAlias">Aggiungi gruppo Alias</button>
                            <button type="button" class="btn admin-btn-danger mb-2" data-bs-toggle="modal"
                                data-bs-target="#deleteModalGroup">Elimina</button>
                            <div class="text-center w-100">
                                <a href="{{ route('admin.dashboard') }}" class="btn admin-btn-info w-100 fs-6 ">Torna
                                    alla
                                    lista
                                    Gruppi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <h1 class="custom-title mt-4 mt-md-0">Date degli allenamenti</h1>
                <form method="GET" action="{{ route('admin.group.details', $group) }}">
                    <div class="mb-3">
                        <label for="data_allenamento" class="form-label">Filtra per data:</label>
                        <select name="data_allenamento" id="data_allenamento" class="custom-form-input"
                            onchange="this.form.submit()">
                            <option value="">Tutte le date</option>
                            @foreach ($availableDates as $date)
                                <option value="{{ $date->data_allenamento }}"
                                    {{ request('data_allenamento') == $date->data_allenamento ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($date->data_allenamento)->translatedFormat('l d F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered admin-table">
                        <thead>
                            <tr>
                                <th>Alias</th>
                                <th>Corsisti</th>
                                <th>Recuperi</th>
                                <th class="d-none d-md-table-cell">Allenatori</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aliases as $alias)
                                <tr class="bg-white">
                                    <td>
                                        <p class="fw-bold">{{ $alias->formatData($alias->data_allenamento) }}</p>
                                    </td>
                                    <td>
                                        @foreach ($group->users as $student)
                                            <div
                                                class="border-bottom py-2 {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                                {{ $student->name }} {{ $student->cognome }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($alias->compareStudents($group->id, $alias->id) as $recupero)
                                            <div class="border-bottom py-2">{{ $recupero->name }}
                                                {{ $recupero->cognome }}</div>
                                        @endforeach
                                    </td>
                                    <div>
                                        <td class="p-0 d-none d-md-table-cell">
                                            @if ($alias->primo_allenatore_id != null)
                                                <p class="card-text">Primo allenatore: <br>
                                                    {{ $alias->primoAllenatore->name }}
                                                    {{ $alias->primoAllenatore->cognome }}
                                                </p>
                                            @endif
                                            @if ($alias->secondo_allenatore_id != null)
                                                <p class="card-text">Secondo allenatore: <br>
                                                    {{ $alias->secondoAllenatore->name }}
                                                    {{ $alias->secondoAllenatore->cognome }}
                                                </p>
                                                @if ($alias->condiviso == 'true')
                                                    <p class="card-text">Condiviso</p>
                                                @endif
                                            @endif
                                        </td>
                                    </div>
                                    <td><a href="{{ route('alias.details', $alias) }}"
                                            class="btn admin-btn-info fs-md-6 w-50 mb-2">Dettagli</a>
                                        <button type="button"
                                            class="btn custom-btn-danger-nav text-uppercase text-white fw-bolder fs-6 w-50"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModalAlias{{ $alias->id }}">
                                            Elimina
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal per eliminare il gruppo -->
    <div class="modal fade" id="deleteModalGroup" tabindex="-1" aria-labelledby="deleteModalGroupLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header admin-modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalGroupLabel">Sicuro di voler eliminare questo Gruppo?
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center admin-modal-body">
                    <form action="{{ route('groups.delete', $group) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn admin-btn-danger mx-2">Si</button>
                    </form>
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
                <div class="modal-footer admin-modal-footer">
                    <button type="button" class="btn admin-modal-btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($aliases as $alias)
        <!-- Modal per eliminare l'alias -->
        <div class="modal fade" id="deleteModalAlias{{ $alias->id }}" tabindex="-1"
            aria-labelledby="deleteModalAliasLabel{{ $alias->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header admin-modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalAliasLabel{{ $alias->id }}">Sicuro di voler
                            eliminare il gruppo Alias in data {{ $alias->formatData($alias->data_allenamento) }}?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center admin-modal-body">
                        <form action="{{ route('alias.delete', $alias->id, $availableDates, $group) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn admin-btn-danger mx-2">Si</button>
                        </form>
                        <button type="button" class="btn admin-modal-btn-secondary"
                            data-bs-dismiss="modal">No</button>
                    </div>
                    <div class="modal-footer admin-modal-footer">
                        <button type="button" class="btn admin-modal-btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Modal per aggiungere l'alias -->
    <div class="modal fade" id="addModalAlias" tabindex="-1" aria-labelledby="addModalAliasLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header admin-modal-header">
                    <h1 class="modal-title fs-5" id="addModalAliasLabel">Aggiungi un gruppo Alias</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body admin-modal-body">
                    <form action="{{ route('storeAlias', $group->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="custom-form-label" for="data_allenamento">In che data vuoi creare il gruppo
                                alias?</label>
                            <select class="custom-form-input" id="data_allenamento" name="data_allenamento" required>
                                @forelse ($dateMancanti as $date)
                                    <option value="{{ $date }}">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}</option>
                                @empty
                                    <option value="">
                                        Non ci sono date disponibili</option>
                                @endforelse
                            </select>
                        </div>
                </div>
                <!-- Modal Footer for buttons -->
                <div class="modal-footer admin-modal-footer">
                    @if (count($dateMancanti) != 0)
                        <button type="submit" class="btn admin-btn-danger w-25">Conferma</button>
                    @endif
                    <button type="button" class="btn admin-modal-btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</x-layout>
