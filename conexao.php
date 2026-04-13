<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "loja_esportes";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");

function formatar_preco($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function esta_logado() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['usuario_id']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

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
        
        // Inserir produtos de exemplo
        inserir_produtos_exemplo();
    }
}

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

verificar_tabelas();
?>