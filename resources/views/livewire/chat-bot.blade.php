<div class="flex flex-row w-full h-full pt-16">
    <div class="w-1/3 p-4 h-full overflow-y-auto">
        <!-- Document list section -->
        <div class="bg-white rounded-lg shadow-lg p-6 h-full overflow-y-auto">
            <h2 class="font-semibold text-lg mb-3">Relevant CRS Reports</h2>
            <ul class="space-y-4">
                @foreach($documents as $document)
                    <li wire:key="{{ $document['doc_id'] }}" class="{{ $document['doc_id'] === $activeDocumentId ? 'bg-gray-200' : 'bg-gray-50' }} p-4 hover:bg-gray-100 transition-colors duration-200 rounded-lg">
                        <a href="#"
                           wire:click.prevent="selectDocument({{ $document['doc_id'] }})"
                           class="{{ $document['doc_id'] === $activeDocumentId ? 'text-red-700 font-semibold' : 'text-gray-500 hover:text-blue-600' }}">
                            {{ $document['doc_title'] }}
                        </a>
                        <div class="text-sm mt-2 flex justify-between items-center">
                            <span class="text-gray-400">{{ $document['doc_date']->format('M d, Y') }} - {{ $document['pages'] }} pages</span>
                            <a href="{{ $document['url'] }}" class="text-blue-500 hover:text-blue-700" target="_blank">View Report</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- Another section on the right -->
    <div class="w-1/2 p-4 flex flex-col h-full overflow-y-auto">
        <div class="flex flex-col h-full bg-white rounded-lg shadow-lg">
            <div id="message-container" class="overflow-y-auto flex-grow p-6">
                <!-- Chat messages -->
                <div class="p-2 my-2 bg-gray-100 rounded">
                    <p>Hi there! I am your CRS data bot.</p>
                    <p>I can help you find information by searching thousands of reports. What can I help you find?</p>
                </div>
                @foreach($messages as $message)
                    @if ($message['role'] === 'user')
                        <div class="flex justify-end">
                            <div class="max-w-xs bg-blue-100 rounded p-0">
                                {!! nl2p($message['content']) !!}
                            </div>
                        </div>
                    @else
                        <div class="flex justify-start">
                            <div class="p-0 my-2 bg-gray-100 rounded">
                                {!! nl2p($message['content']) !!}
                            </div>
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
    <!-- Right column: Additional content -->
    <div class="w-1/4 p-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Additional content goes here -->
            <h3 class="font-semibold mb-3.5">Additional Information</h3>
            <p class="text-sm text-slate-500 mb-3"><span class="italic font-semibold text-slate-600">Disclaimer: </span>This bot is currently in an experimental phase. Some search features may be limited on certain reports.</p>
            <hr>
            <p class="text-sm text-slate-500 mb-3 mt-3"><span class="italic font-semibold text-slate-600">Important: </span>To start a new search on a different topc - simply type "new search" or "new subject" into the chat box.
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
