<?php
include('conexao.php');
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: admin_login.php'); exit; }

function limpar($conn, $k) { return mysqli_real_escape_string($conn, trim($k)); }

$action = isset($_POST['action']) ? $_POST['action'] : null;
if ($action === 'save') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nome = limpar($conn, $_POST['nome']);
    $categoria = limpar($conn, $_POST['categoria']);
    $marca = limpar($conn, $_POST['marca']);
    $preco = floatval($_POST['preco']);
    $preco_prom = isset($_POST['preco_promocional']) && $_POST['preco_promocional'] !== '' ? floatval($_POST['preco_promocional']) : 'NULL';
    $promocao_ativa = isset($_POST['promocao_ativa']) ? 1 : 0;
    $descricao = limpar($conn, $_POST['descricao']);
    $estoque = intval($_POST['estoque']);
    $cores = limpar($conn, $_POST['cores']);
    $tamanhos = limpar($conn, $_POST['tamanhos']);

    // Imagem handling
    $imagem_path = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['imagem']['tmp_name'];
        $info = getimagesize($tmp);
        if ($info === false) { $_SESSION['flash'] = 'Arquivo enviado não é uma imagem válida.'; header('Location: admin_produtos.php'); exit; }
        $ext = image_type_to_extension($info[2]);
        $nome_arquivo = 'uploads/' . time() . '_' . bin2hex(random_bytes(6)) . $ext;
        if (!move_uploaded_file($tmp, $nome_arquivo)) { $_SESSION['flash'] = 'Erro ao mover imagem.'; header('Location: admin_produtos.php'); exit; }
        $imagem_path = $nome_arquivo;
    }

    if ($id > 0) {
        // atualizar
        $set = "nome = '$nome', categoria = '$categoria', marca = '$marca', preco = $preco, promocao_ativa = $promocao_ativa, descricao = '$descricao', estoque = $estoque, cores = '$cores', tamanhos = '$tamanhos'";
        if ($preco_prom !== 'NULL') $set .= ", preco_promocional = $preco_prom";
        if ($imagem_path) $set .= ", imagem = '" . mysqli_real_escape_string($conn, $imagem_path) . "'";
        $sql = "UPDATE produtos SET $set WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['flash'] = 'Produto atualizado.';
        } else {
            $_SESSION['flash'] = 'Erro ao atualizar: ' . mysqli_error($conn);
        }
    } else {
        // inserir
        $img_sql = $imagem_path ? "'" . mysqli_real_escape_string($conn, $imagem_path) . "'" : "''";
        $promo_sql = $preco_prom !== 'NULL' ? $preco_prom : 'NULL';
        $sql = "INSERT INTO produtos (nome,categoria,marca,preco,preco_promocional,promocao_ativa,descricao,imagem,estoque,cores,tamanhos) VALUES ('$nome','$categoria','$marca',$preco,$promo_sql,$promocao_ativa,'$descricao',$img_sql,$estoque,'$cores','$tamanhos')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['flash'] = 'Produto criado.';
        } else {
            $_SESSION['flash'] = 'Erro ao criar: ' . mysqli_error($conn);
        }
    }

    header('Location: admin_produtos.php');
    exit;
}

if ($action === 'delete') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id > 0) {
        // tentar remover imagem associada
        $res = mysqli_query($conn, "SELECT imagem FROM produtos WHERE id = $id LIMIT 1");
        if ($res && mysqli_num_rows($res) > 0) {
            $p = mysqli_fetch_assoc($res);
            if (!empty($p['imagem']) && file_exists($p['imagem'])) @unlink($p['imagem']);
        }
        mysqli_query($conn, "DELETE FROM produtos WHERE id = $id");
        $_SESSION['flash'] = 'Produto excluído.';
    }
    header('Location: admin_produtos.php');
    exit;
}

// fallback
header('Location: admin_produtos.php');
exit;
?>
