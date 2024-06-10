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
                        <div class="px-4 py-5 sm:px-6">
                            <h2 class="text-lg font-medium leading-6 text-gray-900">Relevant CRS Reports</h2>
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
                                            <div class="ml-auto flex max-w-[70%] flex-col gap-2 rounded-l-xl rounded-tr-xl bg-pvox-link-dark p-4 text-sm text-slate-100 md:max-w-[60%] dark:bg-blue-600 dark:text-slate-100">
                                                <div class="text-sm flex flex-col space-y-3">
                                                    {!! nl2p($message['content']) !!}
                                                </div>
                                            </div>
                                            <img class="size-8 rounded-full object-cover" src="{{ asset('images/person.webp') }}" alt="avatar" />
                                        </div>
                                    @else
                                        <div class="flex items-end gap-2">
                                            <img class="size-8 rounded-full object-cover" src="{{ asset('images/bot.webp') }}" alt="avatar" />
                                            <div class="mr-auto flex max-w-[70%] flex-col gap-2 rounded-r-xl rounded-tl-xl bg-slate-100 px-4 py-3 text-slate-700 md:max-w-[60%] dark:bg-slate-800 dark:text-slate-300">
                                                <span class="font-semibold text-black dark:text-white">CRSbot</span>
                                                <div class="text-sm flex flex-col space-y-3">
                                                    {!! nl2p($message['content']) !!}
                                                </div>
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
        const container = document.getElementById('message-container');
        setTimeout(() => {
            container.scrollTop = container.scrollHeight;
        });
    });
</script>
@endscript
