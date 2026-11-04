<?php
session_start();

$id = intval($_GET['id']);
$checkout = isset($_GET['checkout']) ? intval($_GET['checkout']) : 0;

// Recuperar seleção do produto
$cor = $_SESSION['produto_selecionado']['cor'] ?? null;
$tamanho = $_SESSION['produto_selecionado']['tamanho'] ?? null;

// Inicializa carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (!isset($_SESSION['carrinho_detalhes'])) {
    $_SESSION['carrinho_detalhes'] = [];
}

// Limitar tamanho do carrinho para evitar erro de memória
if (count($_SESSION['carrinho']) < 50) {
    // Adiciona o produto
    $_SESSION['carrinho'][] = $id;
    
    // Salvar detalhes do produto (cor, tamanho)
    $_SESSION['carrinho_detalhes'][] = [
        'produto_id' => $id,
        'cor' => $cor,
        'tamanho' => $tamanho,
        'data_adicao' => time()
    ];
    
    $_SESSION['mensagem'] = [
        'tipo' => 'success',
        'texto' => 'Produto adicionado ao carrinho! ' . ($cor ? "Cor: $cor" : "") . ($tamanho ? " Tamanho: $tamanho" : "")
    ];
    
    // Limpar seleção temporária
    unset($_SESSION['produto_selecionado']);
} else {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Carrinho cheio! Remova alguns itens para adicionar mais.'
    ];
}

if ($checkout) {
    header("Location: checkout.php");
} else {
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'carrinho.php'));
}
exit;
?>