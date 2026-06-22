<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invite New Client</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('clients.invite.send') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Client Name</label>
                        <input name="name" required class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input name="email" type="email" required class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">Send invitation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
