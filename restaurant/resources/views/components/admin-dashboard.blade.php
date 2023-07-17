@php
    $inactiveTags = \App\Models\Tag::where('active', false)->count();
    $inactiveFoodTypes = \App\Models\FoodType::where('active', false)->count();
    $inactiveRestaurants = \App\Models\Restaurant::where('active', false)->count();
@endphp


<div class="p-6 lg:p-8 bg-white border-b border-gray-200 flex flex-row items-center gap-12">
    <x-icon :name="'coffee'" :imgClasses="'w-16 h-16'" />

    <h1 class="text-2xl font-medium text-gray-900 w-fit m-0 clear-none">
         Administation du site
    </h1>
</div>

<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <div>
        <a href="{{ route('admin.tags.show') }}" class="p-2 border border-indigo-400 rounded text-xl text-indigo-600">
            Gestion des tags
        </a>
        @if($inactiveTags > 0)
            <span class="ml-8 text-red-500 text-sm font-medium">
                ({{ $inactiveTags }} inactifs)
            </span>
        @endif
    </div>

    <div>
        <a href="{{ route('admin.foodTypes.show') }}" class="p-2 border border-indigo-400 rounded text-xl text-indigo-600">
            Gestion des FoodTypes
        </a>
        @if($inactiveFoodTypes > 0)
            <span class="ml-8 text-red-500 text-sm font-medium">
                ({{ $inactiveFoodTypes }} inactifs)
            </span>
        @endif
    </div>

    <div>
        <a href="{{ route('admin.restaurants.show') }}" class="p-2 border border-indigo-400 rounded text-xl text-indigo-600">
            Gestion des restaurants
        </a>
        @if($inactiveRestaurants > 0)
            <span class="ml-8 text-red-500 text-sm font-medium">
                ({{ $inactiveRestaurants }} inactifs)
            </span>
        @endif
    </div>

    <div>
        <a href="{{ route('admin.contacts.show') }}" class="p-2 border border-indigo-400 rounded text-xl text-indigo-600">
            Gestion des demmandes de support
        </a>
    </div>

    <div>
        <a href="{{ route('admin.users.show') }}" class="p-2 border border-indigo-400 rounded text-xl text-indigo-600">
            Gestion des Users
        </a>
    </div>

    
</div>
