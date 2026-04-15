<?php
$prod = urlencode(json_encode([['id'=>1,'quantidade'=>1], ['id'=>2,'quantidade'=>1]]));
$url = "http://127.0.0.1:8000/api_correios.php?cep=04538000&produtos={$prod}";
$r = @file_get_contents($url);
if ($r === false) {
    echo "(vazio - possivel erro)\n";
    var_dump(@get_headers($url,1));
} else {
    echo $r;
}
