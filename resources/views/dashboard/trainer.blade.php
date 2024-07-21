<x-layout documentTitle="Trainer Dashbord">
    {{-- ! ASSENZE --}}
    {{-- Gruppi primo allenatore --}}
    <div class="container mt-5">
        <h1 class="mt-5 pt-5 text-center">Trainer Dashbord</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="mt-5 mb-4">Gruppi in cui sei primo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Segna assenze</h3>
        <div class="row justify-content-center">
            @forelse ($aliasesPrimoAllenatore as $alias)
                <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">
                            {{ $alias->formatData($alias->data_allenamento) }}</h6>
                        <p>{{ $alias->formatHours($alias->orario) }}</p>
                        @if ($alias->secondo_allenatore_id != null)
                            <p class="card-text text-center">Secondo allenatore:
                                <br>{{ $alias->secondoAllenatore->nome }}
                                {{ $alias->secondoAllenatore->cognome }}
                            </p>
                        @endif
                        <form method="POST" action="{{ route('student.absence', $alias) }}">
                            <div class="boxesTrainer container mt-2">
                                <div class="row justify-content-center">
                                    @csrf
                                    @forelse ($alias->students as $student)
                                        <div class="col-12">
                                            <label class="checkbox">
                                                <input class="form-check-input me-1 ms-4" type="checkbox"
                                                    value="{{ $student->id }}" name="studenti_ids[]">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-center fs-3">Sono tutti assenti</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-center">
                                <button type="submit" class="btn btn-primary mt-3">Conferma assense</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="fs-2">Non ci sono gruppi disponibbili</p>
                </div>
            @endforelse
            <div class="col-12 mt-2">
                {{ $aliasesPrimoAllenatore->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    {{-- Gruppi secondo allenatore --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi in cui sei secondo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Segna assenze</h3>
        <div class="row justify-content-center">
            @forelse ($aliasesSecondoAllenatore as $alias)
                <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">
                            {{ $alias->formatData($alias->data_allenamento) }}</h6>
                        <p>{{ $alias->formatHours($alias->orario) }}</p>
                        @if ($alias->primo_allenatore_id != null)
                            <p class="card-text text-center">Primo allenatore:
                                <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}
                            </p>
                        @endif
                        <form method="POST" action="{{ route('student.absence', $alias) }}">
                            @csrf
                            <div class="boxesTrainer container mt-2">
                                <div class="row justify-content-center">
                                    @foreach ($alias->students as $student)
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
            @empty
                <div class="col-12 text-center">
                    <p class="fs-2">Non ci sono gruppi disponibbili</p>
                </div>
            @endforelse
            <div class="col-12 mt-2">
                {{ $aliasesSecondoAllenatore->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    {{-- Gruppi condivisi --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi condivisi</h2>
        <h3 class="text-center mb-4 text-danger">Segna assenze</h3>
        <div class="row justify-content-center">
            @forelse ($aliasesCond as $alias)
                <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">
                            {{ $alias->formatData($alias->data_allenamento) }}</h6>
                        <p>{{ $alias->formatHours($alias->orario) }}</p>
                        <p class="card-text text-center">Altro allenatore:
                            @if ($alias->primoAllenatore != null && $alias->primoAllenatore->nome != Auth::guard('trainer')->user()->nome)
                                <br>{{ $alias->primoAllenatore->nome }}
                                {{ $alias->primoAllenatore->cognome }}
                            @elseif($alias->secondoAllenatore != null)
                                <br>{{ $alias->secondoAllenatore->nome }}
                                {{ $alias->secondoAllenatore->cognome }}
                            @endif
                        </p>
                        <form method="POST" action="{{ route('student.absence', $alias) }}">
                            @csrf
                            <div class="boxesTrainer container mt-2">
                                <div class="row justify-content-center">
                                    @foreach ($alias->students as $student)
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
            @empty
                <div class="col-12 text-center">
                    <p class="fs-2">Non ci sono gruppi disponibbili</p>
                </div>
            @endforelse
            <div class="col-12 mt-2">
                {{ $aliasesCond->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- ! RECUPERI --}}
    {{-- Gruppi primo allenatore --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi in cui sei primo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Registra recuperi</h3>
        <div class="row justify-content-center">
            @forelse ($aliasesPrimoAllenatore as $alias)
                @if ($alias->studenti_id == null || $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">
                                {{ $alias->formatData($alias->data_allenamento) }}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            @if ($alias->secondo_allenatore_id != null)
                                <p class="card-text text-center">Secondo allenatore:
                                    <br>{{ $alias->secondoAllenatore->nome }}
                                    {{ $alias->secondoAllenatore->cognome }}
                                </p>
                            @endif
                            @if (empty(Auth::guard('trainer')->user()->getRecoverableStudent($alias)))
                                <p class="text-center">Non ci sono Studenti che possono recuperare in questa data</p>
                            @else
                                <a class="btn btn-primary" href="{{ route('editStudent.trainer', $alias) }}">Segna
                                    recuperi</a>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center">
                    <p class="fs-2">Non ci sono gruppi disponibbili</p>
                </div>
            @endforelse
            <div class="col-12 mt-2">
                {{ $aliasesPrimoAllenatore->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    {{-- Gruppi secondo allenatore --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi in cui sei secondo allenatore</h2>
        <h3 class="text-center mb-4 text-danger">Registra recuperi</h3>
        <div class="row justify-content-center">
            @forelse ($aliasesSecondoAllenatore as $alias)
                @if ($alias->studenti_id == null || $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">
                                {{ $alias->formatData($alias->data_allenamento) }}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            @if ($alias->primo_allenatore_id != null)
                                <p class="card-text text-center">Primo allenatore:
                                    <br>{{ $alias->primoAllenatore->nome }}
                                    {{ $alias->primoAllenatore->cognome }}
                                </p>
                            @endif
                            {{-- <form method="POST" action="{{ route('student.recoveries', $alias) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        @forelse (Auth::guard('trainer')->user()->getRecoverableStudent($alias) as $student)
                                            <div class="col-12">
                                                <label class="checkbox">
                                                    <input class="form-check-input me-1 ms-4" type="checkbox"
                                                        value="{{ $student->id }}" name="studenti_ids[]">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-center">Non ci sono corsisti che possono recuperare in
                                                    questo gruppo</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma recuperi</button>
                                </div>
                            </form> --}}
                            @if (empty(Auth::guard('trainer')->user()->getRecoverableStudent($alias)))
                                <p class="text-center">Non ci sono Studenti che possono recuperare in questa data</p>
                            @else
                                <a class="btn btn-primary" href="{{ route('editStudent.trainer', $alias) }}">Segna
                                    recuperi</a>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center">
                    <p class="fs-2">Non ci sono gruppi disponibbili</p>
                </div>
            @endforelse
            <div class="col-12 mt-2">
                {{ $aliasesSecondoAllenatore->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    {{-- Gruppi condivisi --}}
    <div class="container mt-5">
        <h2 class="mt-5 mb-4">Gruppi condivisi</h2>
        <h3 class="text-center mb-4 text-danger">Registra recuperi</h3>
        <div class="row justify-content-center">
            @forelse ($aliasesCond as $alias)
                @if ($alias->numero_massimo_partecipanti > count($alias->studenti_id))
                    <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between align-items-center">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <h6 class="card-subtitle mb-2 text-body-secondary">
                                {{ $alias->formatData($alias->data_allenamento) }}</h6>
                            <p>{{ $alias->formatHours($alias->orario) }}</p>
                            <p class="card-text text-center">Altro allenatore:
                                @if ($alias->primoAllenatore != null && $alias->primoAllenatore->nome != Auth::guard('trainer')->user()->nome)
                                    <br>{{ $alias->primoAllenatore->nome }}
                                    {{ $alias->primoAllenatore->cognome }}
                                @elseif($alias->secondoAllenatore != null)
                                    <br>{{ $alias->secondoAllenatore->nome }}
                                    {{ $alias->secondoAllenatore->cognome }}
                                @endif
                            </p>
                            {{-- <form method="POST" action="{{ route('student.recoveries', $alias) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        @forelse (Auth::guard('trainer')->user()->getRecoverableStudent($alias) as $student)
                                            <div class="col-12">
                                                <label class="checkbox">
                                                    <input class="form-check-input me-1 ms-4" type="checkbox"
                                                        value="{{ $student->id }}" name="studenti_ids[]">
                                                    {{ $student->nome }} {{ $student->cognome }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <p class="text-center">Non ci sono corsisti che possono recuperare in
                                                    questo gruppo</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="btn btn-primary mt-3">Conferma recuperi</button>
                                </div>
                            </form> --}}
                            @if (empty(Auth::guard('trainer')->user()->getRecoverableStudent($alias)))
                                <p class="text-center">Non ci sono Studenti che possono recuperare in questa data</p>
                            @else
                                <a class="btn btn-primary" href="{{ route('editStudent.trainer', $alias) }}">Segna
                                    recuperi</a>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center">
                    <p class="fs-2">Non ci sono gruppi disponibbili</p>
                </div>
            @endforelse
            <div class="col-12 mt-2">
                {{ $aliasesCond->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- ! ASSENZE ALLENATORI --}}
    <div class="container mt-5 mb-4">
        <h2 class="mt-5 mb-4 text-danger">Modifica presenze allenatori</h2>
        <h3 class="text-center mb-4">Gruppi in cui alleni</h3>
        <div class="row justify-content-center">
            @forelse ($aliasesTrainer as $alias)
                <div class="card col-8 col-md-3 mx-1 my-2 border rounded-4 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">
                            {{ $alias->formatData($alias->data_allenamento) }}</h6>
                        <p>{{ $alias->formatHours($alias->orario) }}</p>
                        <form method="POST" action="{{ route('alias.update', $alias) }}">
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
            @empty
                <div class="col-12 text-center">
                    <p class="fs-2">Non ci sono gruppi disponibbili</p>
                </div>
            @endforelse
            <div class="col-12 mt-2">
                {{ $aliasesTrainer->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-layout>
