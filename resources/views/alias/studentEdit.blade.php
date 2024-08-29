<x-layout documentTitle="Create groups Student">
    <div class="container mt-5">
        <h1 class="mb-4 mt-5 pt-4">Lista Studenti</h1>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                @endforeach
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 border rounded-4 shadow p-4">
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cerca studenti...">
                <form method="POST" action="{{ route('student.recoveries', $alias) }}">
                    @csrf
                    <div id="studentsList" class="list-group">
                        @foreach (Auth::user()->getRecoverableStudent($alias) as $student)
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" value="{{ $student->id }}"
                                    name="studenti_id[]">
                                {{ $student->name }} {{ $student->cognome }}
                            </label>
                        @endforeach
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary mt-3">Conferma recuperi</button>
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
