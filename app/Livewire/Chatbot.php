<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Chatbot extends Component
{

    public $currentMessage = '';
    public $openAiResponse = '';
    public $userPrompt = '';
    public $chatMessages = [];

    protected $rules = [
        'currentMessage' => 'required'
    ];

    protected $messages = [
        'currentMessage.required' => 'Please enter a message'
    ];

    public function ask()
    {
        $this->validate();

        $this->chatMessages[] = [
            'role' => 'user',
            'content' => $this->currentMessage
        ];

        $this->userPrompt = $this->currentMessage;

        $this->currentMessage = '';

        $this->js('$wire.generateResponse');
    }


    public function generateResponse(){
        $response = Http::post("http://127.0.0.1:8080/chat/rag-completion" , [
            'message' => $this->userPrompt
        ]);

        $content = $response->json();

        $this->chatMessages[] = [
            'role' => 'assistant',
            'content' => $content['choices'][0]['message']['content']
        ];
    }

    public function render()
    {
        $this->dispatch('scrollChatToBottom');
        return view('livewire.chatbot');
    }
}
