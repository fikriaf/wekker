@extends("layouts_dashboard_wekker::app")

@section("title", "File Manager")

@section("stylesheet")
    <link rel="stylesheet" href="{{asset('wekker_dashboard/sources/prismjs/prism.css')}}">
    <link rel="stylesheet" href="{{asset('wekker_dashboard/file-manager/style.css')}}">
@endsection
    
@section("content")
    <div class="navigation d-flex" style="font-weight: 500;">
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

            <li class="inpage">
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

            <li>
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
    <div class="main px-4">
        <div class="topbar" style="margin-left: 1.5rem;">
            <div class="toggle">
                <ion-icon class="menu-outline" name="menu-outline"></ion-icon>
            </div>
            <div class="close-toggle">
                <ion-icon class="close-outline" name="close-outline"></ion-icon>
            </div>
            <header class="text-dark py-1 rounded mx-2 w-100">
                <div class="container">
                    <h3 class="text-center p-0 m-0" style="font-family: Teko; font-size: 2rem;">Wekker Manager</h3>
                </div>
            </header>
            <div class="nav-build ms-2 d-flex gap-2 justify-content-end align-items-center">
                <div class="dropdown profile d-flex justify-content-end">
                    <button class="btn user border shadow shadow-sm border-primary" type="button" id="dropdownMenuProfile" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        @php
                            $profilePhotoPath = Auth::user()?->profile_photo_path;
                            $profilePhoto = $profilePhotoPath && Storage::exists($profilePhotoPath) 
                                            ? asset('storage/' . $profilePhotoPath) 
                                            : asset('wekker_dashboard/sources/logo/WEKKER_profile.png');
                        @endphp
                        <img src="{{ $profilePhoto }}" alt="">
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
        
        
        <div class="container-fluid main-page">
            <div class="row">
                <!-- Sidebar Folder List -->
                <div class="col-md-3 sidebar p-3">
                    <div class="d-flex justify-content-between align-items-center m-1 mb-3">
                        <div class="">
                            <h5 class="p-0 m-0">Root</h5>
                        </div>
                        <div class="d-flex" id="btnProject">
                            <button type="button" class="btn btn-primary d-flex add-project"><ion-icon name="add" style="font-size: 1.3rem; font-weight: 900;"></ion-icon></button>
                        </div>
                    </div>
                    <ul class="list-group" id="projectList">
                    </ul>
                </div>
            
                <!-- Main File View -->
                <div class="col-md-9 px-3" id="showFiles">
                    <!-- Breadcrumb -->
                    <nav class="d-flex row align-items-center justify-content-between mb-3 subnav" aria-label="breadcrumb">
                        <div class="col-12 col-md-6 m-0">
                            <ol class="breadcrumb m-0" id="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">root</li>
                            </ol>
                        </div>
                        <div class="col-12 col-md-6 p-0 m-0 interaction d-flex gap-2 justify-content-end">
                            <div class="folder">
                                <button type="button" class="btn btn-outline-dark d-flex gap-2 align-items-center">
                                    <ion-icon name="add"></ion-icon>New Folder
                                </button>
                            </div>
                            <div class="upload">
                                <button type="button" class="btn btn-outline-primary d-flex gap-2 align-items-center">
                                    <ion-icon name="cloud-upload-outline"></ion-icon>Upload
                                </button>
                            </div>
                            <div class="view-toggle d-flex">
                                <button type="button" class="btn p-0 m-0 d-flex align-items-center px-2" id="toggleView">
                                    <ion-icon class="p-0 m-0" name="list-outline" style="font-size: 2.2rem; outline: none;"></ion-icon>
                                </button>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script src="{{asset('wekker_dashboard/file-manager/main.js')}}"></script>
@endsection