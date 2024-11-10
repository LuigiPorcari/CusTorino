<x-layout documentTitle="Admin Student">
    <ul class="nav nav-tabs admin-nav-tabs mt-5 pt-5 pt-md-0">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.student') }}">Corsisti</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
    </ul>

    <div class="container mt-md-5 admin-student-dashboard">
        <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Corsisti</h2>
        @if (session('success'))
            <div class="alert alert-dismissible custom-alert-success">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form per inviare i filtri al server -->
        <form id="filterForm" method="GET" action="{{ route('admin.dashboard.student') }}">
            <div class="mb-4 admin-student-filter">
                <div class="row justify-content-center">
                    <!-- Filtro per Nome e Cognome -->
                    <div class="col-6 col-md-4 my-auto">
                        <input type="search" name="student_name" id="student_name" class="custom-form-input shadow-lg"
                            placeholder="Nome o Cognome" value="{{ request('student_name') }}">
                        <input type="search" name="group_name" id="group_name" class="custom-form-input shadow-lg"
                            placeholder="Gruppi" value="{{ request('group_name') }}">
                    </div>

                    <!-- Checkbox per "Non iscritto a nessun gruppo" e "Iscritto a un gruppo" -->
                    <div class="col-6 col-md-3">
                        <div class="filter-box shadow-lg">
                            <p class="fw-bold">Gruppi</p>
                            <input type="hidden" name="no_group" value="0">
                            <label for="no_group_filter" class="mt-2">
                                <input type="checkbox" name="no_group" id="no_group_filter" value="1"
                                    {{ request('no_group') == '1' ? 'checked' : '' }}> Iscritto a nessun Gruppo
                            </label>
                            <label for="group_enrolled_filter" class="mt-2">
                                <input type="checkbox" name="group_enrolled" id="group_enrolled_filter" value="1"
                                    {{ request('group_enrolled') == '1' ? 'checked' : '' }}> Iscritto a un Gruppo
                            </label>
                        </div>
                    </div>

                    <!-- Checkbox per CUS Card -->
                    <div class="col-6 col-md-2">
                        <div class="filter-box shadow-lg">
                            <p class="fw-bold">CUS Card</p>
                            <input type="hidden" name="cus_card_ok" value="0">
                            <label>
                                <input type="checkbox" name="cus_card_ok" value="1"
                                    {{ request('cus_card_ok') == '1' ? 'checked' : '' }}> OK
                            </label>
                            <input type="hidden" name="cus_card_nonok" value="0">
                            <label>
                                <input type="checkbox" name="cus_card_nonok" value="1"
                                    {{ request('cus_card_nonok') == '1' ? 'checked' : '' }}> NonOK
                            </label>
                        </div>
                    </div>

                    <!-- Checkbox per Visita Medica -->
                    <div class="col-6 col-md-2">
                        <div class="filter-box shadow-lg">
                            <p class="fw-bold">Visita Medica</p>
                            <input type="hidden" name="visita_medica_ok" value="0">
                            <label>
                                <input type="checkbox" name="visita_medica_ok" value="1"
                                    {{ request('visita_medica_ok') == '1' ? 'checked' : '' }}> OK
                            </label>
                            <input type="hidden" name="visita_medica_nonok" value="0">
                            <label>
                                <input type="checkbox" name="visita_medica_nonok" value="1"
                                    {{ request('visita_medica_nonok') == '1' ? 'checked' : '' }}> NonOK
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <!-- Filtro per livello -->
                    <div class="col-6 col-md-4">
                        <div>
                            <input type="number" name="student_level" id="student_level"
                                class="custom-form-input shadow-lg" placeholder="Livello"
                                value="{{ request('student_level') }}" min="1" max="12" step="1">
                            <label>
                                <input type="checkbox" name="no_level" value="1"
                                    {{ request('no_level') == '1' ? 'checked' : '' }}> Senza Livello
                            </label>
                        </div>
                    </div>

                    <!-- Checkbox per Pagamento -->
                    <div class="col-6 col-md-3">
                        <div class="filter-box shadow-lg">
                            <p class="fw-bold">Pagamento</p>
                            <input type="hidden" name="pagamento_ok" value="0">
                            <label>
                                <input type="checkbox" name="pagamento_ok" value="1"
                                    {{ request('pagamento_ok') == '1' ? 'checked' : '' }}> OK
                            </label>
                            <input type="hidden" name="pagamento_nonok" value="0">
                            <label>
                                <input type="checkbox" name="pagamento_nonok" value="1"
                                    {{ request('pagamento_nonok') == '1' ? 'checked' : '' }}> NonOK
                            </label>
                        </div>
                    </div>

                    <!-- Checkbox per Trimestrale -->
                    <div class="col-6 col-md-2">
                        <div class="filter-box shadow-lg">
                            <p class="fw-bold">Trimestrale</p>
                            <input type="hidden" name="trimestrale_ok" value="0">
                            <label>
                                <input type="checkbox" name="trimestrale_ok" value="1"
                                    {{ request('trimestrale_ok') == '1' ? 'checked' : '' }}> Sì
                            </label>
                            <input type="hidden" name="trimestrale_nonok" value="0">
                            <label>
                                <input type="checkbox" name="trimestrale_nonok" value="1"
                                    {{ request('trimestrale_nonok') == '1' ? 'checked' : '' }}> No
                            </label>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 d-flex align-items-center justify-content-center">
                        <button type="submit" class="btn admin-btn-info">Applica Filtri</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive admin-table-responsive">
            <table class="table table-bordered admin-student-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th class="d-none d-md-table-cell">CUS Card</th>
                        <th class="d-none d-md-table-cell">Visita Medica</th>
                        <th class="d-none d-md-table-cell">Pagamento</th>
                        <th class="d-none d-md-table-cell">Trimestrale</th>
                        <th class="d-none d-md-table-cell">Livello</th>
                        <th class="d-none d-md-table-cell">Nrecuperi</th>
                        <th class="d-none d-md-table-cell">Gruppi</th>
                        <th>Dettagli</th>
                    </tr>
                </thead>
                <tbody id="student_table_body">
                    @forelse ($students as $student)
                        <tr data-name="{{ $student->name }}" data-cognome="{{ $student->cognome }}"
                            data-pagamento="{{ $student->pagamento ? '1' : '0' }}"
                            data-cus-card="{{ $student->cus_card ? '1' : '0' }}"
                            data-visita-medica="{{ $student->visita_medica ? '1' : '0' }}"
                            data-nrecuperi="{{ $student->Nrecuperi }}"
                            data-groups="@foreach ($student->groups as $group){{ $group->nome }}@if (!$loop->last), @endif @endforeach">
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->cognome }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->cus_card ? 'OK' : 'NonOK' }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->visita_medica ? 'OK' : 'NonOK' }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->pagamento ? 'OK' : 'NonOK' }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->trimestrale ? 'Sì' : 'No' }}</td>
                            <td class="d-none d-md-table-cell">
                                @if ($student->livello == null)
                                    N.C.
                                @else
                                    {{ $student->livello }}
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">{{ $student->Nrecuperi }}</td>
                            <td class="d-none d-md-table-cell">
                                @forelse ($student->groups as $group)
                                    <p class="m-1">{{ $group->nome }}</p>
                                @empty
                                    <p>Non è iscritto a nessun gruppo</p>
                                @endforelse
                            </td>
                            <td><a class="btn admin-btn-info"
                                    href="{{ route('admin.student.details', $student) }}">Visualizza dettagli</a></td>
                        </tr>
                    @empty
                        <tr id="no_students_row">
                            <td colspan="10" class="text-center">Non ci sono corsisti disponibili</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links('pagination::bootstrap-5') }}
    </div>

    <script>
        // Aggiungi evento di aggiornamento automatico quando si cancella il contenuto degli input
        document.querySelectorAll('#student_name, #group_name').forEach(function(input) {
            input.addEventListener('input', function() {
                if (this.value === '') {
                    document.getElementById('filterForm').submit(); // Invia il form automaticamente
                }
            });
        });
    </script>
</x-layout>
