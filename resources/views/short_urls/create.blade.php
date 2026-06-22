<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Short URL</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('error'))
                        <div class="text-red-600">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="/short-urls">
                        @csrf
                        <div>
                            <label>Original URL</label>
                            <input type="url" name="original_url" class="border p-2 w-full" placeholder="https://example.com" required />
                        </div>
                        <div class="mt-4">
                            <button class="btn">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
