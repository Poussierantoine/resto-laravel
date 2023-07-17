<div class="p-6 lg:p-8 bg-white border-b border-gray-200 flex flex-row items-center gap-12">
    <x-icon :name="'coffee'" :imgClasses="'w-16 h-16'"/>

    <h1 class="text-2xl font-medium text-gray-900 w-fit m-0 clear-none">
        Bienvenue sur votre espace, {{ $userName }}
    </h1>
</div>

<div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <div class="row-span-2">
        <h2 class="p-4 text-2xl">
            Mes restaurants
        </h2>

        @if ($hasRestaurant)
            @foreach ($FirstsRestaurants as $restaurant)
                <div class="mb-4 bg-white border-b border-gray-200 p-6 lg:p-8">
                    <h3 class="text-xl font-medium text-gray-900">
                        {{ $restaurant->name }}
                    </h3>
                    @if($restaurant->active == 0)
                        <span class="ml-2 text-red-500 text-sm font-medium">
                            Votre restaurant est inactif, il sera activé sous peu par les administrateurs
                        </span>
                    @endif
                    <a href="{{ route('restaurant.show', $restaurant->url) }}" class="text-blue-500 hover:text-blue-600">
                        Voir le restaurant
                    </a>
                </div>
            @endforeach
            <a href="{{ route('restaurants.show') }}" class="ml-6 text-xl text-blue-500 hover:text-blue-600">
                Voir tous mes restaurants
            </a>
        @endif
        <a href="{{ route('restaurant.create') }}" class="ml-6 text-xl text-blue-500 hover:text-blue-600">
            Créer un restaurant
        </a>
    </div>

    <div>
        <h2 class="p-4 text-2xl">
            Mes commentaires
        </h2>

        @if ($hasComments)
            <a href="{{ route('comments.show') }}" class="ml-6 text-blue-500 hover:text-blue-600">
                Voir tous mes commentaires
            </a>
        @endif

    </div>

    <div>
        <h2 class="p-4 text-2xl">
            Mes contact-support
        </h2>
        @if ($hasContacts)
            <a href="{{ route('contacts.show') }}" class="ml-6 text-blue-500 hover:text-blue-600">
                Voir tous mes contact-support
            </a>
        @endif


    </div>

</div>
