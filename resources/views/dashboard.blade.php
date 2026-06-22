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
                    {{ __("You're logged in!") }}
                    <div class="mt-4">
                        <h3 class="font-semibold">Role(s): {{ implode(', ', $roles ?? []) }}</h3>
                        <ul class="mt-2">
                            <li><strong>Total short URLs:</strong> {{ $total ?? 0 }}</li>
                            <li><strong>Company short URLs:</strong> {{ $byCompany ?? 0 }}</li>
                            <li><strong>Created by you:</strong> {{ $createdByMe ?? 0 }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
