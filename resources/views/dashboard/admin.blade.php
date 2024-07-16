<x-layout documentTitle="Admin Dashbord">
    <h1>Admin Dashbord</h1>
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
    </div>



    <h1 class="mt-5">Admin Dashboard</h1>
    <div class="container mt-5">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Gruppo</th>
                    <th>Alias</th>
                    <th>Studenti</th>
                    <th>Recuperi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td rowspan="{{ $group->aliases->count() + 1 }}">
                            <p>Nome: {{ $group->nome }}</p>
                            <p>Giorno: {{ $group->giorno_settimana }}</p>
                            <p>Orario: {{ $group->orario }}</p>
                            <p>Tipologia: {{ $group->tipo }}</p>
                            <p>Primo allenatore: {{ $group->primoAllenatore->nome }}
                                {{ $group->primoAllenatore->cognome }}</p>
                            @if ($group->secondo_allenatore_id != null)
                                <p>Secondo allenatore: {{ $group->secondoAllenatore->nome }}
                                    {{ $group->secondoAllenatore->cognome }}</p>
                                @if ($group->condiviso == 'true')
                                    <p>Condiviso</p>
                                @endif
                            @endif
                            <p>Numero massimo: {{ $group->numero_massimo_partecipanti }}</p>
                            <p>Studenti:</p>
                            @foreach ($group->students as $student)
                                <p>{{ $student->nome }} {{ $student->cognome }}</p>
                            @endforeach
                            <div class="d-flex justify-content-center mt-5">
                                <a class="btn btn-warning" href="{{route('groups.edit' , $group)}}">Modifica</a>
                                <form method="POST" action="{{route('groups.delete' , compact('group'))}}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="ms-2 btn btn-danger">Elimina</button>
                                </form>
                            </div>

                        </td>
                    </tr>
                    @foreach ($group->aliases as $alias)
                        <tr>
                            <td class="fw-bold w-25">{{ $alias->data_allenamento }}</td>
                            <td>
                                @foreach ($group->students as $student)
                                    <div class="{{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger' }}">
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
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
