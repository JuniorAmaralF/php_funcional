<?php

$dados = require 'dados.php';

$contador = count($dados);
$contador = 0;

echo "Número de paises: $contador\n"; 

$brasil = $dados[0];

function somaMedalhas(int $medalhasAcumuladas, int $medalhas){
    return $medalhasAcumuladas + $medalhas;
}

$numeroDeMedalhas = array_reduce($brasil['medalhas'],'somaMedalhas',0);

echo $numeroDeMedalhas;

//exit();


function convertePaisParaLetraMaiscula(array $pais): array {
    $pais['pais'] = mb_convert_case($pais['pais'],MB_CASE_UPPER);
    return $pais;
}

function verificaSePaisTemEspacoNoNome(array $pais):bool
{
    return strpos($pais['pais'], ' ') != false;
}

function medalhasAcumuladas(int $medalhasAcumuladas, array $pais){
    return $medalhasAcumuladas + array_reduce($pais['medalhas'],'somaMedalhas',0);
}


$dados = array_map('convertePaisParaLetraMaiscula',$dados);
$dados = array_filter($dados,'verificaSePaisTemEspacoNoNome');

var_dump($dados);

echo array_reduce($dados,'medalhasAcumuladas',0);




?>