<?php
require_once __DIR__ . '/env.php';

function generate_pix_qr($amount = '0', $reference = 'Pagamento') {
    $key_type = env('PIX_KEY_TYPE', 'telefone');
    $key = env('PIX_KEY', '');
    $name = env('PIX_NAME', 'Loja');
    $city = env('PIX_CITY', 'Cidade');
    $rapid_host = env('RAPIDAPI_HOST', 'pix-qr-code1.p.rapidapi.com');
    $rapid_key = env('RAPIDAPI_KEY', '');

    if (empty($key) || empty($rapid_key)) {
        return ['success' => false, 'error' => 'PIX_KEY or RAPIDAPI_KEY not configured'];
    }

    $payload = [
        'key_type' => $key_type,
        'key' => $key,
        'name' => $name,
        'city' => $city,
        'amount' => (string)$amount,
        'reference' => (string)$reference,
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

    $result = [
        'success' => false,
        'http_code' => $code,
        'response_raw' => $response,
    ];

    if ($err) {
        $result['error'] = 'cURL error: ' . $err;
        return $result;
    }

    $decoded = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $result['body'] = $decoded;
    } else {
        $result['body'] = null;
    }

    // Consider success when HTTP 200 and body contains something useful
    if ($code >= 200 && $code < 300) {
        $result['success'] = true;
    } else {
        $result['error'] = 'HTTP ' . $code;
    }

    return $result;
}
