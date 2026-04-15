<?php
echo "<!DOCTYPE html>
<html>
<head>
    <title>Teste APIs - SportShop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #667eea; }
        h2 { color: #764ba2; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
        pre { background: #f0f0f0; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #764ba2; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🧪 Teste das APIs - SportShop</h1>
    
    <div class='card'>
        <h2>📌 1. Teste API CEP (ViaCEP)</h2>
        <p>Consultando CEP: <strong>01001000</strong> (São Paulo - Sé)</p>";
        
$cep = "01001000";
$host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000';
$base_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base_path = rtrim($base_path, '/');
$url_cep = "http://" . $host . ($base_path && $base_path !== '.' ? $base_path : '') . "/api_cep.php?cep={$cep}";
$ch = curl_init($url_cep);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_errno($ch) ? curl_error($ch) : null;
curl_close($ch);

if ($response) {
    $data = json_decode($response, true);
    if (isset($data['error'])) {
        echo "<p class='error'>❌ API CEP retornou erro (HTTP {$http_code}): " . htmlspecialchars($data['error']) . "</p>";
        if (!empty($data['details'])) echo "<pre>Detalhes: " . htmlspecialchars($data['details']) . "</pre>";
    } else {
        echo "<p class='success'>✅ API CEP funcionando! (HTTP {$http_code})</p>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
} else {
    echo "<p class='error'>❌ Falha na conexão com a API CEP (cURL error: " . htmlspecialchars($curl_err) . ")</p>";
    echo "<p>URL testada: <code>" . htmlspecialchars($url_cep) . "</code></p>";
}

echo "</div>";

echo "<div class='card'>";
echo "<h2>📌 2. Teste API Correios (Frete)</h2>";
echo "<p>Calculando frete para CEP: <strong>04538000</strong> (São Paulo - Brooklin)</p>";

$produtos = json_encode([['id' => 1, 'quantidade' => 1], ['id' => 2, 'quantidade' => 1]]);
$host = $_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000';
$base_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base_path = rtrim($base_path, '/');
$url_cor = "http://" . $host . ($base_path && $base_path !== '.' ? $base_path : '') . "/api_correios.php?cep=04538000&produtos=" . urlencode($produtos);
$ch = curl_init($url_cor);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_errno($ch) ? curl_error($ch) : null;
curl_close($ch);

$data = $response ? json_decode($response, true) : null;
if ($response && isset($data['success'])) {
    echo "<p class='success'>✅ API Correios respondeu (HTTP {$http_code})</p>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} else {
    echo "<p class='error'>❌ Falha na conexão com a API Correios (HTTP {$http_code})</p>";
    if (!empty($curl_err)) echo "<pre>CURL error: " . htmlspecialchars($curl_err) . "</pre>";
    echo "<p>URL testada: <code>" . htmlspecialchars($url_cor) . "</code></p>";
    if ($response) {
        echo "<pre>Resposta bruta: " . htmlspecialchars($response) . "</pre>";
    }
}

echo "</div>";

echo "<div class='card'>";
echo "<h2>📌 3. Teste Configuração Mailer</h2>";

if (file_exists('config_mail.php')) {
    include('config_mail.php');
    echo "<p class='success'>✅ config_mail.php encontrado!</p>";
    
    if (function_exists('enviar_email_simples')) {
        echo "<p class='success'>✅ Função enviar_email_simples disponível</p>";
    }
    if (function_exists('enviar_email_pedido')) {
        echo "<p class='success'>✅ Função enviar_email_pedido disponível</p>";
    }
    
    echo "<p><strong>⚠️ Importante:</strong> Configure seu e-mail no arquivo <code>config_mail.php</code></p>";
} else {
    echo "<p class='error'>❌ config_mail.php não encontrado</p>";
}

echo "</div>";

echo "<div class='card'>";
echo "<h2>📌 4. Resumo das APIs</h2>";
echo "<ul>
    <li><strong>API CEP:</strong> http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/api_cep.php?cep=01001000</li>
    <li><strong>API Correios:</strong> http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/api_correios.php?cep=04538000&produtos=[{\"id\":1,\"quantidade\":1}]</li>
    <li><strong>Config Mailer:</strong> config_mail.php</li>
</ul>";
echo "</div>";

echo "<a href='index.php' class='btn'>🏠 Voltar para a Loja</a>";
echo "</div></body></html>";
?>