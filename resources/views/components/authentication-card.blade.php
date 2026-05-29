<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    
    <div class="relative w-full sm:max-w-md mt-6">
        
        <div class="absolute top-0 w-8/12 h-20 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-black text-white rounded-sm justify-center flex items-center shadow-lg z-10">
            <div class="bg-blue-600 w-11/12 h-14 rounded-md flex items-center justify-center">
                <p class="text-2xl font-bold tracking-wide">
                    @if (request()->routeIs('register'))
                        Register Page
                    @elseif (request()->routeIs('two-factor.login'))
                        2FA Code
                    @else
                        Login Page
                    @endif
                </p>
            </div>
        </div>

        <div class="w-full px-6 pt-16 pb-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>

    </div>

</div>