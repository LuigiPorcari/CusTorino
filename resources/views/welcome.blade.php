<x-layout documentTitle="Homepage">
    <main>
        <h1 class="visually-hidden">Homepage Beach Volley Custorino</h1>

        @if (!Auth::check())
            <div class="container mt-5 pt-1">
                <div class="row mt-5 pt-5 justify-content-center">
                    <div class="col-6">
                        <!-- Pulsante Accedi -->
                        <a class="btn custom-btn-primary-nav w-100 py-4 fs-4 fw-bolder"
                            href="{{ route('login') }}"
                            aria-label="Accedi all'area riservata">Accedi</a>
                    </div>
                    <div class="col-6">
                        <!-- Pulsante Registrati -->
                        <a class="btn custom-btn-primary-nav w-100 py-4 fs-4 fw-bolder"
                            href="{{ route('corsista.register') }}"
                            aria-label="Registrati come corsista">Registrati</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Carosello -->
        <div class="container mt-4">
            <div id="homepageCarousel" class="carousel slide custom-carousel shadow rounded-4" data-bs-ride="carousel"
                data-bs-interval="2500">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('img/gruppo1.jpg') }}" class="d-block w-100 custom-carousel-img"
                            alt="Allenamento di gruppo all'aperto">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/palla.jpg') }}" class="d-block w-100 custom-carousel-img"
                            alt="Primo piano di una palla da beach volley">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/rete.jpg') }}" class="d-block w-100 custom-carousel-img"
                            alt="Rete da beach volley su campo sabbioso">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('img/simbolo.jpg') }}" class="d-block w-100 custom-carousel-img"
                            alt="Simbolo ufficiale del Beach Volley Custorino">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#homepageCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon custom-carousel-control" aria-hidden="true"></span>
                    <span class="visually-hidden">Immagine precedente</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#homepageCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon custom-carousel-control" aria-hidden="true"></span>
                    <span class="visually-hidden">Immagine successiva</span>
                </button>
            </div>
        </div>
    </main>
</x-layout>
