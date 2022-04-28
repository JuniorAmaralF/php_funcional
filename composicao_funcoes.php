<?php

use function igorw\pipeline;

require_once 'vendor/autoload.php';

$dados = require 'dados.php';

$contador = count($dados);
$contador = 0;

echo "Número de paises: $contador\n"; 

$brasil = $dados[0];

$somaMedalhas = fn(int $medalhasAcumuladas, int $medalhas) => $medalhasAcumuladas + $medalhas;

function somaMedalhas(int $medalhasAcumuladas, int $medalhas)
{
    return $medalhasAcumuladas + $medalhas;
}

$numeroDeMedalhas = array_reduce($brasil['medalhas'],$somaMedalhas,0);

echo $numeroDeMedalhas;

//exit();

function convertePaisParaLetraMaiscula(array $pais): array {
    $pais['pais'] = mb_convert_case($pais['pais'],MB_CASE_UPPER);
    return $pais;
}

$verificaSePaisTemEspacoNome = fn (array $pais):bool => strpos($pais['pais'], ' ') != false;

$medalhasAcumuladas = fn (int $medalhasAcumuladas, array $pais) => $medalhasAcumuladas + array_reduce($pais['medalhas'],'somaMedalhas',0);

function pegaMedalhas(array $medalhas){
    return array_reduce($medalhas,'somaMedalhas',0);
}


$comparaMedalhas = fn (array $medalhasPais1, array $medalhasPais2): callable
    => fn (string $modalidade): int => $medalhasPais2[$modalidade] <=> $medalhasPais1[$modalidade];
 

$nomeDePaisesEmMaiusculo = fn ($dados) => array_map('convertePaisParaLetraMaiscula',$dados);
$filtraPaisesSemEspacoNoNome =  fn ($dados) => array_filter($dados,$verificaSePaisTemEspacoNome);


$funcoes = pipeline($nomeDePaisesEmMaiusculo,$filtraPaisesSemEspacoNoNome);
$dados = $funcoes($dados);



//var_dump($dados);

echo array_reduce($dados,$medalhasAcumuladas,0);

//Arrow Functions  fn e o => sao uma redução na linha de codigo , partir 7.4
//fn serio function . => seria o  {return codigo..}
$medalhas = array_reduce(
    array_map(
        fn (array $medalhas) => array_reduce($medalhas,$somaMedalhas,0),
        array_column($dados,'medalhas')
    ),
    $somaMedalhas,
    0
);

$pais_medalhas = array_column($dados,'medalhas');

$soma_dos_tipo_medalha = array_map('pegaMedalhas',$pais_medalhas);

$medalhas = array_reduce($soma_dos_tipo_medalha,'somaMedalhas',0);

usort($dados, function(array $pais1, array $pais2) use ($comparaMedalhas){
    $medalhasPais1 = $pais1['medalhas'];
    $medalhasPais2 = $pais2['medalhas'];
    $comparador = $comparaMedalhas($medalhasPais1,$medalhasPais2);

    $resultado = ($comparador('ouro') !== 0) ? $comparador('ouro')
        : ($comparador('prata') !== 0 ? $comparador('prata') 
        : $comparador('bronze') );

    return $resultado;
});

var_dump($dados);

echo $medalhas;

    
?>