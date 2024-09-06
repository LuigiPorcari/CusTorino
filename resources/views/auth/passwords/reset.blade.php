<x-layout documentTitle="Reset Password">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-6">
                <div class="card custom-card">
                    <div class="card-header custom-card-header">
                        <h3>Reset Password</h3>
                    </div>
                    <div class="card-body custom-card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="email">Email</label>
                                <input type="email" class="custom-form-input @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Inserisci la tua email"
                                    value="{{ old('email', $request->email) }}" required autofocus>
                                @error('email')
                                    <span class="custom-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="password">Nuova Password</label>
                                <input type="password" class="custom-form-input @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Inserisci la nuova password" required>
                                @error('password')
                                    <span class="custom-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="password_confirmation">Conferma Password</label>
                                <input type="password" class="custom-form-input" id="password_confirmation"
                                    name="password_confirmation" placeholder="Conferma la nuova password" required>
                            </div>

                            <button type="submit" class="btn custom-btn-submit">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>