
<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">

    <h2 class="md:text-5xl text-2xl font-serif md:m-24 text-left md:w-4/5 m-4 leading-loose">Administrez vos commentaires</h2>

    @livewire('crud-table', ['modelName' => 'Comment'])
</x-app-layout>