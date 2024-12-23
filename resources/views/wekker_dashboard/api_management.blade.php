@extends("layouts_dashboard_wekker::app")

@section("title", "API Management")

@section("stylesheet")
    <link rel="stylesheet" href="{{asset('wekker_dashboard/sources/prismjs/prism.css')}}">
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

            <li class="inpage">
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
                    <h3 class="text-center p-0 m-0" style="font-family: Teko; font-size: 2rem;">API Management</h3>
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
        <div class="container-fluid">
            <div class="api-key-container bg-white rounded shadow p-4" style="margin: auto;">
                <!-- Title -->
                <div class="text-center mb-4">
                    <h5 class="fw-bold text-primary">Your API Key</h5>
                    <p class="text-muted small">Manage your API key and track usage.</p>
                </div>

                <!-- API Key Section -->
                <div class="d-flex align-items-center justify-content-center mb-4">
                    <div class="border rounded px-4 py-2 bg-light d-inline-flex align-items-center" 
                        style="font-family: Consolas, monospace; font-size: 1.2rem; letter-spacing: 0.05rem;" id="valueApiKey">{{ Auth::user()->api_key }}</div>
                    <button class="btn btn-outline-primary btn-sm ms-3" onclick="copyApiKey()">
                        <ion-icon name="copy-outline"></ion-icon> Copy
                    </button>
                </div>

                <!-- Additional Information -->
                <div class="info-section border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-secondary">Total Requests:</span>
                        <span class="text-dark">123,456</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-secondary">Expires On:</span>
                        <span class="text-dark">2024-12-31</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-secondary">Status:</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                </div>
            </div>
            <!-- Instruction Section -->
            <div class="instruction mt-3">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="instructionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="python-tab" data-bs-toggle="tab" data-bs-target="#python" 
                                type="button" role="tab" aria-controls="python" aria-selected="true">Python</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="javascript-tab" data-bs-toggle="tab" data-bs-target="#javascript" 
                                type="button" role="tab" aria-controls="javascript" aria-selected="false">JavaScript</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="php-tab" data-bs-toggle="tab" data-bs-target="#php" 
                                type="button" role="tab" aria-controls="php" aria-selected="false">PHP</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="java-tab" data-bs-toggle="tab" data-bs-target="#java" 
                                type="button" role="tab" aria-controls="java" aria-selected="false">Java</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="instructionTabContent">
                    <!-- Python -->
                    <div class="tab-pane fade show active" id="python" role="tabpanel" aria-labelledby="python-tab">
                        <h5>Python Instructions</h5>
                        <p>Use the following code to integrate the API in Python:</p>
                        <pre class="bg-dark text-light p-3 rounded"><code>import requests

api_key = "A1bC2dE3FgH4IjK5LmNo3kG"
response = requests.get("https://api.example.com/data", headers={"Authorization": f"Bearer {api_key}"})
print(response.json())</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div class="tab-pane fade" id="javascript" role="tabpanel" aria-labelledby="javascript-tab">
                        <h5>JavaScript Instructions</h5>
                        <p>Use the following code to integrate the API in JavaScript:</p>
                        <pre class="bg-dark text-light p-3 rounded"><code>const apiKey = "A1bC2dE3FgH4IjK5LmNo3kG";

fetch("https://api.example.com/data", {
headers: {
    "Authorization": `Bearer ${apiKey}`
}
})
.then(response => response.json())
.then(data => console.log(data));</code></pre>
                    </div>

                    <!-- PHP -->
                    <div class="tab-pane fade" id="php" role="tabpanel" aria-labelledby="php-tab">
                        <h5>PHP Instructions</h5>
                        <p>Use the following code to integrate the API in PHP:</p>
                        <pre class="bg-dark text-light p-3 rounded"><code>&lt;?php
$apiKey = "A1bC2dE3FgH4IjK5LmNo3kG";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.example.com/data");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $apiKey));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?&gt;</code></pre>
                    </div>

                    <!-- Java -->
                    <div class="tab-pane fade" id="java" role="tabpanel" aria-labelledby="java-tab">
                        <h5>Java Instructions</h5>
                        <p>Use the following code to integrate the API in Java:</p>
                        <pre class="bg-dark text-light p-3 rounded"><code>import java.net.HttpURLConnection;
import java.net.URL;

public class APIExample {
public static void main(String[] args) throws Exception {
    String apiKey = "A1bC2dE3FgH4IjK5LmNo3kG";
    URL url = new URL("https://api.example.com/data");
    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
    connection.setRequestProperty("Authorization", "Bearer " + apiKey);
    
    int responseCode = connection.getResponseCode();
    System.out.println("Response Code: " + responseCode);
}
}</code></pre>
                    </div>
                </div>
            </div>
        </div>                       
    </div>
@endsection

@section("script")
    <script src="{{asset('wekker_dashboard/sources/prismjs/prism.js')}}"></script>
    <script src="{{asset('wekker_dashboard/api-management/main.js')}}"></script>
@endsection