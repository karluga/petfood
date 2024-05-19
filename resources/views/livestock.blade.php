<h1 id="p-m-title">DESCRIPTION</h1>
<div class="white-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <img src="{{ asset('assets/icons/info.png') }}" height="40" alt="Info">
        <h3 class="text-muted text-uppercase">{{ trans('app.navigation.livestock.name') ?? 'LIVESTOCK' }}</h3>
    </div>
    <p class="fs-4">{{ __('app.navigation.livestock.description') }}</p>
</div>
<div class="white-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <img src="{{ asset('assets/icons/yellow-star.png') }}" height="40" alt="Star icon">
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