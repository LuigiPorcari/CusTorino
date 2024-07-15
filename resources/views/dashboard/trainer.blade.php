<x-layout documentTitle="Trainer Dashbord">
    <h1>Trainer Dashbord</h1>
    {{-- Gruppi primo allenatore --}}
    <h2>Gruppi in cui sei primo allenatore</h2>
    <h3>Segna assenze</h3>
    @foreach ($aliasesPrimoAllenatore as $alias)
        @if ($alias->condiviso == 'false')
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    @if ($alias->secondo_allenatore_id != null)
                        <p class="card-text">Secondo allenatore: {{ $alias->secondoAllenatore->nome }}
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
    {{-- Gruppi secondo allenatore --}}
    <h2>Gruppi in cui sei secondo allenatore</h2>
    <h3>Segna assenze</h3>
    @foreach ($aliasesSecondoAllenatore as $alias)
        @if ($alias->condiviso == 'false')
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    <p class="card-text">Primo allenatore: {{ $alias->primoAllenatore->nome }}
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
    {{-- Gruppi condivisi --}}
    <h2>Gruppi condivisi</h2>
    <h3>Segna assenze</h3>
    @foreach ($aliasesPrimoAllenatore as $alias)
        @if ($alias->condiviso == 'true')
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    <p class="card-text">Altro allenatore: {{ $alias->primoAllenatore->nome }}
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
    @foreach ($aliasesSecondoAllenatore as $alias)
        @if ($alias->condiviso == 'true')
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    <p class="card-text">Altro allenatore: {{ $alias->primoAllenatore->nome }}
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
    {{--! RECUPERI --}}
    {{-- Gruppi primo allenatore --}}
    <h2>Gruppi in cui sei primo allenatore</h2>
    <h3>Registra recuperi</h3>
    @foreach ($aliasesPrimoAllenatore as $alias)
        @if ($alias->condiviso == 'false' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    @if ($alias->secondo_allenatore_id != null)
                        <p class="card-text">Secondo allenatore: {{ $alias->secondoAllenatore->nome }}
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
                        <button type="submit" class="btn btn-primary">Conferma assense</button>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
    {{-- Gruppi secondo allenatore --}}
    <h2>Gruppi in cui sei secondo allenatore</h2>
    <h3>Registra recuperi</h3>
    @foreach ($aliasesSecondoAllenatore as $alias)
        @if ($alias->condiviso == 'false' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    <p class="card-text">Primo allenatore: {{ $alias->primoAllenatore->nome }}
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
                                    <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                        id="radioStudenti" name="student_ids[]">
                                    <label class="form-check-label" for="radioStudenti">
                                        {{ $student->nome }} {{ $student->cognome }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                        <button type="submit" class="btn btn-primary">Conferma assense</button>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
    {{-- Gruppi condivisi --}}
    <h2>Gruppi condivisi</h2>
    <h3>Registra recuperi</h3>
    @foreach ($aliasesPrimoAllenatore as $alias)
        @if ($alias->condiviso == 'true' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    <p class="card-text">Altro allenatore: {{ $alias->primoAllenatore->nome }}
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
                                    <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                        id="radioStudenti" name="student_ids[]">
                                    <label class="form-check-label" for="radioStudenti">
                                        {{ $student->nome }} {{ $student->cognome }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                        <button type="submit" class="btn btn-primary">Conferma assense</button>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
    @foreach ($aliasesSecondoAllenatore as $alias)
        @if ($alias->condiviso == 'true' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alias->nome }}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $alias->data_allenamento }}</h6>
                    <p class="card-text">Altro allenatore: {{ $alias->primoAllenatore->nome }}
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
                                    <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                        id="radioStudenti" name="student_ids[]">
                                    <label class="form-check-label" for="radioStudenti">
                                        {{ $student->nome }} {{ $student->cognome }}
                                    </label>
                                </div>
                            @endif
                        @endforeach
                        <button type="submit" class="btn btn-primary">Conferma assense</button>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
</x-layout>
