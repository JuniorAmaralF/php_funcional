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

function pegaMedalhas(array $medalhas){
    return array_reduce($medalhas,'somaMedalhas',0);
}


function comparaMedalhas(array $medalhasPais1, array $medalhasPais2):callable
{
    return function(string $modalidade) use ($medalhasPais1,$medalhasPais2): int {
        return $medalhasPais2[$modalidade] <=> $medalhasPais1[$modalidade];
    };
} 

$dados = array_map('convertePaisParaLetraMaiscula',$dados);
//$dados = array_filter($dados,'verificaSePaisTemEspacoNoNome');

//var_dump($dados);

echo array_reduce($dados,'medalhasAcumuladas',0);


$medalhas = array_reduce(array_map(function (array $medalhas){
    return array_reduce($medalhas,'somaMedalhas',0);
},array_column($dados,'medalhas')),
'somaMedalhas',
0
);

$pais_medalhas = array_column($dados,'medalhas');

$soma_dos_tipo_medalha = array_map('pegaMedalhas',$pais_medalhas);

$medalhas = array_reduce($soma_dos_tipo_medalha,'somaMedalhas',0);

usort($dados, function(array $pais1, array $pais2){
    $medalhasPais1 = $pais1['medalhas'];
    $medalhasPais2 = $pais2['medalhas'];
    $comparador = comparaMedalhas($medalhasPais1,$medalhasPais2);

    $resultado = ($comparador('ouro') !== 0) ? $comparador('ouro')
        : ($comparador('prata') !== 0 ? $comparador('prata') 
        : $comparador('bronze') );

    return $resultado;
});

var_dump($dados);


echo $medalhas;

?>