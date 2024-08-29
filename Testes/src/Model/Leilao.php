<?php

namespace Testes\Model;

use DomainException;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    /** @var bool */
    private $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance)
    {
        if(!empty($this->lances) && $this->ehDoUltimoUsuario($lance)){
            throw new DomainException('O usuario não pode propor mais de dois lances seguidos');
        }
       
        $totalLancesUsuario = $this->qtdLancesPorUsuario($lance->getUsuario());
        if($totalLancesUsuario >= 5) {
            throw new DomainException('O usuario não pode propor mais de 5 lances por leilão');
        }
       
        $this->lances[] = $lance;
    }

    private function ehDoUltimoUsuario(Lance $lance): bool
    {   
        $ultimoLance = $this->lances[array_key_last($this->lances)];
        return $lance->getUsuario() == $ultimoLance->getUsuario(); 
    }

    private function qtdLancesPorUsuario(Usuario $usuario): int
    {   
        $totalLancesUsuario = array_reduce($this->lances, 
        function (int $total, Lance $lance) use ($usuario) {
            if($lance->getUsuario() == $usuario) {
                return $total + 1;
            }
            return $total;
        },0);
        return $totalLancesUsuario; 
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    public function finaliza()
    {
        $this->finalizado = true;
    }

    public function estaFinalizado() : bool
    {
        return $this->finalizado;
    }
}
