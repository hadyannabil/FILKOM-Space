<div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-xl border border-gray-100">
    
    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
    <p class="text-gray-500 mb-8">Sign in to access your dashboard</p>

    <button class="w-full bg-[#F3C240] hover:bg-[#D4AF37] text-[#0A1628] font-bold py-3 rounded-xl flex items-center justify-center gap-3 mb-6">
        <span>🏛️</span> Login with SSO UB
    </button>

    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-gray-400 text-sm">or</span>
        <div class="flex-1 h-px bg-gray-200"></div>
    </div>

    <form method="POST" action="#">
        @csrf

        <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
            <input type="email" name="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#0A1628] outline-none">
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

        <button type="submit" class="btn-login-utama">
            Sign In
        </button>
    </form>

    <p class="text-center text-gray-500 mt-8">
        Don't have an account? <span class="text-[#0A1628] font-bold">Contact Admin</span>
    </p>
</div>