<x-layout documentTitle="Homepage">
    <div class="container-fluid mt-5 text-center p-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mt-5">Benvenuto nella Homepage</h1>
                {{-- ! <a class="btn btn-primary" href="{{ route('admin.register') }}">Registra nuovo Admin</a> --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container">
        {{-- PRIMA ROW --}}
        <div class="row flex-column-reverse flex-md-row justify-content-center">
            <div
                class="me-2 col-12 col-md-5 border rounded-4 d-flex justify-content-center align-items-center flex-column text-center shadow homepageCol">
                <h3 class="py-2">Chi siamo?</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo doloribus eaque esse mollitia explicabo
                    tenetur maxime eum libero officiis consequatur, inventore temporibus deserunt minima fuga, ducimus
                    veritatis atque harum modi.</p>
            </div>
            <div
                class="ms-2 col-12 col-md-5 border rounded-4 d-flex justify-content-center align-items-center flex-column text-center shadow homepageCol mb-3">
                <h1>IMMAGINE A SCELTA</h1>
            </div>
        </div>
        {{-- SECONDA ROW --}}
        <div class="row flex-column flex-md-row justify-content-center mt-5">
            <div
                class="me-2 col-12 col-md-5 border rounded-4 d-flex justify-content-center align-items-center flex-column text-center shadow homepageCol mb-3">
                <h1>IMMAGINE A SCELTA</h1>
            </div>
            <div
                class="ms-2 col-12 col-md-5 border rounded-4 d-flex justify-content-center align-items-center flex-column text-center shadow homepageCol">
                <h3 class="py-2">Cosa offriamo?</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo doloribus eaque esse mollitia explicabo
                    tenetur maxime eum libero officiis consequatur, inventore temporibus deserunt minima fuga, ducimus
                    veritatis atque harum modi.</p>
            </div>
        </div>
        {{-- TERZA ROW --}}
        <div class="row flex-column-reverse flex-md-row justify-content-center mt-5">
            <div
                class="me-2 col-12 col-md-5 border rounded-4 d-flex justify-content-center align-items-center flex-column text-center shadow homepageCol">
                <h3 class="py-2">Cosa offre questo sito?</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo doloribus eaque esse mollitia explicabo
                    tenetur maxime eum libero officiis consequatur, inventore temporibus deserunt minima fuga, ducimus
                    veritatis atque harum modi.</p>
            </div>
            <div
                class="ms-2 col-12 col-md-5 border rounded-4 d-flex justify-content-center align-items-center flex-column text-center shadow homepageCol mb-3">
                <h1>IMMAGINE A SCELTA</h1>
            </div>
        </div>
    </div>
</x-layout>
