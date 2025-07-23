@extends("layouts_dashboard_wekker::app")

@section("title", "Main Builder")

@section("stylesheet")
    <link rel="stylesheet" href="{{asset('wekker_dashboard/sources/prismjs/prism.css')}}">
    <link rel="stylesheet" href="{{asset('wekker_dashboard/main-builder/style.css')}}">
    <link rel="stylesheet" href="{{asset('wekker_dashboard/sources/loading/loading.css')}}">
@endsection
    
@section("content")
    <!-- Modal 1 -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
        
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Account</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body">
                <div class="info-account d-flex flex-column w-100 mt-1">
                    <div class="info-user d-flex flex-column px-1">
                        <div class="d-inline-flex">
                            <span>Name :</span>
                            <span class="name-user ms-1">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="d-inline-flex">
                            <span>Email  :</span>
                            <span class="email-user ms-2">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="d-flex border p-1 mt-3 justify-content-center align-items-center">
                            <span>Limit Requests :</span>
                            <span class="limit ms-2 d-flex align-items-center gap-1">{{Auth::user()->total_request}} /<ion-icon name="infinite-outline" style="font-size:1.5rem;"></ion-icon></span>
                        </div>
                    </div>
                    <div class="w-100 d-flex rounded align-items-center border mt-3">
                        <div class="py-1 px-2 rounded-start bg-secondary">
                            <span style="color: white;">API Key</span>
                        </div>
                        <div class="api-key ps-2" style="font-family: Consolas;">
                            <span id="valueApiKey">{{ Auth::user()->api_key }}</span>
                        </div>
                        <div class="copy-api ms-auto">
                            <button class="btn d-flex" type="button" id="btnApiKey"><ion-icon name="copy-outline"></ion-icon></button>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Modal footer -->
            <div class="modal-footer">
                <a href="" type="button" class="btn btn-danger" data-bs-dismiss="modal">Sign Up</a>
                <a href="" type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</a>
            </div>
        
            </div>
        </div>
    </div>
    <!-- Modal New Project -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProjectModalLabel">Create New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk proyek baru -->
                    <form action="{{ route('projects.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Project Name:</label>
                            <input type="text" name="name" pattern="^\S+$" title="No spaces allowed" id="name" class="form-control">
                        </div>
                        <button type="button" class="btn rounded-0 btn-secondary border-bottom d-flex align-items-center" id="toggleSections">Add Direct Input <span> </span> <ion-icon id="chevronDown" name="chevron-down-outline"></ion-icon></button>
                        <!-- Sections for HTML, CSS, JavaScript -->
                        <div id="sections" class="mb-3" style="display:none;">
                            <!-- HTML Section -->
                            <div class="mb-3">
                                <label for="html" class="form-label">HTML:</label>
                                <textarea name="html" id="html" class="form-control" rows="10"></textarea>
                            </div>

                            <!-- CSS Section -->
                            <div class="mb-3">
                                <label for="css" class="form-label">CSS:</label>
                                <textarea name="css" id="css" class="form-control" rows="10"></textarea>
                            </div>

                            <!-- JavaScript Section -->
                            <div class="mb-3">
                                <label for="javascript" class="form-label">JavaScript:</label>
                                <textarea name="javascript" id="javascript" class="form-control" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="createProject">Save Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Bagikan -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="shareModalLabel">Bagikan Proyek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Private/Public Dropdown -->
                    <div class="mb-3">
                        <label for="shareType" class="form-label">Pilih Jenis Akses</label>
                        <select id="shareType" class="form-select">
                            <option value="private">Private</option>
                            <option value="public" selected>Public</option>
                        </select>
                    </div>

                    <!-- Informasi Link Proyek yang akan dibagikan -->
                    <div class="mb-3">
                        <label for="shareLink" class="form-label">Tautan Proyek</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareLink" value="" readonly>
                            <button class="btn btn-outline-secondary" id="btnShare">Copy</button>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between mt-3">
                        <button class="btn btn-success" id="sharePublicButton">Bagikan</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Open Project -->
    <div class="modal fade" id="projectListModal" tabindex="-1" aria-labelledby="projectListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="projectListModalLabel">Project List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row d-flex mb-2 ms-1">
                    <div class="col">Name</div>
                    <div class="col">Last Updated</div>
                </div>
                <div class="spinner-border text-primary" id="SpinnerBorder"></div>
                <div class="list-group" id="listProject" style="max-height: 15rem; overflow:auto">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    <div class="navigation" style="font-weight: 500;">
        <ul class="p-0">
            <li>
                <a href="/">
                    <span class="icon d-flex ms-3">
                        <img class="logo" src="{{asset('wekker_dashboard/sources/logo/WEKKER-putih.png')}}" alt="wekker">
                    </span>
                    <span class="title merk">Wekker</span>
                </a>
                <hr>
            </li>
            <li class="inpage">
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
        <div class="loading" id="loadingGenerate">
            <div id="loading-container" class="text-center">
                <div class="text-light" id="headIndicate">Generating...</div>
                <div id="code-line" class="text-light"></div>
            </div>
        </div>
        <div class="topbar ms-4">
            <div class="toggle">
                <ion-icon class="menu-outline" name="menu-outline"></ion-icon>
            </div>
            <div class="close-toggle">
                <ion-icon class="close-outline" name="close-outline"></ion-icon>
            </div>
            <div class="nav-build w-100 ms-2 d-flex gap-2 justify-content-center align-items-center">
                <div class="dropdown menu">
                    <button class="btn dropdown-toggle text-white px-3 m-0 shadow-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: var(--blue)">
                        Menu
                    </button>
                    <ul class="dropdown-menu p-1" aria-labelledby="dropdownMenuButton" style="background-color: var(--blue);">
                        <li>
                        <a class="dropdown-item px-2 text-white" href="#" data-bs-toggle="modal" data-bs-target="#createProjectModal"><span><ion-icon name="document-outline"></ion-icon>&nbsp;&nbsp;</span>New Project</a>
                        </li>
                        <li>
                        <a class="dropdown-item px-2 text-white" href="#" data-bs-toggle="modal" data-bs-target="#projectListModal" id="btnOpenFile"><span><ion-icon name="folder-outline"></ion-icon>&nbsp;&nbsp;</span>Open Project</a>
                        </li>
                        <li>
                        <a class="dropdown-item px-2 text-white" href="#"><span><ion-icon name="code-download-outline"></ion-icon>&nbsp;&nbsp;</span>Download</a>
                        </li>
                        <li>
                        <a class="dropdown-item px-2 text-white" href="{{route('setting')}}"><span><ion-icon name="settings-outline"></ion-icon>&nbsp;&nbsp;</span>Setting</a>
                        </li>
                    </ul>
                </div>
                <div class="project border d-flex align-items-center rounded w-100">
                    <button class="btn btn-project dropdown-toggle w-100 d-flex align-items-center justify-content-between" type="button" id="dropdownProject" data-bs-toggle="dropdown" aria-expanded="false">
                        None
                    </button>
                    <ul class="dropdown-menu w-100 px-1" aria-labelledby="dropdownProject">
                        <li class="d-flex align-items-center pb-2">
                            <div class="d-flex align-items-center px-3">List Project</div>
                        </li>
                        <hr class="p-0 m-0">
                        <li class="pt-2" id="dropdownlistProject" style="max-height: 15rem; overflow:auto">
                            
                        </li>
                    </ul>
                </div>
                <div class="analyze">
                    <button type="button" class="btn border rounded" data-bs-toggle="modal" data-bs-target="#myModal"><ion-icon name="key-outline"></ion-icon></button>
                </div>
                <div class="bagikan d-flex justify-content-end">
                    <button type="button" class="btn btn-primary d-flex justify-content-center align-items-center gap-2" id="btnShareLink" data-bs-toggle="modal" data-bs-target="#shareModal"><ion-icon name="share-social-outline"></ion-icon>Bagikan</button>
                </div>
                <div class="dropdown profile d-flex justify-content-end">
                    <button class="btn user border shadow shadow-sm border-primary" type="button" id="dropdownMenuProfile" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        <img id="photo-preview" src="{{Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('wekker_dashboard/sources/logo/WEKKER_profile.png')}}" alt="">
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
        <div class="row footbar d-flex py-2 ms-0 align-items-center border-top" style="position: fixed; z-index: 1000; background-color: white;">
            <div class="hover-up w-100 justify-content-center" style="position: absolute; top: -2rem; z-index: 999099999999;">
                <input class="label-hover" type="checkbox" name="hover" id="hoverUp" style="display: none;">
                <label class="px-3 rounded-2 bg-secondary" for="hoverUp" style="cursor: pointer; color: white;">
                    <i class="fas fa-angle-double-up"></i>
                </label>
            </div>
            <div class="col-md-6 prompt d-flex">
                <form class="d-flex align-items-center p-1 border rounded border-1 w-100 shadow" id="promptForm">
                    @csrf
                    <div class="d-flex flex-grow-1">
                        <textarea class="form-textarea flex-grow-1" name="prompt" id="inputPrompt" placeholder="Create a system table CRUD (Create Read Update Delete)"
                        style="border: none; outline: none;"></textarea>
                    </div>
                    <div class="d-flex h-100 justify-content-center align-items-center">
                        <button class="btn px-3 d-flex h-100 align-items-center" type="submit" id="submitPrompt" style="font-size: 1.5rem; color: white; background-color: var(--blue);"><ion-icon name="send-outline"></ion-icon></button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 tools">
                <div class="border rounded w-100 col py-1 px-2 dropup shadow">
                    <div class="d-flex align-items-center ms-1">
                        <div class="d-flex" style="width: 100%; overflow-x: auto; white-space: nowrap;">
                            <div>[</div>
                            <span id="selectedItems" style="white-space: nowrap;"></span>
                            <div>]</div>
                            </div>
                    </div>
                    <div class="btn-tools row d-flex align-items-center justify-centent-between mx-1">
                        <div class="framework-build col-6 col-md-3 px-0">
                            <button class="btn btn-outline-dark py-0 px-0 dropdown-toggle w-100" data-bs-auto-close="outside" type="button" id="dropdownFramework" data-bs-toggle="dropdown" aria-expanded="false">
                                Framework
                            </button>
                            <ul class="dropdown-menu p-1" aria-labelledby="dropdownFramework" style="max-height: 200px; overflow-y: scroll;">
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Bootstrap"> Bootstrap
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="font-build col-6 col-md-3 px-0 dropup">
                            <button class="btn btn-outline-dark py-0 dropdown-toggle w-100" data-bs-auto-close="outside" type="button" id="dropdownFont" data-bs-toggle="dropdown" aria-expanded="false">
                                Fonts
                            </button>
                            <ul class="dropdown-menu p-1" aria-labelledby="dropdownFont" style="max-height: 200px; overflow-y: scroll;">
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Inter"> Inter
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Teko"> Teko
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Heebo"> Heebo
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Roboto"> Roboto
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Montserrat"> Montserrat
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Poppins"> Poppins
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Lato"> Lato
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Arial"> Arial
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Verdana"> Verdana
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Georgia"> Georgia
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Times New Roman"> Times New Roman
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Courier New"> Courier New
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Open Sans"> Open Sans
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Oswald"> Oswald
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Raleway"> Raleway
                                    </label>
                                </li>                                    
                            </ul>
                        </div>
                        <div class="icon-build col-6 col-md-3 px-0 dropup">
                            <button class="btn btn-outline-dark py-0 dropdown-toggle w-100" data-bs-auto-close="outside" type="button" id="dropdownIcon" data-bs-toggle="dropdown" aria-expanded="false">
                                Icons
                            </button>
                            <ul class="dropdown-menu p-1" aria-labelledby="dropdownIcon" style="max-height: 200px; overflow-y: scroll;">
                                <li> 
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Awesome"> Awesome
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Ionicons"> Ionicons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="MaterialIcons"> Material Icons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="BootstrapIcons"> Bootstrap Icons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="FeatherIcons"> Feather Icons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Heroicons"> Heroicons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="TablerIcons"> Tabler Icons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="LucideIcons"> Lucide Icons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Octicons"> Octicons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="ThemifyIcons"> Themify Icons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="LineAwesome"> Line Awesome
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Entypo"> Entypo
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Fontello"> Fontello
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="SimpleLineIcons"> Simple Line Icons
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="MaterialDesignIcons"> Material Design Icons
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="script-build col-6 col-md-3 px-0 dropup">
                            <button class="btn btn-outline-dark py-0 dropdown-toggle w-100" 
                                    data-bs-auto-close="outside" 
                                    type="button" 
                                    id="dropdownScript" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                Scripts
                            </button>
                            <ul class="dropdown-menu p-1" aria-labelledby="dropdownScript" style="max-height: 200px; overflow-y: scroll;">
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="jQuery"> jQuery
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Prism.js"> Prism.js
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Axios"> Axios
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="SweetAlert2"> SweetAlert2
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Lodash"> Lodash
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Chart.js"> Chart.js
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Flatpickr"> Flatpickr
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="AOS"> AOS
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Swiper.js"> Swiper.js
                                    </label>
                                </li>
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="form-check-input dropdown-checkbox" value="Moment.js"> Moment.js
                                    </label>
                                </li>
                            </ul>
                        </div>                            
                    </div>
                </div>
            </div>
            <div class="other-mobile flex-column w-100 mt-4 gap-2 mb-2">
                <div class="bagikan-mobile w-100">
                    <a class="btn btn-primary w-100" type="button" data-bs-toggle="modal" data-bs-target="#shareModal">Bagikan</a>
                </div>
                <div class="info-account d-flex flex-column w-100 mt-4">
                    <div class="d-flex px-1 w-100 justify-content-center">
                        <span style="font-size: 1.5rem;">Account</span>
                    </div>
                    <div class="px-1"><hr class="py-0" style="line-height: 0;"></div>
                    <div class="info-user d-flex flex-column px-1">
                        <div class="d-inline-flex">
                            <span>Name :</span>
                            <span class="name-user ms-1">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="d-inline-flex">
                            <span>Email  :</span>
                            <span class="email-user ms-2">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="d-flex border p-1 mt-1 justify-content-center align-items-center">
                            <span>Limit Requests :</span>
                            <span class="limit ms-2 d-flex align-items-center gap-1"">{{Auth::user()->total_request}} /<ion-icon name="infinite-outline" style="font-size:1.5rem;"></ion-icon></span>
                        </div>
                    </div>
                    <div class="w-100 d-flex rounded align-items-center border mt-3">
                        <div class="py-1 px-2 rounded-start bg-secondary">
                            <span style="color: white;">API Key</span>
                        </div>
                        <div class="api-key ps-2" style="font-family: Consolas;">
                            <span id="valueApiKeyMobile">{{ Auth::user()->api_key }}</span>
                        </div>
                        <div class="copy-api ms-auto">
                            <button class="btn d-flex" type="button" id="btnApiKeyMobile"><ion-icon name="copy-outline"></ion-icon></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="center-main d-flex gap-2 mt-2" style="width: 100%; height: auto;">
            <div class="col-12 col-md-4 d-flex flex-column rounded-3 overflow-auto" style="height: auto; aspect-ratio: 16 / 9;">
                <div class="d-flex">
                    <div class="col p-0">
                        <button type="button" class="btn-html btn-code-nav btn color-html bg-html w-100 rounded-end-0 active">
                            <i class="fab fa-html5"></i> HTML
                        </button>
                    </div>
                    <div class="col p-0">
                        <button type="button" class="btn-css btn-code-nav btn color-css bg-css w-100 rounded-0">
                            <i class="fab fa-css3-alt"></i> CSS
                        </button>
                    </div>
                    <div class="col p-0">
                        <button type="button" class="btn-js btn-code-nav btn color-js bg-js w-100 rounded-start-0">
                            <i class="fab fa-js-square"></i> JS
                        </button>
                    </div>
                </div>
                <div class="codenya w-100 h-100 code-html active mt-1" style="overflow: auto; height: 100%;">
                    <pre 
                        class="form-control h-100 language-html m-0 bg-dark text-light" 
                        contenteditable="true"
                        id="codeHTML"
                        style="font-family: 'JetBrains Mono', monospace; font-size: 0.5rem;"></pre>
                </div>
                <div class="codenya h-100 code-css mt-1" style="overflow: auto; height: 100%;">
                    <pre 
                        class="form-control h-100 language-css m-0 bg-dark text-light" 
                        contenteditable="true"
                        id="codeCSS"
                        style="font-family: 'JetBrains Mono', monospace; font-size: 0.5rem;"></pre>
                </div>
                <div class="codenya h-100 code-js mt-1" style="overflow: auto; height: 100%;">
                    <pre 
                        class="form-control h-100 language-js m-0 bg-dark text-light" 
                        contenteditable="true"
                        id="codeJS"
                        style="font-family: 'JetBrains Mono', monospace; font-size: 0.5rem;"></pre>
                </div>
            </div>
            <div class="col-12 col-md d-flex flex-column indukan-preview border border-2 border-secondary rounded-3 overflow-hidden w-100 p-0" style="height: 100%;">
                <div class="w-100">
                    <nav class="navbar navbar-expand-sm bg-secondary navbar-dark row px-2 ps-3">
                        <div class="col-1 p-0 m-0 d-flex justify-content-center align-items-center" style="color: white; font-size: 1.5rem;"><ion-icon name="folder-outline"></ion-icon></div>
                        <div class="col-7 border border-2 bg-light rounded-pill d-flex justify-content-center align-items-center" style="height: 30px; font-size: small;">
                            root/ <div class="p-0 m-0 w-100 bg-transparent border-0 linked" id="rootManager"></div>
                        </div>
                        <div class="col-4 resolusi">
                            <div class="slider-container d-flex border rounded py-0 px-2">
                                <input type="range" class="form-range" id="resolutionSlider" min="412" max="1236" step="412" value="1236">
                                <div id="tooltip" class="slider-tooltip">1236p</div>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="preview p-0 m-0 d-flexbox justify-content-center" style="width: 100%; height: auto; aspect-ratio: 16 / 9; overflow: hidden; position: relative;">
                    <iframe 
                        src="" 
                        class="border"
                        id="previewFrame"
                        sandbox="allow-scripts allow-same-origin">
                    </iframe>
                </div>
                <footer class="footer-preview w-100 bg-secondary mt-auto text-center text-white" style="height: 30px; font-size: small;">
                    On Preview - Desktop
                </footer>
                <!-- <div class="no-preview w-100 h-100 d-flex justify-content-center align-items-center">
                    <div class="">
                        <h4 style="opacity: 0.4;">No Preview Page</h4>
                    </div>
                </div> -->
            </div>                
        </div>
    </div>
@endsection

@section("script")
    <script src="{{asset('wekker_dashboard/sources/prismjs/prism.js')}}"></script>
    <script src="{{asset('wekker_dashboard/sources/loading/loading.js')}}"></script>
    <script src="{{asset('wekker_dashboard/main-builder/main.js')}}"></script>
    <script src="{{asset('wekker_dashboard/requests-api/api.js')}}"></script>
@endsection