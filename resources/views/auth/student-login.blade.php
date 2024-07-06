<x-layout documentTitle="Student Login">
    <h1>Student Login</h1>
    <form method="POST" action="{{route('login.student')}}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class=" d-flex flex-column align-items-center">
            <button type="submit" class="btn btn-primary">Accedi</button>
        </div>
    </form>
</x-layout>