<x-layout documentTitle="Create Groups">
    <h1 class="mt-5 pt-4">Crea Gruppo</h1>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 mt-4 border p-4 rounded-4 shadow mb-5">
                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf
                    {{-- NOME --}}
                    <div class="mb-3">
                        <label class="form-label" for="nome">Nome Gruppo</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    {{-- GIORNO SETTIMANA --}}
                    <div class="mb-3">
                        <label class="form-label" for="giorno_settimana">Giorno della Settimana</label>
                        <select class="form-control" id="giorno_settimana" name="giorno_settimana" required>
                            <option value="monday">Lunedì</option>
                            <option value="tuesday">Martedì</option>
                            <option value="wednesday">Mercoledì</option>
                            <option value="thursday">Giovedì</option>
                            <option value="friday">Venerdì</option>
                            <option value="saturday">Sabato</option>
                            <option value="sunday">Domenica</option>
                        </select>
                    </div>
                    {{-- ORARIO --}}
                    <div class="mb-3">
                        <label class="form-label" for="orario">Orario</label>
                        <select class="form-control" id="orario" name="orario" required>
                            <option value="18:30">18:30</option>
                            <option value="20:00">20:00</option>
                            <option value="21:30">21:30</option>
                        </select>
                    </div>
                    {{-- CAMPO --}}
                    <div class="mb-3">
                        <label class="form-label" for="campo">Campo</label>
                        <input type="number" class="form-control" id="campo" name="campo" min="1"
                            max="4" required>
                    </div>
                    {{-- GENERE --}}
                    <div class="mb-3">
                        <label class="form-label" for="tipo">Tipo</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="M">Maschile</option>
                            <option value="F">Femminile</option>
                        </select>
                    </div>
                    {{-- PRIMO ALLENATORE --}}
                    <div class="mb-3">
                        <label class="form-label" for="primo_allenatore_id">Primo Allenatore</label>
                        <select class="form-control" id="primo_allenatore_id" name="primo_allenatore_id" required>
                            @foreach ($trainers as $trainer)
                                <option value="{{ $trainer->id }}">{{ $trainer->name }} {{ $trainer->cognome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- SECONDO ALLENATORE --}}
                    <div class="mb-3">
                        <label class="form-label" for="secondo_allenatore_id">Secondo Allenatore</label>
                        <select class="form-control" id="secondo_allenatore_id" name="secondo_allenatore_id">
                            <option value="" selected>Nessuno</option>
                            @foreach ($trainers as $trainer)
                                <option value="{{ $trainer->id }}">{{ $trainer->name }} {{ $trainer->cognome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- CONDIVISO --}}
                    <div class="mb-3">
                        <label class="form-label" for="condiviso">Condiviso</label>
                        <select class="form-control" id="condiviso" name="condiviso" required>
                            <option value="true">Sì</option>
                            <option value="false">No</option>
                        </select>
                    </div>
                    {{-- NUMERO MASSIMO PARTECIPANTI --}}
                    <div class="mb-3">
                        <label class="form-label" for="numero_massimo_partecipanti">Numero Massimo Partecipanti</label>
                        <select class="form-control" id="numero_massimo_partecipanti" name="numero_massimo_partecipanti"
                            required>
                            <option value="6">6</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    {{-- LIVELLO --}}
                    <div class="mb-3">
                        <label class="form-label" for="livello">Livello</label>
                        <input type="number" class="form-control" id="livello" name="livello" required min="1"
                            max="10">
                    </div>
                    {{-- DATA INIZIO CORSO --}}
                    <div class="mb-3">
                        <label class="form-label" for="data_inizio_corso">Data Inizio Corso</label>
                        <input type="date" class="form-control" id="data_inizio_corso" name="data_inizio_corso"
                            required>
                    </div>
                    {{-- DATA FINE CORSO --}}
                    <div class="mb-3">
                        <label class="form-label" for="data_fine_corso">Data Fine Corso</label>
                        <input type="date" class="form-control" id="data_fine_corso" name="data_fine_corso"
                            required>
                    </div>
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <button type="submit" class="btn btn-primary">Crea gruppo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
