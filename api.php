<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST requests allowed']);
    exit;
}

$inputJSON = file_get_contents('php://input');
$requestData = json_decode($inputJSON, true);

if (!isset($requestData['image'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing image field']);
    exit;
}

$imageData = $requestData['image'];

if (strpos($imageData, 'base64,') !== false) {
    $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
}

$decodedImage = base64_decode($imageData);

$uploadDir = __DIR__ . '/static';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$filename = time() . '.png';
$filepath = $uploadDir . '/' . $filename;

file_put_contents($filepath, $decodedImage);

echo json_encode(['success' => true, 'file' => $filename]);