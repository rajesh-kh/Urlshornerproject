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
                        <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Original URL</label>
                                <input type="url" name="original_url" class="mt-1 block w-full border rounded px-4 py-3 text-lg" style="font-size:1.125rem; padding:12px 16px;" placeholder="https://example.com" required />
                            </div>
                            <div class="mt-6" style="margin-top:28px;">
                                <button class="btn btn-primary px-6 py-3 text-lg" style="padding:10px 20px; font-size:1.125rem;">Create</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
