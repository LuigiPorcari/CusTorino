<x-layout documentTitle="Reset Password">
    <main id="main-content" class="container mt-5 pt-5" role="main">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-6">
                <div class="card custom-card" aria-labelledby="resetPasswordTitle">
                    <div class="card-header custom-card-header">
                        <h1 class="h4 m-0" id="resetPasswordTitle">Reset Password</h1>
                    </div>
                    <div class="card-body custom-card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="email">Email</label>
                                <input type="email" id="email" name="email"
                                    class="custom-form-input @error('email') is-invalid @enderror"
                                    value="{{ old('email', $request->email) }}" required autofocus aria-required="true"
                                    aria-describedby="emailHelp">
                                @error('email')
                                    <span class="custom-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Nuova Password -->
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="password">Nuova Password</label>
                                <input type="password" id="password" name="password"
                                    class="custom-form-input @error('password') is-invalid @enderror" required
                                    aria-required="true" aria-describedby="passwordHelp">
                                @error('password')
                                    <span class="custom-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Conferma Password -->
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="password_confirmation">Conferma Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="custom-form-input" required aria-required="true">
                            </div>

                            <button type="submit" class="btn custom-btn-submit" aria-label="Conferma reset password">
                                Reset Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>
