<?php

$dados = require 'dados.php';

$contador = count($dados);
$contador = 0;

echo "Número de paises: $contador\n"; 

function convertePaisParaLetraMaiscula(array $pais){
    $pais['pais'] = mb_convert_case($pais['pais'],MB_CASE_UPPER);
    return $pais;
}

$dados = array_map('convertePaisParaLetraMaiscula',$dados);

var_dump($dados);

?>