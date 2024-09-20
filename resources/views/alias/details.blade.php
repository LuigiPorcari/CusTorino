<x-layout documentTitle="Dettagli Alias">
    <div class="container mt-5 pt-5">
        <h1 class="custom-title text-center mb-3 mt-4">Dettagli del Gruppo</h1>
        @if (session('success'))
            <div class="alert alert-dismissible custom-alert-success">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row my-5 justify-content-center">
            {{-- ! Descrizione gruppo alias --}}
            <div class="col-11 col-md-3">
                <h3 class="custom-subtitle text-center mb-4">Dettagli</h3>
                <div class="custom-card equal-height-card mx-1 my-2">
                    <div class="custom-card-body">
                        <h5 class="card-title">{{ $alias->nome }}</h5>
                        <p class="card-text">Luogo: <span class="text-uppercase">{{ $alias->location }}</span></p>
                        <h6 class="custom-date card-subtitle mb-2">{{ $alias->formatData($alias->data_allenamento) }}
                        </h6>
                        <p class="custom-paragraph"><span class="fw-bold">Orario:</span>
                            {{ $alias->formatHours($alias->orario) }}</p>
                        @if ($alias->condiviso == 'false')
                            @if ($alias->primo_allenatore_id != null)
                                <p class="custom-paragraph"><span class="fw-bold">Primo allenatore:</span> <br>
                                    {{ $alias->primoAllenatore->name }} {{ $alias->primoAllenatore->cognome }}</p>
                            @endif
                            @if ($alias->secondo_allenatore_id != null)
                                <p class="custom-paragraph"><span class="fw-bold">Secondo allenatore:</span> <br>
                                    {{ $alias->secondoAllenatore->name }} {{ $alias->secondoAllenatore->cognome }}</p>
                            @endif
                        @endif
                        @if ($alias->condiviso == 'true')
                            @if ($alias->primo_allenatore_id != null)
                                <p class="custom-paragraph"><span class="fw-bold">Allenatore condiviso:</span> <br>
                                    {{ $alias->primoAllenatore->name }} {{ $alias->primoAllenatore->cognome }}</p>
                            @endif
                            @if ($alias->secondo_allenatore_id != null)
                                <p class="custom-paragraph"><span class="fw-bold">Allenatore condiviso:</span> <br>
                                    {{ $alias->secondoAllenatore->name }} {{ $alias->secondoAllenatore->cognome }}</p>
                            @endif
                            <p class="custom-paragraph">Condiviso</p>
                        @endif
                        <div class="row border rounded-3">
                            <div class="col-6 p-0 border-end">
                                <p class="my-0 py-2 card-text border-bottom"><span class="fw-bold">Studenti
                                        originali:</span></p>
                                @foreach ($alias->group->users as $student)
                                    <p
                                        class="my-0 py-1 card-text border-bottom {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                        {{ $student->name }} {{ $student->cognome }}
                                    </p>
                                @endforeach
                            </div>
                            <div class="col-6 p-0">
                                <p class="my-0 py-2 card-text border-bottom"><span class="fw-bold">Recuperi:</span></p>
                                @foreach ($alias->compareStudents($alias->group->id, $alias->id) as $recupero)
                                    @if (!in_array($recupero->id, $alias->group->studenti_id))
                                        <p class="my-0 py-1 card-text border-bottom">{{ $recupero->name }}
                                            {{ $recupero->cognome }}</p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- !Segna assenze studenti --}}
            <div class="col-11 col-md-3">
                <h3 class="custom-subtitle text-center mb-4">Segna Assenze</h3>
                <div class="custom-card equal-height-card mx-1 my-2">
                    <div class="custom-card-body d-flex flex-column justify-content-between align-items-center">
                        @if ($threeDaysCheck || Auth::user()->is_admin)
                            <form method="POST" action="{{ route('student.absence', $alias) }}">
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        @csrf
                                        <div class="col-12">
                                            @foreach ($alias->group->users as $student)
                                                <label class="checkbox">
                                                    <input class="form-check-input me-1 ms-4" type="checkbox"
                                                        value="{{ $student->id }}" name="studenti_ids[]"
                                                        @if (!in_array($student->id, $alias->studenti_id)) checked @endif>
                                                    {{ $student->name }} {{ $student->cognome }}
                                                </label>
                                            @endforeach
                                            @foreach ($alias->users as $student)
                                                @if (!in_array($student->id, $alias->group->studenti_id))
                                                    <label class="checkbox">
                                                        <input class="form-check-input me-1 ms-4" type="checkbox"
                                                            value="{{ $student->id }}" name="studenti_ids[]"
                                                            @if (!in_array($student->id, $alias->studenti_id)) checked @endif>
                                                        {{ $student->name }} {{ $student->cognome }}
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <button type="submit" class="custom-btn-submit mt-3">Conferma assenze</button>
                                </div>
                            </form>
                        @else
                            <p class="text-center fs-3">Sono passati 3 o più giorni</p>
                        @endif
                    </div>
                </div>
            </div>
            {{-- !Segna recuperi studenti --}}
            <div class="col-11 col-md-3">
                <h3 class="custom-subtitle text-center mb-4 text-decoration-none">Segna Recuperi</h3>
                <div class="custom-card equal-height-card mx-1 my-2">
                    <div class="custom-card-body d-flex flex-column justify-content-between align-items-center">
                        @if ($threeDaysCheck || Auth::user()->is_admin)
                            @if ($alias->studenti_id == null || $alias->numero_massimo_partecipanti > count($alias->studenti_id))
                                @if (empty(Auth::user()->getRecoverableStudent($alias)))
                                    <p class="text-center">Non ci sono Studenti che possono recuperare in questa data
                                    </p>
                                @else
                                    <a class="custom-btn-submit text-center text-decoration-none"
                                        href="{{ route('student.edit', $alias) }}">Segna recuperi</a>
                                @endif
                            @else
                                <p class="text-center">Questo gruppo è già al completo</p>
                            @endif
                        @else
                            <p class="text-center fs-3">Sono passati 3 o più giorni</p>
                        @endif
                    </div>
                </div>
            </div>
            {{-- !Segna assenze allenatori --}}
            <div class="col-11 col-md-3">
                <h3 class="custom-subtitle text-center mb-4 pb-1 fs-4">Assenza Allenatore</h3>
                <div class="custom-card equal-height-card mx-1 mt-4 mb-2">
                    <div class="custom-card-body d-flex flex-column justify-content-between align-items-center">
                        @if ($threeDaysCheck || Auth::user()->is_admin)
                            <form method="POST" action="{{ route('alias.update', $alias) }}">
                                @csrf
                                {{-- PRIMO ALLENATORE --}}
                                <div class="mb-3">
                                    <label class="custom-form-label" for="primo_allenatore_id">Primo Allenatore</label>
                                    <select class="custom-form-input" id="primo_allenatore_id"
                                        name="primo_allenatore_id" required>
                                        @foreach ($trainers as $trainer)
                                            <option {{ $alias->primo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                                value="{{ $trainer->id }}">{{ $trainer->name }}
                                                {{ $trainer->cognome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- SECONDO ALLENATORE --}}
                                <div class="mb-3">
                                    <label class="custom-form-label" for="secondo_allenatore_id">Secondo
                                        Allenatore</label>
                                    <select class="custom-form-input" id="secondo_allenatore_id"
                                        name="secondo_allenatore_id">
                                        @if ($alias->secondo_allenatore_id == null)
                                            <option value="" selected>Nessuno</option>
                                        @else
                                            <option value="">Nessuno</option>
                                        @endif
                                        @foreach ($trainers as $trainer)
                                            <option
                                                {{ $alias->secondo_allenatore_id == $trainer->id ? 'selected' : '' }}
                                                value="{{ $trainer->id }}">{{ $trainer->name }}
                                                {{ $trainer->cognome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- CONDIVISO --}}
                                <div class="mb-3">
                                    <label class="custom-form-label" for="condiviso">Condiviso</label>
                                    <select class="custom-form-input" id="condiviso" name="condiviso" required>
                                        @if ($alias->condiviso == 'true')
                                            <option selected value="true">Sì</option>
                                            <option value="false">No</option>
                                        @else
                                            <option value="true">Sì</option>
                                            <option selected value="false">No</option>
                                        @endif
                                    </select>
                                </div>
                                <button type="submit" class="custom-btn-submit">Modifica presenze allenatori</button>
                            </form>
                        @else
                            <p class="text-center fs-3">Sono passati 3 o più giorni</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Pulsante di Ritorno -->
            @if (Auth::user()->is_admin)
                <div class="text-center">
                    <a class="custom-link-btn" href="{{ route('admin.group.details', $alias->group) }}">Indietro</a>
                </div>
            @else
                <div class="text-center">
                    <a class="custom-link-btn" href="{{ route('trainer.group') }}">Indietro</a>
                </div>
            @endif
        </div>
    </div>
</x-layout>
