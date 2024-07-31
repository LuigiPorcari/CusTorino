<x-layout documentTitle="Admin Login">
    <h1 class="mt-5 pt-4">Login Admin</h1>
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            @endforeach
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 mt-4 border p-4 rounded-4 shadow mb-5">
                <form method="POST" action="{{ route('login.admin') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="my-3 text-center">
                        <a class="text-decoration-none" href="{{ route('admins.password.request') }}">Password dimenticata?</a>
                    </div>
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <button type="submit" class="btn btn-primary">Accedi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layout>
