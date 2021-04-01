<?php

namespace Alura\Leilao\Model;

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
        if (!empty($this->lances) && $this->eDoUltimoUsuario($lance)) {
            throw new \DomainException('Usuário não pode propor 2 lances consecutivos');
        }

        $totalDeLancesPorUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());
        if ($totalDeLancesPorUsuario >= 5) {
            throw new \DomainException('Usuário não pode propor mais de 5 lances por leilão');
        }
        $this->lances[] = $lance;
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

    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }

    private function eDoUltimoUsuario(Lance $lance)
    {
        $ultimoLance = $this->lances[count($this->lances) - 1];

        return $lance->getUsuario() == $ultimoLance->getUsuario();
    }

    private function quantidadeLancesPorUsuario(Usuario $usuario): int
    {
        $totalDeLancesPorUsuario = array_reduce(
            $this->lances,
            fn (int $totalAcumulado, Lance $lanceAtual)
                => $lanceAtual->getUsuario() == $usuario ? $totalAcumulado + 1 : $totalAcumulado,
            0
        );

        return $totalDeLancesPorUsuario;
    }
}
