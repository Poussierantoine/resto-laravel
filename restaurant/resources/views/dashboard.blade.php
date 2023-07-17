<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">
    @php
        if (!isset($role)) {
            $role = Auth::user()->role;
        }
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if ($role == 'admin')
                    <x-admin-dashboard />
                @else
                    <x-user-dashboard />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
