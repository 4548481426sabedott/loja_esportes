<?php
include("header.php");

if (!esta_logado()) {
    $_SESSION['mensagem'] = [
        'tipo' => 'error',
        'texto' => 'Você precisa estar logado para acessar esta página.'
    ];
    redirect("login.php");
}

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id = $usuario_id";
$result = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($result);
?>

<div class="container">
    <div class="section-title">
        <h2>Meu Perfil</h2>
    </div>
    
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #06b6d4); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-user" style="font-size: 2rem; color: white;"></i>
                </div>
                <h3><?php echo $usuario['nome']; ?></h3>
                <p style="color: #666;">Membro desde <?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?></p>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="<?php echo $usuario['email']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Telefone</label>
                <input type="text" class="form-control" value="<?php echo $usuario['telefone']; ?>" readonly>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <a href="meus_pedidos.php" style="flex: 1;">
                    <button style="width: 100%; background: #3b82f6;">Meus Pedidos</button>
                </a>
                <a href="logout.php" style="flex: 1;">
                    <button style="width: 100%; background: #ef4444;">Sair</button>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>