<x-app-layout>
    <x-slot name="title">
    Notifications
</x-slot>

    <div class="p-6">
        <h2 class="text-xl font-bold mb-4">Notifications</h2>

        @forelse($notifications as $notification)

            <div class="p-4 mb-3 bg-white border rounded">
                <p>{{ $notification->data['message'] ?? 'No message' }}</p>

                <small class="text-gray-500">
                    {{ $notification->created_at->diffForHumans() }}
                </small>
            </div>

        @empty
            <p>No notifications found.</p>
        @endforelse
    </div>

</x-app-layout>
