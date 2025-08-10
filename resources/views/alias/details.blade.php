<x-layout documentTitle="Dettagli Alias">
        <ul class="nav nav-tabs admin-nav-tabs z-3 pt-5 pt-md-3" role="navigation" aria-label="Navigazione amministrazione">
            @if (Auth::check() && Auth::user()->is_admin)
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Gruppi</a>
                </li>
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Allenatori</a>
                </li>
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link"
                        href="{{ route('admin.dashboard.student', session('student_filters', [])) }}">Corsisti</a>
                </li>
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('admin.week') }}">Settimana</a>
                </li>
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
                </li>
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
                </li>
            @endif
            @if (Auth::check() && Auth::user()->is_trainer)
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('trainer.dashboard') }}">Settimana</a>
                </li>
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('trainer.group') }}">Gruppi</a>
                </li>
                <li class="nav-item admin-nav-item mt-3">
                    <a class="nav-link" href="{{ route('trainer.salary') }}">Compensi</a>
                </li>
            @endif
        </ul>

    <main class="container mt-5 pt-5" id="main-content">
        <header class="pt-5 pt-md-0">
            <h1 class="custom-title text-center mb-3 mt-5 pt-3">Dettagli del Gruppo</h1>
        </header>
        @if (session('success'))
            <div class="alert alert-dismissible custom-alert-success" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-dismissible alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('trainer.update.all', $alias) }}" aria-label="Form modifica alias">
            @csrf
            <div class="row my-5 justify-content-center">
                {{-- Colonna: Dettagli gruppo alias --}}
                <div class="col-11 col-md-3">
                    <h2 class="custom-subtitle text-center mb-4">Dettagli</h2>
                    <div class="custom-card equal-height-card mx-1 my-2" aria-labelledby="Dettagli gruppo">
                        <div class="custom-card-body">
                            <h3 class="card-title">{{ $alias->nome }}</h3>
                            <p class="card-text">Sede: <span class="text-uppercase">{{ $alias->location }}</span></p>
                            <p class="custom-date card-subtitle mb-2">
                                {{ $alias->formatData($alias->data_allenamento) }}
                            </p>
                            <p class="custom-paragraph"><strong>Tipo:</strong> {{ $alias->tipo }}</p>
                            <p class="custom-paragraph"><strong>Orario:</strong>
                                {{ $alias->formatHours($alias->orario) }}</p>

                            <!-- Corsisti e recuperi -->
                            <div class="row border rounded-3" role="list">
                                <div class="col-6 p-0 border-end">
                                    <p class="my-0 py-2 card-text border-bottom"><strong>Corsisti:</strong></p>
                                    @foreach ($alias->group->users as $student)
                                        <p
                                            class="my-0 py-1 card-text border-bottom {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                            {{ $student->name }} {{ $student->cognome }}
                                        </p>
                                    @endforeach
                                </div>
                                <div class="col-6 p-0">
                                    <p class="my-0 py-2 card-text border-bottom"><strong>Recuperi:</strong></p>
                                    @foreach ($alias->compareStudents($alias->group->id, $alias->id) as $recupero)
                                        @if (!in_array($recupero->id, $alias->group->studenti_id))
                                            <p class="my-0 py-1 card-text border-bottom">
                                                {{ $recupero->name }} {{ $recupero->cognome }}
                                            </p>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Colonna: Segna assenze studenti --}}
                <div class="col-11 col-md-3">
                    <h2 class="custom-subtitle text-center mb-4">Segna Assenze</h2>
                    <div class="custom-card equal-height-card mx-1 my-2" aria-labelledby="Segna assenze">
                        <div class="custom-card-body">
                            @if ($threeDaysCheck || Auth::user()->is_admin)
                                <div class="boxesTrainer container mt-2">
                                    <div class="row justify-content-center">
                                        <div class="col-12 list-group">
                                            @foreach ($alias->group->users as $student)
                                                <label class="checkbox" for="absence_{{ $student->id }}">
                                                    <input id="absence_{{ $student->id }}" type="checkbox"
                                                        class="form-check-input me-1 ms-4" name="student_absences[]"
                                                        value="{{ $student->id }}"
                                                        {{ in_array($student->id, $alias->studenti_id) ? '' : 'checked' }}>
                                                    {{ $student->name }} {{ $student->cognome }}
                                                </label>
                                            @endforeach

                                            @foreach ($alias->users as $student)
                                                <input type="hidden" name="all_students[]"
                                                    value="{{ $student->id }}">
                                                @if (!in_array($student->id, $alias->group->studenti_id))
                                                    <label class="checkbox" for="absence_extra_{{ $student->id }}">
                                                        <input id="absence_extra_{{ $student->id }}"
                                                            class="form-check-input me-1 ms-4" type="checkbox"
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
                {{-- Colonna: Segna Recuperi --}}
                <div class="col-11 col-md-3">
                    <h2 class="custom-subtitle text-center mb-4">Segna Recuperi</h2>
                    <div class="custom-card equal-height-card mx-1 my-2 d-flex" aria-labelledby="Segna recuperi">
                        <div class="custom-card-body">
                            @if ($threeDaysCheck || Auth::user()->is_admin)
                                <label for="searchInput" class="form-label visually-hidden">Cerca corsisti</label>
                                <input type="text" id="searchInput" class="custom-form-input mb-3"
                                    placeholder="Cerca corsisti...">
                                <div id="studentsList" class="list-group custom-scrollable-list" role="listbox">
                                    @foreach (Auth::user()->getRecoverableStudent($alias) as $student)
                                        <label class="checkbox" for="recovery_{{ $student->id }}">
                                            <input id="recovery_{{ $student->id }}" class="form-check-input me-1"
                                                type="checkbox" value="{{ $student->id }}"
                                                name="student_recoveries[]">
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
                {{-- Colonna: Allenatori --}}
                <div class="col-11 col-md-3">
                    <h2 class="custom-subtitle text-center mb-4">Allenatori</h2>
                    <div class="custom-card equal-height-card mx-1 my-2">
                        <div class="custom-card-body">
                            @if ($threeDaysCheck || Auth::user()->is_admin)
                                {{-- Primo Allenatore --}}
                                <div class="mb-3">
                                    <label for="primo_allenatore_id" class="custom-form-label">Primo
                                        Allenatore</label>
                                    <select name="primo_allenatore_id" id="primo_allenatore_id"
                                        class="custom-form-input" aria-label="Seleziona il primo allenatore">
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
                                        class="custom-form-input" aria-label="Seleziona il secondo allenatore">
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
                                    <select name="condiviso" id="condiviso" class="custom-form-input"
                                        aria-label="Seleziona se condiviso">
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
            <!-- Pulsante di conferma -->
            <div class="text-center mb-4">
                <button type="submit" class="custom-btn-submit w-50" aria-label="Conferma tutte le modifiche">
                    Conferma tutte le modifiche
                </button>
            </div>
        </form>

        <!-- Pulsante di ritorno -->
        <div class="text-center">
            <a class="custom-link-btn"
                href="{{ Auth::user()->is_admin ? route('admin.group.details', $alias->group) : route('trainer.group') }}"
                aria-label="Torna alla pagina precedente">
                Indietro
            </a>
        </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.setAttribute('aria-label', 'Cerca corsisti per recupero');
                    searchInput.addEventListener('keyup', function() {
                        const value = searchInput.value.toLowerCase();
                        document.querySelectorAll('#studentsList label').forEach(function(label) {
                            label.style.display = label.textContent.toLowerCase().includes(value) ? '' :
                                'none';
                        });
                    });
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Rende tutte le card della stessa altezza
                const cards = document.querySelectorAll('.equal-height-card');
                if (cards.length > 0) {
                    let maxHeight = 0;
                    cards.forEach(card => {
                        card.style.height = 'auto'; // reset temporaneo
                        maxHeight = Math.max(maxHeight, card.offsetHeight);
                    });
                    cards.forEach(card => {
                        card.style.height = `${maxHeight}px`;
                    });
                }
            });
        </script>
</x-layout>
