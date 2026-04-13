<?php
session_start();

$id = $_GET['id'];

if(($key = array_search($id,$_SESSION['carrinho'])) !== false){

unset($_SESSION['carrinho'][$key]);

}

header("Location: carrinho.php");

?>