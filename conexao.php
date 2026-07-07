<?php
$hosts = ["localhost", "127.0.0.1"];
$user = "root";
$pass = "";
$db = "loja_esportes";
$port = 3307;

$conn = null;
$lastError = '';
$connectedHost = null;

foreach ($hosts as $host) {
    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $conn = mysqli_connect($host, $user, $pass, $db, $port);
        if ($conn) {
            mysqli_set_charset($conn, "utf8");
            $connectedHost = $host;
            break;
        }
    } catch (mysqli_sql_exception $e) {
        $lastError = $e->getMessage();
    }
}

if (!$conn) {
    $attempted = implode(', ', $hosts);
    echo '<!DOCTYPE html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Erro de Conexão</title><style>body{font-family:Arial,Helvetica,sans-serif;background:#f4f4f4;color:#333;margin:0;padding:0;display:flex;align-items:center;justify-content:center;min-height:100vh;} .box{max-width:720px;width:100%;background:#fff;border-radius:16px;box-shadow:0 16px 40px rgba(0,0,0,.08);padding:2rem;} h1{margin-top:0;color:#d32f2f;} p{line-height:1.6;}</style></head><body><div class="box"><h1>Erro na conexão com o banco de dados</h1><p>Não foi possível conectar ao MySQL usando os hosts: <strong>' . htmlspecialchars($attempted, ENT_QUOTES, 'UTF-8') . '</strong>.</p><p>Verifique se o serviço MySQL/MariaDB está ativo no XAMPP e se o banco de dados <strong>' . htmlspecialchars($db, ENT_QUOTES, 'UTF-8') . '</strong> existe.</p><p>Mensagem técnica: <strong>' . htmlspecialchars($lastError ?: 'Erro de conexão desconhecido', ENT_QUOTES, 'UTF-8') . '</strong></p></div></body></html>';
    exit;
}


if (!function_exists('formatar_preco')) {
    function formatar_preco($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}

if (!function_exists('esta_logado')) {
    function esta_logado() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario_id']);
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('verificar_tabelas')) {
    function verificar_tabelas() {
        global $conn;
        
        // Verificar se as tabelas existem
        $check = "SHOW TABLES LIKE 'produtos'";
        $result = mysqli_query($conn, $check);
        
        // Se a tabela produtos não existe, criar
        if (mysqli_num_rows($result) == 0) {
            $sql_usuarios = "CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                senha VARCHAR(32) NOT NULL,
                telefone VARCHAR(20),
                data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            mysqli_query($conn, $sql_usuarios);
            
            $sql_produtos = "CREATE TABLE IF NOT EXISTS produtos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                categoria VARCHAR(50),
                marca VARCHAR(50),
                preco DECIMAL(10,2) NOT NULL,
                descricao TEXT,
                imagem TEXT,
                estoque INT DEFAULT 0,
                destaque BOOLEAN DEFAULT FALSE,
                cores TEXT,
                tamanhos TEXT
            )";
            mysqli_query($conn, $sql_produtos);
            
            $sql_pedidos = "CREATE TABLE IF NOT EXISTS pedidos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT NOT NULL,
                total DECIMAL(10,2) NOT NULL,
                forma_pagamento VARCHAR(50),
                endereco_entrega TEXT,
                status VARCHAR(50) DEFAULT 'pendente',
                frete DECIMAL(10,2) NOT NULL DEFAULT 0,
                data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
            )";
            mysqli_query($conn, $sql_pedidos);
            
            $sql_itens = "CREATE TABLE IF NOT EXISTS itens_pedido (
                id INT AUTO_INCREMENT PRIMARY KEY,
                pedido_id INT NOT NULL,
                produto_id INT NOT NULL,
                quantidade INT NOT NULL,
                preco_unitario DECIMAL(10,2) NOT NULL,
                tamanho VARCHAR(10),
                cor VARCHAR(50),
                FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
                FOREIGN KEY (produto_id) REFERENCES produtos(id)
            )";
            mysqli_query($conn, $sql_itens);
            
            // Criar tabela de administradores
            $sql_admin = "CREATE TABLE IF NOT EXISTS admin_users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                senha VARCHAR(255) NOT NULL,
                data_criacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            mysqli_query($conn, $sql_admin);

            // Inserir administrador padrão se não existir nenhum registro
            $res_admin = mysqli_query($conn, "SELECT id, senha FROM admin_users WHERE email = 'admin@sportshop.com' LIMIT 1");
            if ($res_admin && mysqli_num_rows($res_admin) === 0) {
                $admin_nome = mysqli_real_escape_string($conn, 'Administrador');
                $admin_email = mysqli_real_escape_string($conn, 'admin@sportshop.com');
                $admin_senha = password_hash('admin123', PASSWORD_DEFAULT);
                $admin_senha_safe = mysqli_real_escape_string($conn, $admin_senha);
                mysqli_query($conn, "INSERT INTO admin_users (nome, email, senha) VALUES ('$admin_nome', '$admin_email', '$admin_senha_safe')");
            } elseif ($res_admin && mysqli_num_rows($res_admin) === 1) {
                $admin_data = mysqli_fetch_assoc($res_admin);
                $known_example_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
                if ($admin_data['senha'] === $known_example_hash) {
                    $nova_senha = password_hash('admin123', PASSWORD_DEFAULT);
                    $nova_senha_safe = mysqli_real_escape_string($conn, $nova_senha);
                    mysqli_query($conn, "UPDATE admin_users SET senha = '$nova_senha_safe' WHERE id = " . intval($admin_data['id']));
                }
            }
            
            // Inserir produtos de exemplo
            inserir_produtos_exemplo();
        }
    }
}

