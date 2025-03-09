<x-layout documentTitle="Modify Groups">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student', session('student_filters', [])) }}">
                Corsisti
            </a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
</ul>
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
                            <option @if ($group->giorno_settimana == 'lunedi') selected @endif value="lunedi">Lunedì</option>
                            <option @if ($group->giorno_settimana == 'martedi') selected @endif value="martedi">Martedì</option>
                            <option @if ($group->giorno_settimana == 'mercoledi') selected @endif value="mercoledi">Mercoledì</option>
                            <option @if ($group->giorno_settimana == 'giovedi') selected @endif value="giovedi">Giovedì</option>
                            <option @if ($group->giorno_settimana == 'venerdi') selected @endif value="venerdi">Venerdì</option>
                            <option @if ($group->giorno_settimana == 'sabato') selected @endif value="sabato">Sabato</option>
                            <option @if ($group->giorno_settimana == 'domenica') selected @endif value="domenica">Domenica</option>
                        </select>
                    </div>
                    {{-- SEDE --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="location">In che sede si svolge l'allenamento?</label>
                        <select class="custom-form-input" id="location" name="location" required>
                            <option @if ($group->location == 'torino') selected @endif value="torino">TORINO</option>
                            <option @if ($group->location == 'leinì') selected @endif value="leinì" class="text-uppercase">leinì</option>
                        </select>
                    </div>
                    {{-- ORARIO --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="orario">Orario</label>
                        <select class="custom-form-input" id="orario" name="orario" required>
                            <option @if ($group->orario == '13:00:00') selected @endif value="13:00">13:00</option>
                            <option @if ($group->orario == '13:30:00') selected @endif value="13:30">13:30</option>
                            <option @if ($group->orario == '14:00:00') selected @endif value="14:00">14:00</option>
                            <option @if ($group->orario == '14:30:00') selected @endif value="14:30">14:30</option>
                            <option @if ($group->orario == '17:00:00') selected @endif value="17:00">17:00</option>
                            <option @if ($group->orario == '17:30:00') selected @endif value="17:30">17:30</option>
                            <option @if ($group->orario == '18:00:00') selected @endif value="18:00">18:00</option>
                            <option @if ($group->orario == '18:30:00') selected @endif value="18:30">18:30</option>
                            <option @if ($group->orario == '19:00:00') selected @endif value="19:00">19:00</option>
                            <option @if ($group->orario == '19:30:00') selected @endif value="19:30">19:30</option>
                            <option @if ($group->orario == '20:00:00') selected @endif value="20:00">20:00</option>
                            <option @if ($group->orario == '20:30:00') selected @endif value="20:30">20:30</option>
                            <option @if ($group->orario == '21:00:00') selected @endif value="21:00">21:00</option>
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
                            <option @if ($group->tipo == 'misto') selected @endif value="misto">Misto</option>
                            <option @if ($group->tipo == 'under') selected @endif value="under">Under</option>
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
                            <option @if ($group->numero_massimo_partecipanti == '10') selected @endif value="10">10</option>
                            <option @if ($group->numero_massimo_partecipanti == '16') selected @endif value="16">16</option>
                        </select>
                    </div>
                    {{-- LIVELLO --}}
                    <div class="mb-3">
                        <label class="custom-form-label" for="livello">Livello</label>
                        <input value="{{ $group->livello }}" type="number" class="custom-form-input" id="livello" name="livello" required min="1" max="12">
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
