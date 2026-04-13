<?php
// Nomeie este arquivo como: atualizar_imagens.php
// Coloque na raiz do seu site e execute uma vez

include("conexao.php");

// Mapeamento de produtos com suas imagens reais
$imagens_produtos = [
    "Chuteira Nike Mercurial" => "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop",
    "Chuteira Adidas Predator" => "https://images.unsplash.com/photo-1511882150382-421056c89033?w=400&h=400&fit=crop",
    "Chuteira Nike Mercurial Superfly 9 Elite" => "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop",
    "Chuteira Nike Mercurial Vapor 15 Elite" => "https://images.unsplash.com/photo-1556906781-9a412961c28c?w=400&h=400&fit=crop",
    "Chuteira Adidas Predator 24 Elite" => "https://images.unsplash.com/photo-1511882150382-421056c89033?w=400&h=400&fit=crop",
    "Chuteira Adidas X Crazyfast Elite" => "https://images.pexels.com/photos/2529155/pexels-photo-2529155.jpeg?w=400&h=400&fit=crop",
    "Chuteira Puma Ultra 5 Elite" => "https://images.pexels.com/photos/4777734/pexels-photo-4777734.jpeg?w=400&h=400&fit=crop",
    "Chuteira Puma Future 7 Ultimate" => "https://images.pexels.com/photos/248549/pexels-photo-248549.jpeg?w=400&h=400&fit=crop",
    
    "Camisa Brasil I 2024" => "https://images.pexels.com/photos/2583572/pexels-photo-2583572.jpeg?w=400&h=400&fit=crop",
    "Camisa Flamengo I 2024" => "https://images.pexels.com/photos/1884574/pexels-photo-1884574.jpeg?w=400&h=400&fit=crop",
    "Camisa Corinthians I 2024" => "https://images.pexels.com/photos/2583571/pexels-photo-2583571.jpeg?w=400&h=400&fit=crop",
    "Camisa São Paulo I 2024" => "https://images.pexels.com/photos/248568/pexels-photo-248568.jpeg?w=400&h=400&fit=crop",
    "Camisa Palmeiras I 2024" => "https://images.pexels.com/photos/248550/pexels-photo-248550.jpeg?w=400&h=400&fit=crop",
    "Camisa Real Madrid I 2024" => "https://images.pexels.com/photos/248553/pexels-photo-248553.jpeg?w=400&h=400&fit=crop",
    "Camisa Argentina I 2024" => "https://images.pexels.com/photos/1884575/pexels-photo-1884575.jpeg?w=400&h=400&fit=crop",
    
    "Tênis Running Nike" => "https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?w=400&h=400&fit=crop",
    "Tênis Nike Air Max" => "https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?w=400&h=400&fit=crop",
    "Tênis Adidas Ultraboost" => "https://images.pexels.com/photos/2529155/pexels-photo-2529155.jpeg?w=400&h=400&fit=crop",
    "Tênis Casual Adidas" => "https://images.pexels.com/photos/2529155/pexels-photo-2529155.jpeg?w=400&h=400&fit=crop",
    
    "Bola de Futebol Campo" => "https://images.pexels.com/photos/4777735/pexels-photo-4777735.jpeg?w=400&h=400&fit=crop",
    
    "Meião Esportivo" => "https://images.pexels.com/photos/2529149/pexels-photo-2529149.jpeg?w=400&h=400&fit=crop",
    "Meião de Futebol Branco" => "https://images.pexels.com/photos/2529149/pexels-photo-2529149.jpeg?w=400&h=400&fit=crop",
    "Meião de Futebol Preto" => "https://images.pexels.com/photos/2529153/pexels-photo-2529153.jpeg?w=400&h=400&fit=crop",
    "Meião de Futebol Vermelho" => "https://images.pexels.com/photos/2529151/pexels-photo-2529151.jpeg?w=400&h=400&fit=crop",
    "Meião de Futebol Azul" => "https://images.pexels.com/photos/2529152/pexels-photo-2529152.jpeg?w=400&h=400&fit=crop",
];

$atualizados = 0;
$erros = 0;

foreach ($imagens_produtos as $nome_produto => $imagem_url) {
    $sql = "UPDATE produtos SET imagem = '$imagem_url' WHERE nome LIKE '%$nome_produto%'";
    if (mysqli_query($conn, $sql)) {
        $atualizados++;
        echo "✅ Produto '$nome_produto' atualizado com sucesso!<br>";
    } else {
        $erros++;
        echo "❌ Erro ao atualizar '$nome_produto': " . mysqli_error($conn) . "<br>";
    }
}

echo "<br><br>📊 Resumo:<br>";
echo "✅ Produtos atualizados: $atualizados<br>";
echo "❌ Erros: $erros<br>";
echo "<br>🎉 Atualização concluída! <a href='index.php'>Voltar para o site</a>";

mysqli_close($conn);
?>