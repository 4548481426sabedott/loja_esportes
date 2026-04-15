<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/conexao.php';

// Parâmetros
$cep_destino = preg_replace('/[^0-9]/', '', $_GET['cep'] ?? '');
$produtos_raw = $_GET['produtos'] ?? '[]';
$produtos = json_decode(urldecode($produtos_raw), true);
if (!is_array($produtos)) $produtos = [];

if (empty($cep_destino) || strlen($cep_destino) != 8) {
    echo json_encode(['error' => 'CEP de destino inválido']);
    exit;
}

// CEP de origem (São Paulo - Sé)
$cep_origem = "01001000";

// Calcular peso total e valor total dos produtos
$peso_total = 0;
$valor_total = 0;
$itens_resumo = [];

foreach ($produtos as $produto) {
    $sql = "SELECT nome, preco FROM produtos WHERE id = " . intval($produto['id']);
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $peso_unitario = 0.5;
        $peso_total += $peso_unitario * $produto['quantidade'];
        $valor_total += $row['preco'] * $produto['quantidade'];
        $itens_resumo[] = [
            'nome' => $row['nome'],
            'qtd' => $produto['quantidade']
        ];
    }
}

// Se o pedido for acima de R$ 299, frete grátis
if ($valor_total >= 299) {
    echo json_encode([
        'success' => true,
        'frete_gratis' => true,
        'valor_total_produtos' => number_format($valor_total, 2, ',', '.'),
        'opcoes' => [
            [
                'codigo' => 'FREE',
                'nome' => 'Frete Grátis',
                'valor' => 0,
                'valor_formatado' => 'R$ 0,00',
                'prazo' => 7,
                'prazo_texto' => '5 a 7 dias úteis'
            ]
        ]
    ]);
    exit;
}

// Opções de frete dos Correios
$servicos = [
    '41106' => 'PAC',
    '04014' => 'SEDEX'
];

$opcoes_frete = [];

foreach ($servicos as $codigo => $nome) {
    $url = "https://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
    $url .= "nCdEmpresa=";
    $url .= "&sDsSenha=";
    $url .= "&nCdServico={$codigo}";
    $url .= "&sCepOrigem={$cep_origem}";
    $url .= "&sCepDestino={$cep_destino}";
    $url .= "&nVlPeso=" . number_format(max($peso_total, 0.3), 2, '.', '');
    $url .= "&nCdFormato=1";
    $url .= "&nVlComprimento=25";
    $url .= "&nVlAltura=10";
    $url .= "&nVlLargura=20";
    $url .= "&nVlDiametro=0";
    $url .= "&sCdMaoPropria=n";
    $url .= "&nVlValorDeclarado=" . number_format($valor_total, 2, '.', '');
    $url .= "&sCdAvisoRecebimento=n";
    $url .= "&StrRetorno=xml";
    $url .= "&nIndicaCalculo=3";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $xml = curl_exec($ch);
    $curl_err = curl_errno($ch) ? curl_error($ch) : null;
    curl_close($ch);

    if ($xml) {
        libxml_use_internal_errors(true);
        $xml_obj = simplexml_load_string($xml);
        if ($xml_obj && isset($xml_obj->cServico)) {
            $servico = $xml_obj->cServico;
            $valor = floatval(str_replace(',', '.', (string)$servico->Valor));
            $prazo = intval($servico->PrazoEntrega);

            if ($valor > 0) {
                $opcoes_frete[] = [
                    'codigo' => $codigo,
                    'nome' => $nome,
                    'valor' => $valor,
                    'valor_formatado' => 'R$ ' . number_format($valor, 2, ',', '.'),
                    'prazo' => $prazo,
                    'prazo_texto' => $prazo . ' dia(s) útil(eis)'
                ];
            }
        } else {
            // registrar erro de parsing (não é crítico)
            error_log('Correios XML parse error for service ' . $codigo);
        }
    } else {
        error_log('Erro CURL Correios: ' . ($curl_err ?? 'vazio'));
    }
}

// Fallback se não conseguiu calcular
if (empty($opcoes_frete)) {
    $opcoes_frete = [
        [
            'codigo' => 'PAC',
            'nome' => 'PAC',
            'valor' => 19.90,
            'valor_formatado' => 'R$ 19,90',
            'prazo' => 7,
            'prazo_texto' => '7 dias úteis'
        ],
        [
            'codigo' => 'SEDEX',
            'nome' => 'SEDEX',
            'valor' => 39.90,
            'valor_formatado' => 'R$ 39,90',
            'prazo' => 3,
            'prazo_texto' => '3 dias úteis'
        ]
    ];
}

echo json_encode([
    'success' => true,
    'frete_gratis' => false,
    'cep_origem' => $cep_origem,
    'cep_destino' => $cep_destino,
    'peso_total' => number_format($peso_total, 2, ',', '.') . ' kg',
    'valor_total_produtos' => number_format($valor_total, 2, ',', '.'),
    'opcoes' => $opcoes_frete
]);
?>