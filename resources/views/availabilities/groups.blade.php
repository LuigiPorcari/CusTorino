<x-layout documentTitle="Gruppi disponibilità">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-5 pt-md-3" role="navigation" aria-label="Navigazione amministrativa">
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('admin.dashboard') }}" aria-label="Pagina gruppi">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}"
                aria-label="Pagina allenatori">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('admin.dashboard.student', session('student_filters', [])) }}"
                aria-label="Pagina corsisti">Corsisti</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('admin.week') }}" aria-label="Pagina settimana">Settimana</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" aria-current="page"
                href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
        </li>
        <li class="nav-item admin-nav-item mt-3" role="presentation">
            <a class="nav-link" href="{{ route('logs.index') }}" aria-label="Pagina log">Log</a>
        </li>
    </ul>

    <main>
        <h1 class="visually-hidden">Gruppi per disponibilità - area amministrativa</h1>

        <div class="container mt-md-5 admin-student-dashboard">
            <div class="mt-4 pt-1 pt-md-0">
                <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Gruppi per disponibilità</h2>
            </div>

            {{-- Filtro minimo membri (GET) - lasciato commentato come nel tuo codice --}}
            {{-- <form method="GET" action="{{ route('admin.availabilities.groups') }}" id="filterForm" class="mb-4">
                <fieldset class="mb-0 admin-student-filter">
                    <legend class="visually-hidden">Filtro numero minimo membri per gruppo</legend>
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                            <div class="w-100">
                                <label for="min" class="visually-hidden">Min membri</label>
                                <input type="number" name="min" id="min" min="1" value="{{ $min }}" class="custom-form-input shadow-lg" placeholder="Min membri" aria-label="Min membri">
                            </div>
                            <div class="mb-2 mb-md-0">
                                <button type="submit" class="btn admin-btn-info">Filtra</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form> --}}

            @if ($groups->isEmpty())
                <div class="alert alert-info mt-5" role="status" aria-live="polite">Nessun gruppo trovato</div>
            @endif

            @foreach ($groups as $slotKey => $items)
                @php
                    $info = \App\Support\WeeklySlot::fromKey((int) $slotKey);
                    $day = $dayNames[$info['day_of_week']] ?? $info['day_of_week'];
                    $time = $info['time']; // HH:MM
                    $tot = $items->count();

                    // ID univoci per caption e intestazioni di colonna
                    $tblId = 'tbl_' . $slotKey;
                    $capId = 'cap_' . $slotKey;
                    $colStudentId = 'col_student_' . $slotKey;
                    $colCountId = 'col_count_' . $slotKey;
                    $colNotesId = 'col_notes_' . $slotKey;
                @endphp

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        id="hdr_{{ $slotKey }}">
                        <strong>{{ $day }} {{ $time }}</strong>
                        <span class="badge text-bg-primary">{{ $tot }}
                            studente{{ $tot > 1 ? 'i' : '' }}</span>
                    </div>

                    <div class="card-body">
                        {{-- Nessun wrapper con overflow: la tabella non scrollerà. --}}
                        <table id="{{ $tblId }}"
                            class="table table-bordered admin-student-table mb-0 align-middle"
                            aria-labelledby="hdr_{{ $slotKey }}" aria-describedby="{{ $capId }}"
                            style="table-layout: fixed; width: 100%;">
                            <caption id="{{ $capId }}" class="visually-hidden">
                                Elenco studenti con disponibilità {{ $day }} alle {{ $time }}
                            </caption>
                            <thead>
                                <tr>
                                    <th scope="col" id="{{ $colStudentId }}" style="width: 35%">Studente</th>
                                    <th scope="col" id="{{ $colCountId }}" style="width: 15%">#</th>
                                    <th scope="col" id="{{ $colNotesId }}" class="notes">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $row)
                                    <tr>
                                        {{-- Intestazione di riga (semantica), stile invariato con fw-normal --}}
                                        <th scope="row" class="fw-normal" headers="{{ $colStudentId }}">
                                            @php
                                                $nome = trim(
                                                    ($row->user->name ?? '') . ' ' . ($row->user->cognome ?? ''),
                                                );
                                                if ($nome === '') {
                                                    $nome = $row->user->email ?? 'N/D';
                                                }
                                            @endphp
                                            {{ $nome }}
                                        </th>
                                        <td headers="{{ $colCountId }}">{{ $row->availability_count }}</td>
                                        <td class="notes text-wrap text-break" headers="{{ $colNotesId }}">
                                            {{ $row->notes }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
</x-layout>
