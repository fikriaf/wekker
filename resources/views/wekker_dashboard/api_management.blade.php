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
                    <button class="btn btn-outline-primary btn-sm ms-3 px-3 py-2" id="copyApiKey">
                        <ion-icon name="copy-outline"></ion-icon> Copy
                    </button>
                </div>

                <!-- Additional Information -->
                <div class="info-section border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-secondary">Total Requests:</span>
                        <span class="text-dark">{{Auth::user()->total_request ?? '0'}}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-secondary">Expires On:</span>
                        <span class="text-dark">{{Auth::user()->expired ?? 'null'}}</span>
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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ruby-tab" data-bs-toggle="tab" data-bs-target="#ruby" 
                                type="button" role="tab" aria-controls="ruby" aria-selected="false">Ruby</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="csharp-tab" data-bs-toggle="tab" data-bs-target="#csharp" 
                                type="button" role="tab" aria-controls="csharp" aria-selected="false">C#</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="go-tab" data-bs-toggle="tab" data-bs-target="#go" 
                                type="button" role="tab" aria-controls="go" aria-selected="false">Go</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rust-tab" data-bs-toggle="tab" data-bs-target="#rust" 
                                type="button" role="tab" aria-controls="rust" aria-selected="false">Rust</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="instructionTabContent">
                    <!-- Python -->
                    <div class="tab-pane fade show active" id="python" role="tabpanel" aria-labelledby="python-tab">
                        <h5>Python Instructions</h5>
                        <p>Use the following code to integrate the API in Python:</p>
                        <pre class="bg-dark text-light p-3 rounded language-python"><code>import requests

url = '/api/wekker_requests_generate'
headers = {
    'Content-Type': 'application/x-www-form-urlencoded',
}
data = {
    'prompt': "Create Sign Up Form Using HTML CSS JS",
    'api_key': 'YOUR_API_KEY',
    'materials': "Bootstrap, Font Awesome, JQuery"
}

response = requests.post(url, headers=headers, data=data)
print(response.text)</code></pre>
                    </div>

                    <!-- JavaScript -->
                    <div class="tab-pane fade" id="javascript" role="tabpanel" aria-labelledby="javascript-tab">
                        <h5>JavaScript Instructions</h5>
                        <p>Use the following code to integrate the API in JavaScript:</p>
                        <pre class="bg-dark text-light p-3 rounded language-javascript"><code>const response = await fetch('/api/wekker_requests_generate', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: `prompt=${encodeURIComponent("Create Sign Up Form Using HTML CSS JS")}&api_key=YOUR_API_KEY&materials=${encodeURIComponent("Bootstrap, Font Awesome, JQuery")}`,
});

console.log(await response.json());</code></pre>
                    </div>

                    <!-- PHP -->
                    <div class="tab-pane fade" id="php" role="tabpanel" aria-labelledby="php-tab">
                        <h5>PHP Instructions</h5>
                        <p>Use the following code to integrate the API in PHP:</p>
                        <pre class="bg-dark text-light p-3 rounded language-php"><code>$url = '/api/wekker_requests_generate';
$data = http_build_query([
    'prompt' => 'Create Sign Up Form Using HTML CSS JS',
    'api_key' => 'YOUR_API_KEY',
    'materials' => 'Bootstrap, Font Awesome, JQuery'
]);

