<x-layout documentTitle="Disponibilità studente">
    <main>
        <h1 class="visually-hidden">Pagina disponibilità studente</h1>

        <div class="container mt-5 pt-5">
            <div class="row justify-content-center mt-4">
                <div class="col-12 col-md-10">
                    <div class="card custom-card">
                        <div class="card-header custom-card-header fs-4" id="availability-title">
                            Disponibilità – {{ $user->name }} {{ $user->cognome }}
                        </div>

                        <div class="card-body custom-card-body">
                            @if (session('success'))
                                <div class="alert alert-success" role="status" aria-live="polite" aria-atomic="true">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert" aria-live="assertive" aria-atomic="true">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('student.availabilities.update', $user) }}"
                                aria-labelledby="availability-title">
                                @csrf

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-3" style="table-layout: fixed;"
                                        aria-describedby="slots_caption">
                                        <caption id="slots_caption" class="visually-hidden">
                                            Seleziona le disponibilità per giorno della settimana e orario
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" id="col_day">Giorno</th>
                                                @foreach ($timeSlots as $time)
                                                    @php $colId = 'col_time_' . str_replace(':','', $time); @endphp
                                                    <th scope="col" id="{{ $colId }}" class="text-center">
                                                        {{ $time }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($days as $dayValue => $dayLabel)
                                                @php $rowId = 'row_day_' . $dayValue; @endphp
                                                <tr>
                                                    <th scope="row" id="{{ $rowId }}">
                                                        <strong>{{ $dayLabel }}</strong></th>
                                                    @foreach ($timeSlots as $time)
                                                        @php
                                                            [$h, $m] = explode(':', $time);
                                                            $mins = (int) $h * 60 + (int) $m;
                                                            $preKey = $dayValue * 1440 + $mins;
                                                            $cid =
                                                                'slot_' . $dayValue . '_' . str_replace(':', '', $time);
                                                            $colId = 'col_time_' . str_replace(':', '', $time);
                                                        @endphp
                                                        <td class="text-center">
                                                            <div class="form-check d-inline-block m-0">
                                                                <input id="{{ $cid }}" type="checkbox"
                                                                    class="form-check-input" name="slots[]"
                                                                    value="{{ $dayValue }}|{{ $time }}"
                                                                    {{ in_array($preKey, $selectedKeys ?? []) ? 'checked' : '' }}
                                                                    aria-labelledby="{{ $rowId }} {{ $colId }} {{ $cid }}_label">
                                                                <label id="{{ $cid }}_label"
                                                                    for="{{ $cid }}" class="visually-hidden">
                                                                    {{ $dayLabel }} alle {{ $time }}
                                                                </label>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="availability_count">
                                            Numero disponibilità (per slot)
                                        </label>
                                        @php $acErrId = 'availability_count_error'; @endphp
                                        <input id="availability_count" type="number" name="availability_count"
                                            class="custom-form-input @error('availability_count') is-invalid @enderror"
                                            value="{{ old('availability_count', $prefillCount ?? 1) }}" min="0"
                                            step="1" inputmode="numeric"
                                            @error('availability_count') aria-invalid="true" aria-describedby="{{ $acErrId }}" @enderror>
                                        @if (!empty($hasMixed['count']))
                                            <div class="form-text">Sono presenti valori diversi nei vari slot salvati.
                                            </div>
                                        @endif
                                        @error('availability_count')
                                            <div id="{{ $acErrId }}" class="custom-invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label" for="notes">Note</label>
                                        @php $notesErrId = 'notes_error'; @endphp
                                        <textarea id="notes" name="notes" class="custom-form-input @error('notes') is-invalid @enderror" rows="3"
                                            autocomplete="off" @error('notes') aria-invalid="true" aria-describedby="{{ $notesErrId }}" @enderror>{{ old('notes', $prefillNotes) }}</textarea>
                                        @if (!empty($hasMixed['notes']))
                                            <div class="form-text">Sono presenti note diverse nei vari slot salvati.
                                            </div>
                                        @endif
                                        @error('notes')
                                            <div id="{{ $notesErrId }}" class="custom-invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4 d-flex gap-2">
                                    <button type="submit" class="btn custom-btn-submit"
                                        aria-label="Salva le disponibilità">
                                        Salva
                                    </button>
                                    <a class="btn btn-secondary" href="{{ route('admin.student.details', $user) }}">
                                        Indietro
                                    </a>
                                </div>
                            </form>
                        </div> <!-- /card-body -->
                    </div> <!-- /card -->
                </div> <!-- /col -->
            </div> <!-- /row -->
        </div> <!-- /container -->
    </main>
</x-layout>
