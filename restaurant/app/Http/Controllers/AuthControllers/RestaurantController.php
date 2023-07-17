<?php

namespace App\Http\Controllers\AuthControllers;

use App\Core\Image;
use App\Models\FoodType;
use App\Models\Restaurant;
use App\Models\Tag;
use App\Models\User;
use App\Core\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends AuthController
{

    //TODO verifier si le max c'est bien des caracteres ... max_digits?
    protected $rules = [
        'name' => 'required|unique:restaurants,name|max:255',
        'description' => 'required|min:3|max:1000',
        'tags' => 'required',
        'newTag' => 'nullable|max:20',
        'foodTypes' => 'required',
        'newFoodType' => 'nullable|max:20',
        'startprice' => 'required|lte:endprice',
        'endprice' => 'required',
        'status' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function show(Request $request)
    {
        if ($request->session()->has('popups')) {
            $popups = $request->session()->get('popups');
            return view('auth.restaurants.show', compact(
                'popups',
            ));
        } elseif ($request->session()->has('popup')) {
            $popup = $request->session()->get('popup');
            return view('auth.restaurants.show', compact(
                'popup',
            ));
        }
        return view('auth.restaurants.show');
    }

    public function create(Request $request)
    {
        $tags = Tag::where('active', true)->get();
        $foodTypes = FoodType::where('active', true)->get();
        $view = 'auth.restaurants.create';
        if ($request->session()->has('popups')) {
            $popups = $request->session()->get('popups');
            return view(
                $view,
                compact(
                    'tags',
                    'foodTypes',
                    'popups'
                )
            );
        } elseif ($request->session()->has('popup')) {
            $popup = $request->session()->get('popup');
            return view(
                $view,
                compact(
                    'tags',
                    'foodTypes',
                    'popup'
                )
            );
        }

            return view(
                $view,
                compact(
                    'tags',
                    'foodTypes',
                )
            );
    }

    public function store(Request $request)
    {

        $this->validate($request, $this->rules);



        $popup = new Popup("Création de restaurant", "success");

        $restaurant = new Restaurant();

        /**
         * upload de l'image
         */
        $imageUploaded = $request->file('image');
        $tmp_pathToImage = $imageUploaded->store('tmp');

        $image = new Image($imageUploaded, $tmp_pathToImage, $popup);
        $imageName = $image->upload('restaurants', withThumbnail: true);
        //l'upload renvoi null si une erreur est presente dans le popup


        /**
         * creation du restaurant
         */

        $price = $request->startprice . ' - ' . $request->endprice . ' €';
        $url = str_replace(' ', '-', $request->name);
        $user_id = $request->user()->id;


        $restaurant->user_id = $user_id;
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->price = $price;
        $restaurant->url = $url;
        $restaurant->status = $request->status;
        $restaurant->image = $imageName;
        $restaurant->active = false;


        if ($popup->getType() == "error") {
            return redirect()->route('restaurant.create')->withInput()->with('popup', $popup);
        }

        $restaurant->save();



        /**
         * ajout des tags et foodTypes et associations
         */
        foreach ($request->tags as $tag_id) {
            $popup->addMessage(TagRestaurantController::newTagRestaurantAssociation($tag_id, $restaurant->id));
        }
        if ($request->newTag) {
            $popup->addMessage(TagController::newTagRequest($request->newTag));
            $popup->addMessage(
                TagRestaurantController::newTagRestaurantAssociation(
                    $restaurant->tags()->where('name', $request->newTag)->first()->id,
                    $restaurant->id
                )
            );
        }
        foreach ($request->foodTypes as $foodType_id) {
            $popup->addMessage(
                FoodTypeRestaurantController::newFoodTypeRestaurantAssociation($foodType_id, $restaurant->id)
            );
        }
        if ($request->newFoodType) {
            $popup->addMessage(FoodTypeController::newFoodTypeRequest($request->newFoodType));
            $popup->addMessage(
                FoodTypeRestaurantController::newFoodTypeRestaurantAssociation(
                    $restaurant->foodTypes()->where('name', $request->newFoodType)->first()->id,
                    $restaurant->id
                )
            );
        }

        /**
         * on ne continue pas si une erreur est presente dans le popup
         */
        if ($popup->getType() == "error") {
            $popup->addMainMessage(Popup::createMessage(
                'Votre demande n\'a pas pu être prise en compte, veuillez réessayer',
                'error'
            ));
            return redirect()->route('restaurant.create')->withInput()->with('popup', $popup);
        }


        $popup->addMainMessage(Popup::createMessage(
            'Votre demande a bien été prise en compte, votre restaurant sera activé dès que possible',
            'success'
        ));

        return redirect()->route('restaurants.show')->with('popup', $popup);
    }

    public static function edit(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        $tags = Tag::where('active', true)->get();
        $foodTypes = FoodType::where('active', true)->get();
        $linkedTagsIds = $restaurant->tags()->pluck('tags.id')->toArray();
        $linkedFoodTypesIds = $restaurant->foodTypes()->pluck('food_types.id')->toArray();
        $prices = explode(' - ', $restaurant->price);
        $prices[1] = explode(' €', $prices[1])[0];
        if ($request->session()->has('popups')) {
            $popups = $request->session()->get('popups');
            return view(
                'auth.restaurants.edit',
                compact(
                    'restaurant',
                    'tags',
                    'foodTypes',
                    'linkedTagsIds',
                    'linkedFoodTypesIds',
                    'prices',
                    'popups',
                )
            );
        } elseif ($request->session()->has('popup')) {
            $popup = $request->session()->get('popup');
            return view(
                'auth.restaurants.edit',
                compact(
                    'restaurant',
                    'tags',
                    'foodTypes',
                    'linkedTagsIds',
                    'linkedFoodTypesIds',
                    'prices',
                    'popup',
                )
            );
        }
        return view(
            'auth.restaurants.edit',
            compact(
                'restaurant',
                'tags',
                'foodTypes',
                'linkedTagsIds',
                'linkedFoodTypesIds',
                'prices',
            )
        );
    }

    public function update(Request $request, int $id)
    {
        $rules = $this->rules;
        $rules['name'] = 'required|max:255';
        $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        $this->validate($request, $rules);

        $popup = new Popup("Modification du restaurant", "success");

        $price = $request->startprice . ' - ' . $request->endprice;
        $url = str_replace(' ', '-', $request->name);
        $user_id = $request->user()->id;

        $restaurant = Restaurant::find($id);

        $restaurant->user_id = $user_id;
        if ($request->name !== $restaurant->name) {
            $restaurant->name = $request->name;
            $restaurant->url = $url;
            $popup->addMessage(
                Popup::createMessage(
                    "Vous avez modifié le nom du restaurant, il sera donc désormais accessible à l'adresse : " . $url,
                    "success"
                )
            );
        }
        $restaurant->description = $request->description;
        $restaurant->price = $price;
        $restaurant->status = $request->status;
        $restaurant->active = false;

        /**
         * upload de l'image
         */
        if ($request->image) {

            $imageUploaded = $request->file('image');
            $tmp_pathToImage = $imageUploaded->store('tmp');

            $image = new Image($imageUploaded, $tmp_pathToImage, $popup);
            $imageName = $image->upload('restaurants', withThumbnail: true);
            //l'upload renvoi null si une erreur est presente dans le popup
            if ($imageName !== null) {
                $thumbnailPath = explode('/', $restaurant->image);
                $thumbnail[count($thumbnailPath) - 1] = 'thumbnail/thumb_' . $thumbnailPath[count($thumbnailPath) - 1];
                $thumbnailPath = implode('/', $thumbnailPath);
                Storage::delete($thumbnailPath);
                Storage::delete($restaurant->image);
            } else {
                $popup->addMessage(
                    Popup::createMessage(
                        "L'ancienne image a été conservée suite à une erreur lors de l'upload de la nouvelle image",
                        "error"
                    )
                );
            }
            $restaurant->image = $imageName;
        }

        $restaurant->save();

        /**
         * Gestion des tags
         */
        $linkedtags = $restaurant->tags()->pluck('tags.id')->toArray();

        foreach ($request->tags as $tag_id) {
            if (!in_array($tag_id, $linkedtags)) {
                $popup->addMessage(TagRestaurantController::newTagRestaurantAssociation($tag_id, $restaurant->id));
            }
        }
        foreach ($linkedtags as $tag_id) {
            if (!in_array($tag_id, $request->tags)) {
                $popup->addMessage(TagRestaurantController::deleteTagRestaurantAssociation($tag_id, $restaurant->id));
            }
        }
        if ($request->newTag) {
            $popup->addMessage(TagController::newTagRequest($request->newTag));
            $popup->addMessage(
                TagRestaurantController::newTagRestaurantAssociation(
                    $restaurant->tags()->where('name', $request->newTag)->first()->id,
                    $restaurant->id
                )
            );
        }

        /**
         * Gestion des foodTypes
         */
        $linkedFoodTypes = $restaurant->foodTypes()->pluck('food_types.id')->toArray();

        foreach ($request->foodTypes as $foodType_id) {
            if (!in_array($foodType_id, $linkedFoodTypes)) {
                $popup->addMessage(
                    FoodTypeRestaurantController::newFoodTypeRestaurantAssociation($foodType_id, $restaurant->id)
                );
            }
        }
        foreach ($linkedFoodTypes as $foodType_id) {
            if (!in_array($foodType_id, $request->foodTypes)) {
                $popup->addMessage(
                    FoodTypeRestaurantController::deleteFoodTypeRestaurantAssociation($foodType_id, $restaurant->id)
                );
            }
        }
        if ($request->newFoodType) {
            $popup->addMessage(FoodTypeController::newFoodTypeRequest($request->newFoodType));
            $popup->addMessage(
                FoodTypeRestaurantController::newFoodTypeRestaurantAssociation(
                    $restaurant->foodTypes()->where('name', $request->newFoodType)->first()->id,
                    $restaurant->id
                )
            );
        }

        if ($popup->getType() == "error") {
            $popup->addMainMessage(['message' => 'Vos modifications n\'ont pas pu être prises en compte', 'type' => 'error']);
            return redirect()->route('restaurant.edit', $id)->withInput()->with('popup', $popup);
        }

        $popup->addMainMessage(['message' => 'Vos modifications ont bien été prises en compte', 'type' => 'success']);

        return redirect()->route('restaurants.show')->with('popup', $popup);
    }

    public static function delete($id)
    {
        $restaurant = Restaurant::find($id);
        if ($restaurant->image) {
            Storage::delete($restaurant->image);
        }
        $popup = new Popup("Suppression du restaurant $restaurant->name", "success");
        $restaurant->delete();
        return redirect()->route('restaurants.show')->with('popup', $popup);
    }




    public static function getCrud()
    {
        return [
            'columns' => [
                'id' => '',
                'name' => 'Nom',
                'description' => 'Description',
                'price' => 'Gamme de prix',
                'tags' => 'Tags',
                'foodTypes' => 'Types de cuisine proposée',
                'status' => 'Status',
                'image' => 'Image principale',
            ],
            'canEdit' => true,
            'isEditRemote' => true,
            'columnsAllowedToEdit' => [
                'name',
                'description',
                'price',
                'tags',
                'foodTypes',
                'status',
                'image',
            ],
            'sortableColumns' => [
                'name',
                'status'
            ],
            'canDelete' => true,
            'imagesColumns' => [
                'image',
            ],
            'modelLinks' => [
                'tags' => [
                    'columnToDisplay' => 'name',
                    'methodName' => 'tags',
                ],
                'foodTypes' => [
                    'columnToDisplay' => 'name',
                    'methodName' => 'foodTypes',
                ],
            ],
        ];
    }
}
