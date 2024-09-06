<x-layout documentTitle="Admin Trainer Dashboard">
    <ul class="nav nav-tabs mt-5 pt-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.trainer') }}">Trainer</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard.student') }}">Corsisti</a>
        </li>
    </ul>

    <div class="container mt-5">
        {{-- Elenco Trainer --}}
        <h2 class="mt-5 mb-4">Elenco Trainer</h2>
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.dashboard.trainer') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="search" name="trainer_name" class="form-control" placeholder="Nome Trainer"
                            value="{{ request('trainer_name') }}" onsearch="this.form.submit()">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Filtra</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabella Responsive -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="d-table-cell d-md-none">Nome e Cognome</th>
                        <th class="d-none d-md-table-cell">Nome</th>
                        <th class="d-none d-md-table-cell">Cognome</th>
                        <th class="d-none d-md-table-cell">Stipendio Tot:</th>
                        <th>Dettagli</th>
                        <th>Questo Trainer è un Corsista?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trainers as $trainer)
                        <form method="POST" action="{{ route('admin.user.make-trainer-student', $trainer) }}">
                            @csrf
                            <tr>
                                <td class="d-table-cell d-md-none">{{ $trainer->name }} {{ $trainer->cognome }}</td>
                                <td class="d-none d-md-table-cell">{{ $trainer->name }}</td>
                                <td class="d-none d-md-table-cell">{{ $trainer->cognome }}</td>
                                <td class="d-none d-md-table-cell">{{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</td>
                                <td>
                                    <a href="{{ route('admin.trainer.details', $trainer) }}" class="btn btn-info w-100 mb-2 mb-md-0">
                                        Visualizza Dettagli
                                    </a>
                                </td>
                                <td class="d-flex flex-column flex-md-row">
                                    <select class="form-control mb-2 mb-md-0" name="is_corsista">
                                        <option @if ($trainer->is_corsista == 1) selected @endif value="1">SI</option>
                                        <option @if ($trainer->is_corsista == 0) selected @endif value="0">NO</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary w-100 w-md-auto">Modifica</button>
                                </td>
                            </tr>
                        </form>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Non ci sono trainer disponibili</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginazione -->
        {{ $trainers->links('pagination::bootstrap-5') }}
    </div>
</x-layout>
