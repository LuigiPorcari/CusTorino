<x-layout documentTitle="Admin Student">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-5 pt-md-3" role="navigation" aria-label="Navigazione amministrazione">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.dashboard') }}"
                aria-label="Pagina gruppi">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}"
                aria-label="Pagina allenatori">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student', session('student_filters', [])) }}"
                aria-label="Pagina corsisti">Corsisti</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" aria-current="page" href="{{ route('admin.week') }}"
                aria-label="Pagina settimana">Settimana</a>
        </li>
        {{-- <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.availabilities.groups') }}">Disponibilità</a>
        </li> --}}
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('logs.index') }}" aria-label="Pagina log">Log</a>
        </li>
    </ul>
    <main>
        <h1 class="visually-hidden">Gestione corsisti - area amministrativa</h1>
        <div class="container mt-md-5 admin-student-dashboard">
            <div class="mt-4 pt-1 pt-md-0">
                <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Corsisti</h2>
            </div>
            @if (session('success'))
                <div class="alert alert-dismissible custom-alert-success" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Chiudi"></button>
                </div>
            @endif

            <!-- Form per inviare i filtri al server -->
            <form id="filterForm" method="GET" action="{{ route('admin.dashboard.student') }}">
                <fieldset class="mb-4 admin-student-filter">
                    <legend class="visually-hidden">Filtri di ricerca corsisti</legend>
                    <div class="row justify-content-center">
                        <!-- Filtro per Nome e Cognome -->
                        <div class="col-12 col-md-4 my-auto">
                            <label for="student_name" class="visually-hidden">Nome o Cognome</label>
                            <input type="search" name="student_name" id="student_name"
                                class="custom-form-input shadow-lg" placeholder="Nome o Cognome"
                                value="{{ request('student_name') }}" aria-label="Filtro per nome o cognome">

                            <label for="group_name" class="visually-hidden">Gruppi</label>
                            <input type="search" name="group_name" id="group_name" class="custom-form-input shadow-lg"
                                placeholder="Gruppi" value="{{ request('group_name') }}"
                                aria-label="Filtro per nome gruppo">
                        </div>

                        <!-- Filtro per livello mobile -->
                        <div class="col-12 col-md-4 d-md-none d-block mb-2">
                            <label for="student_level_mobile" class="visually-hidden">Livello</label>
                            <input type="number" name="student_level_mobile" id="student_level_mobile"
                                class="custom-form-input shadow-lg d-md-none d-block" placeholder="Livello"
                                value="{{ request('student_level_mobile') }}" min="1" max="12"
                                step="1" aria-label="Livello mobile">
                            <div class="form-check mt-1">
                                <input type="checkbox" id="no_level_mobile" name="no_level" value="1"
                                    class="form-check-input" {{ request('no_level') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="no_level_mobile">Senza Livello</label>
                            </div>
                        </div>

                        <!-- Checkbox gruppo -->
                        <div class="col-12 col-md-3">
                            <fieldset class="filter-box shadow-lg" aria-labelledby="gruppiLabel">
                                <legend id="gruppiLabel" class="fw-bold">Gruppi</legend>
                                <input type="hidden" name="no_group" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="no_group" id="no_group_filter" value="1"
                                        class="form-check-input" {{ request('no_group') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="no_group_filter">Iscritto a nessun
                                        Gruppo</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="group_enrolled" id="group_enrolled_filter"
                                        value="1" class="form-check-input"
                                        {{ request('group_enrolled') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="group_enrolled_filter">Iscritto a un
                                        Gruppo</label>
                                </div>
                            </fieldset>
                        </div>
                        <!-- Checkbox per CUS Card -->
                        <div class="col-12 col-md-2">
                            <fieldset class="filter-box shadow-lg" aria-labelledby="cusCardLabel">
                                <legend id="cusCardLabel" class="fw-bold">CUS Card</legend>
                                <input type="hidden" name="cus_card_ok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="cus_card_ok" id="cus_card_ok" value="1"
                                        class="form-check-input" {{ request('cus_card_ok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cus_card_ok">OK</label>
                                </div>
                                <input type="hidden" name="cus_card_nonok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="cus_card_nonok" id="cus_card_nonok" value="1"
                                        class="form-check-input"
                                        {{ request('cus_card_nonok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cus_card_nonok">NonOK</label>
                                </div>
                            </fieldset>
                        </div>

                        <!-- Checkbox per Visita Medica -->
                        <div class="col-12 col-md-2">
                            <fieldset class="filter-box shadow-lg" aria-labelledby="visitaMedicaLabel">
                                <legend id="visitaMedicaLabel" class="fw-bold">Visita Medica</legend>
                                <input type="hidden" name="visita_medica_ok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="visita_medica_ok" id="visita_medica_ok"
                                        value="1" class="form-check-input"
                                        {{ request('visita_medica_ok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visita_medica_ok">OK</label>
                                </div>
                                <input type="hidden" name="visita_medica_nonok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="visita_medica_nonok" id="visita_medica_nonok"
                                        value="1" class="form-check-input"
                                        {{ request('visita_medica_nonok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="visita_medica_nonok">NonOK</label>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <!-- Livello desktop -->
                        <div class="col-12 col-md-4 d-none d-md-block">
                            <label for="student_level" class="visually-hidden">Livello</label>
                            <input type="number" name="student_level" id="student_level"
                                class="custom-form-input shadow-lg" placeholder="Livello"
                                value="{{ request('student_level') }}" min="1" max="12" step="1"
                                aria-label="Livello desktop">

                            <div class="form-check mt-1">
                                <input type="checkbox" id="no_level_desktop" name="no_level" value="1"
                                    class="form-check-input" {{ request('no_level') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="no_level_desktop">Senza Livello</label>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn admin-btn-info">Applica Filtri</button>
                                <a href="{{ route('admin.dashboard.student') }}" class="btn admin-btn-info"
                                    id="clearFilters">Pulisci Filtri</a>
                            </div>
                        </div>

                        <!-- Pagamento -->
                        <div class="col-12 col-md-3">
                            <fieldset class="filter-box shadow-lg" aria-labelledby="pagamentoLabel">
                                <legend id="pagamentoLabel" class="fw-bold">Pagamento</legend>
                                <input type="hidden" name="pagamento_ok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="pagamento_ok" id="pagamento_ok" value="1"
                                        class="form-check-input"
                                        {{ request('pagamento_ok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pagamento_ok">OK</label>
                                </div>
                                <input type="hidden" name="pagamento_nonok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="pagamento_nonok" id="pagamento_nonok"
                                        value="1" class="form-check-input"
                                        {{ request('pagamento_nonok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pagamento_nonok">NonOK</label>
                                </div>
                            </fieldset>
                        </div>

                        <!-- Trimestrale -->
                        <div class="col-12 col-md-2">
                            <fieldset class="filter-box shadow-lg" aria-labelledby="trimestraleLabel">
                                <legend id="trimestraleLabel" class="fw-bold">Trimestrale</legend>
                                <input type="hidden" name="trimestrale_ok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="trimestrale_ok" id="trimestrale_ok" value="1"
                                        class="form-check-input"
                                        {{ request('trimestrale_ok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="trimestrale_ok">Sì</label>
                                </div>
                                <input type="hidden" name="trimestrale_nonok" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="trimestrale_nonok" id="trimestrale_nonok"
                                        value="1" class="form-check-input"
                                        {{ request('trimestrale_nonok') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="trimestrale_nonok">No</label>
                                </div>
                            </fieldset>
                        </div>

                        <!-- Genere -->
                        <div class="col-12 col-md-2">
                            <fieldset class="filter-box shadow-lg" aria-labelledby="genereLabel">
                                <legend id="genereLabel" class="fw-bold">Genere</legend>
                                <input type="hidden" name="genere_m" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="genere_m" id="genere_m" value="M"
                                        class="form-check-input" {{ request('genere_m') === 'M' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genere_m">Maschi</label>
                                </div>
                                <input type="hidden" name="genere_f" value="0">
                                <div class="form-check">
                                    <input type="checkbox" name="genere_f" id="genere_f" value="F"
                                        class="form-check-input" {{ request('genere_f') === 'F' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genere_f">Femmine</label>
                                </div>
                            </fieldset>
                        </div>

                        <!-- Bottoni mobile -->
                        <div class="col-12 d-md-none d-block d-flex justify-content-center mt-3">
                            <button type="submit" class="btn admin-btn-info me-5">Applica Filtri</button>
                            <a href="{{ route('admin.dashboard.student') }}" class="btn admin-btn-info"
                                id="clearFilters">Pulisci Filtri</a>
                        </div>
                    </div>
                </fieldset>
            </form>
            {{-- TASTO PER RESET MASSIVO --}}
            {{-- <form method="POST" action="{{ route('admin.users.corsisti.reset-flags') }}" class="d-inline"
                onsubmit="return confirm('Questa azione imposta Universitario, Pagamento, Visita medica e CUS Card a NO per tutti i corsisti. Confermi?');">
                @csrf
                <button type="submit" class="btn btn-warning">
                    Reset flag corsisti
                </button>
            </form> --}}
            {{-- FINE TASTO PER RESET MASSIVO --}}
            <div class="table-responsive admin-table-responsive">
                <p class="fw-bold text-uppercase fs-4">Numero Universitari: {{ $uniCount }}</p>
                <table class="table table-bordered admin-student-table" aria-label="Elenco corsisti">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Cognome</th>
                            <th scope="col" class="d-none d-md-table-cell">CUS Card</th>
                            <th scope="col" class="d-none d-md-table-cell">Visita Medica</th>
                            <th scope="col" class="d-none d-md-table-cell">Pagamento</th>
                            <th scope="col" class="d-none d-md-table-cell">Trimestrale</th>
                            <th scope="col" class="d-none d-md-table-cell">Livello</th>
                            <th scope="col" class="d-none d-md-table-cell">Nrecuperi</th>
                            <th scope="col" class="d-none d-md-table-cell">Gruppi</th>
                            <th scope="col">Dettagli</th>
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
                                    @if ($student->livello === null)
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
                                <td>
                                    <a class="btn admin-btn-info"
                                        href="{{ route('admin.student.details', $student) }}"
                                        aria-label="Visualizza dettagli di {{ $student->name }} {{ $student->cognome }}">
                                        Visualizza dettagli
                                    </a>
                                </td>
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
            <script>
                // Invio automatico del form se il contenuto degli input viene cancellato
                document.querySelectorAll('#student_name, #group_name').forEach(function(input) {
                    input.addEventListener('input', function() {
                        if (this.value === '') {
                            document.getElementById('filterForm').submit();
                        }
                    });
                });

                // Pulisci filtri anche da localStorage
                document.addEventListener("DOMContentLoaded", function() {
                    const clearFiltersBtn = document.getElementById("clearFilters");
                    if (clearFiltersBtn) {
                        clearFiltersBtn.addEventListener("click", function() {
                            localStorage.removeItem("studentFilters");
                        });
                    }
                });
            </script>
    </main>
</x-layout>
