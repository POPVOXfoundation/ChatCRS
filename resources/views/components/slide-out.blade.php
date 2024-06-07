<div
    x-data="{ open: false }"
    x-modelable="open"
    x-show="open"
    {{ $attributes->whereStartsWith('wire:model') }}
    class="relative z-10"
    aria-labelledby="slide-over-title"
    role="dialog"
    aria-modal="true">
    <!-- Background backdrop, show/hide based on slide-over state. -->
    <div class="fixed inset-0"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div
                    x-show="open"
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                        <div class="px-4 sm:px-6">
                            <div class="flex items-start justify-between">
                                <h2 class="text-base font-semibold leading-6 text-gray-900" id="slide-over-title">About CRSChat</h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button wire:click="slideIn" type="button" class="relative rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <span class="absolute -inset-2.5"></span>
                                        <span class="sr-only">Close panel</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="relative mt-6 flex-1 px-4 sm:px-6">
                            <ul role="list" class="divide-y divide-gray-200">
                                <li class="px-4 py-4 sm:px-0">
                                    <p class="text-sm text-slate-500 mb-3 mt-3"><span class="italic font-semibold text-slate-600">Important: </span>To start a new search on a different topc - simply type "new search" or "new subject" into the chat box.</p>
                                </li>
                                <li class="px-4 py-4 sm:px-0">
                                    <p class="text-sm text-slate-500 mb-3"><span class="italic font-semibold text-slate-600">Disclaimer: </span>This bot is currently in an experimental phase. Some search features may be limited on certain reports.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
