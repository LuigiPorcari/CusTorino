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
        <small>Una volta modificati i valori per uno studente premere conferma <br> Modificare uno studente per volta</small>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Documentazione</th>
                    <th>Livello</th>
                    <th>Modifica</th>
                    <th>Elimina</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <form method="POST" action="{{ route('admin.update.student', $student) }}">
                        @csrf
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->cognome }}</td>
                            <td>
                                <select class="form-control" name="documentation">
                                    <option @if ($student->documentation == 'true') selected @endif value="true">OK</option>
                                    <option @if ($student->documentation == 'false') selected @endif value="false">NON OK
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input placeholder="@if ($student->livello == null) N.C. @endif" value="{{ $student->livello }}" type="number" class="form-control" name="level"
                                    min="1" max="10">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-warning">Conferma</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $student->id }}">Elimina</button>
                            </td>
                        </tr>
                    </form>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Non ci sono studenti disponibili</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $students->links('pagination::bootstrap-5') }}
    </div>

    <!-- Modali di conferma eliminazione per ogni studente -->
    @foreach ($students as $student)
        <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1"
            aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel{{ $student->id }}">Sicuro di voler eliminare
                            lo studente {{ $student->name }} {{ $student->cognome }}?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center">
                        <form action="{{ route('student.destroy', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-2 px-3">Si</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-layout>
