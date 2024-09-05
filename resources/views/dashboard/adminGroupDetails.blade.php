<x-layout documentTitle="Admin Group Details">
    <div class="container mt-5 pt-5">
        <div class="row mt-4">
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dettagli del {{ $group->nome }}</h5>
                        <p class="card-text">Giorno:
                            @switch($group->giorno_settimana)
                                @case('monday')
                                    Lunedì
                                @break

                                @case('tuesday')
                                    Martedì
                                @break

                                @case('wednesday')
                                    Mercoledì
                                @break

                                @case('thursday')
                                    Giovedì
                                @break

                                @case('friday')
                                    Venerdì
                                @break

                                @case('saturday')
                                    Sabato
                                @break

                                @case('sunday')
                                    Domenica
                                @break
                            @endswitch
                        </p>
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
                        <p class="card-text mb-0">Studenti:</p>
                        @foreach ($group->users as $student)
                            <div class="d-flex">
                                <p class="mt-3 fw-bold">{{ $student->name }} {{ $student->cognome }} - </p>
                                <div class="btn-group dropend">
                                    <button type="button" class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
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
                            <a class="btn btn-warning mb-2" href="{{ route('groups.edit', $group) }}">Modifica</a>
                            <a class="btn btn-warning mb-2"
                                href="{{ route('edit.student', $group) }}">Inserisci-Modifica Corsisti</a>
                            <button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal"
                                data-bs-target="#deleteModalGroup">
                                Elimina
                            </button>
                            <!-- Pulsante di Ritorno -->
                            <div class="text-center">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Torna alla lista Gruppi</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8">
                <h1>Alias del Gruppo</h1>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- Filtro per data -->
                <form method="GET" action="{{ route('admin.group.details', $group) }}">
                    <div class="mb-3">
                        <label for="data_allenamento" class="form-label">Filtra per data:</label>
                        <select name="data_allenamento" id="data_allenamento" class="form-select"
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Alias</th>
                                <th>Studenti</th>
                                <th>Recuperi</th>
                                <th class="d-none d-md-block">Allenatori</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aliases as $alias)
                                <tr>
                                    <td>
                                        <p class="fw-bold">{{ $alias->formatData($alias->data_allenamento) }}</p>
                                    </td>
                                    <td class="p-0">
                                        @foreach ($group->users as $student)
                                            <div
                                                class="border-bottom py-2 {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                                {{ $student->name }} {{ $student->cognome }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="p-0">
                                        @foreach ($alias->compareStudents($group->id, $alias->id) as $recupero)
                                            @if (!in_array($recupero->id, $group->studenti_id))
                                                <div class="border-bottom py-2">
                                                    {{ $recupero->name }} {{ $recupero->cognome }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="p-0 d-none d-md-block">
                                        @if ($alias->primo_allenatore_id != null)
                                            <p class="card-text">Primo allenatore: <br>
                                                {{ $alias->primoAllenatore->name }}
                                                {{ $alias->primoAllenatore->cognome }}</p>
                                        @endif
                                        @if ($alias->secondo_allenatore_id != null)
                                            <p class="card-text">Secondo allenatore: <br>
                                                {{ $alias->secondoAllenatore->name }}
                                                {{ $alias->secondoAllenatore->cognome }}</p>
                                            @if ($alias->condiviso == 'true')
                                                <p class="card-text">Condiviso</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="p-0">
                                        <a href="{{ route('alias.details', $alias) }}" class="btn btn-info">Visualizza
                                            Dettagli</a>
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
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="loginModalLabel">Sicuro di voler eliminare questo Gruppo?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <form action="{{ route('groups.delete', $group) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mx-2 px-3">Si</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
