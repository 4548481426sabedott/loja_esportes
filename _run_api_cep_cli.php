<?php
// Executa api_cep.php em modo CLI definindo $_GET para teste
$_GET['cep'] = '01001000';
ob_start();
include __DIR__ . '/api_cep.php';
$out = ob_get_clean();
echo "==OUTPUT==\n";
echo $out;
echo "\n==END==\n";
