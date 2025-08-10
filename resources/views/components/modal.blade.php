@if (Auth::user())

<!-- Modale Delete Admin -->
<div class="modal fade" id="deleteModalAdmin" tabindex="-1" role="dialog" aria-labelledby="deleteModalAdminLabel" aria-describedby="deleteModalAdminDesc" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header admin-modal-header">
                <h1 class="modal-title fs-5" id="deleteModalAdminLabel">Eliminazione Admin</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi modale"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center text-center admin-modal-body" id="deleteModalAdminDesc">
                <p class="mb-3">Sei sicuro di voler eliminare questo Admin? L’operazione non può essere annullata.</p>
                <form action="{{ route('admin.destroy', Auth::user()->id) }}" method="POST" class="d-flex gap-3 flex-wrap justify-content-center">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn admin-btn-danger" aria-label="Conferma eliminazione Admin">Conferma</button>
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal" aria-label="Annulla eliminazione Admin">Annulla</button>
                </form>
            </div>
            <div class="modal-footer admin-modal-footer">
                <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal" aria-label="Chiudi modale">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modale Delete Trainer -->
<div class="modal fade" id="deleteModalTrainer" tabindex="-1" role="dialog" aria-labelledby="deleteModalTrainerLabel" aria-describedby="deleteModalTrainerDesc" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header admin-modal-header">
                <h1 class="modal-title fs-5" id="deleteModalTrainerLabel">Eliminazione Allenatore</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi modale"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center text-center admin-modal-body" id="deleteModalTrainerDesc">
                <p class="mb-3">Sei sicuro di voler eliminare questo Allenatore? L’operazione non può essere annullata.</p>
                <form action="{{ route('trainer.destroy', Auth::user()->id) }}" method="POST" class="d-flex gap-3 flex-wrap justify-content-center">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn admin-btn-danger" aria-label="Conferma eliminazione Allenatore">Conferma</button>
                    <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal" aria-label="Annulla eliminazione Allenatore">Annulla</button>
                </form>
            </div>
            <div class="modal-footer admin-modal-footer">
                <button type="button" class="btn admin-modal-btn-secondary" data-bs-dismiss="modal" aria-label="Chiudi modale">Chiudi</button>
            </div>
        </div>
    </div>
</div>

@endif