$headers = [
    'Content-Type: application/x-www-form-urlencoded',
    'X-CSRF-TOKEN: ' . $csrfToken
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

echo $response;</code></pre>
                    </div>

                    <!-- Java -->
                    <div class="tab-pane fade" id="java" role="tabpanel" aria-labelledby="java-tab">
                        <h5>Java Instructions</h5>
                        <p>Use the following code to integrate the API in Java:</p>
                        <pre class="bg-dark text-light p-3 rounded language-java"><code>import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class Main {
    public static void main(String[] args) {
        try {
            String url = "https://your-api-endpoint.com/api/wekker_requests_generate";
            String data = "prompt=Create+Sign+Up+Form+Using+HTML+CSS+JS&api_key=YOUR_API_KEY&materials=Bootstrap%2C+Font+Awesome%2C+JQuery";

            HttpURLConnection conn = (HttpURLConnection) new URL(url).openConnection();
            conn.setRequestMethod("POST");
            conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
            conn.setRequestProperty("X-CSRF-TOKEN", "<CSRF_TOKEN_HERE>");
            conn.setDoOutput(true);

            try (OutputStream os = conn.getOutputStream()) {
                os.write(data.getBytes());
                os.flush();
            }

            System.out.println(new String(conn.getInputStream().readAllBytes()));
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}</code></pre>
                    </div>
                    <!-- Ruby -->
                    <div class="tab-pane fade" id="ruby" role="tabpanel" aria-labelledby="ruby-tab">
                        <h5>Ruby Instructions</h5>
                        <p>Use the following code to integrate the API in Ruby:</p>
                        <pre class="bg-dark text-light p-3 rounded language-ruby"><code>require 'net/http'
require 'uri'

uri = URI.parse('/api/wekker_requests_generate')
request = Net::HTTP::Post.new(uri)
request["Content-Type"] = "application/x-www-form-urlencoded"
request["X-CSRF-TOKEN"] = "<CSRF_TOKEN_HERE>"
request.body = "prompt=#{URI.encode_www_form_component("Create Sign Up Form Using HTML CSS JS")}&api_key=YOUR_API_KEY&materials=#{URI.encode_www_form_component("Bootstrap, Font Awesome, JQuery")}"

response = Net::HTTP.start(uri.hostname, uri.port) { |http| http.request(request) }
puts response.body</code></pre>
                    </div>
                    <!-- C# -->
                    <div class="tab-pane fade" id="csharp" role="tabpanel" aria-labelledby="csharp-tab">
                        <h5>C# Instructions</h5>
                        <p>Use the following code to integrate the API in C#:</p>
                        <pre class="bg-dark text-light p-3 rounded language-csharp"><code>using System;
using System.Net.Http;
using System.Text;

class Program
{
    static async System.Threading.Tasks.Task Main(string[] args)
    {
        using var client = new HttpClient();
        var url = "https://your-api-endpoint.com/api/wekker_requests_generate";
        var data = "prompt=Create+Sign+Up+Form+Using+HTML+CSS+JS&api_key=YOUR_API_KEY&materials=Bootstrap%2C+Font+Awesome%2C+JQuery";

        var content = new StringContent(data, Encoding.UTF8, "application/x-www-form-urlencoded");
        content.Headers.Add("X-CSRF-TOKEN", "<CSRF_TOKEN_HERE>");

        var response = await client.PostAsync(url, content);
        var responseString = await response.Content.ReadAsStringAsync();
        Console.WriteLine(responseString);
    }
}</code></pre>
                    </div>
                    <!-- GO -->
                    <div class="tab-pane fade" id="go" role="tabpanel" aria-labelledby="go-tab">
                        <h5>Go Instructions</h5>
                        <p>Use the following code to integrate the API in Go:</p>
                        <pre class="bg-dark text-light p-3 rounded language-go"><code>package main

import (
	"bytes"
	"fmt"
	"net/http"
)

func main() {
	url := "https://your-api-endpoint.com/api/wekker_requests_generate"
	data := "prompt=Create+Sign+Up+Form+Using+HTML+CSS+JS&api_key=YOUR_API_KEY&materials=Bootstrap%2C+Font+Awesome%2C+JQuery"

	req, _ := http.NewRequest("POST", url, bytes.NewBufferString(data))
	req.Header.Set("Content-Type", "application/x-www-form-urlencoded")
	req.Header.Set("X-CSRF-TOKEN", "<CSRF_TOKEN_HERE>")

	client := &http.Client{}
	resp, _ := client.Do(req)
	defer resp.Body.Close()

	buf := new(bytes.Buffer)
	buf.ReadFrom(resp.Body)
	fmt.Println(buf.String())
}</code></pre>
                    </div>
                    <!-- Rust -->
                    <div class="tab-pane fade" id="rust" role="tabpanel" aria-labelledby="rust-tab">
                        <h5>Rust Instructions</h5>
                        <p>Use the following code to integrate the API in Rust:</p>
                        <pre class="bg-dark text-light p-3 rounded language-rust"><code>use reqwest::blocking::Client;

fn main() {
    let client = Client::new();
    let url = "https://your-api-endpoint.com/api/wekker_requests_generate";
    let data = "prompt=Create+Sign+Up+Form+Using+HTML+CSS+JS&api_key=YOUR_API_KEY&materials=Bootstrap%2C+Font+Awesome%2C+JQuery";

    let response = client.post(url)
        .header("Content-Type", "application/x-www-form-urlencoded")
        .header("X-CSRF-TOKEN", "<CSRF_TOKEN_HERE>")
        .body(data)
        .send()
        .unwrap();

    println!("{}", response.text().unwrap());
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