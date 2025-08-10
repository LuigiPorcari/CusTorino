<x-layout documentTitle="Trainer Register">
    <main>
        <h1 class="visually-hidden">Registrazione Allenatore - Beach Volley Custorino</h1>

        <div class="container mt-5 pt-5">
            <div class="row justify-content-center mt-4">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-header custom-card-header fs-4" id="trainer-register-title">Registrati come Allenatore</div>
                        <div class="card-body custom-card-body">
                            <form method="POST" action="{{ route('trainer.register') }}" aria-labelledby="trainer-register-title">
                                @csrf
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <!-- Nome -->
                                            <div class="form-group mb-3">
                                                <label class="custom-form-label" for="name">Nome</label>
                                                <input id="name" type="text"
                                                    class="custom-form-input @error('name') is-invalid @enderror"
                                                    name="name" value="{{ old('name') }}" required autofocus aria-describedby="nameHelp">
                                                <div id="nameHelp" class="visually-hidden">Inserisci il tuo nome</div>
                                                @error('name')
                                                    <span class="custom-invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <!-- Cognome -->
                                            <div class="form-group mb-3">
                                                <label class="custom-form-label" for="cognome">Cognome</label>
                                                <input id="cognome" type="text"
                                                    class="custom-form-input @error('cognome') is-invalid @enderror"
                                                    name="cognome" value="{{ old('cognome') }}" required aria-describedby="surnameHelp">
                                                <div id="surnameHelp" class="visually-hidden">Inserisci il tuo cognome</div>
                                                @error('cognome')
                                                    <span class="custom-invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <!-- Email -->
                                            <div class="form-group mb-3">
                                                <label class="custom-form-label" for="email">Email</label>
                                                <input id="email" type="email"
                                                    class="custom-form-input @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}" required aria-describedby="emailHelp">
                                                <div id="emailHelp" class="visually-hidden">Inserisci il tuo indirizzo email</div>
                                                @error('email')
                                                    <span class="custom-invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <!-- Genere -->
                                            <div class="form-group mb-3">
                                                <label class="custom-form-label" for="genere">Genere</label>
                                                <select id="genere"
                                                    class="custom-form-input @error('genere') is-invalid @enderror"
                                                    name="genere" required aria-describedby="genderHelp">
                                                    <option value="M">Maschio</option>
                                                    <option value="F">Femmina</option>
                                                </select>
                                                <div id="genderHelp" class="visually-hidden">Seleziona il tuo genere</div>
                                                @error('genere')
                                                    <span class="custom-invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <!-- Password -->
                                            <div class="form-group mb-3">
                                                <label class="custom-form-label" for="password">Password</label>
                                                <input id="password" type="password"
                                                    class="custom-form-input @error('password') is-invalid @enderror"
                                                    name="password" required aria-describedby="passwordHelp">
                                                <div id="passwordHelp" class="visually-hidden">Inserisci una password sicura</div>
                                                @error('password')
                                                    <span class="custom-invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <!-- Conferma Password -->
                                            <div class="form-group mb-3">
                                                <label class="custom-form-label" for="password-confirm">Conferma Password</label>
                                                <input id="password-confirm" type="password" class="custom-form-input"
                                                    name="password_confirmation" required aria-describedby="confirmPasswordHelp">
                                                <div id="confirmPasswordHelp" class="visually-hidden">Conferma la tua password</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Bottone di submit -->
                                            <div class="form-group mb-0">
                                                <button type="submit" class="btn custom-btn-submit" aria-label="Conferma registrazione allenatore">
                                                    Registrati
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>