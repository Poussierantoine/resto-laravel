<?php

namespace App\Core;

use Illuminate\Support\Facades\Storage;

class Image
{


    // Constantes

    private const TARGET = 'public/images/';    // Repertoire cible
    private const MAX_SIZE = 100000;    // Taille max en octets du fichier
    private const MAX_IMG_SIZE = 800;    // Largeur ou hauteur max de l'image en pixels
    private const EXTENSION = array('jpg', 'gif', 'png', 'jpeg');    // Extensions autorisees

    private const THUMBNAIL_SIZE = 200; // taille (h ou w -> limitant) maximale de la miniature en pixels


    // Variables

    /**
     * @var \App\Core\Popup
     */
    private $popup; // Messages d'eereurs à afficher
    private $popupIntegration = false; // true si on veut integrer les messages à un popup principal

    private $tmp_name; // Nom de l'image dans le répertoire temporaire de php
    private $tmp_path; // Chemin de l'image dans le répertoire temporaire de php
    private $mimeType = ''; // Extension du fichier

    private $size; // Taille de l'image en octets
    private $originalWidth; // Largeur de l'image
    private $originalHeight; // Hauteur de l'image

    private $folder; // Dossier de destination de l'image
    private $imgName = ''; // nom de la nouvelle image


    /**
     * initialise les variables
     * penser a verifier si les variables globales post et files ne sont pas vides
     * @param \Illuminate\Http\UploadedFile $file : fichier récuperé par la requete POST
     * @param string $tmp_filePathFromStorage : chemin de l'image dans le dossier storage
     * @param string $folder : dossier de destination de l'image à partir du dossier self::TARGET
     * @param \App\Core\Popup $popup : popup si on veut integrer les messages à un popup principal
     * @param bool $keepName : true si on veut garder le nom d'origine de l'image
     */
    public function __construct(
        $file,
        $tmp_filePathFromStorage,
        \App\Core\Popup $popup = null,
        $keepName = false
    ) {
        if ($popup !== null) {
            $this->popup = $popup;
            $this->popupIntegration = true;
        } else {
            $this->popup = new Popup("Upload d'image", "info");
        }
        if (
            $file->getError() !== UPLOAD_ERR_OK
        ) {
            $this->message(Popup::createMessage("Erreur : l'import n'a pas fonctionné", "error"), true);
        } else {
            $tmp_filePathFromStorage = str_replace('\\', '/', $tmp_filePathFromStorage);
            $tmp_filePathFromStorageArray = explode('/', $tmp_filePathFromStorage);
            $this->tmp_name = $tmp_filePathFromStorageArray[count($tmp_filePathFromStorageArray) - 1];
            $this->tmp_path = substr(
                $tmp_filePathFromStorage,
                0,
                strlen($tmp_filePathFromStorage) - strlen($this->tmp_name)
            );
            $this->setFileSize($file->getSize());
            $this->setMimeType($file->getMimeType());
            $this->setImageSize($this->tmp_name);
            if ($keepName) {
                $this->setImageName($file->getClientOriginalName());
            }
        }
    }

    /**
     * ajoute au popup un message en parametre, il faut que le message passé corresponde aux criteres de la classe Popup
     * @param array|string $msg
     * @param bool $isMain : true si on veut que ce soit le message principal
     */
    public function message(array|string $msg, bool $isMain = false)
    {
        if ($isMain && $this->popupIntegration) {
            $this->popup->addMainMessage($msg);
        } else {
            $this->popup->addMessage($msg);
        }
    }


    private function setFileSize($fileSize)
    {
        $this->size = $fileSize;
        if ($this->size > self::MAX_SIZE) {
            $this->message(Popup::createMessage(
                "Le fichier est trop volumineux, taille max autorisée: " . self::MAX_SIZE . " octets",
                "error"
            ));
        }
    }

    private function setMimeType($fileMime)
    {
        $mime = explode('/', $fileMime);
        $this->mimeType = $mime[count($mime) - 1];

        if (!in_array($this->mimeType, self::EXTENSION)) {
            $this->message(Popup::createMessage(
                "Le type de fichier ne correspond pas, types de fichiers autorisés: " . implode(', ', self::EXTENSION),
                "error"
            ));
        }
    }

    private function setImageSize($tmp_name)
    {
        try {
            $imgSizeReturn = getimagesize(Storage::path($this->tmp_path . $tmp_name));
            $this->originalWidth = $imgSizeReturn[0];
            $this->originalHeight = $imgSizeReturn[1];

            if (
                $this->mimeType !== 'svg'
                && ($this->originalWidth > self::MAX_IMG_SIZE || $this->originalHeight > self::MAX_IMG_SIZE)
            ) {
                try {
                    $this->resizeImage(self::MAX_IMG_SIZE);
                    $this->message(Popup::createMessage(
                        "La taille de l'image est trop grande, elle a été redimensionnée.
                    Taille maxaximale autorisée: " . self::MAX_IMG_SIZE . "x" . self::MAX_IMG_SIZE . " pixels",
                        "warning"
                    ));
                } catch (\Exception $e) {
                    $this->message(Popup::createMessage(
                        "L'image est trop grande mais n'a pas pu être redimensionnée, verifiez le type de fichier",
                        "error"
                    ));
                }
            }
        } catch (\Exception $e) {
            $this->message(Popup::createMessage(
                "La taille de l'image n'a pas pu être déterminée, verifiez le type de fichier",
                "error"
            ));
        }
    }

