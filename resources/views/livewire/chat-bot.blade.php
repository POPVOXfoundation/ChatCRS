<div class="flex flex-col h-full">
    <x-top-banner/>
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        <div class="mx-auto w-full max-w-7xl grow lg:flex xl:px-2 h-full">
            <!-- Left sidebar & main wrapper -->
            <div class="flex-1 xl:flex h-full overflow-hidden">
                <!-- Left sidebar -->
                <div class="border-b border-gray-200 px-4 py-6 sm:px-6 lg:pl-8 xl:w-1/3 xl:shrink-0 xl:border-b-0 xl:border-r xl:pl-6 h-full flex flex-col">
                    <!-- Document list section -->
                    <div class="flex-1 overflow-y-auto divide-y divide-gray-200 rounded-lg bg-white shadow">
                        <div class="flex justify-between items-center px-4 py-5 sm:px-6">
                            <h2 class="text-lg font-medium leading-6 text-gray-900">Relevant CRS Reports</h2>
                            @if (!empty($documents))
                            <button wire:click="startNewSearch" type="button" class="rounded bg-red-600 px-2 py-1 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">New Search
                            </button>
                            @endif
                        </div>
                        <div class="px-4 py-2 sm:p-2">
                            <ul role="list" class="divide-y divide-gray-100">
                                @foreach($documents as $document)
                                    <li wire:key="{{ $document['doc_id'] }}" class="{{ $document['doc_id'] === $activeDocumentId ? 'bg-gray-100' : '' }} relative flex items-center justify-between gap-x-6 py-5 px-4">
                                        <div class="min-w-0">
                                            <div class="flex items-start gap-x-3">
                                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                                    <a href="#"
                                                       wire:click.prevent="selectDocument({{ $document['doc_id'] }})">
                                                        {{ $document['doc_title'] }}
                                                    </a>
                                                </p>
                                            </div>
                                            <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                                <p class="whitespace-nowrap"><time datetime="2023-03-17T00:00Z">{{ \Carbon\Carbon::parse($document['doc_date'])->format('M d, Y') }}</time></p>
                                                <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current">
                                                    <circle cx="1" cy="1" r="1" />
                                                </svg>
                                                <p class="truncate">{{ $document['pages'] }} pages</p>
                                            </div>
                                        </div>
                                        <div class="relative flex flex-none items-center gap-x-4">
                                            @if($document['doc_id'] === $activeDocumentId)
                                                <span class="block h-2 w-2 rounded-full bg-green-500"></span>
                                            @endif
                                            <a href="#" class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Link</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main content area -->
                <div class="flex-1 h-full flex flex-col px-4 py-6 sm:px-6 lg:pl-8 xl:pl-6">
                    <!-- Chat section -->
                    <div class="flex-1 overflow-hidden rounded-lg bg-white shadow flex flex-col">
                        <!-- Chat messages -->
                        <div id="message-container" class="flex-1 overflow-y-auto px-4 py-5 sm:p-6">
                            <div class="flex w-full flex-col gap-4">
                                <!-- Received message -->
                                @foreach($messages as $message)
                                    @if ($message['role'] === 'user')
                                        <div class="flex items-end gap-2">
                                            <div class="ml-auto flex w-[70%] flex-col gap-2 rounded-l-xl rounded-tr-xl bg-pvox-link-dark p-4 text-sm text-slate-100 md:w-[60%] dark:bg-blue-600 dark:text-slate-100">
                                                <div class="text-sm flex flex-col space-y-3">
                                                    {!! nl2p($message['content']) !!}
                                                </div>
                                            </div>
                                            <img class="size-8 rounded-full object-cover" src="{{ asset('images/person.webp') }}" alt="avatar" />
                                        </div>
                                    @else
                                        <div class="flex items-end gap-2">
                                            <img class="size-8 rounded-full object-cover" src="{{ asset('images/bot.webp') }}" alt="avatar" />
                                            <div class="mr-auto flex w-[70%] flex-col gap-2 rounded-r-xl rounded-tl-xl bg-slate-100 px-4 py-3 text-slate-700 md:w-[60%] dark:bg-slate-800 dark:text-slate-300">
                                                <span class="font-semibold text-black dark:text-white">CRSbot</span>
                                                <div class="text-sm flex flex-col space-y-3">
                                                    {!! nl2p($message['content']) !!}
                                                </div>
                                                @if ($message['role'] === 'assistant' && empty($message['feedback_type']))
                                                    <div x-data="{ showCommentBox: false, feedbackType: null, feedbackText: '', selected: null, feedbackSubmitted: false, isLoading: false }" class="flex flex-col space-y-2 mt-2" x-init="
    $wire.on('feedback-submitted', (event) => {
        if (event.messageId == {{ $message['id'] }}) {
            feedbackSubmitted = true;
            showCommentBox = false;
        }
    });
