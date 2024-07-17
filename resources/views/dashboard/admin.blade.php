<x-layout documentTitle="Admin Dashbord">
    <div class="container mt-5">
        <h1 class="mt-5 pt-5 text-center">Admin Dashboard</h1>
        <h2 class="text-center">Benvenuto, {{Auth::guard('admin')->user()->nome}} {{Auth::guard('admin')->user()->cognome}}</h2>
        <h3 class="text-center mt-5 mb-0">Tabelle dei gruppi</h3>
        <div class="row">
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
                                    <p>Giorno: {{ $group->giorno_settimana }}</p>
                                    <p>Orario: {{ $group->formatHours($group->orario) }}</p>
                                    <p>Tipologia: {{ $group->tipo }}</p>
                                    <p>Primo allenatore: <br> {{ $group->primoAllenatore->nome }}
                                        {{ $group->primoAllenatore->cognome }}</p>
                                    @if ($group->secondo_allenatore_id != null)
                                        <p>Secondo allenatore: <br> {{ $group->secondoAllenatore->nome }}
                                            {{ $group->secondoAllenatore->cognome }}</p>
                                        @if ($group->condiviso == 'true')
                                            <p>Condiviso</p>
                                        @endif
                                    @endif
                                    <p>Numero massimo: {{ $group->numero_massimo_partecipanti }}</p>
                                    <p>Studenti:</p>
                                    @foreach ($group->students as $student)
                                        <p>{{ $student->nome }} {{ $student->cognome }} <br> Documentazione: @if($student->documentation == "true")OK @else NON OK @endif</p>
                                    @endforeach
                                    <div class="d-flex flex-column align-items-center mt-5">
                                        <a class="btn btn-warning mb-2" href="{{ route('groups.edit', $group) }}">Modifica</a>
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
                                    <td class="fw-bold w-25">{{$alias->formatData($alias->data_allenamento)}}</td>
                                    <td class="p-0">
                                        @foreach ($group->students as $student)
                                            <div
                                                class="border-top py-1 {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                                {{ $student->nome }} {{ $student->cognome }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($alias->compareStudents($group->id, $alias->id) as $recupero)
                                            @if (!in_array($recupero->id, $group->studenti_id))
                                                <p class="mt-2">{{ $recupero->nome }} {{ $recupero->cognome }}</p>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>

    {{-- STIPENDI --}}
    <div class="container">
        <div class="row">
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
                                    <td>{{$alias->formatData($alias->data_allenamento)}}</td>
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
                                    <td>{{$alias->formatData($alias->data_allenamento)}}</td>
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
    </div>
</x-layout>
{{-- <h1>Admin Dashbord</h1>
    <div class="container">
        <div class="row">
            @foreach ($groups as $group)
                <div class="col-12 border">
                    <table class="table table-hover my-5">
                        <thead>
                            <tr>
                                <th scope="col">{{ $group->nome }}</th>
                                @foreach ($group->aliases as $alias)
                                    <th scope="col">{{ $alias->nome }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">{{ $group->giorno_settimana }}</th>
                                @foreach ($group->aliases as $alias)
                                    <td>{{ $alias->data_allenamento }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row">{{ $group->orario }}</th>
                                @foreach ($group->aliases as $alias)
                                    <td>{{ $alias->orario }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row">{{ $group->tipo }}</th>
                                @foreach ($group->aliases as $alias)
                                    <td>{{ $alias->tipo }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row">Primo allenatore:<br>{{ $group->primoAllenatore->nome }}
                                    {{ $group->primoAllenatore->cognome }}</th>
                                @foreach ($group->aliases as $alias)
                                    <td>Primo allenatore:<br>{{ $alias->primoAllenatore->nome }}
                                        {{ $alias->primoAllenatore->cognome }}</td>
                                @endforeach
                            </tr>
                            @if ($group->secondo_allenatore_id != null)
                                <tr>
                                    <th scope="row">Secondo allenatore:<br>{{ $group->secondoAllenatore->nome }}
                                        {{ $group->secondoAllenatore->cognome }}</th>
                                    @foreach ($group->aliases as $alias)
                                        <td>Secondo allenatore:<br>{{ $alias->secondoAllenatore->nome }}
                                            {{ $alias->secondoAllenatore->cognome }}</td>
                                    @endforeach
                                </tr>
                            @endif
                            @if ($group->condiviso == 'true')
                                <tr>
                                    <th scope="row">Condiviso</th>
                                    @foreach ($group->aliases as $alias)
                                        @if ($group->condiviso == 'true')
                                            <td>Condiviso</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endif
                            <tr>
                                <th scope="row">{{ $group->numero_massimo_partecipanti }}</th>
                                @foreach ($group->aliases as $alias)
                                    <td>{{ $alias->numero_massimo_partecipanti }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row">Studenti:</th>
                                @foreach ($group->aliases as $alias)
                                    <td>Studenti:</td>
                                @endforeach
                            </tr>
                            @foreach ($group->students as $student)
                                <tr>
                                    <th scope="row">{{ $student->nome }} {{ $student->cognome }}</th>
                                    @foreach ($group->aliases as $alias)
                                        @foreach ($alias->students as $studentAlias)
                                            @if ($studentAlias->id == $student->id)
                                                <td>{{ $studentAlias->nome }} {{ $studentAlias->cognome }}</td>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <th scope="row"></th>
                                @foreach ($group->aliases as $alias)
                                    <td>Recuperi:<br>
                                        @foreach ($alias->compareStudents($group->id, $alias->id) as $studentMerge)
                                            @if (!in_array($studentMerge->id, $group->studenti_id))
                                                <p class="mt-2">{{ $studentMerge->nome }}
                                                    {{ $studentMerge->cognome }}</p>
                                            @endif
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div> --}}
