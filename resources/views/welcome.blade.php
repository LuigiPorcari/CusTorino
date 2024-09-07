<x-layout documentTitle="Homepage">
    <div class="container-fluid mt-5 text-center p-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mt-5 custom-title">Homepage</h1>
                <a class="btn custom-btn-primary-home" href="{{ route('admin.register') }}">Registra nuovo Admin</a>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible custom-alert-success-home">
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
                class="me-2 col-12 col-md-5 border rounded-5 d-flex justify-content-center align-items-center flex-column text-center shadow custom-homepage-col">
                <h3 class="custom-subtitle py-2">Chi siamo?</h3>
                <p class="custom-paragraph">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo doloribus
                    eaque
                    esse mollitia explicabo tenetur maxime eum libero officiis consequatur, inventore temporibus
                </p>
            </div>
            <div
                class="ms-2 col-12 col-md-5 border rounded-5 d-flex justify-content-center align-items-center flex-column text-center shadow custom-homepage-col mb-3">
                <img src="{{ asset('img/gruppo.jpg') }}" alt="gruppo di persone" class="img-fluid">
            </div>
        </div>
        {{-- SECONDA ROW --}}
        <div class="row flex-column flex-md-row justify-content-center mt-5">
            <div
                class="me-2 col-12 col-md-5 border rounded-5 d-flex justify-content-center align-items-center flex-column text-center shadow custom-homepage-col mb-3">
                <img src="{{ asset('img/rete.jpg') }}" alt="rete di pallavolo" class="img-fluid">
            </div>
            <div
                class="ms-2 col-12 col-md-5 border rounded-5 d-flex justify-content-center align-items-center flex-column text-center shadow custom-homepage-col">
                <h3 class="custom-subtitle py-2">Cosa offriamo?</h3>
                <p class="custom-paragraph">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo doloribus
                    eaque
                    esse mollitia explicabo tenetur maxime eum libero officiis consequatur, inventore temporibus
                </p>
            </div>
        </div>
        {{-- TERZA ROW --}}
        <div class="row flex-column-reverse flex-md-row justify-content-center mt-5">
            <div
                class="me-2 col-12 col-md-5 border rounded-5 d-flex justify-content-center align-items-center flex-column text-center shadow custom-homepage-col">
                <h3 class="custom-subtitle py-2">Cosa offre questo sito?</h3>
                <p class="custom-paragraph">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo doloribus
                    eaque
                    esse mollitia explicabo tenetur maxime eum libero officiis consequatur, inventore temporibus
                </p>
            </div>
            <div
                class="ms-2 col-12 col-md-5 border rounded-5 d-flex justify-content-center align-items-center flex-column text-center shadow custom-homepage-col mb-3">
                <img src="{{ asset('img/palla.jpg') }}" alt="palla di pallavolo" class="img-fluid">
            </div>
        </div>
    </div>
</x-layout>
