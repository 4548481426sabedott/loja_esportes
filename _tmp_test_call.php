echo @file_get_contents('http://127.0.0.1:8000/api_cep.php?cep=01001000');
<?php
// Test call to local API (mostrando detalhes)
$r = @file_get_contents('http://127.0.0.1:8000/api_cep.php?cep=01001000');
if ($r === false) {
	echo "(vazio - possivel HTTP 503)\n";
	// mostrar headers HTTP via stream context
	$h = @get_headers('http://127.0.0.1:8000/api_cep.php?cep=01001000', 1);
	var_dump($h);
} else {
	echo $r;
}