if (!function_exists('inserir_produtos_exemplo')) {
    function inserir_produtos_exemplo() {
        global $conn;
        
        $produtos = [
        [
            'nome' => 'Chuteira Nike Mercurial Vapor 15',
            'categoria' => 'Chuteiras',
            'marca' => 'Nike',
            'preco' => 499.90,
            'descricao' => 'A chuteira mais rápida do mercado. Tecnologia Aerowtrac para máxima velocidade e controle de bola. Cabedal em material sintético de alta durabilidade.',
            'imagem' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop',
            'estoque' => 50,
            'destaque' => true,
            'cores' => 'Preto,Vermelho,Azul,Branco',
            'tamanhos' => '34,35,36,37,38,39,40,41,42,43,44'
        ],
        [
            'nome' => 'Camisa Flamengo I 2024',
            'categoria' => 'Camisas',
            'marca' => 'Adidas',
            'preco' => 299.90,
            'descricao' => 'Camisa oficial do Flamengo 2024. Tecido Climalite que absorve o suor. Tecnologia de ventilação nas laterais.',
            'imagem' => 'https://images.pexels.com/photos/1884574/pexels-photo-1884574.jpeg?w=500&h=500&fit=crop',
            'estoque' => 100,
            'destaque' => true,
            'cores' => 'Vermelho,Preto,Branco',
            'tamanhos' => 'P,M,G,GG,XG'
        ],
        [
            'nome' => 'Tênis Running Nike Revolution 6',
            'categoria' => 'Tênis',
            'marca' => 'Nike',
            'preco' => 349.90,
            'descricao' => 'Conforto e amortecimento para suas corridas diárias. Solado em borracha durável e cabedal em mesh respirável.',
            'imagem' => 'https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?w=500&h=500&fit=crop',
            'estoque' => 75,
            'destaque' => true,
            'cores' => 'Preto,Branco,Cinza,Azul',
            'tamanhos' => '34,35,36,37,38,39,40,41,42,43,44,45'
        ],
        [
            'nome' => 'Bola de Futebol Campo Oficial',
            'categoria' => 'Equipamentos',
            'marca' => 'Penalty',
            'preco' => 129.90,
            'descricao' => 'Bola oficial tamanho 5. Couro sintético costurado à máquina. Câmara de butil para retenção de ar.',
            'imagem' => 'https://images.pexels.com/photos/4777735/pexels-photo-4777735.jpeg?w=500&h=500&fit=crop',
            'estoque' => 30,
            'destaque' => true,
            'cores' => 'Branco/Preto,Branco/Azul,Branco/Vermelho',
            'tamanhos' => 'Tamanho 5 (Oficial),Tamanho 4,Júnior'
        ],
        [
            'nome' => 'Meião Esportivo Cano Alto',
            'categoria' => 'Meias',
            'marca' => 'Nike',
            'preco' => 49.90,
            'descricao' => 'Meião cano alto com amortecimento nas áreas de impacto. Tecnologia Dri-FIT para manter os pés secos.',
            'imagem' => 'https://images.pexels.com/photos/2529149/pexels-photo-2529149.jpeg?w=500&h=500&fit=crop',
            'estoque' => 200,
            'destaque' => false,
            'cores' => 'Branco,Preto,Vermelho,Azul',
            'tamanhos' => 'P,M,G'
        ],
        [
            'nome' => 'Chuteira Adidas Predator 24',
            'categoria' => 'Chuteiras',
            'marca' => 'Adidas',
            'preco' => 599.90,
            'descricao' => 'Controle de bola excepcional com as lâminas de borracha. Cabedal em Primeknit para máximo conforto.',
            'imagem' => 'https://images.unsplash.com/photo-1511882150382-421056c89033?w=500&h=500&fit=crop',
            'estoque' => 35,
            'destaque' => true,
            'cores' => 'Preto,Vermelho,Azul',
            'tamanhos' => '36,37,38,39,40,41,42,43,44'
        ]
    ];
    
        foreach ($produtos as $produto) {
            $sql = "INSERT INTO produtos (nome, categoria, marca, preco, descricao, imagem, estoque, destaque, cores, tamanhos) 
                    VALUES (
                        '{$produto['nome']}', 
                        '{$produto['categoria']}', 
                        '{$produto['marca']}', 
                        {$produto['preco']}, 
                        '{$produto['descricao']}', 
                        '{$produto['imagem']}', 
                        {$produto['estoque']}, 
                        " . ($produto['destaque'] ? 1 : 0) . ",
                        '{$produto['cores']}',
                        '{$produto['tamanhos']}'
                    )";
            mysqli_query($conn, $sql);
        }
    }
}

if (!defined('CONEXAO_TABELAS_VERIFICADAS')) {
    verificar_tabelas();
    define('CONEXAO_TABELAS_VERIFICADAS', true);
}
?>