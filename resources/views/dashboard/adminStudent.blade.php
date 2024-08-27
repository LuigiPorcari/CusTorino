<x-layout documentTitle="Admin Student">
    <ul class="nav nav-tabs mt-5 pt-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Trainer</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.student') }}">Corsisti</a>
        </li>
    </ul>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container mt-5">
        {{-- Elenco Studenti --}}
        <h2 class="mt-5 mb-4">Elenco Studenti</h2>
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.dashboard.student') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="student_name" class="form-control" placeholder="Nome Studente"
                            value="{{ request('student_name') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filtra</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-bordered">
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
                        <td><a class="btn btn-primary" href="{{route('admin.student.details' , $student)}}">Visualizza dettagli</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Non ci sono studenti disponibili</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $students->links('pagination::bootstrap-5') }}
    </div>
</x-layout>
