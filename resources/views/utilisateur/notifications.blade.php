<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                            <div class="flex items-center p-4 border-b border-gray-200 dark:border-gray-700 {{ $notification->read_at ? '' : 'bg-gray-50 dark:bg-gray-700' }}">
                                <div class="mr-4 text-primary">
                                    <i class="bi bi-person-plus-fill fs-4"></i>
                                </div>
                                <div class="flex-grow">
                                    <p class="mb-1 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                    </p>
                                    <small class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                @if(isset($notification->data['sender_id']))
                                    <a href="{{ route('profile.detail', $notification->data['sender_id']) }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                        Voir le profil
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-5 text-gray-500 dark:text-gray-400">
                                <i class="bi bi-bell-slash fs-1 block mb-3"></i>
                                Aucune notification pour le moment.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
