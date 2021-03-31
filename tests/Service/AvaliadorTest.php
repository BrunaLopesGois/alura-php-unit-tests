<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private Avaliador $leiloeiro;
    
    public function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }
    
    /**
     * @dataProvider entregaLeiloes
     */
    public function testAvaliadorDeveEncontrarOMaiorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        $this->assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider entregaLeiloes
     */
    public function testAvaliadorDeveEncontrarOMenorValorDeLances(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        $this->assertEquals(1700, $menorValor);
    }

    public function testAvaliadorDeveBuscar3MaioresValores()
    {
        $leilao = new Leilao('Fiat 147 0KM');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $moquidesia = new Usuario('Moquidésia');
        $irineu = new Usuario('Irineu');

        $leilao->recebeLance(new Lance($moquidesia, 1500));
        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 2000));
        $leilao->recebeLance(new Lance($irineu, 1700));

        $this->leiloeiro->avalia($leilao);

        $maiores = $this->leiloeiro->getMaioresLances();
        $this->assertCount(3, $maiores);
        $this->assertEquals(2000, $maiores[0]->getValor());
        $this->assertEquals(1700, $maiores[1]->getValor());
        $this->assertEquals(1500, $maiores[2]->getValor());
    }

    public function leilaoEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $moquidesia = new Usuario('Moquidésia');

        $leilao->recebeLance(new Lance($moquidesia, 1700));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return $leilao;
    }

    public function leilaoEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $moquidesia = new Usuario('Moquidésia');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($moquidesia, 1700));

        return $leilao;
    }

    public function leilaoEmOrdemAleatoria()
    {
        $leilao = new Leilao('Fiat 147 0KM');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        $moquidesia = new Usuario('Moquidésia');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($moquidesia, 1700));
        $leilao->recebeLance(new Lance($maria, 2500));

        return $leilao;
    }

    public function entregaLeiloes()
    {
        return [
            "Ordem crescente" => [
                $this->leilaoEmOrdemCrescente()
            ],
            "Ordem decrescente" => [
                $this->leilaoEmOrdemDecrescente()
            ],
            "Ordem aleatoria" => [
                $this->leilaoEmOrdemAleatoria()
            ]
        ];
    }
}
