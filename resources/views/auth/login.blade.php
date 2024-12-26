<x-guest-layout>
    <!-- Iklan Atas -->
    <div class="iklan-atas">
            <div class="content">
                <img src="{{asset('wekker_dashboard/sources/iklan/lazada-top.png')}}" alt="Iklan">
                <br><br>
                <img src="{{asset('wekker_dashboard/sources/iklan/lazada-top2.png')}}" alt="Iklan">
                <div>
                    <h3>Diskon Besar!</h3>
                    <p>Promo akhir tahun, diskon hingga 70%! Belanja lebih hemat.</p>
                </div>
            </div>
            <a class="btn btn-outline-info" href="https://www.google.com/url?url=https://www.lazada.co.id/products/jas-pria-blazer-pria-jas-wisuda-blazer-lamaran-jas-resmi-blazer-i6006462599-s11899308512.html%3Ffrom_gmc%3D1%26fl_tag%3D1&rct=j&q=&esrc=s&opi=95576897&sa=U&ved=0ahUKEwj46oOl4MKKAxXUwjgGHZxfBJ4QgOUECMIG&usg=AOvVaw3Z7V8pFfQZeyNa6OKEcWc8" target="_blank" >Belanja Sekarang</a>
            <button class="close-iklan" onclick="closeAd()">✖</button>
        </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div><strong><h1 style="font-size: 2rem !important;">Login</h1></strong></div>
        <hr class="my-3">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="me-auto" href="{{route('register')}}">
                <ion-icon name="arrow-undo-outline"></ion-icon>&nbsp;Register
            </a>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
