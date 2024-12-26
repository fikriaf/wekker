@extends("layouts_dashboard_wekker::app")

@section("title", "Developer Tools")

@section("stylesheet")
    <link rel="stylesheet" href="{{asset('wekker_dashboard/developer-tool/style.css')}}">
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

            <li class="inpage">
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
    <div class="main">
        <div class="topbar px-4" style="margin-left: 1.5rem;">
            <div class="toggle">
                <ion-icon class="menu-outline" name="menu-outline"></ion-icon>
            </div>
            <div class="close-toggle">
                <ion-icon class="close-outline" name="close-outline"></ion-icon>
            </div>
            <header class="text-dark py-1 rounded mx-2 w-100">
                <div class="container">
                    <h3 class="text-center p-0 m-0" style="font-family: Teko; font-size: 2rem;">Developer Tools</h3>
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
        <div class="container-fluid p-0">
            <div class="tabnavbar">
                <ul class="nav nav-tabs m-0" id="toolsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="database-tab" data-bs-toggle="tab" data-bs-target="#database"
                                type="button" role="tab" aria-controls="database" aria-selected="true">Database</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="framework-tab" data-bs-toggle="tab" data-bs-target="#framework"
                                type="button" role="tab" aria-controls="framework" aria-selected="false">Framework</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="stylesheet-tab" data-bs-toggle="tab" data-bs-target="#stylesheet"
                                type="button" role="tab" aria-controls="stylesheet" aria-selected="false">Stylesheet</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="library-tab" data-bs-toggle="tab" data-bs-target="#library"
                                type="button" role="tab" aria-controls="library" aria-selected="false">Library</button>
                    </li>
                </ul>
                <div class="tab-content">  
                    <div class="tab-pane w-100 h-100 position-absolute bg-dark bg-opacity-75 justify-content-center align-items-center" id="panelCreateDB" style="z-index: 9;">
                        <div class="text-center bg-white p-4 rounded shadow">
                            <div class="spinner-border text-primary mx-5" id="SpinnerBorder"></div>
                            <div class="text-primary mx-5" id="successCreate" style="font-size: 4rem;"><ion-icon name="checkmark-circle"></ion-icon></div>
                            <div id="infoEmptyDB">
                                <h4 class="mb-3">Your database is not yet available</h4>
                                <button class="btn btn-primary" id="btnCreateDB">Create Database</button>
                            </div>
                        </div>
                    </div>                  
                    <div class="tab-pane fade show active mt-3" id="database" role="tabpanel" aria-labelledby="database-tab">
                        <h2 class="text-center mb-4">Database Management System</h2>
                        <div class="card mb-4 mx-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Database Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6>Database Name</h6>
                                        <p><strong id="nameDB">my_database</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Tables</h6>
                                        <p><strong id="sumTable">null</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Owner</h6>
                                        <p><strong id="namaUser">null</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>DBMS Version</h6>
                                        <p><strong>MySQL 8.0</strong></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h6>Storage Used</h6>
                                        <p><strong id="storageUsed">null</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Expires at</h6>
                                        <p><strong id="endTime">Null</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Users Connected</h6>
                                        <p><strong>1</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Status</h6>
                                        <p><strong class="text-success" id="statusDB">Online</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Tabs -->
                        <ul class="nav nav-tabs d-inline-flex mx-4" id="databaseTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="view-tab" data-bs-toggle="tab" data-bs-target="#view"
                                    type="button" role="tab" aria-controls="view" aria-selected="true">View Records</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="add-tab" data-bs-toggle="tab" data-bs-target="#add"
                                    type="button" role="tab" aria-controls="add" aria-selected="false">Add Record</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="update-tab" data-bs-toggle="tab" data-bs-target="#update"
                                    type="button" role="tab" aria-controls="update" aria-selected="false">Update Record</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete"
                                    type="button" role="tab" aria-controls="delete" aria-selected="false">Delete Record</button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3 mx-4" id="databaseTabContent">
                            <!-- View Records -->
                            <div class="tab-pane fade show active" id="view" role="tabpanel" aria-labelledby="view-tab">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>John Doe</td>
                                            <td>johndoe@example.com</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm">Edit</button>
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Add Record -->
                            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab">
                                <form id="addRecordForm">
                                    <div class="mb-3">
                                        <label for="addName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="addName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="addEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="addEmail" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Record</button>
                                </form>
                            </div>

                            <!-- Update Record -->
                            <div class="tab-pane fade" id="update" role="tabpanel" aria-labelledby="update-tab">
                                <form id="updateRecordForm">
                                    <div class="mb-3">
                                        <label for="updateId" class="form-label">Record ID</label>
                                        <input type="number" class="form-control" id="updateId" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="updateName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="updateName">
                                    </div>
                                    <div class="mb-3">
                                        <label for="updateEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="updateEmail">
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Record</button>
                                </form>
                            </div>

                            <!-- Delete Record -->
                            <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">
                                <form id="deleteRecordForm">
                                    <div class="mb-3">
                                        <label for="deleteId" class="form-label">Record ID</label>
                                        <input type="number" class="form-control" id="deleteId" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Delete Record</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Framework Dashboard -->
                    <div class="tab-pane fade mt-3" id="framework" role="tabpanel" aria-labelledby="framework-tab">
                        <h2 class="text-center mb-4">Framework Management</h2>
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">Installed Frameworks</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="framework-card card shadow-sm">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Laravel</h6>
                                                <p class="text-muted">Version: 10.0</p>
                                                <button class="btn btn-primary btn-sm">Update</button>
                                                <button class="btn btn-danger btn-sm">Uninstall</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="framework-card card shadow-sm">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Django</h6>
                                                <p class="text-muted">Version: 4.2</p>
                                                <button class="btn btn-primary btn-sm">Update</button>
                                                <button class="btn btn-danger btn-sm">Uninstall</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="framework-card card shadow-sm">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">React</h6>
                                                <p class="text-muted">Version: 18.2.0</p>
                                                <button class="btn btn-primary btn-sm">Update</button>
                                                <button class="btn btn-danger btn-sm">Uninstall</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Framework Installation -->
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Install New Framework</h5>
                            </div>
                            <div class="card-body">
                                <form id="installFrameworkForm">
                                    <div class="mb-3">
                                        <label for="frameworkName" class="form-label">Framework Name</label>
                                        <input type="text" class="form-control" id="frameworkName" placeholder="Enter framework name (e.g., Laravel, Vue.js)" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="frameworkVersion" class="form-label">Version</label>
                                        <input type="text" class="form-control" id="frameworkVersion" placeholder="Enter specific version or leave blank for latest">
                                    </div>
                                    <button type="submit" class="btn btn-success">Install Framework</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Stylesheet Dashboard -->
                    <div class="tab-pane fade mt-3" id="stylesheet" role="tabpanel" aria-labelledby="stylesheet-tab">
                        <h2 class="text-center mb-4">Stylesheet Management</h2>
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Loaded Stylesheets</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Bootstrap 5.3
                                        <span class="badge bg-primary rounded-pill">Default</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Custom Styles (styles.css)
                                        <span class="badge bg-secondary rounded-pill">Custom</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Theme Light Mode
                                        <span class="badge bg-success rounded-pill">Active</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Add New Stylesheet</h5>
                            </div>
                            <div class="card-body">
                                <form id="addStylesheetForm">
                                    <div class="mb-3">
                                        <label for="stylesheetUrl" class="form-label">Stylesheet URL</label>
                                        <input type="url" class="form-control" id="stylesheetUrl" placeholder="Enter the stylesheet URL" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Stylesheet</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Library Dashboard -->
                    <div class="tab-pane fade mt-3" id="library" role="tabpanel" aria-labelledby="library-tab">
                        <h2 class="text-center mb-4">Library Management</h2>
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">Loaded Libraries</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        jQuery 3.6.0
                                        <span class="badge bg-primary rounded-pill">Default</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Font Awesome 6.0
                                        <span class="badge bg-secondary rounded-pill">Icon</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Chart.js 4.0
                                        <span class="badge bg-info rounded-pill">Visualization</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">Add New Library</h5>
                            </div>
                            <div class="card-body">
                                <form id="addLibraryForm">
                                    <div class="mb-3">
                                        <label for="libraryName" class="form-label">Library Name</label>
                                        <input type="text" class="form-control" id="libraryName" placeholder="Enter the library name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="libraryUrl" class="form-label">Library URL</label>
                                        <input type="url" class="form-control" id="libraryUrl" placeholder="Enter the library URL" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Add Library</button>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script src="{{asset('wekker_dashboard/developer-tool/main.js')}}"></script>
@endsection