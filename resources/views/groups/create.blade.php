<x-layout documentTitle="Create Groups">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-5" role="navigation" aria-label="Navigazione amministrativa">
        <li class="nav-item admin-nav-item mt-3 pt-md-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3 pt-md-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3 pt-md-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student', session('student_filters', [])) }}">
                Corsisti
            </a>
        </li>
        <li class="nav-item admin-nav-item mt-3 pt-md-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.week') }}">Settimana</a>
        </li>
        {{-- <li class="nav-item admin-nav-item mt-3 pt-md-3">
            <a class="nav-link" href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
        </li> --}}
        <li class="nav-item admin-nav-item mt-3 pt-md-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
    </ul>

    <main id="main-content" tabindex="-1" class="container pt-5">
        <div class="mt-2 mt-md-0 pt-5 pt-md-4">
            <h1 class="custom-title mt-5 pt-5 text-center">Crea Gruppo</h1>
        </div>
        <div class="row">
            <div class="col-12 custom-card mt-4 mb-5 p-4">
                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label class="custom-form-label" for="nome">Nome Gruppo</label>
                                    <input type="text" class="custom-form-input" id="nome" name="nome"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="custom-form-label" for="giorno_settimana">Giorno della
                                        Settimana</label>
                                    <select class="custom-form-input" id="giorno_settimana" name="giorno_settimana"
                                        required>
                                        <option value="lunedi">Lunedì</option>
                                        <option value="martedi">Martedì</option>
                                        <option value="mercoledi">Mercoledì</option>
                                        <option value="giovedi">Giovedì</option>
                                        <option value="venerdi">Venerdì</option>
                                        <option value="sabato">Sabato</option>
                                        <option value="domenica">Domenica</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label class="custom-form-label" for="orario">Orario</label>
                                    <select class="custom-form-input" id="orario" name="orario" required>
                                        <option value="13:00">13:00</option>
                                        <option value="13:30">13:30</option>
                                        <option value="14:00">14:00</option>
                                        <option value="14:30">14:30</option>
                                        <option value="17:00">17:00</option>
                                        <option value="17:30">17:30</option>
                                        <option value="18:00">18:00</option>
                                        <option value="18:30">18:30</option>
                                        <option value="19:00">19:00</option>
                                        <option value="19:30">19:30</option>
                                        <option value="20:00">20:00</option>
                                        <option value="20:30">20:30</option>
                                        <option value="21:00">21:00</option>
                                        <option value="21:30">21:30</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="custom-form-label" for="campo">Campo</label>
                                    <input type="number" class="custom-form-input" id="campo" name="campo"
                                        min="1" max="4" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label class="custom-form-label" for="location">Sede dell'allenamento</label>
                                    <select class="custom-form-input" id="location" name="location" required>
                                        <option value="torino">TORINO</option>
                                        <option value="leinì">LEINÌ</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="custom-form-label" for="tipo">Tipo</label>
                                    <select class="custom-form-input" id="tipo" name="tipo" required>
                                        <option value="M">Maschile</option>
                                        <option value="F">Femminile</option>
                                        <option value="misto">Misto</option>
                                        <option value="under">Under</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label class="custom-form-label" for="primo_allenatore_id">Primo
                                        Allenatore</label>
                                    <select class="custom-form-input" id="primo_allenatore_id"
                                        name="primo_allenatore_id">
                                        <option value="" selected>Nessuno</option>
                                        @foreach ($trainers as $trainer)
                                            <option value="{{ $trainer->id }}">{{ $trainer->name }}
                                                {{ $trainer->cognome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="custom-form-label" for="secondo_allenatore_id">Secondo
                                        Allenatore</label>
                                    <select class="custom-form-input" id="secondo_allenatore_id"
                                        name="secondo_allenatore_id">
                                        <option value="" selected>Nessuno</option>
                                        @foreach ($trainers as $trainer)
                                            <option value="{{ $trainer->id }}">{{ $trainer->name }}
                                                {{ $trainer->cognome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="custom-form-label" for="condiviso">Condiviso</label>
                                    <select class="custom-form-input" id="condiviso" name="condiviso" required>
                                        <option value="true">Sì</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label class="custom-form-label" for="numero_massimo_partecipanti">Numero
                                        Partecipanti</label>
                                    <select class="custom-form-input" id="numero_massimo_partecipanti"
                                        name="numero_massimo_partecipanti" required>
                                        <option value="6">6</option>
                                        <option value="8">8</option>
                                        <option value="10">10</option>
                                        <option value="16">16</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="custom-form-label" for="livello">Livello</label>
                                    <input type="number" class="custom-form-input" id="livello" name="livello"
                                        min="1" max="12" required>
                                </div>
                            </div>

                            <div class="col-12 col-md-2">
                                <div class="mb-3">
                                    <label class="custom-form-label" for="data_inizio_corso">Data Inizio Corso</label>
                                    <input type="date" class="custom-form-input-date" id="data_inizio_corso"
                                        name="data_inizio_corso" required>
                                </div>
                                <div class="mb-3">
                                    <label class="custom-form-label" for="data_fine_corso">Data Fine Corso</label>
                                    <input type="date" class="custom-form-input-date" id="data_fine_corso"
                                        name="data_fine_corso" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3 d-flex flex-column align-items-center">
                                    <button type="submit" class="custom-btn-create-group">Crea gruppo</button>
                                </div>
                            </div>
                        </div> {{-- chiude row --}}
                    </div> {{-- chiude container-fluid --}}
                </form>
            </div>
        </div>
    </main>
</x-layout>
