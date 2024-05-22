<h1 id="p-m-title">{{ __('app.section.animals.description') }}</h1>
<div class="white-box">
    <div class="d-flex justify-content-between align-items-center mb-3 position-relative">
        <img src="{{ asset('assets/icons/info.png') }}" height="40" alt="Info">
        <h3 id="title-pc" class="text-muted text-uppercase">{{ trans('app.navigation.livestock.name') ?? 'LIVESTOCK' }}</h3>
        <div id="title-mobile" class="single-triangle" style="--accent-color: #c32070">
            <div>
                <div class="text">{{ trans('app.navigation.livestock.name') ?? 'Livestock' }}</div>
                <div class="emoji">🐷</div>
            </div>
        </div>
    </div>
    <p class="fs-4">{{ __('app.navigation.livestock.description') }}</p>
</div>
<div class="white-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <img src="{{ asset('assets/icons/yellow-star.png') }}" class="mr-3" height="40" alt="Star icon">
        <h3 class="text-muted">{{ __('app.section.animals.species') }}</h3>
    </div>
    <div class="animals-container">
        @foreach($livestock as $order => $animals)
        <div class="row mb-3">
            <h4>{{ $order }}</h4>
            @foreach($animals as $animal)
            <div class="single-animal col-auto mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-link"></i>
                    <a class="species-link fs-4 text-muted" href="/{{ app()->getLocale() . '/species/' . $animal['gbif_id'] }}">
                        {{ $animal['name'] }}
                    </a>
                </div>
                <img src="{{ asset('assets/images/' . $animal['gbif_id'] . '/' . $animal['filename']) }}" height="100" alt="{{ $animal['name'] }}">
            </div>
            @endforeach
        </div>
        @endforeach
    </div>    
</div>