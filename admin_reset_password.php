<?php
// Script temporário para redefinir senha de um administrador.
// Uso (abra no navegador):
// http://localhost/loja_esportes/admin_reset_password.php?confirm=1&email=admin@sportshop.com&senha=admin123

include('conexao.php');

// Proteção básica: exige confirm=1 para executar
if (!isset($_GET['confirm']) || $_GET['confirm'] !== '1') {
    echo "Para executar este script, acesse com o parâmetro ?confirm=1 .<br>Ex: ?confirm=1&email=admin@exemplo.com&senha=novaSenha";
    exit;
}

$email = isset($_GET['email']) ? $_GET['email'] : 'admin@sportshop.com';
$nova = isset($_GET['senha']) ? $_GET['senha'] : 'admin123';

if (empty($email) || empty($nova)) {
    echo "Parâmetros 'email' e 'senha' são obrigatórios.";
    exit;
}

$email_safe = mysqli_real_escape_string($conn, $email);
$hash = password_hash($nova, PASSWORD_DEFAULT);
$hash_safe = mysqli_real_escape_string($conn, $hash);

$sql = "UPDATE admin_users SET senha = '$hash_safe' WHERE email = '$email_safe'";

if (mysqli_query($conn, $sql)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo "Senha atualizada com sucesso para: " . htmlspecialchars($email) . "<br>Senha nova: " . htmlspecialchars($nova) . "<br>Por segurança, remova este arquivo após o uso.";
    } else {
        echo "Nenhum usuário encontrado com o email informado: " . htmlspecialchars($email);
    }
} else {
    echo "Erro ao atualizar senha: " . mysqli_error($conn);
}

?>
