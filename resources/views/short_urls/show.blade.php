<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Short URL Details</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <dl>
                    <div class="mb-4"><dt class="font-semibold">Slug</dt><dd>{{ $short->slug }}</dd></div>
                    <div class="mb-4"><dt class="font-semibold">Original URL</dt><dd><a href="{{ $short->original_url }}" target="_blank">{{ $short->original_url }}</a></dd></div>
                    <div class="mb-4"><dt class="font-semibold">Company ID</dt><dd>{{ $short->company_id }}</dd></div>
                    <div class="mb-4"><dt class="font-semibold">Created By</dt><dd>{{ $short->created_by }}</dd></div>
                    <div class="mb-4"><dt class="font-semibold">Created At</dt><dd>{{ $short->created_at }}</dd></div>
                </dl>
                <div class="mt-4">
                    <a href="/short-urls" class="btn">Back</a>
                    <a href="/s/{{ $short->slug }}" class="btn ml-2" target="_blank">Open</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