">
                                                        <div class="flex justify-end space-x-1">
                                                            <button
                                                                x-on:click="showCommentBox = true; feedbackType = 1; selected = 1"
                                                                x-bind:class="{'bg-green-100 text-green-600': selected === 1}"
                                                                class="rounded-full p-1.5 text-slate-700/75 hover:bg-slate-900/10 hover:text-slate-700 focus:outline-none focus-visible:text-slate-700 focus-visible:outline focus-visible:outline-offset-0 focus-visible:outline-blue-700 active:bg-slate-900/5 active:-outline-offset-2 dark:text-slate-300/75 dark:hover:bg-white/10 dark:hover:text-slate-300 dark:focus-visible:text-slate-300 dark:focus-visible:outline-blue-600 dark:active:bg-white/5"
                                                                title="Useful"
                                                                aria-label="Useful"
                                                                x-bind:disabled="selected !== null && selected !== 1"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                                    <path d="M2.09 15a1 1 0 0 0 1-1V8a1 1 0 1 0-2 0v6a1 1 0 0 0 1 1ZM5.765 13H4.09V8c.663 0 1.218-.466 1.556-1.037a4.02 4.02 0 0 1 1.358-1.377c.478-.292.907-.706.989-1.26V4.32a9.03 9.03 0 0 0 0-2.642c-.028-.194.048-.394.224-.479A2 2 0 0 1 11.09 3c0 .812-.08 1.605-.235 2.371a.521.521 0 0 0 .502.629h1.733c1.104 0 2.01.898 1.901 1.997a19.831 19.831 0 0 1-1.081 4.788c-.27.747-.998 1.215-1.793 1.215H9.414c-.215 0-.428-.035-.632-.103l-2.384-.794A2.002 2.002 0 0 0 5.765 13Z" />
                                                                </svg>
                                                            </button>
                                                            <button
                                                                x-on:click="showCommentBox = true; feedbackType = 0; selected = 0"
                                                                x-bind:class="{'bg-red-100 text-red-600': selected === 0}"
                                                                class="rounded-full p-1.5 text-slate-700/75 hover:bg-slate-900/10 hover:text-slate-700 focus:outline-none focus-visible:text-slate-700 focus-visible:outline focus-visible:outline-offset-0 focus-visible:outline-blue-700 active:bg-slate-900/5 active:-outline-offset-2 dark:text-slate-300/75 dark:hover:bg-white/10 dark:hover:text-slate-300 dark:focus-visible:text-slate-300 dark:focus-visible:outline-blue-600 dark:active:bg-white/5"
                                                                title="Not Useful"
                                                                aria-label="Not Useful"
                                                                x-bind:disabled="selected !== null && selected !== 0"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                                    <path d="M10.325 3H12v5c-.663 0-1.219.466-1.557 1.037a4.02 4.02 0 0 1-1.357 1.377c-.478.292-.907.706-.989 1.26v.005a9.031 9.031 0 0 0 0 2.642c.028.194-.048.394-.224.479A2 2 0 0 1 5 13c0-.812.08-1.605.234-2.371a.521.521 0 0 0-.5-.629H3C1.896 10 .99 9.102 1.1 8.003A19.827 19.827 0 0 1 2.18 3.215C2.45 2.469 3.178 2 3.973 2h2.703a2 2 0 0 1 .632.103l2.384.794a2 2 0 0 0 .633.103ZM14 2a1 1 0 0 0-1 1v6a1 1 0 1 0 2 0V3a1 1 0 0 0-1-1Z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div x-show="showCommentBox && !feedbackSubmitted" class="flex flex-col space-y-2 mt-2 text-sm">
                                                            <label for="feedback_text_{{ $message['id'] }}" class="sr-only">Feedback Text</label>
                                                            <textarea x-model="feedbackText" id="feedback_text_{{ $message['id'] }}" rows="2" class="p-2 border rounded text-sm" placeholder="Add a comment..."></textarea>
                                                            <div class="flex justify-end">
                                                                <button
                                                                    x-on:click="isLoading = true; $wire.submitFeedback({{ $message['id'] }}, feedbackType, feedbackText).then(() => { isLoading = false; feedbackSubmitted = true; showCommentBox = false; })"
                                                                    x-bind:disabled="isLoading"
                                                                    class="px-2 py-1 text-xs text-white bg-blue-500 rounded flex items-center"
                                                                >
                                                                    <svg x-show="isLoading" class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C6.477 0 2 4.477 2 10s4.477 10 10 10v-4a8 8 0 01-8-8z"></path>
                                                                    </svg>
                                                                    <span x-show="!isLoading">Submit</span>
                                                                    <span x-show="isLoading">Sending</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div x-show="feedbackSubmitted" class="mt-2 text-green-600 text-sm">Thank you for your feedback!</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Typing indicator -->
                            <div wire:loading wire:target="ask" style="display: none;">
                                <div class="flex items-end gap-2">
                                    <img class="size-8 rounded-full object-cover" src="{{ asset('images/bot.webp') }}" alt="avatar" />
                                    <div class="flex gap-1">
                                        <span class="size-1.5 rounded-full bg-slate-700 motion-safe:animate-[bounce_1s_ease-in-out_infinite] dark:bg-slate-300"></span>
                                        <span class="size-1.5 rounded-full bg-slate-700 motion-safe:animate-[bounce_0.5s_ease-in-out_infinite] dark:bg-slate-300"></span>
                                        <span class="size-1.5 rounded-full bg-slate-700 motion-safe:animate-[bounce_1s_ease-in-out_infinite] dark:bg-slate-300"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input field -->
                        <div class="p-6">
                            <form class="flex w-full" wire:submit.prevent="submitPrompt">
                                <input wire:model="prompt" type="text" class="w-full min-w-0 appearance-none rounded-md border-0 bg-white px-3 py-1.5 text-base text-gray-900 shadow-sm ring-1 ring-inset ring-pvox-link-dark placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-pvox-link-dark sm:w-64 sm:text-sm sm:leading-6 xl:w-full" placeholder="Type your message here..." autofocus>
                                <div class="mt-4 sm:ml-4 sm:mt-0 sm:flex-shrink-0">
                                    <button type="submit" class="flex w-full items-center justify-center rounded-md bg-pvox-link-dark px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <x-slide-out wire:model="showSlideOut"></x-slide-out>
        </div>
    </div>
</div>
@script
<script>
    $wire.on('scroll-to-bottom', () => {
        console.log('I am doing the scroll to bottom thing');
        const container = document.getElementById('message-container');
        setTimeout(() => {
            container.scrollTop = container.scrollHeight;
        });
    });
</script>
@endscript
