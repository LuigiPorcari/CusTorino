<x-layout documentTitle="Reset Password Email">
    <main id="main-content" class="container mt-5 pt-5" role="main">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-6">
                <div class="card custom-card" aria-labelledby="resetPasswordTitle">
                    <div class="card-header custom-card-header">
                        <h1 class="h4 m-0" id="resetPasswordTitle">Reset della Password</h1>
                    </div>
                    <div class="card-body custom-card-body">
                        <p class="visually-hidden" id="resetPasswordInstructions">
                            Inserisci la tua email per ricevere il link per reimpostare la password.
                        </p>
                        <form method="POST" action="{{ route('password.email') }}"
                            aria-describedby="resetPasswordInstructions">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="email">Email</label>
                                <input type="email" class="custom-form-input @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Inserisci la tua email" required
                                    aria-required="true" aria-describedby="emailHelp">
                                @error('email')
                                    <span class="custom-invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn custom-btn-submit"
                                aria-label="Invia il link per reimpostare la password">
                                Invia Link
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout>
