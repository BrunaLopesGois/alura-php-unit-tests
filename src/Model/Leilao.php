<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->eDoUltimoUsuario($lance)) {
            return;
        }

        $totalDeLancesPorUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());
        if ($totalDeLancesPorUsuario >= 5) {
            return;
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
