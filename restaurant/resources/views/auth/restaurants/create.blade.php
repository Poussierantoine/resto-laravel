<x-app-layout :popups="$popups ?? null" :popup="$popup ?? null">

    <h2 class="md:text-5xl text-2xl font-serif md:m-24 text-left md:w-4/5 m-4 leading-loose">Créer un restaurant</h2>

    <form action="/restaurants/store" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8 md:w-3/4 md:ml-32">
        @csrf
        <div class="flex flex-col gap-2">
            <label for="name">Nom du restaurant</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="description">Description</label>
            <textarea name="description" id="description">{{ old('description') }}</textarea>
            @error('description')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="tags">Tags</label>
            <select name="tags[]" id="tags" multiple="multiple">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags')??[])?'selected':'' }}>{{ $tag->name }}</option>
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
            <label for="foodTypes">Type de plats proposés</label>
            <select name="foodTypes[]" id="foodTypes" multiple="multiple">
                @foreach ($foodTypes as $foodType)
                    <option value="{{ $foodType->id }}" {{ in_array($foodType->id, old('foodTypes')??[])?'selected':'' }}>{{ $foodType->name }}</option>
                @endforeach
            </select>
            <button class="add-visibility-to-sibling w-fit p-4 bg-indigo-300 rounded hover:bg-indigo-400" type="button" id="addfoodType">Demander l'ajout d'un nouveau type
                de nourriture</button>
            <!--TODO faire la fonction js pour rendre visible le champ -->
            <input type="text" name="newFoodType" id="newfoodTypeInput" placeholder="Nouveau type de nourriture"
                style="display: none">
            @error('foodTypes')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <legend>Gamme de prix</legend>
            <label for="startprice">Prix</label>
            <input type="number" name="startprice" id="startprice" value="{{ old('startprice') }}" min="0">
            <label for="endprice">Prix</label>
            <input type="number" name="endprice" id="endprice" value="{{ old('endprice') }}" min="0">
            @error('price')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="status">Status du restaurant</label>
            <select name="status" id="status">
                <option value="Ouvert">Ouvert</option>
                <option value="Fermé">Fermé</option>
            </select>
            @error('status')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col gap-2">
            <label for="image">Ajouter une image principale à votre restaurant</label>
            <input type="file" name="image" id="image" value="{{ old('image') }}" accept="image/*" class="w-fit">
            @error('image')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class=" p-4 bg-indigo-500 rounded hover:bg-indigo-600 mb-16">Créer</button>
    </form>


</x-app-layout>
