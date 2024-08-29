<x-layout documentTitle="Admin Student Details">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Scheda del corsista {{ $student->name }} {{ $student->cognome }}</h3>
                    </div>
                    <form method="POST" action="{{ route('admin.update.student', $student) }}">
                        @csrf
                        <div class="card-body">
                            <!-- Nome -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Nome:</strong></label>
                                <p class="form-control">{{ $student->name }}</p>
                            </div>
                            <!-- Cognome -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Cognome:</strong></label>
                                <p class="form-control">{{ $student->cognome }}</p>
                            </div>
                            <!-- Livello -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Livello:</strong></label>
                                <input placeholder="@if ($student->livello == null) N.C. @endif"
                                    value="{{ $student->livello }}" type="number" class="form-control" name="level"
                                    min="1" max="10">
                            </div>
                            <!-- Genere -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Genere:</strong></label>
                                <p class="form-control">{{ $student->genere }}</p>
                            </div>
                            <!-- Cus Card -->
                            <div class="mb-3">
                                <label class="form-label"><strong>CUS Card:</strong></label>
                                <select class="form-control" name="cus_card">
                                    <option @if ($student->cus_card == 1) selected @endif value="1">OK</option>
                                    <option @if ($student->cus_card == 0) selected @endif value="0">NON OK
                                    </option>
                                </select>
                            </div>
                            <!-- Visita Medica -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Visita Medica:</strong></label>
                                <select class="form-control" name="visita_medica">
                                    <option @if ($student->visita_medica == 1) selected @endif value="1">OK</option>
                                    <option @if ($student->visita_medica == 0) selected @endif value="0">NON OK
                                    </option>
                                </select>
                            </div>
                            <!-- Pagamento -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Pagamento:</strong></label>
                                <select class="form-control" name="pagamento">
                                    <option @if ($student->pagamento == 1) selected @endif value="1">OK</option>
                                    <option @if ($student->pagamento == 0) selected @endif value="0">NON OK
                                    </option>
                                </select>
                            </div>
                            <!-- Universitario -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Universitario:</strong></label>
                                <select class="form-control" name="universitario">
                                    <option @if ($student->universitario == 1) selected @endif value="1">SI</option>
                                    <option @if ($student->universitario == 0) selected @endif value="0">NO
                                    </option>
                                </select>
                            </div>
                            {{-- Promozione a Trainer --}}
                            <div class="mb-3">
                                <label class="form-label"><strong>Questo trainer è anche un corsista?</strong></label>
                                <select class="form-control" name="is_trainer">
                                    <option @if ($student->is_trainer == 1) selected @endif value="1">SI</option>
                                    <option @if ($student->is_trainer == 0) selected @endif value="0">NO
                                    </option>
                                </select>
                            </div>
                            <!-- Numero di Recuperi -->
                            <div class="mb-3">
                                <label class="form-label"><strong>Recuperi:</strong></label>
                                <input value="{{ $student->Nrecuperi }}" type="number" class="form-control"
                                    name="Nrecuperi" min="0">
                            </div>
                            <div class="mb-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-warning me-1">Salva Modifiche</button>
                                <button type="button" class="btn btn-danger ms-1" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $student->id }}">Elimina Corsista</button>
                            </div>
                    </form>
                    <!-- Pulsante di Ritorno -->
                    <div class="text-center">
                        <a href="{{ route('admin.dashboard.student') }}" class="btn btn-primary">Torna alla Lista
                            Corsisti</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



    <!-- Modali di conferma eliminazione per ogni studente -->
    <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1"
        aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel{{ $student->id }}">Sicuro di voler eliminare
                        lo studente {{ $student->name }} {{ $student->cognome }}?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <form action="{{ route('student.destroy', $student->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mx-2 px-3">Si</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
