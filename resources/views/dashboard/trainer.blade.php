<x-layout documentTitle="Trainer Dashbord">
    {{-- ! ASSENZE --}}
    {{-- Gruppi primo allenatore --}}
    <div class="container mt-5">
        <h1 class="mt-5 pt-5 text-center">Trainer Dashbord</h1>
        <h2 class="mt-5 mb-4">Gruppi in cui sei primo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Segna assenze</h3>
        <div class="row justify-content-center">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'false')
                    <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento) }}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            @if ($alias->secondo_allenatore_id != null)
                                <p class="card-text text-center">Secondo allenatore:
                                    <br>{{ $alias->secondoAllenatore->nome }}
                                    {{ $alias->secondoAllenatore->cognome }}
                                </p>
                            @endif
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        @foreach ($alias->students as $student)
                                            {{-- <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $student->id }}" id="radioStudenti" name="student_ids[]">
                                                <label class="form-check-label" for="radioStudenti">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div> --}}
                                            <div class="col-12">
                                                <label class="checkbox">
                                                    <input class="form-check-input me-1 ms-4" type="checkbox"
                                                        value="{{ $student->id }}" name="studenti_ids[]">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma assense</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    {{-- Gruppi secondo allenatore --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi in cui sei secondo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Segna assenze</h3>
        <div class="row justify-content-center">
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'false')
                    <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento) }}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            <p class="card-text text-center">Primo allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                @csrf
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @foreach ($alias->students as $student)
                                            {{-- <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $student->id }}" id="radioStudenti"
                                                    name="student_ids[]">
                                                <label class="form-check-label" for="radioStudenti">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div> --}}
                                            <div class="col-12">
                                                <label class="checkbox">
                                                    <input class="form-check-input me-1 ms-4" type="checkbox"
                                                        value="{{ $student->id }}" name="studenti_ids[]">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma assense</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    {{-- Gruppi condivisi --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi condivisi</h2>
        <h3 class="text-center mb-4 text-danger">Segna assenze</h3>
        <div class="row justify-content-center">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'true')
                    <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            <p class="card-text text-center">Altro allenatore:
                                <br>{{ $alias->secondoAllenatore->nome }}
                                {{ $alias->secondoAllenatore->cognome }}
                            </p>
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                @csrf
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @foreach ($alias->students as $student)
                                            {{-- <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $student->id }}" id="radioStudenti"
                                                    name="student_ids[]">
                                                <label class="form-check-label" for="radioStudenti">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div> --}}
                                            <div class="col-12">
                                                <label class="checkbox">
                                                    <input class="form-check-input me-1 ms-4" type="checkbox"
                                                        value="{{ $student->id }}" name="studenti_ids[]">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma assense</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'true')
                    <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            <p class="card-text text-center">Altro allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.absence', $alias->id) }}">
                                @csrf
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @foreach ($alias->students as $student)
                                            {{-- <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $student->id }}"
                                            id="radioStudenti" name="student_ids[]">
                                        <label class="form-check-label" for="radioStudenti">
                                            {{ $student->nome }} {{ $student->cognome }}
                                        </label>
                                    </div> --}}
                                            <div class="col-12">
                                                <label class="checkbox">
                                                    <input class="form-check-input me-1 ms-4" type="checkbox"
                                                        value="{{ $student->id }}" name="studenti_ids[]">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma assense</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ! RECUPERI --}}
    {{-- Gruppi primo allenatore --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi in cui sei primo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Registra recuperi</h3>
        <div class="row justify-content-center">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'false' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            @if ($alias->secondo_allenatore_id != null)
                                <p class="card-text text-center">Secondo allenatore:
                                    <br>{{ $alias->secondoAllenatore->nome }}
                                    {{ $alias->secondoAllenatore->cognome }}
                                </p>
                            @endif
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        @foreach ($students as $student)
                                            @if (
                                                !in_array($student->id, $alias->studenti_id) &&
                                                    $student->Nrecoveries > 0 &&
                                                    $student->level - 1 < $alias->livello &&
                                                    $alias->livello < $student->level + 2 &&
                                                    $student->gender == $alias->tipo)
                                                {{-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $student->id }}" id="radioStudenti"
                                                        name="student_ids[]">
                                                    <label class="form-check-label" for="radioStudenti">
                                                        {{ $student->nome }} {{ $student->cognome }}
                                                    </label>
                                                </div> --}}
                                                <div class="col-12">
                                                    <label class="checkbox">
                                                        <input class="form-check-input me-1 ms-4" type="checkbox"
                                                            value="{{ $student->id }}" name="studenti_ids[]">
                                                        {{ $student->nome }} {{ $student->cognome }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma recuperi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    {{-- Gruppi secondo allenatore --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi in cui sei secondo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Registra recuperi</h3>
        <div class="row justify-content-center">
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'false' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            <p class="card-text text-center">Primo allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        @foreach ($students as $student)
                                            @if (
                                                !in_array($student->id, $alias->studenti_id) &&
                                                    $student->Nrecoveries > 0 &&
                                                    $student->level - 1 < $alias->livello &&
                                                    $alias->livello < $student->level + 2 &&
                                                    $student->gender == $alias->tipo)
                                                {{-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $student->id }}" id="radioStudenti"
                                                        name="student_ids[]">
                                                    <label class="form-check-label" for="radioStudenti">
                                                        {{ $student->nome }} {{ $student->cognome }}
                                                    </label>
                                                </div> --}}
                                                <div class="col-12">
                                                    <label class="checkbox">
                                                        <input class="form-check-input me-1 ms-4" type="checkbox"
                                                            value="{{ $student->id }}" name="studenti_ids[]">
                                                        {{ $student->nome }} {{ $student->cognome }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma recuperi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    {{-- Gruppi condivisi --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi condivisi</h2>
        <h3 class="text-center mb-4 text-danger">Registra recuperi</h3>
        <div class="row justify-content-center">
            @foreach ($aliasesPrimoAllenatore as $alias)
                @if ($alias->condiviso == 'true' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            <p class="card-text text-center">Altro allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        @foreach ($students as $student)
                                            @if (
                                                !in_array($student->id, $alias->studenti_id) &&
                                                    $student->Nrecoveries > 0 &&
                                                    $student->level - 1 < $alias->livello &&
                                                    $alias->livello < $student->level + 2 &&
                                                    $student->gender == $alias->tipo)
                                                {{-- <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $student->id }}" id="radioStudenti" name="student_ids[]">
                                            <label class="form-check-label" for="radioStudenti">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </label>
                                        </div> --}}
                                                <div class="col-12">
                                                    <label class="checkbox">
                                                        <input class="form-check-input me-1 ms-4" type="checkbox"
                                                            value="{{ $student->id }}" name="studenti_ids[]">
                                                        {{ $student->nome }} {{ $student->cognome }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma recuperi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
            @foreach ($aliasesSecondoAllenatore as $alias)
                @if ($alias->condiviso == 'true' && $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm"">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            <p class="card-text text-center">Altro allenatore: <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}</p>
                            <form method="POST" action="{{ route('student.recoveries', $alias->id) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        @foreach ($students as $student)
                                            @if (
                                                !in_array($student->id, $alias->studenti_id) &&
                                                    $student->Nrecoveries > 0 &&
                                                    $student->level - 1 < $alias->livello &&
                                                    $alias->livello < $student->level + 2 &&
                                                    $student->gender == $alias->tipo)
                                                {{-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $student->id }}" id="radioStudenti"
                                                        name="student_ids[]">
                                                    <label class="form-check-label" for="radioStudenti">
                                                        {{ $student->nome }} {{ $student->cognome }}
                                                    </label>
                                                </div> --}}
                                                <div class="col-12">
                                                    <label class="checkbox">
                                                        <input class="form-check-input me-1 ms-4" type="checkbox"
                                                            value="{{ $student->id }}" name="studenti_ids[]">
                                                        {{ $student->nome }} {{ $student->cognome }}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma recuperi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ! ASSENZE ALLENATORI --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4 text-danger">Modifica presenze allenatori</h2>
        <h3 class="text-center mb-4">Gruppi in cui alleni</h3>
        <div class="row justify-content-center">
            @foreach ($aliasesPrimoAllenatore as $alias)
                <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                        <p>{{ $alias->formatHours($alias->orario) }}</p>
                        <form method="POST" action="{{ route('alias.update', $alias->id) }}">
                            @csrf
                            {{-- PRIMO ALLENATORE --}}
                            <div class="mb-3">
                                <label class="form-label" for="primo_allenatore_id">Primo Allenatore</label>
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
                            <div class="mb-3">
                                <label class="form-label" for="secondo_allenatore_id">Secondo Allenatore</label>
                                <select class="form-control" id="secondo_allenatore_id" name="secondo_allenatore_id">
                                    @if ($alias->secondo_allenatore_id == null)
                                        <option value="" selected>Nessuno</option>
                                    @else
                                        <option value="">Nessuno</option>
                                    @endif
                                    @foreach ($trainers as $trainer)
                                        <option {{ $alias->secondo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                            value="{{ $trainer->id }}">{{ $trainer->nome }} {{ $trainer->cognome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- CONDIVISO --}}
                            <div class="mb-3">
                                <label class="form-label" for="condiviso">Condiviso</label>
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
                <div class="card col-8 col-md-3col-3 mx-1 my-2 border rounded-4 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">{{$alias->formatData($alias->data_allenamento)}}</h6>
                        <p>{{ $alias->formatHours($alias->orario) }}</p>
                        <form method="POST" action="{{ route('alias.update', $alias->id) }}">
                            @csrf
                            {{-- PRIMO ALLENATORE --}}
                            <div class="mb-3">
                                <label class="form-label" for="primo_allenatore_id">Primo Allenatore</label>
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
                            <div class="mb-3">
                                <label class="form-label" for="secondo_allenatore_id">Secondo Allenatore</label>
                                <select class="form-control" id="secondo_allenatore_id" name="secondo_allenatore_id">
                                    @if ($alias->secondo_allenatore_id == null)
                                        <option value="" selected>Nessuno</option>
                                    @else
                                        <option value="">Nessuno</option>
                                    @endif
                                    <option value="">Nessuno</option>
                                    @foreach ($trainers as $trainer)
                                        <option {{ $alias->secondo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                            value="{{ $trainer->id }}">{{ $trainer->nome }}
                                            {{ $trainer->cognome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- CONDIVISO --}}
                            <div class="mb-3">
                                <label class="form-label" for="condiviso">Condiviso</label>
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
        </div>
    </div>
</x-layout>
