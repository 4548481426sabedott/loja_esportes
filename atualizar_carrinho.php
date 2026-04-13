<?php
session_start();

$id = $_GET['id'];
$acao = $_GET['acao'];

if ($acao == 'adicionar') {
    $_SESSION['carrinho'][] = $id;
} elseif ($acao == 'remover') {
    $key = array_search($id, $_SESSION['carrinho']);
    if ($key !== false) {
        unset($_SESSION['carrinho'][$key]);
        // Reindexa o array
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    }
}

// Se for requisição AJAX
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['success' => true]);
    exit;
}

header("Location: carrinho.php");
exit;
?>