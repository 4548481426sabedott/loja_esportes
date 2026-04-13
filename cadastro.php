<?php
include("conexao.php");
session_start();

if (isset($_POST['cadastrar'])) {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = md5($_POST['senha']);
    $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
    
    // Verificar se email já existe
    $check = "SELECT id FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $erro = "Este email já está cadastrado!";
    } else {
        $sql = "INSERT INTO usuarios (nome, email, senha, telefone) 
                VALUES ('$nome', '$email', '$senha', '$telefone')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = [
                'tipo' => 'success',
                'texto' => 'Cadastro realizado com sucesso! Faça login.'
            ];
            header("Location: login.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - SportShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    
    <div style="background: white; border-radius: 20px; padding: 3rem; width: 100%; max-width: 500px; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: #333; margin-bottom: 0.5rem;">Crie sua conta</h1>
            <p style="color: #666;">Preencha os dados abaixo</p>
        </div>
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone" class="form-control" placeholder="(11) 99999-9999" required>
            </div>
            
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Confirmar Senha</label>
                <input type="password" name="confirmar_senha" class="form-control" required onblur="validarSenha(this)">
                <small style="color: #666;" id="senha-msg"></small>
            </div>
            
            <button type="submit" name="cadastrar" style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: bold; cursor: pointer; margin-bottom: 1rem;">
                Cadastrar
            </button>
            
            <p style="text-align: center;">
                Já tem conta? <a href="login.php" style="color: #667eea; text-decoration: none; font-weight: bold;">Faça login</a>
            </p>
        </form>
    </div>
    
    <script>
    function validarSenha(input) {
        const senha = document.querySelector('input[name="senha"]').value;
        const msg = document.getElementById('senha-msg');
        
        if (input.value !== senha) {
            msg.style.color = '#dc3545';
            msg.textContent = 'As senhas não conferem';
            input.setCustomValidity('As senhas não conferem');
        } else {
            msg.textContent = '';
            input.setCustomValidity('');
        }
    }
    
    // Máscara para telefone
    document.querySelector('input[name="telefone"]').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = value;
        }
    });
    </script>
    
</body>
</html>