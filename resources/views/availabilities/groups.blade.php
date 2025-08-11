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

            @if ($groups->isEmpty())
                <div class="alert alert-info mt-5" role="status" aria-live="polite">Nessun gruppo trovato</div>
            @endif

            @foreach ($groups as $g)
                @php
                    $info = \App\Support\WeeklySlot::fromKey((int) $g->slot_key);
                    $day = $dayNames[$info['day_of_week']] ?? $info['day_of_week'];
                    $time = $info['time']; // HH:MM
                    $tot = $g->count;

                    // ID univoci per caption e intestazioni di colonna
                    $slotKey = $g->slot_key;
                    $tblId = 'tbl_' . $slotKey . '_' . $g->livello;
                    $capId = 'cap_' . $slotKey . '_' . $g->livello;
                    $colStudentId = 'col_student_' . $slotKey . '_' . $g->livello;
                    $colLevelId = 'col_level_' . $slotKey . '_' . $g->livello;
                    $colCountId = 'col_count_' . $slotKey . '_' . $g->livello;
                    $colNotesId = 'col_notes_' . $slotKey . '_' . $g->livello;
                @endphp

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        id="hdr_{{ $tblId }}">
                        <div>
                            <strong>{{ $day }} {{ $time }}</strong>
                            <span class="ms-2 badge text-bg-secondary" aria-label="Livello">Livello
                                {{ $g->livello }}</span>
                        </div>
                        <span class="badge text-bg-primary">{{ $tot }}
                            studente{{ $tot > 1 ? 'i' : '' }}</span>
                    </div>

                    <div class="card-body">
                        <table id="{{ $tblId }}"
                            class="table table-bordered admin-student-table mb-0 align-middle"
                            aria-labelledby="hdr_{{ $tblId }}" aria-describedby="{{ $capId }}"
                            style="table-layout: fixed; width: 100%;">
                            <caption id="{{ $capId }}" class="visually-hidden">
                                Elenco studenti con disponibilità {{ $day }} alle {{ $time }} per
                                livello {{ $g->livello }}
                            </caption>
                            <thead>
                                <tr>
                                    <th scope="col" id="{{ $colStudentId }}" style="width: 30%">Studente</th>
                                    <th scope="col" id="{{ $colLevelId }}"
                                        style="width: 10%; white-space: nowrap;">Lvl</th>
                                    <th scope="col" id="{{ $colCountId }}" aria-label="Numero disponibilità"
                                        style="width: 20%; white-space: nowrap;">#disp</th>
                                    <th scope="col" id="{{ $colNotesId }}" class="notes">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($g->items as $row)
                                    <tr>
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
                                        <td headers="{{ $colLevelId }}">{{ $row->user->livello }}</td>
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
