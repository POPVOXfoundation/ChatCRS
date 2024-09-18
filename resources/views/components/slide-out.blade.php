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
{{--                            <ul role="list" class="divide-y divide-gray-200">--}}
{{--                                <li class="px-4 py-4 sm:px-0">--}}
{{--                                    <p class="text-sm text-slate-500 mb-3 mt-3"><span class="italic font-semibold text-slate-600">Important: </span>To start a new search on a different topc - simply type "new search" or "new subject" into the chat box.</p>--}}
{{--                                </li>--}}
{{--                                <li class="px-4 py-4 sm:px-0">--}}
{{--                                    <p class="text-sm text-slate-500 mb-3"><span class="italic font-semibold text-slate-600">Disclaimer: </span>This bot is currently in an experimental phase. Some search features may be limited on certain reports.</p>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
                            <div class="mt-10 lg:col-span-7 lg:mt-0">
                                <dl class="space-y-10">
                                    <div>
                                        <dt class="text-sm font-semibold leading-7 text-gray-900">How does this work?</dt>
                                        <dd class="mt-2 text-sm leading-7 text-gray-600">ChatCRS allows you to perform an initial search that identifies  CRS reports relevant to your topic from [year] forward. After your initial search, select the report you want to interact with. You may then ask questions relevant to that report.</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-semibold leading-7 text-gray-900">What are some sample prompts I can try?</dt>
                                        <dd class="mt-2 text-sm leading-7 text-gray-600">
                                            <ul class="list-disc list-inside">
                                                <li>Summarize this report (include page numbers for reference)</li>
                                                <li>Write a memo for a lawmaker summarizing key points of this report</li>
                                                <li>Use this report to explain how [X] program works</li>
                                                <li>Write an email to a constituent explaining how to access the program
                                                    described in this report</li>
                                            </ul>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-semibold leading-7 text-gray-900">How do I change the report I am engaging with?</dt>
                                        <dd class="mt-2 text-sm leading-7 text-gray-600">Just type "new subject" or "new search'</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-semibold leading-7 text-gray-900">How can I read the full CRS report?</dt>
                                        <dd class="mt-2 text-sm leading-7 text-gray-600">Just click "link" to the right of the report title (report will open in new window)</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-semibold leading-7 text-gray-900">Can I save my session?</dt>
                                        <dd class="mt-2 text-sm leading-7 text-gray-600">At the moment, it is not possible to maintain a “history” of your interactions with the ChatCRS tool.</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-semibold leading-7 text-gray-900">Is my query saved?</dt>
                                        <dd class="mt-2 text-sm leading-7 text-gray-600">Yes, your query and the response to it is saved to help us improve the system. It is not associated with your identity in any way. We do not save or track IP addresses or any other identifying information.
                                            <br>
                                            <span class="font-semibold">**PLEASE NOTE THAT YOU SHOULD NEVER INPUT PERSONALLY IDENTIFIABLE OR
                                                SENSITIVE INFORMATION INTO THE CHAT WINDOW.</span></dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-semibold leading-7 text-gray-900">Where do these reports come from?</dt>
                                        <dd class="mt-2 text-sm leading-7 text-gray-600">The linked reports were created by the nonpartisan Congressional Research Service and made available by the nonprofit open source project
                                            <a href="https://www.everycrsreport.com/" class="font-semibold" target="_blank">EveryCRSReport.com</a>.</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
