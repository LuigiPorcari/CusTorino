<x-layout documentTitle="Admin Group Dashboard">
    <ul class="nav nav-tabs admin-nav-tabs mt-5 pt-5 pt-md-0">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Allenatori</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.dashboard.student') }}">Corsisti</a>
        </li>
    </ul>
    <div class="container mt-md-5 admin-dashboard">
        <h2 class="mt-5 mb-4 pt-5 pt-md-0 custom-title">Elenco Gruppi</h2>
        @if (session('success'))
            <div class="alert alert-dismissible custom-alert-success">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="search" name="group_name" class="custom-form-input shadow-lg"
                            placeholder="Nome Gruppo" value="{{ request('group_name') }}" onsearch="this.form.submit()">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn w-100 admin-btn-info py-2 mt-1 fs-6 shadow-lg">Filtra</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-bordered admin-table">
            <thead>
                <tr>
                    <th>Nome Gruppo</th>
                    <th>Dettagli</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groups as $group)
                    <tr>
                        <td>{{ $group->nome }}</td>
                        <td>
                            <a href="{{ route('admin.group.details', $group) }}" class="btn admin-btn-info">Visualizza
                                Dettagli</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Non ci sono gruppi disponibili</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $groups->links('pagination::bootstrap-5') }}
    </div>
</x-layout>
