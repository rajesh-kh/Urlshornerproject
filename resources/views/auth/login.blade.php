<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2">
            <!-- Left: Branding -->
            <div class="hidden md:flex items-center justify-center bg-white p-8">
                <div class="text-center">
                    <p class="mt-1 text-sm text-gray-600">Url Shortner</p>
                </div>
            </div>

            <!-- Right: Minimal Login Form -->
            <div class="bg-white p-8 flex items-center justify-center">
                <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('password') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full btn btn-primary">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
