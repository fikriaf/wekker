<?php
session_start();
set_time_limit(0);
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");

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

    if ($api_key != "123") {
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
        echo extractAndReturnJSON($response);
    }
}

function ai($teksnya, $materials) {
    $hashnya = "l9hdjdc60e";
    $url = "https://qwen-qwen1-5-110b-chat-demo.hf.space/queue/join?__theme=light";
    $url_res = "https://qwen-qwen1-5-110b-chat-demo.hf.space/queue/data?session_hash=". $hashnya;
    $ua = file_exists('ua.txt') ? trim(file('ua.txt')[array_rand(file('ua.txt'))]) : "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36";

    $teks = $materials ? $teksnya.". HTML CSS JS TERPISAH (jika ada), Sertakan body di HTML. Harus mengandung komponen: ".$materials : $teksnya.". HTML CSS JS TERPISAH (jika ada), Sertakan body di HTML";
    $data = [
        "data" => [
            $teks,
            $_SESSION['hist1'],
            "NO EXPLANATION, DIRECT CODING. You are a webpage creation machine. You can only create web code using HTML, CSS and JS separately."
        ],
        "event_data" => null,
        "fn_index" => 0,
        "trigger_id" => 12,
        "session_hash" => $hashnya
    ];
    
    $jsonData = json_encode($data);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n".
                            "User-Agent: ".$ua,
            'method'  => 'POST',
            'content' => $jsonData
        ]
    ];
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);

    $optionsGet = [
        'http' => [
            'header' => "User-Agent: ".$ua
        ]
    ];
    $contextGet = stream_context_create($optionsGet);
    
    $bufferData = "";
    $respon = "";
    $berhenti = true;
    try {
        $handle = fopen($url_res, "rb", false, $contextGet);
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fread($handle, 1);
                $bufferData .= $buffer;

                if (strpos($bufferData, "process_completed")) {
                    $berhenti = false;
                }

                if (str_contains($bufferData, "\n\ndata: ")) {
                    $messages = explode("\n\ndata: ", $bufferData);
                    foreach ($messages as $mess) {
                        if (strpos($mess, "process_generating") !== false) {
                            try {
                                $mesh = preg_replace('/^data: /', '', $mess);
                                $jsonnya = json_decode($mesh, true);
                    
                                if (isset($jsonnya['output']) && isset($jsonnya['output']['data'])) {
                                    $data_list = $jsonnya['output']['data'][1];
                    
                                    foreach ($data_list as $item) {
                                        if (is_array($item) && count($item) > 1) {
                                            $text_value = $item[1];
                    
                                            if (is_string($text_value)) {
                                                $exists = false;
                                                foreach ($_SESSION['hist1'] as $i) {
                                                    if (isset($i[1]) && $i[1] == $text_value) {
                                                        $exists = true;
                                                        break;
                                                    }
                                                }
                    
                                                if (!$exists) {
                                                    // echo $text_value;
                                                    // flush();
                                                    // ob_flush();
                                                    $respon .= $text_value;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                break;
                            }
                        }
                    }
                    
                    foreach ($messages as $message) {                    
                        if (strpos($message, "process_generating") !== false) {
                            try {
                                $meshh = preg_replace('/^data: /', '', $message);
                                $jsonnyaa = json_decode($meshh, true);
                    
                                if (isset($jsonnyaa['output']) && isset($jsonnyaa['output']['data'])) {
                                    $data_listt = $jsonnyaa['output']['data'][1];
                    
                                    foreach ($data_listt as $item) {
                                        if (is_array($item) && count($item) > 2) {
                                            $text_value = $item[2];
                    
                                            if (is_string($text_value) && !in_array($text_value, array_column($_SESSION['hist1'], 1))) {
                                                // echo $text_value;
                                                // flush();
                                                // ob_flush();
                                                $respon .= $text_value;
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                break;
                            }
                        }
                    }
                    $bufferData = "";
                    
                }
                if (!$berhenti) {
                    break;
                }
            }
            fclose($handle);
        } else {
            echo "Error opening URL.";
        }
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