<x-layout documentTitle="Admin Trainer Dashboard">
    <ul class="nav nav-tabs mt-5 pt-5 admin-nav-tabs">
        <li class="nav-item admin-nav-item">
            <a class="nav-link mt-3" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard.trainer') }}">Trainer</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student') }}">Corsisti</a>
        </li>
    </ul>
    @if (session('success'))
        <div class="alert alert-dismissible custom-alert-success">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container mt-md-5 admin-trainer-dashboard">
        <h2 class="mt-md-5 mb-4 custom-title">Elenco Trainer</h2>
        <div class="mb-4 admin-trainer-filter">
            <form method="GET" action="{{ route('admin.dashboard.trainer') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="search" name="trainer_name" class="custom-form-input " placeholder="Nome Trainer"
                            value="{{ request('trainer_name') }}" onsearch="this.form.submit()">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn admin-btn-info w-100 py-2 mt-1 fs-6">Filtra</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive admin-table-responsive">
            <table class="table table-bordered admin-trainer-table">
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
                                <td class="d-none d-md-table-cell">
                                    {{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</td>
                                <td>
                                    <a href="{{ route('admin.trainer.details', $trainer) }}"
                                        class="btn admin-btn-info">Visualizza Dettagli</a>
                                </td>
                                <td class="d-flex flex-column flex-md-row">
                                    <select class="form-control mb-2 mb-md-0" name="is_corsista">
                                        <option @if ($trainer->is_corsista == 1) selected @endif value="1">SI
                                        </option>
                                        <option @if ($trainer->is_corsista == 0) selected @endif value="0">NO
                                        </option>
                                    </select>
                                    <button type="submit" class="btn admin-btn-info">Modifica</button>
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

        {{ $trainers->links('pagination::bootstrap-5') }}
    </div>
</x-layout>
