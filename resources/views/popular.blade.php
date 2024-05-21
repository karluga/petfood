<h1 id="p-m-title">DESCRIPTION</h1>
<div class="white-box">
    <div class="d-flex justify-content-between align-items-center mb-3 position-relative">
        <img src="{{ asset('assets/icons/info.png') }}" height="40" alt="Info">
        <h3 id="title-pc" class="text-muted text-uppercase">{{ $type->name }}</h3>
        <div id="title-mobile" class="single-triangle" style="--accent-color: {{ $type->hex_color }}">
            <div>
                <div class="text">{{ $type->name }}</div>
                <div class="emoji">{{ $type->emoji }}</div>
            </div>
        </div>
    </div>
    <h4>Appearance</h4>
    <p class="fs-4">{{ $type->appearance }}</p>
    <hr>
    <h4>Food</h4>
    <p class="fs-4">{{ $type->food }}</p>
</div>
<div class="white-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <img src="{{ asset('assets/icons/yellow-star.png') }}" class="mr-3" height="40" alt="Star icon">
        <h3 class="text-muted">WELL-KNOWN ORDERS</h3>
    </div>
    <div class="animals-container">
        {{-- foreach --}}
        <div class="row">
            <h4>Anura</h4>
            {{-- foreach --}}
            <div class="single-animal col-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-link"></i>                                                                        {{--  below the slug in animals table --}}
                    <a class="species-link fs-4 text-muted" href="{{ app()->getLocale() . '/' . (trans('app.navigation.species.slug') ?? 'species') }}/frog">
                        FROGS
                    </a>
                </div>
                <img src="{{ asset('assets/images/frog.png') }}" height="100" alt="Animal">
            </div>
        </div>
    </div>
</div>