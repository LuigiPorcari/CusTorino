<x-layout documentTitle="Create groups Student">
    <div class="container mt-5 pt-5">
        <h1 class="custom-title mb-4 mt-5 pt-4 text-center">Lista Studenti</h1>
        @if ($errors->any())
            <div class="custom-alert alert-dismissible">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                @endforeach
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 custom-card p-3">
                <input type="text" id="searchInput" class="custom-form-input mb-3" placeholder="Cerca studenti...">
                <form method="POST" action="{{ route('create.student', $group) }}">
                    @csrf
                    <div id="studentsList" class="list-group">
                        @foreach ($studentsAvaiable as $student)
                            <label class="list-group-item">
                                <input @if ($group->users->contains($student)) checked @endif class="form-check-input me-1"
                                    type="checkbox" value="{{ $student->id }}" name="studenti_id[]">
                                {{ $student->name }} {{ $student->cognome }}
                            </label>
                        @endforeach
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="custom-btn-submit mt-3">Salva</button>
                        <a class="btn admin-btn-info mt-3" href="{{route('admin.group.details' , $group)}}">Indietro</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#studentsList label').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</x-layout>
