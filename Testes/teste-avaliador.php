<?php

use Testes\Model\Lance;
use Testes\Model\Leilao;
use Testes\Model\Usuario;
use Testes\Service\Avaliador;

require 'vendor/autoload.php';

$leilão = new Leilao('Fiat 147 0KM');

$maria = new Usuario('maria');
$joao = new Usuario('joao');

$leilão->recebeLance(new Lance($joao, 2000));
$leilão->recebeLance(new Lance($maria, 2500));
$leilão->recebeLance(new Lance($joao, 3000));

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilão);

$maiorValor = $leiloeiro->getMaiorValor();
$valorEsperado = 3000;

if($valorEsperado == $maiorValor){
    echo "Teste Ok";
} else {
    echo "Teste falhou";
}
