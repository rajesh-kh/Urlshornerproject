<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-3xl w-full grid grid-cols-1 gap-6">
            <div class="bg-white p-8 rounded-lg shadow">
                <h2 class="text-2xl font-bold">Create an account</h2>
                <p class="mt-2 text-sm text-gray-600">Sign up to manage short links for your company.</p>
            </div>

            <div class="bg-white p-8 rounded-lg shadow">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('name') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('password') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="mt-1 block w-full border rounded px-3 py-2" />
                    </div>

                    <div class="flex items-center">
                        <button type="submit" class="btn btn-primary">Register</button>
                        <a href="{{ route('login') }}" class="ms-4 text-sm text-gray-600">Already have an account?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
