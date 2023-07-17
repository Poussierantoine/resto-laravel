@push('css')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush


<x-app-layout :title="'Contact'" :popups="$popups ?? null" :popup="$popup ?? null">
    <h2 class="md:text-5xl text-2xl font-serif md:m-24 text-left md:w-4/5 m-4 leading-loose">Contactez nous</h2>
    <form action="/contacts/store" method="POST" class="flex flex-col gap-8 md:w-3/4 md:ml-32">
        @csrf
        <div class="flex flex-col gap-2">
            <label for="name">Nom: {{ $name }}</label>
            <input type="{{ $role == 'user' ? "hidden" : "text" }}" name="name" value="{{ $name }}" placeholder="John Doe">
            @error('name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="email">e-mail: {{$email}}</label>
            <input type="{{ $role == 'user' ? "hidden" : "email" }}" name="email" value="{{ $email }}" placeholder="John.Doe@bonresto.fr">
            @error('email')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="message">Message:</label>
        <textarea name="message" placeholder="Entrez votre message ici"></textarea>
        @error('message')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
        </div>
        <input type="submit" value="Envoyer" class="p-4 bg-indigo-400 w-48 rounded m-auto">
    </form>
    <!-- TODO: mettre les error -->
</x-app-layout>

