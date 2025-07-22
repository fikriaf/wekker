<?php
session_start();
set_time_limit(0);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");

require_once __DIR__ . '/../../vendor/autoload.php';

// Memuat file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

// Membuat koneksi MySQL menggunakan mysqli
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['hist1'])) {
    $_SESSION['hist1'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = [];

    if (!isset($_POST["api_key"])) {
        $error["error"] = "Error parameter api_key. Please Check Your Api Key";
        echo json_encode($error, JSON_PRETTY_PRINT);
        error_log("Missing api_key");
        exit;
    }

    $api_key = $_POST["api_key"];
    $prompt = $_POST["prompt"] ?? null;
    $material = $_POST["materials"] ?? null;

    // Query untuk memeriksa apakah api_key valid
    $query = "SELECT * FROM users WHERE api_key = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $api_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $error["invalid"] = "API Key Invalid. Please Check Your Api Key!";
        echo json_encode($error, JSON_PRETTY_PRINT);
        error_log("Invalid API Key");
        exit;
    } else if (!$prompt) {
        $error["error"] = "Error parameter prompt. Please insert value to parameter prompt!";
        echo json_encode($error, JSON_PRETTY_PRINT);
        error_log("Missing prompt");
        exit;
    } else {
        $response = ai($prompt, $material);
        $final = extractAndReturnJSON($response);
        if ($final){
            $sql = "UPDATE users SET total_request = total_request + 1 WHERE api_key = ?";
    
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $api_key);

                if ($stmt->execute()) {
                    $response = ['status' => 'success', 'message' => 'Request berhasil diupdate.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Gagal memperbarui total request.'];
                }

                $stmt->close();
            } else {
                $response = ['status' => 'error', 'message' => 'Gagal menyiapkan statement.'];
            }
        }
        $conn->close();
        echo $final;
    }
}

function ai($teksnya, $materials) {
    $hashnya = "krc0toubb4r";
    $url = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/join?__theme=system";
    $url_res = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/data?session_hash=" . $hashnya;

    $ua = file_exists('ua.txt') ? trim(file('ua.txt')[array_rand(file('ua.txt'))]) : "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36";

    $teks = $materials ? $teksnya.". HTML CSS JS TERPISAH (jika ada), Sertakan body di HTML. Harus mengandung komponen: ".$materials : $teksnya.". HTML CSS JS TERPISAH (jika ada), Sertakan body di HTML";
    
    $data = [
        "data" => [$teksnya, null, null],
        "event_data" => null,
        "fn_index" => 8,
        "trigger_id" => 12,
        "session_hash" => $hashnya
    ];

    $headers = [
        "Host: qwen-qwen2-5-coder-artifacts.hf.space",
        "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/600.8.9 (KHTML, like Gecko) Version/8.0.8 Safari/600.8.9",
        "Origin: https://qwen-qwen2-5-coder-artifacts.hf.space",
        "Referer: https://qwen-qwen2-5-coder-artifacts.hf.space/?__theme=system",
        "Content-Type: application/json"
    ];
    
    $ch_post = curl_init($url);
    curl_setopt($ch_post, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_post, CURLOPT_POST, true);
    curl_setopt($ch_post, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch_post, CURLOPT_POSTFIELDS, json_encode($data));
    curl_exec($ch_post);
    curl_close($ch_post);

    $respon = "";

    try {
        $ch_get = curl_init($url_res);
        curl_setopt($ch_get, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch_get, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_get, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) use (&$respon) {
            static $buffer = "";

            $buffer .= $chunk;

            while (strpos($buffer, "\n\n") !== false) {
                list($event, $buffer) = explode("\n\n", $buffer, 2);
                $lines = [];

                foreach (explode("\n", $event) as $line) {
                    if (strpos($line, "data: ") === 0) {
                        $lines[] = substr($line, 6);
                    }
                }

                if (empty($lines)) continue;

                $full_data = implode("", $lines);

                $data_json = json_decode($full_data, true);
                if (!$data_json) continue;

                $msg = $data_json["msg"] ?? null;

                if ($msg === "process_completed") {
                    return 0; // Stop curl streaming
                }

                if ($msg === "process_generating") {
                    $output_data = $data_json["output"]["data"] ?? [];

                    foreach ($output_data as $entry) {
                        if (is_string($entry)) {
                            echo $entry;
                            $respon .= $entry;
                        } elseif (is_array($entry)) {
                            foreach ($entry as $item) {
                                if (is_array($item) && count($item) >= 3) {
                                    list($action, $_, $value) = $item;
                                    if ($action === "append" && is_string($value)) {
                                        echo $value;
                                        flush();
                                        ob_flush();
                                        $respon .= $value;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return strlen($chunk);
        });
        curl_exec($ch_get);
        curl_close($ch_get);
    } catch (DivisionByZeroError $e) {
        echo "Error: " . $e->getMessage() . "\n";
    } catch (Exception $e) {
        echo "General Error: " . $e->getMessage() . "\n";
    }
    $_SESSION['hist1'][] = [$teks, $respon];
    // error_log("Session Result: " . json_encode($_SESSION['hist1'], JSON_PRETTY_PRINT));
    return $respon;
}

function extractAndReturnJSON($response) {
    $pattern = "/```(\w+)\n(.*?)\n```/s";

    $result = [];
    if (preg_match_all($pattern, $response, $matches)) {
        foreach ($matches[1] as $index => $language) {
            $result[$language] = [
                "code" => $matches[2][$index]
            ];
        }
    } else {
        $result['error'] = "Sorry, nothing response from server.";
    }
    // Debug: Log extracted result
    error_log("Extracted Result: " . json_encode($result, JSON_PRETTY_PRINT));

    return json_encode($result, JSON_PRETTY_PRINT);
}