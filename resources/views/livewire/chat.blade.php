{{-- chat component --}}
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($user->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-full bg-white rounded-lg shadow-lg p-6">
                <!-- Chat Messages -->
                <div class="flex flex-col gap-4 pb-6 max-h-[500px] overflow-y-auto">

                    @foreach ($messages as $message)

                    {{-- first check if the sedner user is not auth user --}}
                        @if ($message->sender->id !== auth()->user()->id)

                        <!-- Recevier Other User's Messages -->
                        <div class="flex gap-3 items-start">
                            <img src="https://pagedone.io/asset/uploads/1710412177.png" alt="Shanay image" class="w-10 h-10 rounded-full">
                            <div class="flex flex-col gap-1">
                                <h5 class="text-gray-900 text-sm font-semibold">{{$message->sender->name}}</h5>
                                <div class="flex flex-col gap-1">
                                    <div class="px-4 py-2 bg-gray-100 rounded-lg max-w-xs">
                                        <p class="text-gray-900 text-sm">{{$message->message}}</p>
                                    </div>
                                    <p class="text-gray-500 text-xs text-right">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        @else

                            <!-- Your Messages -->
                            <div class="flex gap-3 items-start justify-end">
                                <div class="flex flex-col gap-1 items-end">
                                    <h5 class="text-gray-900 text-sm font-semibold">You</h5>
                                    <div class="flex flex-col gap-1">
                                        <div class="px-4 py-2 bg-gray-100 rounded-lg max-w-xs">
                                            <p class="text-sm">{{$message->message}}</p>
                                        </div>
                                        <p class="text-gray-500 text-xs text-left">{{ $message->created_at->format('h:i A') }}</p>
                                    </div>
                                </div>
                                <img src="https://pagedone.io/asset/uploads/1704091591.png" alt="Hailey image" class="w-10 h-10 rounded-full">
                            </div>

                        @endif

                    @endforeach
                </div>

                {{-- form for input message --}}
                <form  wire:submit="sendMessage">

                <!-- Input Area -->
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-full border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                        <path d="M6.05 17.6C6.05 15.3218 8.26619 13.475 11 13.475C13.7338 13.475 15.95 15.3218 15.95 17.6M13.475 8.525C13.475 9.89191 12.3669 11 11 11C9.6331 11 8.525 9.89191 8.525 8.525C8.525 7.1581 9.6331 6.05 11 6.05C12.3669 6.05 13.475 7.1581 13.475 8.525ZM19.25 11C19.25 15.5563 15.5563 19.25 11 19.25C6.44365 19.25 2.75 15.5563 2.75 11C2.75 6.44365 6.44365 2.75 11 2.75C15.5563 2.75 19.25 6.44365 19.25 11Z" stroke="#4F46E5" stroke-width="1.6" />
                    </svg>

                    {{-- input --}}
                    <input wire:model="message" class="flex-1 text-black text-sm font-medium leading-4 focus:outline-none bg-transparent" placeholder="Type here...">
                    <div class="flex items-center gap-2">
                        <svg class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <path d="M14.9332 7.79175L8.77551 14.323C8.23854 14.8925 7.36794 14.8926 6.83097 14.323C6.294 13.7535 6.294 12.83 6.83097 12.2605L12.9887 5.72925M12.3423 6.41676L13.6387 5.04176C14.7126 3.90267 16.4538 3.90267 17.5277 5.04176C18.6017 6.18085 18.6017 8.02767 17.5277 9.16676L16.2314 10.5418M16.8778 9.85425L10.72 16.3855C9.10912 18.0941 6.49732 18.0941 4.88641 16.3855C3.27549 14.6769 3.27549 11.9066 4.88641 10.198L11.0441 3.66675" stroke="#9CA3AF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        {{-- submitnbutton --}}
                        <button type="submit" class="flex items-center px-4 py-2  bg-indigo-600 rounded-full shadow hover:bg-indigo-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M9.04071 6.959L6.54227 9.45744M6.89902 10.0724L7.03391 10.3054C8.31034 12.5102 8.94855 13.6125 9.80584 13.5252C10.6631 13.4379 11.0659 12.2295 11.8715 9.81261L13.0272 6.34566C13.7631 4.13794 14.1311 3.03408 13.5484 2.45139C12.9657 1.8687 11.8618 2.23666 9.65409 2.97257L6.18714 4.12822C3.77029 4.93383 2.56187 5.33664 2.47454 6.19392C2.38721 7.0512 3.48957 7.68941 5.69431 8.96584L5.92731 9.10074C6.23326 9.27786 6.38623 9.36643 6.50978 9.48998C6.63333 9.61352 6.72189 9.7665 6.89902 10.0724Z" stroke="white" stroke-width="1.6" stroke-linecap="round" />
                            </svg>
                            <span class="text-black bg-gray-600 text-xs font-semibold pl-2">Send</span>
                        </button>
                    </div>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>