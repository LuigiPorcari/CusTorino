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
    </ul>

    @if (session('success'))
        <div class="alert alert-dismissible custom-alert-success">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-md-5 admin-student-dashboard">
        <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Corsisti</h2>
        <!-- Filtro Studenti -->
        <div class="mb-4 admin-student-filter">
            <div class="row">
                <!-- Filtro per Nome e Cognome -->
                <div class="col-md-4 my-auto">
                    <input type="search" id="student_name" class="custom-form-input shadow-lg" placeholder="Nome o Cognome">
                    <select id="group_filter" class="custom-form-input">
                        <option value="">Tutti i Gruppi</option>
                        <option value="no_group">Non è iscritto a nessun gruppo</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->nome }}">{{ $group->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Checkbox per CUS Card -->
                <div class="col-6 col-md-2">
                    <div class="filter-box shadow-lg">
                        <p class="fw-bold">CUS Card</p>
                        <label for="cus_card_filter_ok">
                            <input type="checkbox" id="cus_card_filter_ok" value="1"> OK
                        </label>
                        <label for="cus_card_filter_nonok">
                            <input type="checkbox" id="cus_card_filter_nonok" value="0"> NonOK
                        </label>
                    </div>
                </div>

                <!-- Checkbox per Visita Medica -->
                <div class="col-6 col-md-2">
                    <div class="filter-box shadow-lg">
                        <p class="fw-bold">Visita Medica</p>
                        <label for="visita_medica_filter_ok">
                            <input type="checkbox" id="visita_medica_filter_ok" value="1"> OK
                        </label>
                        <label for="visita_medica_filter_nonok">
                            <input type="checkbox" id="visita_medica_filter_nonok" value="0"> NonOK
                        </label>
                    </div>
                </div>

                <!-- Checkbox per Pagamento -->
                <div class="col-6 col-md-2">
                    <div class="filter-box shadow-lg">
                        <p class="fw-bold">Pagamento</p>
                        <label for="pagamento_filter_ok">
                            <input type="checkbox" id="pagamento_filter_ok" value="1"> OK
                        </label>
                        <label for="pagamento_filter_nonok">
                            <input type="checkbox" id="pagamento_filter_nonok" value="0"> NonOK
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive admin-table-responsive">
            <table class="table table-bordered admin-student-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-md-table-cell">CUS Card</th>
                        <th class="d-none d-md-table-cell">Visita Medica</th>
                        <th class="d-none d-md-table-cell">Pagamento</th>
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
                            <td class="d-none d-md-table-cell">{{ $student->email }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->cus_card ? 'OK' : 'NonOK' }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->visita_medica ? 'OK' : 'NonOK' }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->pagamento ? 'OK' : 'NonOK' }}</td>
                            <td class="d-none d-md-table-cell">{{ $student->Nrecuperi }}</td>
                            <td class="d-none d-md-table-cell">
                                @forelse ($student->groups as $group)
                                    <p class="m-1">{{ $group->nome }}</p>
                                @empty
                                    <p>Non è iscritto a nessun gruppo</p>
                                @endforelse
                            </td>
                            <td><a class="btn admin-btn-info" href="{{ route('admin.student.details', $student) }}">Visualizza dettagli</a></td>
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
        document.querySelectorAll(
            '#student_name, #pagamento_filter_ok, #pagamento_filter_nonok, #cus_card_filter_ok, #cus_card_filter_nonok, #visita_medica_filter_ok, #visita_medica_filter_nonok, #group_filter'
        ).forEach(function(input) {
            input.addEventListener('input', filterStudents);
        });

        function filterStudents() {
            const name = document.getElementById('student_name').value.toLowerCase();

            // Checkboxes for Pagamento
            const pagamentoOk = document.getElementById('pagamento_filter_ok').checked;
            const pagamentoNonOk = document.getElementById('pagamento_filter_nonok').checked;

            // Checkboxes for CUS Card
            const cusCardOk = document.getElementById('cus_card_filter_ok').checked;
            const cusCardNonOk = document.getElementById('cus_card_filter_nonok').checked;

            // Checkboxes for Visita Medica
            const visitaMedicaOk = document.getElementById('visita_medica_filter_ok').checked;
            const visitaMedicaNonOk = document.getElementById('visita_medica_filter_nonok').checked;

            // Group filter
            const selectedGroup = document.getElementById('group_filter').value.toLowerCase();

            let visibleRows = 0; // Contatore per righe visibili

            // Nascondi la riga "Non ci sono corsisti disponibili"
            const noResultsRow = document.getElementById('no_students_row');
            if (noResultsRow) {
                noResultsRow.remove();
            }

            // Filtro delle righe visibili
            document.querySelectorAll('#student_table_body tr').forEach(function(row) {
                const rowName = row.dataset.name.toLowerCase();
                const rowCognome = row.dataset.cognome.toLowerCase();
                const rowPagamento = row.dataset.pagamento;
                const rowCusCard = row.dataset.cusCard;
                const rowVisitaMedica = row.dataset.visitaMedica;
                const rowGroups = row.dataset.groups.toLowerCase();

                const matchesName = !name || rowName.includes(name) || rowCognome.includes(name) || (rowName + ' ' +
                    rowCognome).includes(name);

                const matchesPagamento = (!pagamentoOk && !pagamentoNonOk) ||
                    (pagamentoOk && rowPagamento === '1') ||
                    (pagamentoNonOk && rowPagamento === '0');

                const matchesCusCard = (!cusCardOk && !cusCardNonOk) ||
                    (cusCardOk && rowCusCard === '1') ||
                    (cusCardNonOk && rowCusCard === '0');

                const matchesVisitaMedica = (!visitaMedicaOk && !visitaMedicaNonOk) ||
                    (visitaMedicaOk && rowVisitaMedica === '1') ||
                    (visitaMedicaNonOk && rowVisitaMedica === '0');

                const matchesGroup = !selectedGroup ||
                    (selectedGroup === 'no_group' && rowGroups.trim() === '') ||
                    (selectedGroup !== 'no_group' && rowGroups.includes(selectedGroup));

                if (matchesName && matchesPagamento && matchesCusCard && matchesVisitaMedica && matchesGroup) {
                    row.style.display = '';
                    visibleRows++; // Incrementa il contatore se la riga è visibile
                } else {
                    row.style.display = 'none';
                }
            });

            // Se nessuna riga è visibile, aggiungi il messaggio "Non ci sono corsisti disponibili"
            if (visibleRows === 0) {
                const noResults = document.createElement('tr');
                noResults.id = 'no_students_row';
                noResults.innerHTML = '<td colspan="10" class="text-center">Non ci sono corsisti disponibili</td>';
                document.getElementById('student_table_body').appendChild(noResults);
            }
        }
    </script>
</x-layout>
