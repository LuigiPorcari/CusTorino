<x-layout documentTitle="Login">
    <main>
        <h1 class="visually-hidden">Pagina di accesso al sito Beach Volley Custorino</h1>

        <div class="container mt-5 pt-5">
            <div class="row justify-content-center mt-4">
                <div class="col-12 col-md-8">
                    <div class="card custom-card">
                        <div class="card-header custom-card-header fs-4" id="login-title">Login</div>
                        <div class="card-body custom-card-body">
                            <form method="POST" action="{{ route('login') }}" aria-labelledby="login-title">
                                @csrf
                                <!-- Email -->
                                <div class="form-group mb-3">
                                    <label class="custom-form-label mb-1" for="email">Email</label>
                                    <input id="email" type="email"
                                        class="custom-form-input @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autofocus aria-describedby="emailHelp">
                                    <div id="emailHelp" class="visually-hidden">Inserisci la tua email registrata</div>
                                    @error('email')
                                        <span class="custom-invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group mb-3">
                                    <label class="custom-form-label mb-1" for="password">Password</label>
                                    <input id="password" type="password"
                                        class="custom-form-input @error('password') is-invalid @enderror" name="password"
                                        required aria-describedby="passwordHelp">
                                    <div id="passwordHelp" class="visually-hidden">Inserisci la tua password</div>
                                    @error('password')
                                        <span class="custom-invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Ricordami -->
                                <div class="form-group form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="custom-form-label" for="remember">Ricordami</label>
                                </div>

                                <!-- Password dimenticata -->
                                <div class="my-3 text-center mb-1">
                                    <a class="text-decoration-none" href="{{ route('password.request') }}">Password dimenticata?</a>
                                </div>

                                <!-- Login -->
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn custom-btn-submit" aria-label="Entra nell'area riservata">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>