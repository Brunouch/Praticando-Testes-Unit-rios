<?php

namespace Testes\tests\Service;

use DomainException;
use PHPUnit\Framework\TestCase;
use Testes\Model\Lance;
use Testes\Model\Leilao;
use Testes\Model\Usuario;
use Testes\Service\Avaliador;



class AvaliadorTest extends TestCase
{
    /** @var Avaliador  */
    private $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }
    /**
     * @dataProvider leilao
     */
    public function test_avaliador_busca_lance_com_maior_valor(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        $this->assertEquals(3000, $maiorValor);
    }

    /**
     * @dataProvider leilao
     */
    public function test_avaliador_busca_lance_com_menor_valor(Leilao $leilao)
    {

        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        $this->assertEquals(2000, $menorValor);
    }

    /**
     * @dataProvider leilao
     */
    public function test_avaliador_busca_3_maiores_lances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->getMaioresLances();

        $this->assertCount(3, $maioresLances);
        $this->assertEquals(3000, $maioresLances[0]->getValor());
        $this->assertEquals(2500, $maioresLances[1]->getValor());
        $this->assertEquals(2000, $maioresLances[2]->getValor());
    }

    public function testLeilaoVazioNaoPodeSerAvaliado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Não é possivel avaliar leilão vazio');
        $leilao = new Leilao('Fusca azul 0KM');
        $this->leiloeiro->avalia($leilao);
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Leilão já finalizado');
        
        $leilao = new Leilao('Fiat 147 0KM');
        $leilao->recebeLance(new Lance(new Usuario('Teste'), 2000));
        $leilao->finaliza();

        $this->leiloeiro->avalia($leilao);
    }

    public static function leilao()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('maria');
        $joao = new Usuario('joao');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 3000));
        $leilao->recebeLance(new Lance($joao, 2500));

        return [
            [$leilao]
        ];
    }

}
