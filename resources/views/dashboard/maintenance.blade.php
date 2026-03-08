<x-layout documentTitle="Admin - Manutenzione">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-5 pt-md-3" role="navigation" aria-label="Navigazione amministrazione">
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
            <a class="nav-link" href="{{ route('logs.index') }}">Log</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link bg-danger text-white" href="{{ route('admin.maintenance') }}">Manutenzione</a>
        </li>
    </ul>

    <main>
        <h1 class="visually-hidden">Manutenzione database - area amministrativa</h1>
        <div class="container mt-md-5 admin-student-dashboard">

            {{-- HEADER --}}
            <div class="mb-5 mt-md-5">
                <h2 class="custom-title mb-2 mt-md-5">Manutenzione sistema</h2>
                <p class="text-muted">
                    Questa sezione contiene operazioni amministrative sul database.
                    Usare solo se necessario. Operazioni irreversibili.
                </p>
            </div>

            {{-- OPERAZIONI STANDARD --}}
            <section aria-label="Operazioni di manutenzione" class="mb-5">
                <h3 class="mb-4">Operazioni di manutenzione</h3>
                <div class="row g-4">
                    {{-- CARD PULIZIA LOG --}}
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card shadow-lg h-100 border-0">
                            <div class="card-body">
                                <h4 class="h5 fw-bold mb-2">Pulizia log</h4>
                                <p class="text-muted small mb-3">
                                    Elimina i log più vecchi di 4 mesi per ridurre il peso del database.
                                    Operazione irreversibile.
                                </p>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalPurgeLogs">
                                    Pulisci log (> 4 mesi)
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- CARD AZZERA RECUPERI --}}
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card shadow-lg h-100 border-0">
                            <div class="card-body">
                                <h4 class="h5 fw-bold mb-2">Azzera Nrecuperi</h4>
                                <p class="text-muted small mb-3">
                                    Imposta a 0 il contatore recuperi per tutti i corsisti.
                                    Operazione irreversibile.
                                </p>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalResetRecuperi">
                                    Azzera tutti i Nrecuperi
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- CARD RESET TRIMESTRALE --}}
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card shadow-lg h-100 border-0">
                            <div class="card-body">
                                <h4 class="h5 fw-bold mb-2">Reset trimestrale corsisti</h4>
                                <p class="text-muted small mb-3">
                                    Imposta “trimestrale = NO” per tutti i corsisti.
                                    Operazione irreversibile.
                                </p>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalResetTrimestrale">
                                    Reset trimestrale corsisti
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- DANGER ZONE --}}
            <section aria-label="Operazioni pericolose">
                <div class="p-4 rounded bg-danger bg-opacity-10 border border-danger">
                    <h3 class="text-danger fw-bold mb-4">Danger zone</h3>
                    <div class="row g-4">
                        {{-- ELIMINA GRUPPI --}}
                        <div class="col-12 col-md-6">
                            <div class="card shadow h-100 border-danger">
                                <div class="card-body">
                                    <h4 class="h5 fw-bold mb-2 text-danger">
                                        Eliminazione gruppi
                                    </h4>
                                    <p class="text-muted small mb-3">
                                        Elimina tutti i gruppi presenti nel sistema.
                                        Operazione irreversibile.
                                    </p>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalDeleteGroups">
                                        Elimina tutti i gruppi
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- RESET FLAG --}}
                        <div class="col-12 col-md-6">
                            <div class="card shadow h-100 border-danger">
                                <div class="card-body">
                                    <h4 class="h5 fw-bold mb-2 text-danger">
                                        Reset flag corsisti
                                    </h4>
                                    <p class="text-muted small mb-3">
                                        Resetta i flag principali (universitario, pagamento,
                                        visita medica, CUS card).
                                        Operazione irreversibile.
                                    </p>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalResetFlags">
                                        Reset flag corsisti
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- BLOCCO STATO OPERAZIONE (identico) --}}
            @if (!is_null($op))
                <section class="mb-4 mt-5" aria-label="Stato operazione">
                    <div id="op-root" data-op-id="{{ $op->id }}"
                        data-status-url="{{ route('admin.bulk-operations.status', ['id' => $op->id]) }}">
                    </div>
                    <div class="alert alert-info" role="alert" id="op-alert">
                        Operazione <strong>#{{ $op->id }}</strong> avviata.
                        Stato: <strong id="op-status">{{ $op->status }}</strong>
                    </div>
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <p><strong>Tipo:</strong> <span id="op-type">{{ $op->type }}</span></p>
                            <p><strong>Stato:</strong> <span id="op-status-2">{{ $op->status }}</span></p>
                            <p><strong>Righe interessate:</strong>
                                <span id="op-affected">{{ $op->affected_rows ?? '-' }}</span>
                            </p>
                            <p><strong>Inizio:</strong>
                                <span id="op-started">
                                    {{ $op->started_at ? \Carbon\Carbon::parse($op->started_at)->format('d/m/Y H:i:s') : '-' }}
                                </span>
                            </p>
                            <p><strong>Fine:</strong>
                                <span id="op-finished">
                                    {{ $op->finished_at ? \Carbon\Carbon::parse($op->finished_at)->format('d/m/Y H:i:s') : '-' }}
                                </span>
                            </p>
                            <hr>
                            <p class="mb-1"><strong>Messaggio:</strong></p>
                            <pre class="bg-light p-2 rounded mb-0" style="white-space: pre-wrap;" id="op-message">{{ $op->message ?? '—' }}</pre>
                        </div>
                    </div>

                </section>
            @endif
        </div>

        {{-- ===================== MODALI (con checkbox) ===================== --}}
        {{-- Modal: purge logs --}}
        <div class="modal fade" id="modalPurgeLogs" tabindex="-1" aria-labelledby="modalPurgeLogsLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="modalPurgeLogsLabel">Conferma pulizia log</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2"><strong>Attenzione:</strong> verranno eliminati tutti i log più vecchi di 4
                            mesi.</p>
                        <p class="mb-3">Operazione irreversibile.</p>

                        <div class="form-check">
                            <input class="form-check-input confirm-check" type="checkbox" id="chkPurgeLogs"
                                data-target-btn="btnPurgeLogs">
                            <label class="form-check-label" for="chkPurgeLogs">
                                Ho capito e voglio procedere
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn admin-btn-info" data-bs-dismiss="modal">Annulla</button>

                        <form method="POST" action="{{ route('admin.logs.purge_old') }}" class="d-inline">
                            @csrf
                            <button id="btnPurgeLogs" type="submit" class="btn btn-danger" disabled>
                                Confermo: pulisci log
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal: delete groups --}}
        <div class="modal fade" id="modalDeleteGroups" tabindex="-1" aria-labelledby="modalDeleteGroupsLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="modalDeleteGroupsLabel">Conferma eliminazione gruppi</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2"><strong>Attenzione:</strong> verranno eliminati tutti i gruppi.</p>
                        <p class="mb-3">Operazione irreversibile.</p>

                        <div class="form-check">
                            <input class="form-check-input confirm-check" type="checkbox" id="chkDeleteGroups"
                                data-target-btn="btnDeleteGroups">
                            <label class="form-check-label" for="chkDeleteGroups">
                                Ho capito e voglio procedere
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn admin-btn-info" data-bs-dismiss="modal">Annulla</button>

                        <form action="{{ route('groups.deleteAll') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button id="btnDeleteGroups" type="submit" class="btn btn-danger" disabled>
                                Confermo: elimina tutti i gruppi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal: reset recuperi --}}
        <div class="modal fade" id="modalResetRecuperi" tabindex="-1" aria-labelledby="modalResetRecuperiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="modalResetRecuperiLabel">Conferma reset Nrecuperi</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2"><strong>Attenzione:</strong> Nrecuperi verrà impostato a 0 per tutti i
                            corsisti.</p>
                        <p class="mb-3">Operazione potenzialmente impattante.</p>

                        <div class="form-check">
                            <input class="form-check-input confirm-check" type="checkbox" id="chkResetRecuperi"
                                data-target-btn="btnResetRecuperi">
                            <label class="form-check-label" for="chkResetRecuperi">
                                Ho capito e voglio procedere
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn admin-btn-info" data-bs-dismiss="modal">Annulla</button>

                        <form action="{{ route('students.resetRecuperi') }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button id="btnResetRecuperi" type="submit" class="btn btn-danger" disabled>
                                Confermo: azzera Nrecuperi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal: reset flags --}}
        <div class="modal fade" id="modalResetFlags" tabindex="-1" aria-labelledby="modalResetFlagsLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="modalResetFlagsLabel">Conferma reset flag corsisti</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2"><strong>Attenzione:</strong> verranno resettati i flag principali per tutti i
                            corsisti.</p>
                        <p class="mb-3">Operazione irreversibile.</p>

                        <div class="form-check">
                            <input class="form-check-input confirm-check" type="checkbox" id="chkResetFlags"
                                data-target-btn="btnResetFlags">
                            <label class="form-check-label" for="chkResetFlags">
                                Ho capito e voglio procedere
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn admin-btn-info" data-bs-dismiss="modal">Annulla</button>

                        <form method="POST" action="{{ route('admin.users.corsisti.reset-flags') }}"
                            class="d-inline">
                            @csrf
                            <button id="btnResetFlags" type="submit" class="btn btn-danger" disabled>
                                Confermo: reset flag
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal: reset trimestrale --}}
        <div class="modal fade" id="modalResetTrimestrale" tabindex="-1"
            aria-labelledby="modalResetTrimestraleLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="modalResetTrimestraleLabel">Conferma reset trimestrale</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2"><strong>Attenzione:</strong> trimestrale verrà impostato a NO per tutti i
                            corsisti.</p>
                        <p class="mb-3">Operazione irreversibile.</p>

                        <div class="form-check">
                            <input class="form-check-input confirm-check" type="checkbox" id="chkResetTrimestrale"
                                data-target-btn="btnResetTrimestrale">
                            <label class="form-check-label" for="chkResetTrimestrale">
                                Ho capito e voglio procedere
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn admin-btn-info" data-bs-dismiss="modal">Annulla</button>

                        <form method="POST" action="{{ route('admin.users.corsisti.reset-trimestrale') }}"
                            class="d-inline">
                            @csrf
                            <button id="btnResetTrimestrale" type="submit" class="btn btn-danger" disabled>
                                Confermo: reset trimestrale
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== SCRIPT ===================== --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // checkbox -> abilita bottone conferma
                document.querySelectorAll('.confirm-check').forEach((chk) => {
                    chk.addEventListener('change', function() {
                        const btnId = this.getAttribute('data-target-btn');
                        const btn = btnId ? document.getElementById(btnId) : null;
                        if (btn) btn.disabled = !this.checked;
                    });
                });

                // submit: disabilita bottone + testo "Avvio..."
                document.querySelectorAll('form').forEach((form) => {
                    form.addEventListener('submit', () => {
                        const submitBtn = form.querySelector('button[type="submit"]');
                        if (!submitBtn) return;

                        submitBtn.disabled = true;
                        submitBtn.dataset.originalText = submitBtn.dataset.originalText || submitBtn
                            .innerText;
                        submitBtn.innerText = 'Avvio...';
                    });
                });

                // chiusura modale: reset checkbox + bottone
                document.querySelectorAll('.modal').forEach((modalEl) => {
                    modalEl.addEventListener('hidden.bs.modal', () => {
                        modalEl.querySelectorAll('.confirm-check').forEach((chk) => {
                            chk.checked = false;

                            const btnId = chk.getAttribute('data-target-btn');
                            const btn = btnId ? document.getElementById(btnId) : null;

                            if (btn) {
                                btn.disabled = true;
                                if (btn.dataset.originalText) btn.innerText = btn.dataset
                                    .originalText;
                            }
                        });
                    });
                });

            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const root = document.getElementById('op-root');
                if (!root) return;

                const opId = parseInt(root.dataset.opId || '0', 10);
                const url = root.dataset.statusUrl || '';

                const elAlert = document.getElementById('op-alert');
                const elStatus = document.getElementById('op-status');
                const elStatus2 = document.getElementById('op-status-2');
                const elType = document.getElementById('op-type');
                const elAffected = document.getElementById('op-affected');
                const elStarted = document.getElementById('op-started');
                const elFinished = document.getElementById('op-finished');
                const elMessage = document.getElementById('op-message');

                function setAlert(kind, html) {
                    if (!elAlert) return;
                    elAlert.className = 'alert ' + kind;
                    elAlert.innerHTML = html;
                }

                function formatDateTime(value) {
                    if (!value) return '-';

                    // Gestisce stringhe tipo "2025-03-08 14:22:01"
                    const normalized = value.replace(' ', 'T');
                    const date = new Date(normalized);

                    if (isNaN(date.getTime())) {
                        return value; // fallback: mostra il valore originale se non parsabile
                    }

                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');

                    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
                }

                async function poll() {
                    try {
                        if (!url) throw new Error('Missing statusUrl');

                        const res = await fetch(url, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!res.ok) throw new Error('HTTP ' + res.status);

                        const data = await res.json();

                        if (elType) elType.textContent = data.type ?? '';
                        if (elStatus) elStatus.textContent = data.status ?? '';
                        if (elStatus2) elStatus2.textContent = data.status ?? '';
                        if (elAffected) elAffected.textContent = String(data.affected_rows ?? '-');
                        if (elStarted) elStarted.textContent = formatDateTime(data.started_at);
                        if (elFinished) elFinished.textContent = formatDateTime(data.finished_at);
                        if (elMessage) elMessage.textContent = data.message ?? '—';

                        if (data.status === 'completed') {
                            setAlert('alert-success', `Operazione <strong>#${opId}</strong> completata ✅`);
                            clearInterval(timer);
                        } else if (data.status === 'failed') {
                            setAlert('alert-danger',
                                `Operazione <strong>#${opId}</strong> fallita ❌ Controlla il messaggio.`);
                            clearInterval(timer);
                        } else if (data.status === 'running') {
                            setAlert('alert-warning', `Operazione <strong>#${opId}</strong> in esecuzione…`);
                        } else {
                            setAlert('alert-info', `Operazione <strong>#${opId}</strong> in coda…`);
                        }
                    } catch (e) {
                        setAlert('alert-danger', `Errore nel polling (operazione #${opId}). Riprovo…`);
                    }
                }

                poll();
                const timer = setInterval(poll, 3000);
            });
        </script>
    </main>
</x-layout>
