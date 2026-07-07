<?php
require_once __DIR__ . '/env.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) $input = $_POST;

$amount = isset($input['amount']) ? (string)$input['amount'] : '0';
$reference = isset($input['reference']) ? (string)$input['reference'] : 'Pagamento';
$key_type = env('PIX_KEY_TYPE', 'telefone');
$key = env('PIX_KEY', '');
$name = env('PIX_NAME', '');
$city = env('PIX_CITY', '');
$rapid_host = env('RAPIDAPI_HOST', 'pix-qr-code1.p.rapidapi.com');
$rapid_key = env('RAPIDAPI_KEY', '');

if (empty($key)) {
    http_response_code(400);
    echo json_encode(['error' => 'PIX key not configured. Set PIX_KEY in .env']);
    exit;
}
if (empty($rapid_key)) {
    http_response_code(400);
    echo json_encode(['error' => 'RapidAPI key not configured. Set RAPIDAPI_KEY in .env']);
    exit;
}

$payload = [
    'key_type' => $key_type,
    'key' => $key,
    'name' => $name,
    'city' => $city,
    'amount' => $amount,
    'reference' => $reference,
];

$ch = curl_init('https://pix-qr-code1.p.rapidapi.com/generate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-rapidapi-host: ' . $rapid_host,
    'x-rapidapi-key: ' . $rapid_key,
]);
$response = curl_exec($ch);
$err = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);

if ($err) {
    http_response_code(500);
    echo json_encode(['error' => 'cURL error: ' . $err]);
    exit;
}

if ($code) http_response_code($code);
echo $response;
