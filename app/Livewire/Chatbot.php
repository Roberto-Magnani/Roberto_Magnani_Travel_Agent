<?php

namespace App\Livewire;

use Livewire\Component;

class Chatbot extends Component
{

    public $currentMessage = '';
    public $openAiResponse = '';
    public $userPrompt = '';
    public $chatMessages = [];

    public function ask()
    {
        $this->validate();

        
        
    }

    public function render()
    {
        $this->dispatch('scrollChatToBottom');
        return view('livewire.chatbot');
    }
}
