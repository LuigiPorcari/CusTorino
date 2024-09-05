<x-layout documentTitle="Trainer Register">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">Registrati come Trainer</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('trainer.register') }}">
                            @csrf
                            <!-- Nome -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="name">Nome</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- Cognome -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="cognome">Cognome</label>
                                <input id="cognome" type="text"
                                    class="form-control @error('cognome') is-invalid @enderror" name="cognome"
                                    value="{{ old('cognome') }}" required>
                                @error('cognome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="email">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- Genere -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="genere">Genere</label>
                                <select id="genere" class="form-control @error('genere') is-invalid @enderror"
                                    name="genere" required>
                                    <option value="M">Maschio</option>
                                    <option value="F">Femmina</option>
                                </select>
                                @error('genere')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- Password -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="password">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <!-- Conferma Password -->
                            <div class="form-group mb-3">
                                <label class="mb-1" for="password-confirm">Conferma Password</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    Registrati
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
