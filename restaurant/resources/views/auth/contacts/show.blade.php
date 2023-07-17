
<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">

    <h2 class="md:text-5xl text-2xl font-serif md:m-24 text-left md:w-4/5 m-4 leading-loose">Administrez vos demande de support</h2>

    @livewire('crud-table', ['modelName' => 'Contact'])
</x-app-layout>