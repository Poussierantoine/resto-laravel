@push('css')
    <style>
        #imageTitle {
            background-image: url({{ Storage::url($restaurantImage) }});
        }
        #imageTitle *{
            text-shadow: 0 0 15px rgb(0,0,0)
        }
    </style>
@endpush


<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">

    <x-image-title-banner :pageTitle="$restaurantName" />

    <section id="restaurant">
        <h2 class="ml-6 md:text-5xl text-2xl font-serif md:mt-24 mt-4 text-left md:w-4/5 leading-loose">
            {{ $restaurantName }}</h2>
        <p class="mt-6 mb-2 md:ml-16 h-fit">
            @foreach ($tags as $tag)
                @if ($tag->active)
                    <span
                        class="mr-2 p-2 border border-indigo-300 rounded rounded-tl-lg rounded-br-lg text-center text-lg italic leading-loose">{{ $tag->name }}</span>
                @endif
            @endforeach
            @foreach ($foodTypes as $foodType)
                @if ($foodType->active)
                    <span
                        class="mr-2 p-2 border border-yellow-500 rounded rounded-tl-lg rounded-br-lg text-center text-lg italic leading-loose">{{ $foodType->name }}</span>
                @endif
            @endforeach
        </p>
        <h3 class="ml-8 md:ml-16 m-4 text-xl md:text-2xl text-slate-500">Cet établissement est tenu par
            {{ $owner }}</h3>
        <p id="description" class="pl-24 text-lg">{{ $restaurantDescription }}</p>
    </section>

    <section id="comments" class="md:w-2/4">
        <h2 class="md:text-3xl text-xl font-serif md:mt-20 mt-4 text-center leading-loose">Commentaires</h2>
        <div class=" mt-12 flex flex-col items-center">
            @if ($role === 'user')
                <form action="{{ route('comment.store') }}" method="POST" class=" md:w-2/4 p-4 flex flex-col items-center">
                    @csrf
                    <input type="hidden" name="restaurantId" value="{{ $restaurantId }}">
                    <textarea name="content" id="content" rows="5" placeholder="Donnez votre avis" class="rounded-lg w-full"></textarea>
                    <button type="submit" class="m-4 w-full p-2 bg-indigo-300 rounded">Envoyer</button>
                </form>
            @endif
        <ul class="md:w-2/4 rounded-xl border bg-slate-100">
            @foreach ($comments as $comment)
                <li class="m-6 p-6 bg-slate-200 rounded-lg shadow-md">
                    <p class="text-lg m-2 font-bold">{{ $comment['name'] }}</p>
                    <p class="italic">"{{ $comment['content'] }}"</p>
                </li>
            @endforeach
        </ul>
        </div>
    </section>

</x-app-layout>
