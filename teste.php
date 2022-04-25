<?php


function outra(callable $funcao): void 
{
    echo "Execuntando outro função";
    echo $funcao();
}

$nomeDafuncao = function (){
    return "uma outra função";
};

outra($nomeDafuncao);

var_dump($nomeDafuncao);

?>