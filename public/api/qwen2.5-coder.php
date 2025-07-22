<?php

$hashnya = "krc0toubb4r";
$url = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/join?__theme=system";
$url_res = "https://qwen-qwen2-5-coder-artifacts.hf.space/gradio_api/queue/data?session_hash=" . $hashnya;

$headers = [
    "Host: qwen-qwen2-5-coder-artifacts.hf.space",
    "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/600.8.9 (KHTML, like Gecko) Version/8.0.8 Safari/600.8.9",
    "Origin: https://qwen-qwen2-5-coder-artifacts.hf.space",
    "Referer: https://qwen-qwen2-5-coder-artifacts.hf.space/?__theme=system",
    "Content-Type: application/json"
];

echo "\nyou:\n";
$stdin = fopen("php://stdin", "r");
$teks = rtrim(fgets($stdin), "\n");
$teks = str_replace("\n", "\\n", $teks);

echo "AI : \n";

// Kirim POST request
$data = [
    "data" => [$teks, null, null],
    "event_data" => null,
    "fn_index" => 8,
    "trigger_id" => 12,
    "session_hash" => $hashnya
];

$ch_post = curl_init($url);
curl_setopt($ch_post, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_post, CURLOPT_POST, true);
curl_setopt($ch_post, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch_post, CURLOPT_POSTFIELDS, json_encode($data));
curl_exec($ch_post);
curl_close($ch_post);

// Stream GET response
$start_time = microtime(true);
$respon = "";

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

$end_time = microtime(true);
$duration = $end_time - $start_time;
echo "\nWaktu respons: " . number_format($duration, 3) . " detik\n";

?>
