<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invite New Client</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('clients.invite.send') }}">
                    @csrf
                    <div class="flex gap-8 items-end" style="gap:32px;">
                        <div class="mb-4 flex-1">
                            <label class="block text-sm font-medium text-gray-700">Client Name</label>
                            <input name="name" required class="mt-1 block w-full border rounded px-4 py-3 text-lg" style="font-size:1.125rem; padding:12px 16px;" />
                            @error('name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4 flex-1">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input name="email" type="email" required class="mt-1 block w-full border rounded px-4 py-3 text-lg" style="font-size:1.125rem; padding:12px 16px;" />
                            @error('email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mt-6" style="margin-top:28px;">
                        <button type="submit" class="btn btn-primary px-6 py-3 text-lg" style="padding:10px 20px; font-size:1.125rem;">Send invitation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
