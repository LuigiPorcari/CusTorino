<x-layout documentTitle="Password Update">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-8">
                <div class="card custom-card">
                    <div class="card-header custom-card-header fs-4">Cambia Password</div>
                    <div class="card-body custom-card-body">
                        <form method="POST" action="{{ route('password.change') }}">
                            @csrf
                            <!-- Vecchia Password -->
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="old_password">Vecchia Password</label>
                                <input id="old_password" type="password" class="custom-form-input @error('old_password') is-invalid @enderror"
                                    name="old_password" required>
                                @error('old_password')
                                    <span class="custom-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Nuova Password -->
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="password">Nuova Password</label>
                                <input id="password" type="password" class="custom-form-input @error('password') is-invalid @enderror"
                                    name="password" required>
                                @error('password')
                                    <span class="custom-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Conferma Nuova Password -->
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="password-confirm">Conferma Nuova Password</label>
                                <input id="password-confirm" type="password" class="custom-form-input"
                                    name="password_confirmation" required>
                            </div>

                            <!-- Bottone di submit -->
                            <div class="form-group mb-0">
                                <button type="submit" class="btn custom-btn-submit">
                                    Cambia Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
