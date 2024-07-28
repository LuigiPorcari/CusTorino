<x-layout documentTitle="Admin Group Details">
    <div class="container mt-5">
        <div class="row">
            <h1 class="mt-5">Dettagli del {{ $group->nome }}</h1>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
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
                                    <a class="btn btn-warning mb-2" href="{{ route('edit.student', $group) }}">Inserisci-Modifica
                                        Corsisti</a>
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
        </div>
    </div>
</x-layout>
