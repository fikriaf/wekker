@extends("layouts_dashboard_wekker::app")

@section("title", "Sign Out")

@section("stylesheet")
    <link rel="stylesheet" href="{{asset('wekker_dashboard/signout/style.css')}}">
@endsection
    
@section("content")
    <div class="navigation" style="font-weight: 500;">
        <ul class="p-0">
            <li>
                <a href="/">
                    <span class="icon d-flex ms-3">
                        <img class="logo" src="{{asset('wekker_dashboard/sources/logo/WEKKER-putih.png')}}" alt="logo">
                    </span>
                    <span class="title merk">Wekker</span>
                </a>
                <hr>
            </li>
            <li>
                <a href="{{route('main.builder')}}">
                    <span class="icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </span>
                    <span class="title">Main Builder</span>
                </a>
            </li>

            <li>
                <a href="{{route('developer.tool')}}">
                    <span class="icon">
                        <ion-icon name="cube-outline"></ion-icon>
                    </span>
                    <span class="title">Developer Tools</span>
                </a>
            </li>

            <li>
                <a href="{{route('file.manager')}}">
                    <span class="icon">
                        <ion-icon name="folder-outline"></ion-icon>
                    </span>
                    <span class="title">File Manager</span>
                </a>
            </li>

            <li>
                <a href="{{route('api.management')}}">
                    <span class="icon">
                        <ion-icon name="browsers-outline"></ion-icon>
                    </span>
                    <span class="title">Api Management</span>
                </a>
            </li>

            <li>
                <a href="{{route('setting')}}">
                    <span class="icon">
                        <ion-icon name="settings-outline"></ion-icon>
                    </span>
                    <span class="title">Setting</span>
                </a>
            </li>

            <li>
                <a href="{{route('support.center')}}">
                    <span class="icon">
                        <ion-icon name="help-circle-outline"></ion-icon>
                    </span>
                    <span class="title">Support Center</span>
                </a>
            </li>

            <li class="inpage">
                <a href="{{route('signout')}}">
                    <span class="icon">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </span>
                    <span class="title">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- ========================= Main ==================== -->
    <div class="main px-4 h-100" style="overflow: hidden;">
        <div class="topbar" style="margin-left: 1.5rem;">
            <div class="toggle">
                <ion-icon class="menu-outline" name="menu-outline"></ion-icon>
            </div>
            <div class="close-toggle">
                <ion-icon class="close-outline" name="close-outline"></ion-icon>
            </div>
            <header class="text-dark py-1 rounded mx-2 w-100">
                <div class="container">
                    <h3 class="text-center p-0 m-0" style="font-family: Teko; font-size: 2rem;">Sign Out</h3>
                </div>
            </header>
            <div class="nav-build ms-2 d-flex gap-2 justify-content-end align-items-center">
                <div class="dropdown profile d-flex justify-content-end">
                    <button class="btn user border" type="button" id="dropdownMenuProfile" data-bs-toggle="dropdown" aria-expanded="false">
                        @auth
                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('wekker_dashboard/sources/logo/WEKKER_profile.png') }}" alt="">
                        @endauth
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuProfile">
                        <li>
                            <a class="dropdown-item px-2" href="{{route('profile.edit')}}"><span><ion-icon name="person-outline"></ion-icon>&nbsp;&nbsp;</span>Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item px-2" href="#"><span><ion-icon name="swap-horizontal-outline"></ion-icon>&nbsp;&nbsp;</span>Switch Account</a>
                        </li>
                        <li>
                            <a class="dropdown-item px-2" href="{{route('signout')}}"><span><ion-icon name="log-out-outline"></ion-icon>&nbsp;&nbsp;</span>Sign Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr style="margin-top: 0.1em;">
        <div class="container-fluid h-75 d-flex justify-content-center align-items-center bg-light">
            <div class="confirm w-50">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-center bg-primary text-white py-3" style="font-weight: 800; font-size: 1.5rem;">
                        Account Details
                    </div>
                    <div class="card-body px-4 py-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                            <span class="fw-bold text-muted">Name:</span>
                            <span class="text-dark">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="fw-bold text-muted">Email:</span>
                            <span class="text-dark">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end py-2">
                        <button type="button" class="btn btn-outline-danger px-4">Log Out</button>
                    </div>
                </div>
            </div>
        </div>            
    </div>
@endsection

@section("script")
    <script src="{{asset('wekker_dashboard/signout/main.js')}}"></script>
@endsection