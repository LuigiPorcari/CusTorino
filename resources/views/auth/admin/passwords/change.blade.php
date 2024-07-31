<x-layout documentTitle="Admin Change Password">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Cambia Password</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.password.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Vecchia Password</label>
                                <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Inserisci la vecchia password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nuova Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Inserisci la nuova password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Conferma Nuova Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Conferma la nuova password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Aggiorna Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
