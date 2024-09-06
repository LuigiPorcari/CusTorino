<x-layout documentTitle="Reset Password Email">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-6">
                <div class="card custom-card">
                    <div class="card-header custom-card-header">
                        <h3>Invia Link per il Reset della Password</h3>
                    </div>
                    <div class="card-body custom-card-body">
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="custom-form-label" for="email">Email</label>
                                <input type="email" class="custom-form-input" id="email" name="email"
                                    placeholder="Inserisci la tua email" required>
                            </div>
                            <button type="submit" class="btn custom-btn-submit">Invia Link</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
