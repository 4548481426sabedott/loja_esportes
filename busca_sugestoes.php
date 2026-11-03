<?php
header('Content-Type: application/json; charset=utf-8');

include("conexao.php");

$termo = isset($_GET['q']) ? trim($_GET['q']) : '';
$produtos = [];

if (mb_strlen($termo, 'UTF-8') >= 2) {
    $busca = '%' . $termo . '%';
    $sql = "SELECT id, nome, marca, categoria, imagem, preco
            FROM produtos
            WHERE nome LIKE ? OR marca LIKE ? OR categoria LIKE ? OR descricao LIKE ?
            ORDER BY id DESC
            LIMIT 6";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssss', $busca, $busca, $busca, $busca);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        while ($produto = mysqli_fetch_assoc($resultado)) {
            $produtos[] = $produto;
        }

        mysqli_stmt_close($stmt);
    }
}

echo json_encode($produtos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);