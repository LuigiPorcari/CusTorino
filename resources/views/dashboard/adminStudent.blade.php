<x-layout documentTitle="Admin Student">
    <ul class="nav nav-tabs mt-5 pt-5 admin-nav-tabs">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Trainer</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.student') }}">Corsisti</a>
        </li>
    </ul>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible admin-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-5 admin-student-dashboard">
        <h2 class="mt-5 mb-4 custom-title">Elenco Studenti</h2>
        <div class="mb-4 admin-student-filter">
            <form method="GET" action="{{ route('admin.dashboard.student') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="search" name="student_name" class="form-control" placeholder="Nome Studente"
                            value="{{ request('student_name') }}" onsearch="this.form.submit()">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn admin-btn-info w-100">Filtra</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive admin-table-responsive">
            <table class="table table-bordered admin-student-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Dettagli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->cognome }}</td>
                            <td><a class="btn admin-btn-info" href="{{ route('admin.student.details', $student) }}">Visualizza
                                    dettagli</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Non ci sono studenti disponibili</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $students->links('pagination::bootstrap-5') }}
    </div>
</x-layout>