<?php
session_start();

// Permitir CORS para requisições AJAX
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['produto_id'])) {
    $_SESSION['produto_selecionado'] = [
        'produto_id' => $data['produto_id'],
        'cor' => $data['cor'] ?? null,
        'tamanho' => $data['tamanho'] ?? null
    ];
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>