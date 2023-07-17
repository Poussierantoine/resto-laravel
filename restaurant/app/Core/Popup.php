<?php

namespace App\Core;


class Popup
{


    private $operation;
    private $mainMessage;
    private $messages;
    private $type;

    /**
     * @param $mainMessage string : message principal du popup
     * @param $type string : type de message (success, error, warning, info)
     * @param $messages array : tableau de messages secondaires
     * les messages sont sous la forme ['message' => 'le message', 'type' => 'type']
     * 
     */
    public function __construct($operation, $type, $messages = [])
    {
        $this->operation = $operation;
        $this->type = $type;
        $this->messages = [];
        if (is_array($messages)) {
            foreach ($messages as $message) {
                $this->addMessage($message);
            }
        } elseif (is_string($messages)) {
            $this->addMessage($messages);
        } else {
            throw new \InvalidArgumentException(
                "le parametre \$messages doit etre un tableau ou une chaine de caractere"
            );
        }
    }

    /**
     * renvoi un message sous forme de tableau ['message' => 'le message', 'type' => 'type'] à partir des parametres
     * @param $message string : message
     * @param $type string : type de message (success, error, warning, info)
     * @return array : tableau de message
     */
    public static function createMessage($message, $type)
    {
        if (!is_string($message)) {
            throw new \InvalidArgumentException(
                "le parametre \$message doit etre une chaine de caractere"
            );
        } elseif (!is_string($type)) {
            throw new \InvalidArgumentException(
                "le parametre \$type doit etre une chaine de caractere"
            );
        } elseif (!in_array($type, ['success', 'error', 'warning', 'info'])) {
            throw new \InvalidArgumentException(
                "le parametre \$type doit etre une chaine de caractere parmi : success, error, warning, info"
            );
        } else {
            return ['message' => $message, 'type' => $type];
        }
    }

    /**
     * ajoute un message au tableau de messages secondaires en transformant si besoin
     * leve une erreur si le parametre n'est pas un tableau ou une chaine de caractere
     * @param $message array|string : tableau de message ou chaine de caractere
     * si tableau : ['message' => 'le message', 'type' => 'type']
     */
    public function addMessage($message)
    {
        $this->isValidMessage($message);
        if (is_string($message)) {
            $message = ['message' => $message, 'type' => 'info'];
        } elseif (is_array($message)) {
            if (
                count($message) < 2
                || (isset($message['type']) && $message['type'] == "")
                || (isset($message[1]) && $message[1] == "")
            ) {
                $message = ['message' => $message[0], 'type' => 'info'];
            } elseif (!isset($message['type']) || !isset($message['message'])) {
                $message = ['message' => $message[0], 'type' => $message[1]];
            }
            if ($message['type'] !== $this->type) {
                $this->changeType($message['type']);
            }
        }
        $this->messages[] = $message;
    }

    /**
     * verifie que le message est valide
     * @param $message array|string : tableau (length <=2) de message ou chaine de caractere
     */
    private function isValidMessage($message)
    {
        if (is_array($message)) {
            if (count($message) > 2) {
                throw new \InvalidArgumentException(
                    "le tableau \$message ne doit pas contenir plus de 2 elements"
                );
            }
            foreach ($message as $value) {
                if (!is_string($value)) {
                    throw new \InvalidArgumentException(
                        "le tableau \$message ne doit contenir que des chaines de caractere"
                    );
                }
            }
            if (
                (isset($message['type']) && !in_array($message['type'], ['success', 'error', 'warning', 'info']))
                || (isset($message[1]) && !in_array($message[1], ['success', 'error', 'warning', 'info']))
            ) {
                throw new \InvalidArgumentException(
                    "le type de message doit etre success, error, warning ou info"
                );
            }
        } elseif (!is_string($message)) {
            throw new \InvalidArgumentException(
                "le parametre \$message doit etre un tableau ou une chaine de caractere"
            );
        }
    }

    /**
     * change le type du popup si le type du message est plus important qe celui actuel
     */
    private function changeType($type)
    {
        switch ($this->type) {
            case 'success':
                if ($type == 'error' || $type == 'warning') {
                    $this->type = $type;
                }
                break;
            case 'info':
                if ($type == 'error' || $type == 'warning' || $type == 'success') {
                    $this->type = $type;
                }
                break;
            case 'warning':
                if ($type == 'error') {
                    $this->type = $type;
                }
                break;
            default:
                break;
        }
    }

    public function addMainMessage($message)
    {
        $this->isValidMessage($message);
        if (is_string($message)) {
            $message = ['message' => $message, 'type' => $this->type];
        } else {
            if (
                count($message) < 2
                || (isset($message['type']) && $message['type'] == "")
                || (isset($message[1]) && $message[1] == "")
            ) {
                $message = ['message' => $message[0], 'type' => $this->type];
            }
        }
        if (!isset($message['type']) || !isset($message['message'])) {
            $message = ['message' => $message[0], 'type' => $message[1]];
        }
        $this->mainMessage = $message;
        $this->changeType($message['type']);
    }


    /**
     * renvoi le message principal s'il existe,
     * sinon renvoi une chaine de caractere selon le type du popup
     * @return string
     */
    public function getMainMessage()
    {
        if ($this->mainMessage) {
            return $this->mainMessage['message'];
        }
        $mainMessage = "";
        switch ($this->type) {
            case 'success':
                $mainMessage = "L'opération $this->operation a été effectuée avec succès";
                break;
            case 'error':
                $mainMessage = "L'opération $this->operation n'a pas pu être effectuée";
                break;
            case 'warning':
                $mainMessage = "L'opération $this->operation a été effectuée avec des avertissements";
                break;
            default:
                $mainMessage = "Informations sur l'opération $this->operation";
                break;
        }
        return $mainMessage;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
