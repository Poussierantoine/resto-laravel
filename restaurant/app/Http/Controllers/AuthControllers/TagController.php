<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Core\Popup;


class TagController extends Controller
{


    /**
     * demande l'ajout d'un nouveau tag et le crée avec $active = false
     * @param string $name
     * @return array popup a afficher dans le redirect
     */
    public static function newTagRequest($name)
    {
        $name = ucfirst(strtolower($name));
        if (Tag::where('name', $name)->exists()) {
            $popup = Popup::createMessage('Le tag demandé existe déjà, vous avez été affecté à ce tag', 'warning');
        } else {
            $tag = new Tag();
            $tag->name = $name;
            $tag->active = false;
            $tag->save();
            
            $popup = Popup::createMessage(
                'Votre demande a bien été prise en compte, vous serez affecté à ce tag dès que possible',
                'success'
            );
        }
        return $popup;
    }



    /**
     * rend un tag actif
     * @param int $tag_id
     * @return array popup a afficher dans le redirect
     */
    public static function activation($tag_id)
    {
        $tag = Tag::find($tag_id);
        if ($tag) {
            $tag->active = true;
            $tag->save();
            $popup = Popup::createMessage('Le tag a bien été activé', 'success');
        } else {
            $popup = Popup::createMessage('Le tag n\'existe pas', 'error');
        }
        return $popup;
    }
}
