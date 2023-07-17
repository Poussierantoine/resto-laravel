@push('css')
    <link rel="stylesheet" href="/css/welcome.css">
@endpush


@section('pageTitle')
    Accueil
@endsection

<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">
    <x-image-title-banner :pageTitle="'Accueil'" />

    <section id="restaurants">
        <h2 class="md:text-5xl text-2xl font-serif md:m-24 text-left md:w-4/5 m-4 leading-loose">Decouvrez nos restaurants</h2>
        <ul class="carousel">
            @foreach ($restaurants as $restaurant)
                <li class="item">
                    <div class="item-image">
                        <img src="{{ Storage::url($restaurant->image) }}" alt="{{ $restaurant->name }} - image">
                    </div>
                    <div class="item-text absolute bottom-0 w-3/4 p-1.5 text-slate-100 text-base cursor-pointer rounded" onclick="location.href = '/restaurants/{{ $restaurant->url }}';">
                        <p class="item-title">{{ $restaurant->name }}</p>
                        <p class="item-description">{{ substr($restaurant->description, 0, 100) }}...</p>
                    </div>
                </li>
            @endforeach

            <li class="item">
                <div class="item-text" id="item-link_show-all" onclick="location.href = '/restaurants/';">
                    <p>Voir tous les restaurants</p>
                </div>
            </li>
        </ul>
        <div class="h-64 w-8"></div>
    </section>


</x-app-layout>
