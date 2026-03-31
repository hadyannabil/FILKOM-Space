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
        <div class="hidden lg:flex w-1/2 bg-[#0A1628] items-center justify-center p-12 text-center">
            <div class="max-w-md">
                <div class="w-20 h-20 bg-white/10 rounded-2xl mx-auto mb-8 flex items-center justify-center border border-white/20">
                    <span class="text-white text-4xl">🏢</span>
                </div>
                <h1 class="text-white text-5xl font-bold mb-4">FILKOM Space</h1>
                <p class="text-gray-400 text-xl">University Room Booking System</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white lg:bg-transparent">
            <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-xl lg:shadow-none border border-gray-100 lg:border-none">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-500 mb-8">Sign in to access your dashboard</p>

                <button class="w-full bg-[#F3C240] hover:bg-[#D4AF37] text-[#0A1628] font-bold py-3 px-4 rounded-xl flex items-center justify-center gap-3 transition-all mb-6">
                    <span>🏛️</span> Login with SSO UB
                </button>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <span class="text-gray-400 text-sm">or</span>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                <form action="#" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" placeholder="Enter your email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" placeholder="Enter your password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none text-gray-500">
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="rounded border-gray-300 text-[#0A1628]">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="text-[#0A1628] font-semibold hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="w-full bg-[#0A1628] text-white font-bold py-4 rounded-xl shadow-lg hover:bg-black transition-all">
                        Sign In
                    </button>
                </form>

                <p class="text-center text-gray-500 mt-8">
                    Don't have an account? <a href="#" class="text-[#0A1628] font-bold">Contact Admin</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>