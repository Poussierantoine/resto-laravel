<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">

    <h2 class="md:text-5xl text-2xl font-serif md:m-24 text-left md:w-4/5 m-4 leading-loose">Edition du restaurant: {{ $restaurant->name }}</h2>

    <form action="{{ route('restaurant.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8 md:w-3/4 md:ml-32">
        @method('PUT')
        @csrf
        <div class="flex flex-col gap-2">
            <label for="name">Modifier le nom du restaurant</label>
            <input type="text" name="name" id="name" value="{{ $restaurant->name }}">
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="description">Modifier la description</label>
            <textarea name="description" id="description">{{ $restaurant->description }}</textarea>
            @error('description')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="tags">Modifier les tags</label>
            <select name="tags[]" id="tags" multiple>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}"  {{ in_array($tag->id, $linkedTagsIds)?'selected':'' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
            <button class="add-visibility-to-sibling w-fit p-4 bg-indigo-300 rounded hover:bg-indigo-400" type="button" id="addTag">Demander l'ajout d'un tag</button> 
            <!--TODO faire la fonction js pour rendre visible le champ -->
            <input type="text" name="newTag" id="newTagInput" placeholder="Nouveau tag" style="display: none">
            @error('tags')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="foodTypes">Modifier les type de plats proposés</label>
            <select name="foodTypes[]" id="foodTypes" multiple>
                @foreach ($foodTypes as $foodType)
                    <option value="{{ $foodType->id }}" {{ in_array($foodType->id, $linkedFoodTypesIds)?'selected':'' }}>{{ $foodType->name }}</option>
                @endforeach
            </select>
            <button class="add-visibility-to-sibling w-fit p-4 bg-indigo-300 rounded hover:bg-indigo-400" type="button" id="addfoodType">Demander l'ajout d'un nouveau type de nourriture</button> 
            <!--TODO faire la fonction js pour rendre visible le champ -->
            <input type="text" name="newFoodType" id="newfoodTypeInput" placeholder="Nouveau type de nourriture" style="display: none">
            @error('foodTypes')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <legend>Modifier la gamme de prix</legend>
            <label for="startprice">Prix</label>
            <input type="number" name="startprice" id="startprice" value="{{ $prices[0] }}" min="0">
            <label for="endprice">Prix</label>
            <input type="number" name="endprice" id="endprice" value="{{ $prices[1] }}" min="0">
            @error('price')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="status">Modifier le status du restaurant</label>
            <select name="status" id="status">
                <option value="Ouvert" selected="{{ $restaurant->status == "Ouvert" }}">Ouvert</option>
                <option value="Fermé" selected="{{ $restaurant->status == "Fermé" }}">Fermé</option>
            </select>
            @error('status')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <div>
                <p>Ancienne image</p>
                <img src="{{ Storage::url($restaurant->image) }}" alt="image du restaurant">
            </div>
            <label for="image">Changer l'image image</label>
            <input type="file" name="image" id="image" value="{{ old('image') }}">
            @error('image')
                <div>{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit">Modifier</button>
    </form>

</x-app-layout>