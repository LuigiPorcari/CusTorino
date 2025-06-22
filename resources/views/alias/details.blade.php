<x-layout documentTitle="Dettagli Alias">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0">
        @if (Auth::check() && Auth::user()->is_admin)
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
                <a class="nav-link" aria-current="page" href="{{ route('admin.week') }}">Settimana</a>
            </li>
            <li class="nav-item admin-nav-item mt-3">
                <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
            </li>
        @endif
        @if (Auth::check() && Auth::user()->is_trainer)
            <li class="nav-item admin-nav-item mt-3">
                <a class="nav-link" aria-current="page" href="{{ route('trainer.dashboard') }}">Settimana</a>
            </li>
            <li class="nav-item admin-nav-item mt-3">
                <a class="nav-link" aria-current="page" href="{{ route('trainer.group') }}">Gruppi</a>
            </li>
            <li class="nav-item admin-nav-item mt-3">
                <a class="nav-link" href="{{ route('trainer.salary') }}">Compensi</a>
            </li>
        @endif
    </ul>
    <div class="container mt-5 pt-5">
        <div class="pt-5 pt-md-0">
            <h1 class="custom-title text-center mb-3 mt-5 pt-3">Dettagli del Gruppo</h1>
        </div>
        @if (session('success'))
            <div class="alert alert-dismissible custom-alert-success">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-dismissible alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                @endforeach
            </div>
        @endif
        <!-- Form principale per tutte le modifiche -->
        <form method="POST" action="{{ route('trainer.update.all', $alias) }}">
            @csrf
            <div class="row my-5 justify-content-center">
                {{-- Colonna: Dettagli gruppo alias --}}
                <div class="col-11 col-md-3">
                    <h3 class="custom-subtitle text-center mb-4">Dettagli</h3>
                    <div class="custom-card equal-height-card mx-1 my-2">
                        <div class="custom-card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <p class="card-text">Sede: <span class="text-uppercase">{{ $alias->location }}</span></p>
                            <h6 class="custom-date card-subtitle mb-2">
                                {{ $alias->formatData($alias->data_allenamento) }}</h6>
                            <p class="custom-paragraph"><span class="fw-bold">Tipo:</span> {{ $alias->tipo }}</p>
                            <p class="custom-paragraph"><span class="fw-bold">Orario:</span>
                                {{ $alias->formatHours($alias->orario) }}</p>
                            <!-- Corsisti e recuperi -->
                            <div class="row border rounded-3">
                                <div class="col-6 p-0 border-end">
                                    <p class="my-0 py-2 card-text border-bottom"><span class="fw-bold">Corsisti:</span>
                                    </p>
                                    @foreach ($alias->group->users as $student)
                                        <p
                                            class="my-0 py-1 card-text border-bottom {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                            {{ $student->name }} {{ $student->cognome }}
                                        </p>
                                    @endforeach
                                </div>
                                <div class="col-6 p-0">
                                    <p class="my-0 py-2 card-text border-bottom"><span class="fw-bold">Recuperi:</span>
                                    </p>
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
                {{-- Colonna: Segna assenze studenti --}}
                <div class="col-11 col-md-3">
                    <h3 class="custom-subtitle text-center mb-4">Segna Assenze</h3>
                    <div class="custom-card equal-height-card mx-1 my-2">
                        <div class="custom-card-body">
                            @if ($threeDaysCheck || Auth::user()->is_admin)
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        <div class="col-12 list-group">
                                            @foreach ($alias->group->users as $student)
                                                <label class="checkbox">
                                                    <input type="checkbox" class="form-check-input me-1 ms-4"
                                                        name="student_absences[]" value="{{ $student->id }}"
                                                        {{ in_array($student->id, $alias->studenti_id) ? '' : 'checked' }}>
                                                    {{ $student->name }} {{ $student->cognome }}
                                                </label>
                                            @endforeach
                                            @foreach ($alias->users as $student)
                                                <input type="hidden" name="all_students[]"
                                                    value="{{ $student->id }}">
                                                @if (!in_array($student->id, $alias->group->studenti_id))
                                                    <label class="checkbox">
                                                        <input class="form-check-input me-1 ms-4" type="checkbox"
                                                            value="{{ $student->id }}" name="student_absences[]"
                                                            @if (!in_array($student->id, $alias->studenti_id)) checked @endif>
                                                        {{ $student->name }} {{ $student->cognome }}
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-center fs-3">Sono passati 3 o più giorni</p>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Colonna: Segna recuperi studenti --}}
                <div class="col-11 col-md-3">
                    <h3 class="custom-subtitle text-center mb-4">Segna Recuperi</h3>
                    <div class="custom-card equal-height-card mx-1 my-2 d-flex">
                        <div class="custom-card-body">
                            @if ($threeDaysCheck || Auth::user()->is_admin)
                                <input type="text" id="searchInput" class="custom-form-input mb-3"
                                    placeholder="Cerca corsisti...">
                                <div id="studentsList" class="list-group custom-scrollable-list">
                                    @foreach (Auth::user()->getRecoverableStudent($alias) as $student)
                                        <label class="checkbox">
                                            <input class="form-check-input me-1" type="checkbox"
                                                value="{{ $student->id }}" name="student_recoveries[]">
                                            {{ $student->name }} {{ $student->cognome }}
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center fs-3">Sono passati 3 o più giorni</p>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Colonna: Assenza Allenatore --}}
                <div class="col-11 col-md-3">
                    <h3 class="custom-subtitle text-center mb-4">Allenatori</h3>
                    <div class="custom-card equal-height-card mx-1 my-2">
                        <div class="custom-card-body">
                            @if ($threeDaysCheck || Auth::user()->is_admin)
                                {{-- Primo Allenatore --}}
                                <div class="mb-3">
                                    <label for="primo_allenatore_id" class="custom-form-label">Primo
                                        Allenatore</label>
                                    <select name="primo_allenatore_id" id="primo_allenatore_id"
                                        class="custom-form-input">
                                        <option value=""
                                            {{ is_null($alias->primo_allenatore_id) ? 'selected' : '' }}>Nessuno
                                        </option>
                                        @foreach ($trainers as $trainer)
                                            <option value="{{ $trainer->id }}"
                                                {{ $alias->primo_allenatore_id == $trainer->id ? 'selected' : '' }}>
                                                {{ $trainer->name }} {{ $trainer->cognome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Secondo Allenatore --}}
                                <div class="mb-3">
                                    <label for="secondo_allenatore_id" class="custom-form-label">Secondo
                                        Allenatore</label>
                                    <select name="secondo_allenatore_id" id="secondo_allenatore_id"
                                        class="custom-form-input">
                                        <option value=""
                                            {{ is_null($alias->secondo_allenatore_id) ? 'selected' : '' }}>Nessuno
                                        </option>
                                        @foreach ($trainers as $trainer)
                                            <option value="{{ $trainer->id }}"
                                                {{ $alias->secondo_allenatore_id == $trainer->id ? 'selected' : '' }}>
                                                {{ $trainer->name }} {{ $trainer->cognome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Condiviso --}}
                                <div class="mb-3">
                                    <label for="condiviso" class="custom-form-label">Condiviso</label>
                                    <select name="condiviso" id="condiviso" class="custom-form-input">
                                        <option value="true" {{ $alias->condiviso == 'true' ? 'selected' : '' }}>Sì
                                        </option>
                                        <option value="false" {{ $alias->condiviso == 'false' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            @else
                                <p class="text-center fs-3">Sono passati 3 o più giorni</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Unico pulsante di conferma -->
            <div class="text-center mb-4">
                <button type="submit" class="custom-btn-submit w-50">Conferma tutte le modifiche</button>
            </div>
        </form>
        <!-- Pulsante di Ritorno -->
        <div class="text-center">
            <a class="custom-link-btn"
                href="{{ Auth::user()->is_admin ? route('admin.group.details', $alias->group) : route('trainer.group') }}">Indietro</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const value = searchInput.value.toLowerCase();
                document.querySelectorAll('#studentsList label').forEach(function(label) {
                    label.style.display = label.textContent.toLowerCase().includes(value) ? '' :
                        'none';
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleziona tutte le colonne con la classe .equal-height-card
            const columns = document.querySelectorAll('.equal-height-card');

            // Controlla se ci sono colonne nella pagina
            if (columns.length > 0) {
                // Trova l'altezza massima tra tutte le colonne
                let maxHeight = 0;
                columns.forEach(column => {
                    maxHeight = Math.max(maxHeight, column.offsetHeight);
                });

                // Applica l'altezza massima a tutte le colonne
                columns.forEach(column => {
                    column.style.height = `${maxHeight}px`;
                });
            }
        });
    </script>
</x-layout>
