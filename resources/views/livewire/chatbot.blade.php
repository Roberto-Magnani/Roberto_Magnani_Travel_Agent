<div class="chat-container">
    <div class="chat-header my-3 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between pe-2">
        {{-- <h2 class="fs-4 text-truncate">{{ $chatTitle ?? '' }}</h2> --}}
    </div>
    <div class="chat-box mt-3 mt-md-0">
        @forelse ($chatMessages as $key => $chatMessage)
            <div wire:key="{{ $key }}" class="chat-message {{ $chatMessage['role'] == 'user' ? 'sent' : '' }}">
                <div class="chat-message-avatar">
                    @if($chatMessage['role'] == 'assistant')
                        <img src="/RagsAI-LOGO.png" alt="Avatar">
                    @else 
                        <img src="/user.png" alt="">
                    @endif
                </div>
                
                <div>
                    <p>{{ $chatMessage['content'] }}</p>
                </div>
            </div>
        @empty
            <div class="chat-message">
                <div>
                    <p class="fs-3">What do you need today?</p>
                </div>
            </div>
        @endforelse 
    </div>
    <form id="submitForm" wire:submit.prevent="ask" class="chat-input py-3 align-items-center">
        <div class="flex-grow-1">
            <div id="inputWrapper" class="w-100">
                <input class="messageInput" placeholder="Send a message to start the conversation..." wire:model.live="currentMessage" type="text">
                @error('currentMessage') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <button type="submit" class="btn messageBtn">
            <i class="bi bi-send"></i>
        </button>
        
    </form>
</div>



@script
<script>
$wire.on('scrollChatToBottom', () => {
    setTimeout(() => {
        chatBox.scrollTop = chatBox.scrollHeight;
    }, 500);
});
</script>
@endscript