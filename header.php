<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("conexao.php");

$cart_count = 0;
if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    $cart_count = count($_SESSION['carrinho']);
}

// CATEGORIAS CORRETAS EM ORDEM
$categorias = ['Chuteiras', 'Camisas', 'Tênis', 'Meias', 'Equipamentos', 'Acessórios'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportShop - Sua Loja de Esportes Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        
        .navbar-premium {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo-text {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
            transition: transform 0.3s;
        }
        
        .logo-text:hover {
            transform: scale(1.05);
        }
        
        .logo-text span {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .nav-link-custom {
            color: #4a5568;
            font-weight: 600;
            margin: 0 0.5rem;
            transition: all 0.3s;
            position: relative;
            text-decoration: none;
        }
        
        .nav-link-custom:hover {
            color: #667eea;
            transform: translateY(-2px);
        }
        
        .nav-link-custom::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s;
        }
        
        .nav-link-custom:hover::after {
            width: 100%;
        }
        
        .cart-icon {
            position: relative;
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -12px;
            background: linear-gradient(135deg, #f5576c, #f093fb);
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .icon-btn {
            background: transparent;
            border: none;
            color: #4a5568;
            font-size: 1.2rem;
            transition: all 0.3s;
            padding: 0.5rem;
            border-radius: 10px;
            text-decoration: none;
        }
        
        .icon-btn:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-outline-gradient {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 8px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }
        
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .product-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.6s;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.1);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }
        
        .footer-premium {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #94a3b8;
            padding: 60px 0 20px;
            margin-top: 60px;
        }
        
        .footer-title {
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s;
            margin-right: 10px;
            color: white;
            text-decoration: none;
        }
        
        .social-icon:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: translateY(-3px);
            color: white;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-premium">
    <div class="container">
        <a class="logo-text" href="index.php">SPORT<span>SHOP</span></a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="index.php">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                <?php foreach($categorias as $cat): ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="categoria.php?cat=<?php echo urlencode($cat); ?>">
                        <?php echo htmlspecialchars($cat); ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="ofertas.php">
                        <i class="fas fa-fire text-danger"></i> Ofertas
                    </a>
                </li>
            </ul>
            
            <div class="d-flex gap-2">
                <a href="carrinho.php" class="icon-btn cart-icon position-relative">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if($cart_count > 0): ?>
                        <span class="cart-badge"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
                
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <div class="dropdown">
                        <button class="icon-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-id-card me-2"></i> Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="meus_pedidos.php"><i class="fas fa-shopping-bag me-2"></i> Meus Pedidos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="icon-btn"><i class="fas fa-user"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main>