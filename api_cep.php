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
$cache_dir = __DIR__ . '/cache/';
if (!is_dir($cache_dir)) {
    @mkdir($cache_dir, 0755, true);
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
$curl_errno = curl_errno($ch);
$curl_error = curl_error($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response && $http_code == 200) {
    $data = json_decode($response, true);
    if (isset($data['erro'])) {
        echo json_encode(['error' => 'CEP não encontrado']);
        exit;
    }
    // salvar cache (não falhar se não puder escrever)
    @file_put_contents($cache_file, $response);
    echo $response;
    exit;
}

// Se houve problema na consulta externa, tentar devolver cache quando disponível
if (file_exists($cache_file)) {
    echo file_get_contents($cache_file);
    exit;
}

// Fallback embutido para desenvolvimento offline (alguns CEPs comuns)
$fallbacks = [
    '01001000' => [
        'cep' => '01001-000',
        'logradouro' => 'Praça da Sé',
        'complemento' => 'lado ímpar',
        'bairro' => 'Sé',
        'localidade' => 'São Paulo',
        'uf' => 'SP',
        'ibge' => '3550308',
        'gia' => '1004',
        'ddd' => '11',
        'siafi' => '7107'
    ],
    '04538000' => [
        'cep' => '04538-000',
        'logradouro' => 'Avenida Brigadeiro Faria Lima',
        'complemento' => '',
        'bairro' => 'Itaim Bibi',
        'localidade' => 'São Paulo',
        'uf' => 'SP',
        'ibge' => '3550308',
        'gia' => '',
        'ddd' => '11',
        'siafi' => ''
    ]
];

if (isset($fallbacks[$cep])) {
    $resp = json_encode($fallbacks[$cep]);
    @file_put_contents($cache_file, $resp);
    echo $resp;
    exit;
}

// Mensagem mais informativa em ambiente sem acesso externo
http_response_code(503);
echo json_encode([
    'error' => 'Erro ao consultar CEP (serviço externo indisponível)',
    'details' => $curl_errno ? $curl_error : null
]);
exit;
?>