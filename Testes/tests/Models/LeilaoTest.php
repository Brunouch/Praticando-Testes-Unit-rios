<?php

namespace Testes\tests\Models;

use DomainException;
use PHPUnit\Framework\TestCase;
use Testes\Model\Lance;
use Testes\Model\Leilao;
use Testes\Model\Usuario;

class LeilaoTest extends TestCase
{
    public function testLeilaonNaoDeveTerLancesRepetidos()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('O usuario não pode propor mais de dois lances seguidos');
        $leilao = new Leilao('Variante');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($ana, 1500));
    }

    public function testLeilaoNaoAceitaMaisDe5LancesPorUsuario()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('O usuario não pode propor mais de 5 lances por leilão');
        $leilao = new Leilao('Brasília Amarela 0KM');
        $ana = new Usuario('Ana');
        $joao = new Usuario('João');
        $leilao->recebeLance(new Lance($ana, 1000));
        $leilao->recebeLance(new Lance($joao, 1500));
        $leilao->recebeLance(new Lance($ana, 2000));
        $leilao->recebeLance(new Lance($joao, 2500));
        $leilao->recebeLance(new Lance($ana, 3000));
        $leilao->recebeLance(new Lance($joao, 3500));
        $leilao->recebeLance(new Lance($ana, 4000));
        $leilao->recebeLance(new Lance($joao, 4500));
        $leilao->recebeLance(new Lance($ana, 5000));
        $leilao->recebeLance(new Lance($joao, 5500));
        $leilao->recebeLance(new Lance($ana, 6000));
    }
    /**
     * @dataProvider geraLances
     */
    public function testLeilaoRecebeLances(int $qtdLances, Leilao $leilao, array $valores)
    {   
        static::assertCount($qtdLances, $leilao->getLances());

        foreach ($valores as $i => $item) {
            static::assertEquals($item, $leilao->getLances()[$i]->getValor());
        }
    }

    public static function geraLances()
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao2Lances = new leilao('Fiat 147 0KM');
        $leilao2Lances->recebeLance(new Lance($joao, 1000));
        $leilao2Lances->recebeLance(new Lance($maria, 2000));

        $leilao1Lance = new leilao('Fisca 1970 0KM');
        $leilao1Lance->recebeLance(new Lance($joao, 5000));

        return [
            [2, $leilao2Lances, [1000, 2000]],
            [1, $leilao1Lance, [5000]],
        ];
    }
}