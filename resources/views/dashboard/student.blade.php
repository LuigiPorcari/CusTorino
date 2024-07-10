<x-layout documentTitle="Student Dashbord">
    <h1>Student Dashbord</h1>
    {{-- Segna assenze --}}
    <h2 class="mt-5">Gruppi dove ti alleni</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Pulsante Assenza</th>
                <th scope="col">Gruppo</th>
                <th scope="col">Data</th>
                <th scope="col">Orario</th>
            </tr>
        </thead>
        <tbody>
            @forelse (Auth::guard('student')->user()->aliases as $alias)
                <tr>
                    <th scope="row">
                        <form action="{{ route('student.markAbsence', $alias->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Segna Assenza</button>
                        </form>
                    </th>
                    <td>{{ $alias->nome }}</td>
                    <td>{{ $alias->data_allenamento }}</td>
                    <td>{{ $alias->orario }}</td>
                </tr>
            @empty
                <tr>
                    <td>
                        <h2>Non sei ancora iscritto ad un gruppo</h2>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{-- Segna nuova presenza --}}
    <h2 class="mt-5">Gruppi dove puoi recuperare</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Pulsante Recupero</th>
                <th scope="col">Gruppo</th>
                <th scope="col">Data</th>
                <th scope="col">Orario</th>
            </tr>
        </thead>
        <tbody>
            @if (Auth::guard('student')->user()->Nrecoveries > 0)
                @foreach ($aliases as $alias)
                    @foreach (Auth::guard('student')->user()->groups as $group)
                        @if ($group->nome != $alias->nome &&
                            !in_array(Auth::guard('student')->user()->id, $alias->studenti_id) &&
                                Auth::guard('student')->user()->level - 1 < $alias->livello &&
                                $alias->livello < Auth::guard('student')->user()->level + 2 &&
                                Auth::guard('student')->user()->gender == $alias->tipo &&
                                count($alias->studenti_id) < $alias->numero_massimo_partecipanti)
                            <tr>
                                <th scope="row">
                                    <form action="{{ route('student.recAbsence', $alias->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Recupera Assenza</button>
                                    </form>
                                </th>
                                <td>{{ $alias->nome }}</td>
                                <td>{{ $alias->data_allenamento }}</td>
                                <td>{{ $alias->orario }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td>
                        <h2>Non hai gettoni recupero</h2>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</x-layout>
