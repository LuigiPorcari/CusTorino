<x-layout documentTitle="Trainer Dashboard">
    <ul class="nav nav-tabs mt-5 pt-5 admin-nav-tabs">
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link active" aria-current="page" href="{{ route('trainer.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item admin-nav-item mt-3">
            <a class="nav-link" href="{{ route('trainer.salary') }}">Stipendio</a>
        </li>
    </ul>
{{-- Gruppi allenati --}}
<div class="container mt-5">
    <h1 class="custom-title mt-5 pt-5 text-center">Benvenuto Trainer {{ Auth::user()->name }}</h1>
    <h2 class="custom-subtitle mt-5 mb-4">Gruppi in cui alleni</h2>
    @if (session('success'))
        <div class="admin-alert alert-dismissible">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="mb-4">
        <form method="GET" action="{{ route('trainer.dashboard') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="search" name="alias_name" class="custom-form-input" placeholder="Nome Alias"
                        value="{{ request('alias_name') }}" onsearch="this.form.submit()">
                </div>
                <div class="col-md-4">
                    <select name="alias_date" class="custom-form-input" onchange="this.form.submit()">
                        <option value="">Tutte le date</option>
                        @foreach ($availableDates as $date)
                            <option value="{{ $date['original'] }}"
                                {{ request('alias_date') == $date['original'] ? 'selected' : '' }}>
                                {{ $date['formatted'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 admin-btn-info py-2 my-1">Filtra</button>
                </div>
            </div>
        </form>
    </div>
    <table class="table table-bordered admin-trainer-table">
        <thead>
            <tr>
                <th>Nome Alias</th>
                <th>Data Alias</th>
                <th>Dettagli</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($aliasesTrainer as $alias)
                <tr>
                    <td>{{ $alias->nome }}</td>
                    <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                    <td>
                        <a href="{{ route('alias.details', $alias) }}" class="btn admin-btn-info">Visualizza
                            Dettagli</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Non ci sono gruppi disponibili</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $aliasesTrainer->links('pagination::bootstrap-5') }}
</div>
</x-layout>