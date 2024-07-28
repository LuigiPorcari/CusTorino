<x-layout documentTitle="Admin Student Details">
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
                    <th>Documentazione</th>
                    <th>Livello</th>
                    <th>Modifica</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <form method="POST" action="{{ route('admin.update.student', $student) }}">
                        @csrf
                        <tr>
                            <td>{{ $student->nome }}</td>
                            <td>{{ $student->cognome }}</td>
                            <td>
                                <select class="form-control" name="documentation">
                                    <option @if ($student->documentation == 'true') selected @endif value="true">OK</option>
                                    <option @if ($student->documentation == 'false') selected @endif value="false">NON OK
                                    </option>
                                </select>
                            </td>
                            <td>
                                @if ($student->level != null)
                                    <input value="{{ $student->level }}" type="number" class="form-control"
                                        name="level" min="1" max="10">
                                @else
                                    <input value="" type="number" class="form-control" name="level"
                                        min="1" max="10">
                                @endif
                            </td>
                            <td>
                                <button type="submint" class="btn btn-warning">Conferma</button>
                            </td>
                        </tr>
                    </form>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Non ci sono studenti disponibili</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $students->links('pagination::bootstrap-5') }}
    </div>
</x-layout>
