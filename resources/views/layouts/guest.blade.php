<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .iklan-atas {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                background-color: #ff6347;
                color: blue;
                padding: 15px 20px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                display: flex;
                justify-content: space-between;
                align-items: center;
                animation: slideDown 0.5s ease-out;
            }

            .iklan-atas img {
                width: auto;
                height: 3rem;
                border-radius: 10px;
                object-fit: cover;
                margin-right: 15px;
            }

            .iklan-atas .content {
                display: flex;
                align-items: center;
            }

            .iklan-atas h3 {
                font-size: 18px;
                margin: 0;
                font-weight: bold;
            }

            .iklan-atas p {
                font-size: 14px;
                margin: 5px 0 0;
            }

            .iklan-atas a {
                background-color: white;
                color: #ff6347;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                font-size: 14px;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }

            .iklan-atas a:hover {
                background-color: #ffddcc;
            }

            .close-iklan {
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                background: none;
                border: none;
                color: white;
                padding: 5px 10px;
            }

            @keyframes slideDown {
                from {
                    transform: translateY(-100%);
                }
                to {
                    transform: translateY(0);
                }
            }
            .iklan-kiri, .iklan-kanan {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 300px;
                padding: 20px;
                background-color: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                transition: transform 0.3s ease;
            }

            .iklan-atas {
                top: 0;
                position: absolute;
                width: 100%;
                background-color: white;
            }

            /* Iklan di kiri */
            .iklan-kiri {
                left: 5%;
                animation: slideInLeft 1s ease-out;
                display: flex;
                justify-content: center;
                justify-items: center;
            }

            /* Iklan di kanan */
            .iklan-kanan {
                right: 5%;
                animation: slideInRight 1s ease-out;
            }

            /* Gaya iklan */
            .iklan-konten {
                text-align: center;
            }

            .iklan-konten h3 {
                font-size: 22px;
                color: #333;
                margin-bottom: 15px;
                font-weight: bold;
            }

            .iklan-konten p {
                font-size: 16px;
                color: #555;
                margin-bottom: 15px;
            }

            .iklan-konten .btn-iklan {
                padding: 10px 20px;
                background-color: #ff6347;
                color: white;
                font-size: 16px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                text-decoration: none;
                transition: background-color 0.3s ease;
            }

            .iklan-konten .btn-iklan:hover {
                background-color: #e5533b;
            }

            .iklan-gambar {
                width: 100%;
                height: 100%;
                margin-bottom: 15px;
                border-radius: 16px;
                object-fit: cover;
            }

            /* Animasi sliding */
            @keyframes slideInLeft {
                from {
                    left: -350px;
                    opacity: 0;
                }
                to {
                    left: 5%;
                    opacity: 1;
                }
            }

            @keyframes slideInRight {
                from {
                    right: -350px;
                    opacity: 0;
                }
                to {
                    right: 5%;
                    opacity: 1;
                }
            }

        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <!-- Iklan Kiri -->
        <div class="iklan-kiri">
            <div class="iklan-konten">
                <div class="d-flex py-3 justify-content-center">
                    <img src="data:image/png;base64,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" alt="">
                </div>
                <h3>Promo Menarik!</h3>
                <p>Diskon 50% untuk semua produk! Hanya hari ini!</p>
                <a href="https://www.googleadservices.com/pagead/aclk?sa=L&ai=DChcSEwiBxvDO4sKKAxXypGYCHaORHvQYABAAGgJzbQ&ae=2&aspm=1&co=1&ase=5&gclid=Cj0KCQiA9667BhDoARIsANnamQapILUHRihPZFOaQxzUUpZckhr23zF2vE_LI8Zn9GppywWRxtiFLVEaAkEOEALw_wcB&ohost=www.google.com&cid=CAESVeD2_lf70acFcUcVaBERdqT-xpdQnRBBj9NWLIyCw7Pt9m7JGUHMM2zvtKCkA278W3a7aP-2GnwcSy-MkuankDPRHZG-t_emgu9D69nO5kmieQkGqHI&sig=AOD64_0VG1sBrdABQZsJotKg7XZaKuQyUw&q&adurl&ved=2ahUKEwjUq-jO4sKKAxVSUGwGHf0yGCEQ0Qx6BAgKEAE" target="_blank" class="btn-iklan">Dapatkan Diskon</a>
            </div>
        </div>

        <div class="iklan-kanan">
            <div class="iklan-konten">
                <img src="{{asset('wekker_dashboard/sources/iklan/lazada.png')}}" alt="Produk Kanan" class="iklan-gambar">
                <div>
                    <h3>Jangan Lewatkan!</h3>
                    <p>Beli sekarang dan hemat hingga 60%!</p>
                    <a href="https://www.google.com/url?url=%23&rct=j&q=&esrc=s&opi=95576897&sa=U&ved=0ahUKEwi-l-fl4sKKAxWqUGwGHT8JCy0Q2SkIgAc&usg=AOvVaw2hbAa_VIUS9MOyXUvvnbgj" target="_blank" class="btn-iklan">Lihat</a>
                </div>
            </div>
        </div>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
    </body>
</html>
