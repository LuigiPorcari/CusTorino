<x-layout documentTitle="Trainer Dashboard">
    <ul class="nav nav-tabs admin-nav-tabs z-3 pt-0">
            <li class="nav-item admin-nav-item mt-3">
                <a class="nav-link" aria-current="page" href="{{ route('trainer.dashboard') }}">Settimana</a>
            </li>
            <li class="nav-item admin-nav-item mt-3">
                <a class="nav-link" aria-current="page" href="{{ route('trainer.group') }}">Gruppi</a>
            </li>
            <li class="nav-item admin-nav-item mt-3">
                <a class="nav-link" href="{{ route('trainer.salary') }}">Compensi</a>
            </li>
    </ul>
    <div class="container">
        <div class="mt-5 pt-5">
            @if (session('success'))
                <div class="alert alert-dismissible custom-alert-success mt-5">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="row mb-5 justify-content-center">
            {{-- ! Descrizione gruppo alias --}}
            @forelse($aliasesTrainer as $alias)
                <div class="col-11 col-md-4 mt-5 pt-4">
                    <div class="custom-card equal-height-card mx-1 mb-2 mt-5">
                        <div class="custom-card-body">
                            <h5 class="card-title">{{ $alias->nome }}</h5>
                            <p class="card-text">Sede: <span class="text-uppercase">{{ $alias->location }}</span></p>
                            <h6 class="custom-date card-subtitle mb-2">
                                {{ $alias->formatData($alias->data_allenamento) }}
                            </h6>
                            <p class="custom-paragraph"><span class="fw-bold">Tipo:</span>
                                {{ $alias->tipo }}</p>
                            <p class="custom-paragraph"><span class="fw-bold">Orario:</span>
                                {{ $alias->formatHours($alias->orario) }}</p>
                            @if ($alias->condiviso == 'false')
                                @if ($alias->primo_allenatore_id != null)
                                    <p class="custom-paragraph"><span class="fw-bold">Primo allenatore:</span> <br>
                                        {{ $alias->primoAllenatore->name }} {{ $alias->primoAllenatore->cognome }}</p>
                                @endif
                                @if ($alias->secondo_allenatore_id != null)
                                    <p class="custom-paragraph"><span class="fw-bold">Secondo allenatore:</span> <br>
                                        {{ $alias->secondoAllenatore->name }} {{ $alias->secondoAllenatore->cognome }}
                                    </p>
                                @endif
                            @endif
                            @if ($alias->condiviso == 'true')
                                @if ($alias->primo_allenatore_id != null)
                                    <p class="custom-paragraph"><span class="fw-bold">Allenatore condiviso:</span> <br>
                                        {{ $alias->primoAllenatore->name }} {{ $alias->primoAllenatore->cognome }}</p>
                                @endif
                                @if ($alias->secondo_allenatore_id != null)
                                    <p class="custom-paragraph"><span class="fw-bold">Allenatore condiviso:</span> <br>
                                        {{ $alias->secondoAllenatore->name }} {{ $alias->secondoAllenatore->cognome }}
                                    </p>
                                @endif
                                <p class="custom-paragraph">Condiviso</p>
                            @endif
                            <div class="row border rounded-3">
                                <div class="col-6 p-0 border-end">
                                    <p class="my-0 py-2 card-text border-bottom"><span class="fw-bold">Corsisti:</span>
                                    </p>
                                    @foreach ($alias->group->users as $student)
                                        <p
                                            class="my-0 py-1 card-text border-bottom {{ in_array($student->id, $alias->studenti_id) ? '' : 'bg-danger text-white' }}">
                                            {{ $student->name }} {{ $student->cognome }}
                                        </p>
                                    @endforeach
                                </div>
                                <div class="col-6 p-0">
                                    <p class="my-0 py-2 card-text border-bottom"><span class="fw-bold">Recuperi:</span>
                                    </p>
                                    @foreach ($alias->compareStudents($alias->group->id, $alias->id) as $recupero)
                                        @if (!in_array($recupero->id, $alias->group->studenti_id))
                                            <p class="my-0 py-1 card-text border-bottom">{{ $recupero->name }}
                                                {{ $recupero->cognome }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="text-center row">
                            <div class="col-6">
                                <a class="custom-link-btn mb-4"
                                    href="{{ route('alias.details', $alias) }}">Modifica</a>
                            </div>
                            <div class="col-6">
                                <form action="{{ route('aliases.checkConf', $alias->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="custom-link-btn border-0">
                                        Conferma
                                    </button>
                                </form>
                            </div>
                            @if ($alias->check_conf)
                                <div class="col-12">
                                    <p class="custom-subtitle text-success">Gruppo Confermato</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="min-vh-100 text-center custom-title">
                    <h2>Non alleni nessun gruppo in questa settimana</h2>
                </div>
            @endforelse
        </div>
</x-layout>
