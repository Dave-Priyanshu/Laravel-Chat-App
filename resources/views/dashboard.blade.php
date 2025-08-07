<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b border-gray-200 border-r">Id</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b border-gray-200 border-r">Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b border-gray-200 border-r">Email</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users->isNotEmpty())
                                    @foreach ($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-900 border-b border-gray-200 border-r">{{ $loop->index + 1 }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 border-b border-gray-200 border-r">{{ $user->name }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 border-b border-gray-200 border-r">{{ $user->email }}</td>
                                           <td class="px-4 py-2 text-sm text-gray-900 border-b border-gray-200">
                                                <a navigate href="{{ route('chat', $user->id) }}" class="flex items-center space-x-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-700">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 
                                                            0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 
                                                            1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 
                                                            8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 
                                                            20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 
                                                            .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 
                                                            3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                                    </svg>
                                                    
                                                    <span id="unread-count-{{ $user->id }}" class="{{$user->unread_message_count > 0 ? 'bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold': ''}}">
                                                        {{ $user->unread_message_count >0 ? $user->unread_message_count : null  }}
                                                    </span>
                                                </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-sm text-gray-500 text-center border-b border-gray-200">No users found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script type="module">
    window.Echo.private('unread-channel.{{ Auth::user()->id }}').listen('UnreadMessage', (event) => {
        const unreadElementCount = document.getElementById(`unread-count-${event.senderId}`);

        if(unreadElementCount){
            unreadElementCount.textContent = event.unreadMessageCount > 0 ? event.unreadMessageCount : '';
        }

    })
</script>