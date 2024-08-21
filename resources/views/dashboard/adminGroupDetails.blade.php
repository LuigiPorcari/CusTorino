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
                                    <p>Primo allenatore: <br> {{ $group->primoAllenatore->name }}
                                        {{ $group->primoAllenatore->cognome }}</p>
                                @endif
                                @if ($group->secondo_allenatore_id != null)
                                    <p>Secondo allenatore: <br> {{ $group->secondoAllenatore->name }}
                                        {{ $group->secondoAllenatore->cognome }}</p>
                                    @if ($group->condiviso == 'true')
                                        <p>Condiviso</p>
                                    @endif
                                @endif
                                <p>Numero massimo: {{ $group->numero_massimo_partecipanti }}</p>
                                <p>Livello: {{ $group->livello }}</p>
                                <p>Studenti:</p>
                                @foreach ($group->users as $student)
                                    <p>{{ $student->name }} {{ $student->cognome }} <br> Documentazione:
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
                                        href="{{ route('edit.student', $group) }}">Inserisci-Modifica
                                        Corsisti</a>
                                    {{-- <form method="POST" action="{{ route('groups.delete', compact('group')) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="ms-2 btn btn-danger me-2">Elimina</button>
                                    </form> --}}
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModalGroup">
                                        Elimina
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @foreach ($group->aliases as $alias)
                            <tr>
                                <td class="fw-bold w-25">{{ $alias->formatData($alias->data_allenamento) }}</td>
                                <td class="p-0">
                                    @foreach ($group->users as $student)
                                        <div
                                            class="border-bottom py-2 {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                            {{ $student->name }} {{ $student->cognome }}
                                        </div>
                                    @endforeach
                                </td>
                                <td class="p-0">
                                    @foreach ($alias->compareStudents($group->id, $alias->id) as $recupero)
                                        @if (!in_array($recupero->id, $group->studenti_id))
                                            <div class="border-bottom py-2">
                                                {{ $recupero->name }} {{ $recupero->cognome }}
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
    <div class="modal fade" id="deleteModalGroup" tabindex="-1" aria-labelledby="deleteModalGroupLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="loginModalLabel">Sicuro di voler eliminare questo Gruppo?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <form action="{{ route('groups.delete', compact('group')) }}" method="POST">
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
</x-layout>
