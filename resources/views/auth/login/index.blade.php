<x-layout.auth title="Login - FILKOM Space">
    <div class="flex min-h-screen">
        
        @include('auth.login.left-panel')

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white lg:bg-transparent">
            @include('auth.login.form')
        </div>

    </div>
</x-layout.auth>