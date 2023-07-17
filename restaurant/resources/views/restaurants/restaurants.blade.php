
@push('css')
    <link rel="stylesheet" type="text/css" href={{ asset('css/main.css') }}>
@endpush

<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">


    <x-image-title-banner :pageTitle="'Restaurants'" />



    <section id="restaurants" class="p-20">

        <ul class="flex flex-col gap-8 mb-0">

            @foreach ($restaurants as $restaurant)
            @if($restaurant->active == 1)
            <li >
                    <a href="/restaurants/show/{{ $restaurant->url }}" class="p-8 md:ml-8 m-4 flex flex-row items-center bg-slate-200 rounded-xl">
                    <img src="{{Storage::url($restaurant->image)}}" alt="" width="200px" heigth="200px">
                    <div class="flex flex-col gap-4">
                        <p class="ml-8 text-3xl">{{ $restaurant->name }}</p>
                        <p class="ml-12 italic text-xl">{{ substr($restaurant->description, 0, 80) }} ...</p>
                    </div>
                    </a>
                </li>
            @endif
            @endforeach

        </ul>

    </section>

</x-app-layout>
