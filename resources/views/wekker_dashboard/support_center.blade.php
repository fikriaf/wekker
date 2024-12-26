@extends("layouts_dashboard_wekker::app")

@section("title", "Support Center")

@section("stylesheet")
    <link rel="stylesheet" href="{{asset('wekker_dashboard/support-center/style.css')}}">
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

            <li class="inpage">
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
                    <h3 class="text-center p-0 m-0" style="font-family: Teko; font-size: 2rem;">Support Center</h3>
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
        <div class="container-fluid">
            <div class="row g-4">
                <!-- Tutorial Section -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="bi bi-book-half fs-1 text-primary"></i>
                            </div>
                            <h5 class="card-title">Tutorial</h5>
                            <p class="card-text">Explore step-by-step guides to help you understand and use the platform effectively.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tutorialModal">View Tutorials</button>
                        </div>
                    </div>
                </div>
        
                <!-- Pertanyaan & Permasalahan Section -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="bi bi-question-circle fs-1 text-success"></i>
                            </div>
                            <h5 class="card-title">Pertanyaan & Permasalahan</h5>
                            <p class="card-text">Find answers to common questions or submit your own issues for support.</p>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#faqModal">Submit Question</button>
                        </div>
                    </div>
                </div>
        
                <!-- Chat Section -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="bi bi-chat-dots fs-1 text-warning"></i>
                            </div>
                            <h5 class="card-title">Chat</h5>
                            <p class="card-text">Connect with our assistant AI Chat directly for instant help and assistance.</p>
                            <button class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#chatModal">Start Chat</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tutorial Modal -->
        <div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tutorialModalLabel">Tutorial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Here you can access our step-by-step guides:</p>
                        <ul>
                            <li><a href="#">How to Create Your First Project</a></li>
                            <li><a href="#">Understanding Dashboard Features</a></li>
                            <li><a href="#">Advanced Settings Explained</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ Modal -->
        <div class="modal fade" id="faqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="faqModalLabel">Pertanyaan & Permasalahan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="question" class="form-label">Your Question</label>
                                <textarea class="form-control" id="question" rows="3" placeholder="Describe your issue or ask a question"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Your Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email">
                            </div>
                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Modal -->
        <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chatModalLabel">Live Chat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="chat-box w-100 d-flex flex-column" style="max-height: 24rem; overflow-y: auto; border: 1px solid #dee2e6; padding: 10px;" id="pushMessage">
                            <div class="message-ai my-2">
                                Hi! How can we help you today?
                            </div>
                            <div class="message-user my-2 text-end">
                                I need assistance with my account.
                            </div>
                        </div>
                        <div class="mt-3">
                            <textarea class="form-control" placeholder="Type your message..." id="messageAssistant"></textarea>
                            <button class="btn btn-warning text-white mt-2 w-100" id="sendAssistant">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script src="{{asset('wekker_dashboard/support-center/main.js')}}"></script>
@endsection