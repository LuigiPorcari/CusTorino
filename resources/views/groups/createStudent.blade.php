<x-layout documentTitle="Create groups Student">
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
            <a class="nav-link" aria-current="page" href="{{ route('admin.week') }}">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
    </ul>
    <div class="container mt-5 pt-5">
        <div class="pt-5 pt-md-0">
            <h1 class="custom-title mb-4 mt-5 pt-4 text-center">Lista Corsisti</h1>
        </div>
        @if ($errors->any())
            <div class="alert alert-dismissible custom-alert-success">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                @endforeach
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-12 col-md-5 custom-card p-3 me-2">
                <input type="text" id="searchInput" class="custom-form-input mb-3" placeholder="Cerca corsisti...">
                <form method="POST" action="{{ route('create.student', $group) }}">
                    @csrf
                    <div id="studentsList" class="list-group">
                        @foreach ($studentsAvaiable as $student)
                            <label class="list-group-item">
                                <input @if ($group->users->contains($student)) checked @endif class="form-check-input me-1"
                                    type="checkbox" value="{{ $student->id }}" name="studenti_id[]">
                                {{ $student->name }} {{ $student->cognome }}
                            </label>
                        @endforeach
                    </div>
            </div>
            <div class="col-12 col-md-5 custom-card p-3 ms-2">
                <div class="mb-5">
                    <!-- Checkbox Tutte le Date -->
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input me-2 fs-3" id="selectAllDates" name="all_dates"
                            value="1" checked><span class="fs-4">Tutte le date</span>
                    </label>
                </div>
                <!-- Select Data Inizio Modifica -->
                <div class="mb-2">
                    <label for="startDate" class="form-label fw-bold">Data inizio modifica:</label>
                    <select id="startDate" name="start_date" class="custom-form-input">
                        <option value="">Seleziona una data</option>
                        @foreach ($possibleDates as $date)
                            <option value="{{ $date }}">
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Select Data Fine Modifica -->
                <div class="mb-3">
                    <label for="endDate" class="form-label fw-bold">Data fine modifica:</label>
                    <select id="endDate" name="end_date" class="custom-form-input">
                        <option value="">Seleziona una data</option>
                        @foreach ($possibleDates as $date)
                            <option value="{{ $date }}">
                                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F') }}</option>
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
    </div>

    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#studentsList label').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</x-layout>
