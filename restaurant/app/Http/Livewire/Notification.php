<?php

namespace App\Http\Livewire;

use App\Core\Popup;
use Livewire\Component;

class Notification extends Component
{

    

        public string $mainType;
        public string $mainMessage;
        public array $subMessages;
        /**
     * @var bool $subMessagesActive : affiche les sub-messages si true, messages cachés sinon
     */
        public bool $subMessagesActive;
        public bool $hasMessages;
        public string $classClosed = "";
    

    public function mount(Popup $popup)
    {
        $this->mainType = $popup->getType();
        $this->mainMessage = $popup->getMainMessage();
        $this->subMessages = $popup->getMessages();
        $this->hasMessages = count($this->subMessages) > 0;
        $this->subMessagesActive = false;
    }

    public function toggleMessages()
    {
        $this->subMessagesActive = !$this->subMessagesActive;
    }

    public function close()
    {
        $this->classClosed = "closed";
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
