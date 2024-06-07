<div>
    <input type="text" wire:model="prompt" wire:keydown.enter="submitPrompt" />
    <button wire:click="submitPrompt">Send</button>
</div>
