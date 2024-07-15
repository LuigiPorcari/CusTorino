<x-layout documentTitle="Trainer Dashbord">
    <h1>Trainer Dashbord</h1>
    {{-- ! ASSENZE --}}
    {{-- Gruppi primo allenatore --}}
    <h2>Gruppi in cui sei primo allenatore</h2>
    <h3>Segna assenze</h3>
    <div class="container">
        <div class="row">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'false')
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            @if ($alias->secondo_allenatore_id != null)
                                <p class="card-text">Secondo allenatore: <br>{{ $alias->secondoAllenatore->nome }}
                                    {{ $alias->secondoAllenatore->cognome }}</p>
                            @endif
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                @csrf
                                @foreach ($alias->students as $student)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                            id="radioStudenti" name="student_ids[]">
                                        <label class="form-check-label" for="radioStudenti">
                                            {{ $student->nome }} {{ $student->cognome }}
                                        </label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma assense</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Gruppi secondo allenatore --}}
    <h2>Gruppi in cui sei secondo allenatore</h2>
    <h3>Segna assenze</h3>
    <div class="container">
        <div class="row">
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'false')
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            <p class="card-text">Primo allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                @csrf
                                @foreach ($alias->students as $student)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                            id="radioStudenti" name="student_ids[]">
                                        <label class="form-check-label" for="radioStudenti">
                                            {{ $student->nome }} {{ $student->cognome }}
                                        </label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma assense</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Gruppi condivisi --}}
    <h2>Gruppi condivisi</h2>
    <h3>Segna assenze</h3>
    <div class="container">
        <div class="row">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'true')
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            <p class="card-text">Altro allenatore: <br>{{ $alias->secondoAllenatore->nome }}
                                {{ $alias->secondoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                @csrf
                                @foreach ($alias->students as $student)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                            id="radioStudenti" name="student_ids[]">
                                        <label class="form-check-label" for="radioStudenti">
                                            {{ $student->nome }} {{ $student->cognome }}
                                        </label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma assense</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'true')
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            <p class="card-text">Altro allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                @csrf
                                @foreach ($alias->students as $student)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                            id="radioStudenti" name="student_ids[]">
                                        <label class="form-check-label" for="radioStudenti">
                                            {{ $student->nome }} {{ $student->cognome }}
                                        </label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma assense</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ! RECUPERI --}}
    {{-- Gruppi primo allenatore --}}
    <h2>Gruppi in cui sei primo allenatore</h2>
    <h3>Registra recuperi</h3>
    <div class="container">
        <div class="row">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'false' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            @if ($alias->secondo_allenatore_id != null)
                                <p class="card-text">Secondo allenatore: <br>{{ $alias->secondoAllenatore->nome }}
                                    {{ $alias->secondoAllenatore->cognome }}</p>
                            @endif
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                @csrf
                                @foreach ($students as $student)
                                    @if (
                                        !in_array($student->id, $alias->studenti_id) &&
                                            $student->Nrecoveries > 0 &&
                                            $student->level - 1 < $alias->livello &&
                                            $alias->livello < $student->level + 2 &&
                                            $student->gender == $alias->tipo)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                                id="radioStudenti" name="student_ids[]">
                                            <label class="form-check-label" for="radioStudenti">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma recuperi</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Gruppi secondo allenatore --}}
    <h2>Gruppi in cui sei secondo allenatore</h2>
    <h3>Registra recuperi</h3>
    <div class="container">
        <div class="row">
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'false' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            <p class="card-text">Primo allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                @csrf
                                @foreach ($students as $student)
                                    @if (
                                        !in_array($student->id, $alias->studenti_id) &&
                                            $student->Nrecoveries > 0 &&
                                            $student->level - 1 < $alias->livello &&
                                            $alias->livello < $student->level + 2 &&
                                            $student->gender == $alias->tipo)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $student->id }}" id="radioStudenti" name="student_ids[]">
                                            <label class="form-check-label" for="radioStudenti">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma recuperi</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Gruppi condivisi --}}
    <h2>Gruppi condivisi</h2>
    <h3>Registra recuperi</h3>
    <div class="container">
        <div class="row">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'true' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            <p class="card-text">Altro allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                @csrf
                                @foreach ($students as $student)
                                    @if (
                                        !in_array($student->id, $alias->studenti_id) &&
                                            $student->Nrecoveries > 0 &&
                                            $student->level - 1 < $alias->livello &&
                                            $alias->livello < $student->level + 2 &&
                                            $student->gender == $alias->tipo)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $student->id }}" id="radioStudenti" name="student_ids[]">
                                            <label class="form-check-label" for="radioStudenti">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma recuperi</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'true' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            <p class="card-text">Altro allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                @csrf
                                @foreach ($students as $student)
                                    @if (
                                        !in_array($student->id, $alias->studenti_id) &&
                                            $student->Nrecoveries > 0 &&
                                            $student->level - 1 < $alias->livello &&
                                            $alias->livello < $student->level + 2 &&
                                            $student->gender == $alias->tipo)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $student->id }}" id="radioStudenti" name="student_ids[]">
                                            <label class="form-check-label" for="radioStudenti">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                                <button type="submit" class="btn btn-primary">Conferma recuperi</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ! ASSENZE ALLENATORI --}}
    <h2>Gruppi in cui alleni</h2>
    <div class="container">
        <div class="row">
            @foreach ($aliasesPrimoAllenatore as $alias)
                <div class="card col-2">
                    <div class="card-body">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                        <form method="POST" action="{{ route('alias.update', $alias->id) }}">
                            @csrf
                            {{-- PRIMO ALLENATORE --}}
                            <div class="form-group">
                                <label for="primo_allenatore_id">Primo Allenatore</label>
                                <select class="form-control" id="primo_allenatore_id" name="primo_allenatore_id"
                                    required>
                                    @foreach ($trainers as $trainer)
                                        <option {{ $alias->primo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                            value="{{ $trainer->id }}">{{ $trainer->nome }} {{ $trainer->cognome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- SECONDO ALLENATORE --}}
                            <div class="form-group">
                                <label for="secondo_allenatore_id">Secondo Allenatore</label>
                                <select class="form-control" id="secondo_allenatore_id" name="secondo_allenatore_id">
                                    @if ($alias->secondo_allenatore_id == null)
                                        <option value="" selected>Nessuno</option>
                                    @endif
                                    <option value="">Nessuno</option>
                                    @foreach ($trainers as $trainer)
                                        <option {{ $alias->secondo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                            value="{{ $trainer->id }}">{{ $trainer->nome }} {{ $trainer->cognome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- CONDIVISO --}}
                            <div class="form-group">
                                <label for="condiviso">Condiviso</label>
                                <select class="form-control" id="condiviso" name="condiviso" required>
                                    @if ($alias->condiviso == 'true')
                                        <option selected value="true">Sì</option>
                                        <option value="false">No</option>
                                    @else
                                        <option value="true">Sì</option>
                                        <option selected value="false">No</option>
                                    @endif
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Modifica presenze allenatori</button>
                        </form>
                    </div>
                </div>
            @endforeach
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'true' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                            <form method="POST" action="{{ route('alias.update', $alias->id) }}">
                                @csrf
                                {{-- PRIMO ALLENATORE --}}
                                <div class="form-group">
                                    <label for="primo_allenatore_id">Primo Allenatore</label>
                                    <select class="form-control" id="primo_allenatore_id" name="primo_allenatore_id"
                                        required>
                                        @foreach ($trainers as $trainer)
                                            <option {{ $alias->primo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                                value="{{ $trainer->id }}">{{ $trainer->nome }}
                                                {{ $trainer->cognome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- SECONDO ALLENATORE --}}
                                <div class="form-group">
                                    <label for="secondo_allenatore_id">Secondo Allenatore</label>
                                    <select class="form-control" id="secondo_allenatore_id"
                                        name="secondo_allenatore_id">
                                        @if ($alias->secondo_allenatore_id == null)
                                            <option value="" selected>Nessuno</option>
                                        @endif
                                        <option value="">Nessuno</option>
                                        @foreach ($trainers as $trainer)
                                            <option
                                                {{ $alias->secondo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                                value="{{ $trainer->id }}">{{ $trainer->nome }}
                                                {{ $trainer->cognome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- CONDIVISO --}}
                                <div class="form-group">
                                    <label for="condiviso">Condiviso</label>
                                    <select class="form-control" id="condiviso" name="condiviso" required>
                                        @if ($alias->condiviso == 'true')
                                            <option selected value="true">Sì</option>
                                            <option value="false">No</option>
                                        @else
                                            <option value="true">Sì</option>
                                            <option selected value="false">No</option>
                                        @endif
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Modifica presenze allenatori</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

</x-layout>
