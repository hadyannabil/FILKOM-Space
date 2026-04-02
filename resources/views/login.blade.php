<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FILKOM Space</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-inter">
    
    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex w-1/2 bg-sidebar-gradient items-center justify-center p-12 text-center">
            <div class="max-w-md">
                <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <img src="{{ asset('assets/login/gedung.webp') }}" class="w-14 h-22" alt="Gedung FILKOM">
                </div>
                <h1 class="text-white text-5xl font-bold mb-4">FILKOM Space</h1>
                <p class="text-gray-400 text-xl">University Room Booking System</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white lg:bg-transparent">
            
            <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
                
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-500 mb-8">Sign in to access your dashboard</p>

                <button class="btn-gold-gradient text-white w-full mb-6">
                    <img src="{{ asset('assets/login/sso.webp') }}" alt="SSO Icon" class="w-5.5 h-5.5 object-contain">
                    Login with SSO UB
                </button>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-gray-400 text-sm">or</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <form method="POST" action="{{ route('login.proses') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none @error('email') border-red-500 @enderror">
                        
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none">
                    </div>

                    <div class="flex items-center justify-between text-sm mb-5">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="text-[#0A1628] font-semibold">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Sign In
                    </button>
                </form>

                <p class="text-center text-gray-500 mt-8">
                    Don't have an account? <span class="text-[#0A1628] font-bold">Contact Admin</span>
                </p>
            </div>
            
        </div>

    </div>

</body>
</html>