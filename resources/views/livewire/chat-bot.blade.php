<div class="flex flex-row w-full h-full">
    <div class="w-1/3 p-4">
        <!-- Document list section -->
        <div class="bg-white rounded-lg shadow-lg p-6 h-full">
            <h2 class="font-semibold text-lg mb-3">Relevant CSR Reports</h2>
            <ul>
                @foreach($documents as $document)
                <li class="mb-2"><a href="#" wire:click.prevent="selectDocument({{ $document['doc_id'] }})" class="text-blue-600 hover:text-blue-800">{{ $document['doc_title'] }}</a></li>
                 @endforeach
            </ul>
        </div>
    </div>
    <!-- Another section on the right -->
    <div class="w-1/2 p-4 flex flex-col">
        <div class="flex flex-col h-full bg-white rounded-lg shadow-lg">
            <div id="message-container" class="overflow-y-auto flex-grow p-6">
                <!-- Chat messages -->
                <div class="p-2 my-2 bg-gray-100 rounded">
                    <p>Hi there! I am your CRS data bot.</p>
                    <p>I can help you find information by searching over 20,000 reports. What can I help you find?</p>
                </div>
                @foreach($messages as $message)
                    @if ($message['role'] === 'user')
                        <div class="flex justify-end">
                            <div class="max-w-xs bg-blue-100 rounded p-2">
                                <p>{{ $message['content'] }}</p>
                            </div>
                        </div>
                    @else
                        <div class="p-2 my-2 bg-gray-100 rounded">
                            <p>{{ $message['content'] }}</p>
                        </div>
                    @endif
                @endforeach
                <!-- Typing indicator -->
                <div wire:loading wire:target="ask" style="display: none;">
                    <div class="flex items-end">
                        <div class="flex flex-col space-y-2 text-md leading-tight order-2 items-start">
                            <div><img src="/typing-animation-3x.gif" alt="..." class="w-16 ml-0"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-auto bg-white rounded-lg p-6">
                <!-- Input field -->
                <form class="flex w-full" wire:submit="submitPrompt">
                    <input wire:model="prompt" type="text" class="border flex-grow p-2 rounded-l-lg" placeholder="Type your message here..." autofocus>
                    <button class="bg-blue-500 text-white p-2 rounded-r-lg">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('scroll-to-bottom', () => {
        const container = document.getElementById('message-container');
        setTimeout(() => {
            container.scrollTop = container.scrollHeight;
        });
    });
</script>
@endscript
