<?php

namespace Testes\Service;

use DomainException;
use Testes\Model\Lance;
use Testes\Model\Leilao;

class Avaliador
{
    private $maiorValor = -INF;

    private $menorValor = INF;

    private $maioresLances;

    public function avalia(Leilao $leilao): void
    {
        if($leilao->estaFinalizado()) {
            throw new DomainException('Leilão já finalizado');
        }
        
        if(empty($leilao->getLances())) {
            throw new DomainException('Não é possivel avaliar leilão vazio');
        }
        foreach ($leilao->getLances() as $lance) {
            if ($lance->getValor() > $this->maiorValor) {
                $this->maiorValor = $lance->getValor();
            } 
            
            if ($lance->getValor() < $this->menorValor) {
                $this->menorValor = $lance->getValor();
            }
        }

        $lances = $leilao->getLances();
        usort($lances, function (Lance $lance1, Lance $lance2) {
            return $lance2->getValor() - $lance1->getValor();
        });

        $this->maioresLances = array_slice($lances, 0, 3);
    }

    public function getMenorValor(): float
    {
        return $this->menorValor;
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMaioresLances(): array
    {
        return $this->maioresLances;
    }
}
