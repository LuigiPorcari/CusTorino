<x-layout documentTitle="Create groups Student">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0" role="navigation" aria-label="Navigazione amministrativa">
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
            <a class="nav-link" href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
    </ul>

    <main class="container mt-5 pt-5" id="main-content">
        <header class="pt-5 pt-md-0 text-center">
            <h1 class="custom-title mb-4 mt-5 pt-4">Lista Corsisti</h1>
        </header>
        @if ($errors->any())
            <div class="alert alert-dismissible custom-alert-success" role="alert" aria-live="polite">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-12 col-md-5 custom-card p-3 me-2">
                <label for="searchInput" class="visually-hidden">Cerca corsisti</label>
                <input type="text" id="searchInput" class="custom-form-input mb-3" placeholder="Cerca corsisti..."
                    aria-label="Cerca corsisti">

                <form method="POST" action="{{ route('create.student', $group) }}">
                    @csrf
                    <div id="studentsList" class="list-group" role="group" aria-label="Lista corsisti disponibili">
                        @foreach ($studentsAvaiable as $student)
                            <label class="list-group-item" for="student-{{ $student->id }}">
                                <input class="form-check-input me-1" type="checkbox" id="student-{{ $student->id }}"
                                    value="{{ $student->id }}" name="studenti_id[]"
                                    @if ($group->users->contains($student)) checked @endif>
                                {{ $student->name }} {{ $student->cognome }}
                            </label>
                        @endforeach
                    </div>
            </div>
            <div class="col-12 col-md-5 custom-card p-3 ms-2">
                <div class="mb-5">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="selectAllDates" name="all_dates"
                            value="1" checked>
                        <label for="selectAllDates" class="form-check-label fs-4">Tutte le date</label>
                    </div>
                </div>

                <div class="mb-2">
                    <label for="startDate" class="form-label fw-bold">Data inizio modifica:</label>
                    <select id="startDate" name="start_date" class="custom-form-input">
                        <option value="">Seleziona una data</option>
                        @foreach ($possibleDates as $date)
                            <option value="{{ $date }}">
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="endDate" class="form-label fw-bold">Data fine modifica:</label>
                    <select id="endDate" name="end_date" class="custom-form-input">
                        <option value="">Seleziona una data</option>
                        @foreach ($possibleDates as $date)
                            <option value="{{ $date }}">
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 text-center col-12 col-md-10">
                <button type="submit" class="custom-btn-submit mt-3 w-100">Salva</button>
                <a class="btn admin-btn-info mt-3" href="{{ route('admin.group.details', $group) }}">Indietro</a>
            </div>
            </form>
        </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const searchInput = document.getElementById("searchInput");
                const studentLabels = document.querySelectorAll("#studentsList label");

                searchInput.addEventListener("keyup", function() {
                    const searchValue = this.value.toLowerCase();
                    studentLabels.forEach(function(label) {
                        const text = label.textContent.toLowerCase();
                        label.style.display = text.includes(searchValue) ? "block" : "none";
                    });
                });
            });
        </script>
</x-layout>
