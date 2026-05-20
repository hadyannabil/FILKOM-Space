<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FILKOM Space</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-inter">

    <div class="flex min-h-screen">

        {{-- Left panel --}}
        <div class="hidden lg:flex w-1/2 bg-sidebar-gradient items-center justify-center p-12 text-center">
            <div class="max-w-md">
                <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <img src="{{ asset('assets/login/gedung.webp') }}" class="w-14 h-22" alt="Gedung FILKOM">
                </div>
                <h1 class="text-white text-5xl font-bold mb-4">FILKOM Space</h1>
                <p class="text-gray-400 text-xl">University Room Booking System</p>
            </div>
        </div>

        {{-- Right panel --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white lg:bg-transparent">

            <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-xl border border-gray-100">

                <h2 class="text-3xl font-bold text-gray-900 mb-2 text-center">Create Account</h2>
                <p class="text-gray-500 mb-8 text-center">Register to start using FILKOM Space</p>

                {{-- Success message --}}
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.proses') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            class="w-full px-4 py-3 rounded-xl border @error('name') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-[#0A1628] outline-none"
                        >
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="example@student.ub.ac.id"
                            class="w-full px-4 py-3 rounded-xl border @error('email') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-[#0A1628] outline-none"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Minimum 8 characters"
                            class="w-full px-4 py-3 rounded-xl border @error('password') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-[#0A1628] outline-none"
                        >
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Repeat your password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none"
                        >
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Register Now
                    </button>
                </form>

                <p class="text-center text-gray-500 mt-8">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#0A1628] font-bold hover:underline">Sign In</a>
                </p>

            </div>
        </div>

    </div>

</body>
</html>
