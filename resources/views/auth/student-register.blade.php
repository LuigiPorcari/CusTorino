<x-layout documentTitle="Student Register">
    <h2>Registrazione Student</h2>
        <form method="POST" action="{{ route('register.student') }}">
            @csrf
            {{-- NOME --}}
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            {{-- COGNOME --}}
            <div class="form-group">
                <label for="cognome">Cognome</label>
                <input type="text" class="form-control" id="cognome" name="cognome" required>
            </div>
            {{-- EMAIL --}}
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            {{-- GENERE --}}
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="M" id="radioMaschio" name="gender">
                    <label class="form-check-label" for="radioMaschio">
                        Maschio
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="F" id="radioFemmina" name="gender">
                    <label class="form-check-label" for="radioFemmina">
                        Femmina
                    </label>
                </div>
            </div>
            {{-- LIVELLO --}}
            <div class="mb-3">
                <label class="form-label">Livello</label>
                <input type="number" class="form-control" name="level" min="1" max="10">
            </div>
            {{-- DOCUMENTAZIONE --}}
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="true" id="documentationOk" name="documentation">
                    <label class="form-check-label" for="documentationOk">
                        Documentazione OK
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="false" id="documentatioNoOk" name="documentation" checked>
                    <label class="form-check-label" for="documentatioNoOk">
                        Documentazione NON OK
                    </label>
                </div>
            </div>
            {{-- PASSWORD --}}
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            {{-- CONFERMA PASSWORD --}}
            <div class="form-group">
                <label for="password_confirmation">Conferma Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrati</button>
        </form>
</x-layout>