<h1 id="p-m-title">{{ __('app.section.animals.description') }}</h1>
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
    <h4>{{ __('app.section.animals.appearance') }}</h4>
    <p class="fs-4">{{ $type->appearance }}</p>
    <hr>
    <h4>{{ __('app.section.animals.food') }}</h4>
    <p class="fs-4">{{ $type->food }}</p>
</div>
<div class="white-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <img src="{{ asset('assets/icons/yellow-star.png') }}" class="mr-3" height="40" alt="Star icon">
        <h3 class="text-muted">{{ __('app.section.animals.well_known') }} {{ trans_choice('app.section.animals.ranks.' . $closestDescendantRank, 2) }}</h3>
    </div>
    <div class="animals-container">
        @foreach($descendantsByCategory as $category => $descendants)
        <div class="row mb-3">
            <h4>{{ $category }}</h4>
            @foreach($descendants as $descendant)
            @foreach($descendant as $species)
            <div class="single-animal col-auto mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fa-solid fa-link"></i>
                    <a class="species-link fs-4 text-muted" href="/{{ app()->getLocale() . '/species/' . $species->gbif_id }}">
                        {{ $species->name }}
                    </a>
                </div>
                <img src="{{ asset('assets/images/' . $species->gbif_id . '/' . $species->filename) }}" height="100" alt="{{ $species->name }}">
            </div>
            @endforeach
            @endforeach
        </div>
        @endforeach
    </div>    
</div>
