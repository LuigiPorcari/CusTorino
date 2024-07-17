<x-layout documentTitle="Trainer Register">
    <h1 class="mt-5 pt-4">Registrazione Trainer</h1>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 mt-4">
                <form method="POST" action="{{ route('register.trainer') }}">
                    @csrf
                    {{-- NOME --}}
                    <div class="mb-3">
                        <label class="form-label" for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    {{-- COGNOME --}}
                    <div class="mb-3">
                        <label class="form-label" for="cognome">Cognome</label>
                        <input type="text" class="form-control" id="cognome" name="cognome" required>
                    </div>
                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    {{-- CONFERMA PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">Conferma Password</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" required>
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Registrati</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
