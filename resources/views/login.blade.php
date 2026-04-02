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
                <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <img src="{{ asset('assets/login/gedung.webp') }}" class="w-14 h-16">
                </div>
                <h1 class="text-white text-5xl font-bold mb-4">FILKOM Space</h1>
                <p class="text-gray-400 text-xl">University Room Booking System</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white lg:bg-transparent">
            
            <x-login.form />
            
        </div>

    </div>

</body>
</html>