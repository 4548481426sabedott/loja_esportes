<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['cep'])) {
    echo json_encode(['error' => 'CEP não informado']);
    exit;
}

$cep = preg_replace('/[^0-9]/', '', $_GET['cep']);

if (strlen($cep) != 8) {
    echo json_encode(['error' => 'CEP inválido']);
    exit;
}

// Cache para evitar muitas requisições
$cache_dir = 'cache/';
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

$cache_file = $cache_dir . 'cep_' . $cep . '.json';
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 86400) {
    echo file_get_contents($cache_file);
    exit;
}

$url = "https://viacep.com.br/ws/{$cep}/json/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200 && $response) {
    $data = json_decode($response, true);
    
    if (isset($data['erro'])) {
        echo json_encode(['error' => 'CEP não encontrado']);
    } else {
        file_put_contents($cache_file, $response);
        echo $response;
    }
} else {
    echo json_encode(['error' => 'Erro ao consultar CEP']);
}
?>