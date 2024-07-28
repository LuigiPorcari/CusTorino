<x-layout documentTitle="Admin Group Dashbord">
    <ul class="nav nav-tabs mt-5 pt-3">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard') }}">Gruppi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard.trainer') }}">Trainer</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard.student') }}">Studenti</a>
        </li>
    </ul>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container mt-5">
        {{-- Elenco Gruppi --}}
        <h2 class="mt-2 mb-4">Elenco Gruppi</h2>
        <div class="mb-4">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="group_name" class="form-control" placeholder="Nome Gruppo"
                            value="{{ request('group_name') }}">
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
                    <th>Nome Gruppo</th>
                    <th>Dettagli</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groups as $group)
                    <tr>
                        <td>{{ $group->nome }}</td>
                        <td>
                            <a href="{{ route('admin.group.details', $group) }}" class="btn btn-info">Visualizza
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
</x-layout>
{{-- <div class="container mt-5">
        <h1 class="mt-5 pt-5 text-center">Admin Dashboard</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h2 class="text-center">Benvenuto, {{ Auth::guard('admin')->user()->nome }}
            {{ Auth::guard('admin')->user()->cognome }}</h2>
        <h3 class="text-center mt-5 mb-0">Tabelle dei gruppi</h3>
        <div class="row justify-content-center">
            @foreach ($groups as $group)
                <div class="col-12 mt-5">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="colAdmin">Gruppo</th>
                                <th>Alias</th>
                                <th>Studenti</th>
                                <th>Recuperi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="{{ $group->aliases->count() + 1 }}">
                                    <p>Nome: {{ $group->nome }}</p>
                                    <p>Giorno:
                                        @switch($group->giorno_settimana)
                                            @case('monday')
                                                Lunedì
                                            @break

                                            @case('tuesday')
                                                Martedì
                                            @break

                                            @case('wednesday')
                                                Mercoledì
                                            @break

                                            @case('thursday,')
                                                Giovedì
                                            @break

                                            @case('friday')
                                                Venerdì
                                            @break

                                            @case('saturday')
                                                Sabato
                                            @break

                                            @case('sunday')
                                                Domenica
                                            @break
                                        @endswitch
                                    </p>
                                    <p>Orario: {{ $group->formatHours($group->orario) }}</p>
                                    <p>Tipologia: {{ $group->tipo }}</p>
                                    @if ($group->primo_allenatore_id != null)
                                        <p>Primo allenatore: <br> {{ $group->primoAllenatore->nome }}
                                            {{ $group->primoAllenatore->cognome }}</p>
                                    @endif
                                    @if ($group->secondo_allenatore_id != null)
                                        <p>Secondo allenatore: <br> {{ $group->secondoAllenatore->nome }}
                                            {{ $group->secondoAllenatore->cognome }}</p>
                                        @if ($group->condiviso == 'true')
                                            <p>Condiviso</p>
                                        @endif
                                    @endif
                                    <p>Numero massimo: {{ $group->numero_massimo_partecipanti }}</p>
                                    <p>Livello: {{ $group->livello }}</p>
                                    <p>Studenti:</p>
                                    @foreach ($group->students as $student)
                                        <p>{{ $student->nome }} {{ $student->cognome }} <br> Documentazione:
                                            @if ($student->documentation == 'true')
                                                OK
                                            @else
                                                NON OK
                                            @endif
                                        </p>
                                    @endforeach
                                    <div class="d-flex flex-column align-items-center mt-5">
                                        <a class="btn btn-warning mb-2"
                                            href="{{ route('groups.edit', $group) }}">Modifica</a>
                                        <a class="btn btn-warning mb-2"
                                            href="{{ route('edit.student', $group) }}">Modifica Corsisti</a>
                                        <form method="POST" action="{{ route('groups.delete', compact('group')) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="ms-2 btn btn-danger me-2">Elimina</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($group->aliases as $alias)
                                <tr>
                                    <td class="fw-bold w-25">{{ $alias->formatData($alias->data_allenamento) }}</td>
                                    <td class="p-0">
                                        @foreach ($group->students as $student)
                                            <div
                                                class="border-bottom py-2 {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="p-0">
                                        @foreach ($alias->compareStudents($group->id, $alias->id) as $recupero)
                                            @if (!in_array($recupero->id, $group->studenti_id))
                                                <div class="border-bottom py-2">
                                                    {{ $recupero->nome }} {{ $recupero->cognome }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
            <div class="col-8">
                {{ $groups->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div> --}}

{{-- ! STIPENDI --}}
{{-- <div class="container">
        <div class="row justify-content-center">
            <h2 class="mt-5 text-center">Stipendi Allenatori</h2>
            @foreach ($trainers as $trainer)
                <div class="col-12">
                    <h2 class="mt-5 mb-4">{{ $trainer->nome }} {{ $trainer->cognome }}</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <p>Data Gruppo Alias</p>
                                </th>
                                <th class="d-flex justify-content-between">
                                    <p>Tipo allenatore</p>
                                    <p>Condiviso</p>
                                </th>
                                <th>
                                    <p>Stipendio relativo alla prestazione (€)</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainer->primoAllenatoreAliases as $alias)
                                <tr>
                                    <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                    <td class="d-flex justify-content-between">
                                        <p>Primo Allenatore</p>
                                        <p>
                                            @if ($alias->condiviso == 'true')
                                                Si
                                            @else
                                                No
                                            @endif
                                        </p>
                                    </td>
                                    @if ($alias->condiviso == 'true')
                                        <td>15.00 €</td>
                                    @else
                                        <td>22.50 €</td>
                                    @endif
                                </tr>
                            @endforeach
                            @foreach ($trainer->secondoAllenatoreAliases as $alias)
                                <tr>
                                    <td>{{ $alias->formatData($alias->data_allenamento) }}</td>
                                    <td class="d-flex justify-content-between">
                                        <p>Secondo Allenatore</p>
                                        <p>
                                            @if ($alias->condiviso == 'true')
                                                Si
                                            @else
                                                No
                                            @endif
                                        </p>
                                    </td>
                                    @if ($alias->condiviso == 'true')
                                        <td>15.00 €</td>
                                    @else
                                        <td>7.50 €</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right"><strong>Stipendio Totale:</strong></td>
                                <td><strong>{{ $trainer->calcolaStipendioAllenatore($trainer->id) }} €</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
        <div class="row justify-content-center">
            <div class="col-8">
                {{ $trainers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div> --}}
