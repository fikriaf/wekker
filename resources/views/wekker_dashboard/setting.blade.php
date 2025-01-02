@extends("layouts_dashboard_wekker::app")

@section("title", "Setting")

@section("stylesheet")
    <link rel="stylesheet" href="{{asset('wekker_dashboard/setting/style.css')}}">
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

            <li class="inpage">
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
                    <h3 class="text-center p-0 m-0" style="font-family: Teko; font-size: 2rem;">Setting</h3>
                </div>
            </header>
            <div class="nav-build ms-2 d-flex gap-2 justify-content-end align-items-center">
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
        <hr style="margin-top: 0.1em;">
        <div class="container-fluid">
            <!-- Settings Tab -->
            <div class="tab-pane fade show active" id="setting" role="tabpanel" aria-labelledby="setting-tab">
                <div class="accordion" id="settingsAccordion">
                    <!-- 1. Interface Settings -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="interfaceHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#interfaceSettings" aria-expanded="true" aria-controls="interfaceSettings">
                                Interface Settings
                            </button>
                        </h2>
                        <div id="interfaceSettings" class="accordion-collapse collapse show" aria-labelledby="interfaceHeading">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label for="languageSelect" class="form-label">Language</label>
                                    <select id="languageSelect" class="form-select">
                                        <option value="en" selected>English</option>
                                        <option value="id">Bahasa Indonesia</option>
                                        <option value="es">Español</option>
                                        <option value="fr">Français</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="layoutSelect" class="form-label">Dashboard Layout</label>
                                    <select id="layoutSelect" class="form-select">
                                        <option value="sidebar">Sidebar Collapsible</option>
                                        <option value="top-menu">Top Menu</option>
                                        <option value="minimal">Minimal Layout</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- 2. Editor Settings -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="editorHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#editorSettings" aria-expanded="false" aria-controls="editorSettings">
                                Editor Settings
                            </button>
                        </h2>
                        <div id="editorSettings" class="accordion-collapse collapse" aria-labelledby="editorHeading">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label for="syntaxTheme" class="form-label">Syntax Highlighting Theme</label>
                                    <select id="syntaxTheme" class="form-select">
                                        <option value="dracula">Dracula</option>
                                        <option value="solarized">Solarized</option>
                                        <option value="monokai">Monokai</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tabWidth" class="form-label">Tab Width</label>
                                    <select id="tabWidth" class="form-select">
                                        <option value="2">2 Spaces</option>
                                        <option value="4">4 Spaces</option>
                                        <option value="8">8 Spaces</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-check-label" for="lineWrap">Enable Line Wrapping</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="lineWrap">
                                </div>
                                <div class="mb-3">
                                    <label class="form-check-label" for="autoSave">Enable Auto-save</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="autoSave">
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- 3. Notification Settings -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="notificationHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#notificationSettings" aria-expanded="false" aria-controls="notificationSettings">
                                Notification Settings
                            </button>
                        </h2>
                        <div id="notificationSettings" class="accordion-collapse collapse" aria-labelledby="notificationHeading">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="emailNotifications">
                                </div>
                                <div class="mb-3">
                                    <label class="form-check-label" for="pushNotifications">Push Notifications</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="pushNotifications">
                                </div>
                                <div class="mb-3">
                                    <label class="form-check-label" for="soundNotifications">Sound Notifications</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="soundNotifications">
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- 4. Performance Settings -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="performanceHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#performanceSettings" aria-expanded="false" aria-controls="performanceSettings">
                                Performance Settings
                            </button>
                        </h2>
                        <div id="performanceSettings" class="accordion-collapse collapse" aria-labelledby="performanceHeading">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-check-label" for="lazyLoading">Enable Lazy Loading</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="lazyLoading">
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-outline-danger w-100">Clear Cache</button>
                                </div>
                                <div class="mb-3">
                                    <label for="animationSpeed" class="form-label">Animation Speed</label>
                                    <select id="animationSpeed" class="form-select">
                                        <option value="default">Default</option>
                                        <option value="slow">Slow</option>
                                        <option value="fast">Fast</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- 5. Integration Settings -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="integrationHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#integrationSettings" aria-expanded="false" aria-controls="integrationSettings">
                                Integration Settings
                            </button>
                        </h2>
                        <div id="integrationSettings" class="accordion-collapse collapse" aria-labelledby="integrationHeading">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label for="apiKey" class="form-label">API Key</label>
                                    <input type="text" class="form-control" id="apiKey" placeholder="Enter API key">
                                </div>
                                <div class="mb-3">
                                    <label for="connectedServices" class="form-label">Connected Services</label>
                                    <select id="connectedServices" class="form-select">
                                        <option value="google-drive">Google Drive</option>
                                        <option value="dropbox">Dropbox</option>
                                        <option value="onedrive">OneDrive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- 6. Security Settings -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="securityHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#securitySettings" aria-expanded="false" aria-controls="securitySettings">
                                Security Settings
                            </button>
                        </h2>
                        <div id="securitySettings" class="accordion-collapse collapse" aria-labelledby="securityHeading">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label class="form-check-label" for="twoFactorAuth">Enable Two-Factor Authentication</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="twoFactorAuth">
                                </div>
                                <div class="mb-3">
                                    <label for="sessionTimeout" class="form-label">Session Timeout (minutes)</label>
                                    <input type="number" id="sessionTimeout" class="form-control" placeholder="Enter duration (e.g., 30)">
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- 7. Project Settings -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="projectHeading">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#projectSettings" aria-expanded="false" aria-controls="projectSettings">
                                Project Settings
                            </button>
                        </h2>
                        <div id="projectSettings" class="accordion-collapse collapse" aria-labelledby="projectHeading">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <label for="projectPath" class="form-label">Default Project Path</label>
                                    <input type="text" id="projectPath" class="form-control" placeholder="e.g., /projects/my-app">
                                </div>
                                <div class="mb-3">
                                    <label class="form-check-label" for="projectAutoSave">Enable Autosave</label>
                                    <input type="checkbox" class="form-check-input ms-2" id="projectAutoSave">
                                </div>
                                <div class="mb-3">
                                    <label for="frameworkPreference" class="form-label">Framework Preference</label>
                                    <select id="frameworkPreference" class="form-select">
                                        <option value="bootstrap">Bootstrap</option>
                                        <option value="vuejs">Vue.js</option>
                                        <option value="react">React</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
    </div>
@endsection

@section("script")
    <script src="{{asset('wekker_dashboard/setting/main.js')}}"></script>
@endsection