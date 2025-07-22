<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiWekkerRequestController extends Controller
{
    public function GenerateWebPage(Request $request) {
        session_start();
        set_time_limit(0);
        
        // header('Content-Type: text/html; charset=utf-8');
        // header('Cache-Control: no-cache');
        // header('Connection: keep-alive');
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Headers: Content-Type");
        // header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");

        if (!isset($_SESSION['hist1'])) {
            $_SESSION['hist1'] = [];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $error = [];

            if (!$request->has('api_key')) {
                $error["error"] = "Error parameter api_key. Please Check Your Api Key!";
                echo json_encode($error, JSON_PRETTY_PRINT);
                error_log("Missing api_key");
                exit;
            }

            $api_key = $request->input('api_key');
            $prompt = $request->input('prompt') ?? null;
            $inputMate = $request->input('materials');
            $material = ($inputMate === 'None' || $inputMate === null) ? null : $inputMate;

            error_log($material);

            error_log("Final Response: " . json_encode( $request->user()->api_key));

            $isValid = DB::table('users')
                    ->where('api_key', $api_key)
                    ->exists();    
                    
            if (!$isValid) {
                return response()->json([
                    'error' => 'API Key Invalid. Please Check Your Api Key!'
                ], 401);
            } else if (!$request->has('prompt')) {
                $error["error"] = "Error parameter prompt. Please insert value to parameter prompt!";
                error_log("Missing prompt");
                exit;
            } else {
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                return response()->stream(function ()  use ($prompt, $material, $api_key) {
                    $respon = (new self)->aiku($prompt, $material);
                    $parsed = (new self)->extractAndReturnJSON($respon);
                    echo "\n\n[[[PARSED_START]]]".json_encode($parsed)."[[[PARSED_END]]]";
                    if (ob_get_level() > 0) {
                        ob_flush();
                    };
                    flush();
                }, 200, [
                    'Content-Type' => 'text/plain',
                    'Cache-Control' => 'no-cache',
                    'X-Accel-Buffering' => 'no',
                ]);
            }
        }
    }


    function aiku($teksnya, $materials) {

        $hashnya = "krc0toubb4r";
        $url = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/join?__theme=system";
        $url_res = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/data?session_hash=" . $hashnya;

        $ua = file_exists('ua.txt') ? trim(file('ua.txt')[array_rand(file('ua.txt'))]) : "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36";
        $headers = [
            "Host: qwen-qwen2-5-coder-artifacts.hf.space",
            "User-Agent: ".$ua,
            "Origin: https://qwen-qwen2-5-coder-artifacts.hf.space",
            "Referer: https://qwen-qwen2-5-coder-artifacts.hf.space/?__theme=system",
            "Content-Type: application/json"
        ];

        $teks = $materials
            ? $teksnya.". Kode HTML, CSS, dan JS harus DIPISAHKAN ke dalam tiga blok berbeda. HTML harus berisi tag <body>. Komponen harus mencakup: ".$materials
            : $teksnya.". Kode HTML, CSS, dan JS harus DIPISAHKAN ke dalam tiga blok berbeda. HTML harus berisi tag <body>.";
            
        $data = [
            "data" => [
                $teks,
                "
                You are a web development assistant that outputs HTML, CSS, and JS **SEPARATELY in three distinct code blocks**. Do not merge them into one HTML file. Do not explain. Just output 3 parts: HTML code, CSS code, JS code in that order. Make sure each part is complete and standalone. HTML must contain <body> structure. SYSPROMPT>>.
                ",
                null
            ],
            "event_data" => null,
            "fn_index" => 8,
            "trigger_id" => 12,
            "session_hash" => $hashnya
        ];
        
        $jsonData = json_encode($data);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $jsonData,
        ]);
        curl_exec($ch);
        curl_close($ch);

        $contextGet = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers),
            ]
        ]);
        
        $buffer = "";
        $respon = "";

        try {
            $fp = fopen($url_res, 'r', false, $contextGet);
            // stream_set_timeout($fp, 0);
            if ($fp) {
                while (!feof($fp)) {
                    $chunk = fread($fp, 1);
                    if ($chunk === false) break;

                    $buffer .= $chunk;

                    while (strpos($buffer, "\n\n") !== false) {
                        [$event, $buffer] = explode("\n\n", $buffer, 2);

                        $lines = [];
                        foreach (explode("\n", $event) as $line) {
                            if (strpos($line, "data: ") === 0) {
                                $lines[] = substr($line, 6);
                            }
                        }

                        if (empty($lines)) continue;

                        $json_string = implode('', $lines);
                        $data_json = json_decode($json_string, true);

                        if (!$data_json) {
                            continue;
                        }

                        $msg = $data_json["msg"] ?? null;
                        if ($msg === "process_completed") {
                            break 2;
                        }

                        if ($msg === "process_generating") {
                            $output_data = $data_json["output"]["data"] ?? [];

                            foreach ($output_data as $entry) {
                                if (is_string($entry)) {
                                    echo $entry;
                                    if (ob_get_level() > 0) {
                                        ob_flush();
                                    }
                                    flush();
                                    $respon .= $entry;
                                } elseif (is_array($entry)) {
                                    foreach ($entry as $item) {
                                        if (is_array($item) && count($item) >= 3) {
                                            [$action, , $value] = $item;
                                            if ($action === "append" && is_string($value)) {
                                                // error_log($value);
                                                echo $value;
                                                if (ob_get_level() > 0) {
                                                    ob_flush();
                                                }
                                                flush();
                                                $respon .= $value;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                fclose($fp);
            } else {
                echo "Error opening URL.";
            }
        } catch (\DivisionByZeroError $e) {
            error_log("Final Response: " . json_encode($e, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            error_log("Final Response: " . json_encode($e, JSON_PRETTY_PRINT));
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
            $result['error'] = "Sorry, nothing response code from server.";
        }
        // Debug: Log extracted result
        error_log("Extracted Result: " . json_encode($result, JSON_PRETTY_PRINT));
        return $result;
        // return json_encode($result, JSON_PRETTY_PRINT);
    }
}