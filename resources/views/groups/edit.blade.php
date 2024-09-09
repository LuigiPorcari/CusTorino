<x-layout documentTitle="Modify Groups">
    <div class="container mt-5 pt-1">
        <h1 class="custom-title mt-5 pt-5 text-center">Modifica Gruppo</h1>
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 custom-card mb-5 p-4">
                <form method="POST" action="{{ route('groups.update', $group) }}">
                    @csrf
                    {{-- NOME --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="nome">Nome Gruppo</label>
                        <input value="{{ $group->nome }}" type="text" class="custom-form-input" name="nome">
                    </div>
                    {{-- GIORNO SETTIMANA --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="giorno_settimana">Giorno della Settimana</label>
                        <select class="custom-form-input" id="giorno_settimana" name="giorno_settimana" required>
                            <option @if ($group->giorno_settimana == 'monday') selected @endif value="monday">Lunedì</option>
                            <option @if ($group->giorno_settimana == 'tuesday') selected @endif value="tuesday">Martedì</option>
                            <option @if ($group->giorno_settimana == 'wednesday') selected @endif value="wednesday">Mercoledì</option>
                            <option @if ($group->giorno_settimana == 'thursday') selected @endif value="thursday">Giovedì</option>
                            <option @if ($group->giorno_settimana == 'friday') selected @endif value="friday">Venerdì</option>
                            <option @if ($group->giorno_settimana == 'saturday') selected @endif value="saturday">Sabato</option>
                            <option @if ($group->giorno_settimana == 'sunday') selected @endif value="sunday">Domenica</option>
                        </select>
                    </div>
                    {{-- ORARIO --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="orario">Orario</label>
                        <select class="custom-form-input" id="orario" name="orario" required>
                            <option @if ($group->orario == '18:30:00') selected @endif value="18:30">18:30</option>
                            <option @if ($group->orario == '20:00:00') selected @endif value="20:00">20:00</option>
                            <option @if ($group->orario == '21:30:00') selected @endif value="21:30">21:30</option>
                        </select>
                    </div>
                    {{-- CAMPO --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="campo">Campo</label>
                        <input value="{{ $group->campo }}" type="number" class="custom-form-input" id="campo" name="campo" min="1" max="4" required>
                    </div>
                    {{-- GENERE --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="tipo">Tipo</label>
                        <select class="custom-form-input" id="tipo" name="tipo" required>
                            <option @if ($group->tipo == 'M') selected @endif value="M">Maschile</option>
                            <option @if ($group->tipo == 'F') selected @endif value="F">Femminile</option>
                        </select>
                    </div>
                    {{-- PRIMO ALLENATORE --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="primo_allenatore_id">Primo Allenatore</label>
                        <select class="custom-form-input" id="primo_allenatore_id" name="primo_allenatore_id" required>
                            @foreach ($trainers as $trainer)
                                <option @if ($group->primo_allenatore_id == $trainer->id) selected @endif value="{{ $trainer->id }}">
                                    {{ $trainer->name }} {{ $trainer->cognome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- SECONDO ALLENATORE --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="secondo_allenatore_id">Secondo Allenatore</label>
                        <select class="custom-form-input" id="secondo_allenatore_id" name="secondo_allenatore_id">
                            <option value="" selected>Nessuno</option>
                            @foreach ($trainers as $trainer)
                                <option @if ($group->secondo_allenatore_id == $trainer->id) selected @endif value="{{ $trainer->id }}">
                                    {{ $trainer->name }} {{ $trainer->cognome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- CONDIVISO --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="condiviso">Condiviso</label>
                        <select class="custom-form-input" id="condiviso" name="condiviso" required>
                            <option @if ($group->condiviso == 'true') selected @endif value="true">Sì</option>
                            <option @if ($group->condiviso == 'false') selected @endif value="false">No</option>
                        </select>
                    </div>
                    {{-- NUMERO MASSIMO PARTECIPANTI --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="numero_massimo_partecipanti">Numero Massimo Partecipanti</label>
                        <select class="custom-form-input" id="numero_massimo_partecipanti" name="numero_massimo_partecipanti" required>
                            <option @if ($group->numero_massimo_partecipanti == '6') selected @endif value="6">6</option>
                            <option @if ($group->numero_massimo_partecipanti == '8') selected @endif value="8">8</option>
                        </select>
                    </div>
                    {{-- LIVELLO --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="livello">Livello</label>
                        <input value="{{ $group->livello }}" type="number" class="custom-form-input" id="livello" name="livello" required min="1" max="10">
                    </div>
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <button type="submit" class="custom-btn-submit">Modifica Gruppo</button>
                        <a class="btn admin-btn-info mt-3" href="{{route('admin.group.details' , $group)}}">Indietro</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
