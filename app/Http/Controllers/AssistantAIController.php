<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssistantAIController extends Controller
{
    public function responAI(Request $request) {
        session_start();
        set_time_limit(0);
        header('Content-Type: text/html; charset=utf-8');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        flush();
        ob_flush();

        $hashnya = "l9hdjdc60e";
        $url = "https://qwen-qwen1-5-110b-chat-demo.hf.space/queue/join?__theme=light";
        $url_res = "https://qwen-qwen1-5-110b-chat-demo.hf.space/queue/data?session_hash=" . $hashnya;
        $ua = file_exists('ua.txt') ? trim(file('ua.txt')[array_rand(file('ua.txt'))]) : "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.85 Safari/537.36";

        if (!isset($_SESSION['hist1'])) {
            $_SESSION['hist1'] = [];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $teks = $request->input('teksnya');
            $data = [
                "data" => [
                    $teks,
                    $_SESSION['hist1'],
                    "You are AI assistant from Wekker. Dengan Wekker, pengguna dapat menciptakan berbagai jenis halaman web dengan cepat tanpa memerlukan keahlian teknis yang mendalam. Fitur-fitur seperti Main Builder, Developer Tools, dan API Management memungkinkan pengguna untuk menyesuaikan halaman web sesuai kebutuhan mereka, mulai dari desain hingga fungsionalitas backend"
                ],
                "event_data" => null,
                "fn_index" => 0,
                "trigger_id" => 12,
                "session_hash" => $hashnya
            ];
            
            $jsonData = json_encode($data);

            // Kirim permintaan POST
            $options = [
                'http' => [
                    'header' => "Content-Type: application/json\r\n" . "User-Agent: " . $ua,
                    'method' => 'POST',
                    'content' => $jsonData
                ]
            ];
            $context = stream_context_create($options);
            file_get_contents($url, false, $context);

            // Setup untuk GET
            $optionsGet = [
                'http' => [
                    'header' => "User-Agent: " . $ua
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

                        // Jika "process_completed" ditemukan, hentikan stream
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
                                                            echo $text_value;
                                                            flush();
                                                            ob_flush();
                                                            $respon .= $text_value;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        break;
                                    }
                                }
                            }

                            // Cek ulang dan kirimkan data baru
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
                                                        echo $text_value;
                                                        flush();
                                                        ob_flush();
                                                        $respon .= $text_value;
                                                    }
                                                }
                                            }
                                        }
                                    } catch (\Exception $e) {
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
            } catch (\DivisionByZeroError $e) {
                echo "Error: " . $e->getMessage() . "\n";
            } catch (\Exception $e) {
                echo "General Error: " . $e->getMessage() . "\n";
            }
            $_SESSION['hist1'][] = [$teks, $respon];
        }
    }

}
