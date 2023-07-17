<?php

namespace App\Http\Controllers\AuthControllers;

use App\Core\Popup;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends AuthController
{

    protected $rules = [
        'content' => 'required|string|min:3|max:255',
    ];

    public function show(Request $request)
    {
        if ($request->session()->has('popups')) {
            $popups = $request->session()->get('popups');
            return view('auth.comments.show', compact(
                'popups',
            ));
        } elseif ($request->session()->has('popup')) {
            $popup = $request->session()->get('popup');
            return view('auth.comments.show', compact(
                'popup',
            ));
        }
        return view('auth.comments.show');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $comment = new Comment();
        $comment->restaurant_id = $request->restaurantId;
        $comment->user_id = auth()->user()->id;
        $comment->content = $request->content;
        $comment->save();

        $popup = new Popup("comment", "success");
        $popup->addMainMessage(
            Popup::createMessage("Commentaire ajouté avec succès !", "success")
        );
        return redirect()->back()->with('popup', $popup);
    }


    public static function update($id, $editForm)
    {
        if (
            $editForm['content']['value'] == null
            || strlen($editForm['content']['value']) < 3
            || strlen($editForm['content']['value']) > 255
        ) {
            $popup = new Popup("modification de commentaire", "error");
            $popup->addMainMessage(
                Popup::createMessage("le contenu du commentaire doit contenir entre 3 et 255 caractères", "error")
            );
            return $popup;
        }

        $comment = Comment::find($id);
        $comment->content = $editForm['content']['value'];
        $comment->save();

        $popup = new Popup("modification de commentaire", "success");
        $popup->addMainMessage(
            Popup::createMessage("Commentaire modifié avec succès !", "success")
        );
        return $popup;
    }



    public static function getCrud()
    {
        return [
            'columns' => [
                'id' => '',
                'content' => 'Contenu',
                'restaurant_id' => 'Restaurant',
            ],
            'canEdit' => true,
            'isEditRemote' => false,
            'columnsAllowedToEdit' => [
                'content'
            ],
            'sortableColumns' => [
                'restaurant_id'
            ],
            'canDelete' => true,
            'modelLinks' => [
                'restaurant_id' => [
                    'columnToDisplay' => 'name',
                    'methodName' => 'restaurant',
                ]
            ],
        ];
    }

    public static function editForm($id)
    {
        $comment = Comment::find($id);
        $form = [
            'content' => [
                'type' => 'textarea',
                'value' => $comment->content,
            ],
        ];

        return $form;
    }
}
