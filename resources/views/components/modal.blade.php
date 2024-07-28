<!-- Modale Accedi -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginModalLabel">In che ruolo vuoi fare l'accesso?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <a class="btn btn-primary mx-2" href="{{ route('student.login') }}">Corsista</a>
                <a class="btn btn-primary mx-2" href="{{ route('trainer.login') }}">Trainer</a>
                <a class="btn btn-primary mx-2" href="{{ route('admin.login') }}">Admin</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modale Delete Admin -->
@if (Auth::guard('admin')->check())
    <div class="modal fade" id="deleteModalAdmin" tabindex="-1" aria-labelledby="deleteModalAdminLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="loginModalLabel">Sicuro di voler eliminare questo Admin?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <form action="{{ route('admin.destroy', Auth::guard('admin')->user()->id) }}" method="POST">
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
@endif