    private function setImageName($fileName)
    {
        $name = explode('.', $fileName)[0];
        $name = str_replace(' ', '_', $name);
        $name = strtolower($name);
        $this->imgName = $name;
    }


    /**
     * fonction qui upload l'image sous un nom uniqid si un name n'est pas déjà defini
     * @param bool $withThumbnail : true si on veut créer une miniature de l'image
     * @return string : nom de l'image, null si il y a eu des erreurs
     */
    public function upload($folder, $withThumbnail = false, $copyInsteadOfMove = false)
    {
        $folder = str_ends_with($folder, '/') ? $folder : $folder . '/';

        if ($this->popup->getType() === 'error') {
            return null;
        }
        if ($this->imgName === '') {
            $this->rename();
        }
        try {
            if ($withThumbnail) {
                $this->thumbnailUpload($folder, true);
            }

            $from = $this->tmp_path . $this->tmp_name;
            $destination = self::TARGET . $folder . $this->imgName;
            if ($copyInsteadOfMove) {
                $success = Storage::copy($from, $destination);
            } else {
                $success = Storage::move($from, $destination);
            }

            if ($success) {
                $this->message(Popup::createMessage("Upload de l'image reussi", 'success'));
            } else {
                $this->message(Popup::createMessage("Upload de l'image n'a pas réussi", 'error'));
            }

            if ($withThumbnail && !$copyInsteadOfMove) {
                Storage::delete($this->tmp_path . $this->tmp_name);
            }

            return $destination;
        } catch (\Exception $e) {
            $this->message(Popup::createMessage(
                "L'image n'a pas pu être uploadée,
                vérifiez que vous diposiez des droits suffisants pour le faire ou créez le manuellement !",
                'error'
            ));
            return null;
        }
    }

    /**
     * on réutilise l'unique id créé par Storage::store() pour le nom de l'image
     */
    private function rename()
    {
        $this->imgName = $this->tmp_name;
    }




    /**
     * upload la miniature de l'image (resized) dans un repertoire thumbnails dans le repertoire $his->folder
     * si aucun nom n'est defini pour l'image, on lui donne un nom uniqid
     */
    public function thumbnailUpload($folder, $copyInsteadOfMove = false)
    {
        $folder = str_ends_with($folder, '/') ? $folder : $folder . '/';

        if ($this->popup->getType() === 'error') {
            return null;
        }
        try {
            if ($this->imgName === '') {
                $this->rename();
            }
            $newImageName = $this->resizeImage(self::THUMBNAIL_SIZE, $copyInsteadOfMove);

            $destination = self::TARGET . $folder . 'thumbnails/' . $this->getThumbnailName();
            $success = Storage::move(
                $this->tmp_path . $newImageName,
                $destination
            );


            if ($success) {
                $this->message(Popup::createMessage("Upload de la miniature reussi", 'success'));
            } else {
                $this->message(Popup::createMessage("Erreur lors de l'upload de la miniature", 'error'));
            }

            return $destination;
        } catch (\Exception $e) {
            $this->message(Popup::createMessage("Erreur lors de la création de la miniature", 'error'));
        }
    }

    /**
     * fonction qui redimensionne l'image avec les dimensions passées en parametre (sauf pour les svg)
     * @param int $width
     * @param int $height
     * @param bool $copyAndResize : true si on veut redimensionner l'image dans une copie
     * @return string : nom de l'image redimensionnée (repertoir $this->tmp_path)
     */
    public function resizeImage($maxSize, $copyAndResize = false)
    {
        if ($this->mimeType === 'png') {
            $image = imagecreatefrompng(Storage::path($this->tmp_path . $this->tmp_name));
        } elseif ($this->mimeType === 'jpg' || $this->mimeType === 'jpeg') {
            $image = imagecreatefromjpeg(Storage::path($this->tmp_path . $this->tmp_name));
        } elseif ($this->mimeType === 'gif') {
            $image = imagecreatefromgif(Storage::path($this->tmp_path . $this->tmp_name));
        }
        if ($this->mimeType !== 'svg') {

            $ratio = ($this->originalHeight >= $this->originalWidth) ?
                $this->originalHeight / $maxSize : $this->originalWidth / $maxSize;

                $newHeight = $this->originalHeight / $ratio;
                $newWidth = $this->originalWidth / $ratio;

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled(
                $newImage,
                $image,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $this->originalWidth,
                $this->originalHeight
            );
            if ($copyAndResize) {
                $newImagePath = $this->tmp_path . 'resized_' . $this->tmp_name;
                imagejpeg($newImage, Storage::path($newImagePath));
                return 'resized_' . $this->tmp_name;
            } else {
                imagejpeg($newImage, Storage::path($this->tmp_path . $this->tmp_name));
                $this->mimeType = 'jpg';
            }
            imagedestroy($image);
            imagedestroy($newImage);
        }
        return $this->tmp_name;
    }

    public function getName()
    {
        return $this->imgName;
    }

    public function getThumbnailName()
    {
        return 'thumb_' . $this->imgName;
    }

    public function setName($name)
    {
        $name = explode('.', $name)[0];
        $name = str_replace(' ', '_', $name);
        $name = strtolower($name);
        $this->imgName = $name;
    }

    public function deleteTmpFile()
    {
        Storage::delete($this->tmp_path . $this->tmp_name);
    }
}
