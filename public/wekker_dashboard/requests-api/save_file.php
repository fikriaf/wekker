<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'], $_POST['content'])) {
    $filename = $_POST['filename'];
    $content = $_POST['content'];
    $saveDir = __DIR__ . '/data/savedFiles/';

    // Buat direktori jika tidak ada
    if (!file_exists($saveDir)) {
        mkdir($saveDir, 0777, true);
    }

    $filePath = $saveDir . $filename;

    if (file_put_contents($filePath, $content) !== false) {
        echo json_encode(['success' => true, 'message' => 'Saved File']);
    } else {
        echo json_encode(['failer' => false, 'message' => 'Error Server']);
    }
} else {
    echo json_encode(['warning' => false, 'message' => 'Data is not valid']);
}
?>
