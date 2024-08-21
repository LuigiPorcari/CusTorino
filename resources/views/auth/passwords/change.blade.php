<x-layout documentTitle="Password Update">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header fw-bold">Cambia Password</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.change') }}">
                            @csrf
                            <!-- Vecchia Password -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="old_password">Vecchia Password</label>
                                <input id="old_password" type="password"
                                    class="form-control @error('old_password') is-invalid @enderror" name="old_password"
                                    required>
                                @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- Nuova Password -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="password">Nuova Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Conferma Nuova Password -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="password-confirm">Conferma Nuova Password</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    Cambia Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
