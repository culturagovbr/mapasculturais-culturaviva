<?php

namespace CulturaViva\Controllers;

use MapasCulturais\App;

/**
 * Gerenciamento dos Critérios usados para avaliação de uma Inscrição
 */
class Criterio extends \MapasCulturais\Controller {

    /**
     * Lista os critérios
     */
    function GET_listar() {
        $criteriosAtivos = App::i()
                ->repo('\CulturaViva\Entities\Criterio')
                ->findBy(['ativo' => true], ['ordem' => 'ASC']);
        $this->json($criteriosAtivos);
    }

    /**
     * Salva os critérios de avaliação
     */
    function POST_salvar() {
        $this->requireAuthentication();
        $app = App::i();

        // Obtém os critérios salvos na base
        $salvos = App::i()->repo('\CulturaViva\Entities\Criterio')->findBy(['ativo' => true]);
        if ($salvos == null) {
            $salvos = [];
        }

        // Criterios da requisicao
        $aPersistir = json_decode($app->request()->getBody(), true);
        usort($aPersistir, function ($a, $b) {
            return $a['ordem'] - $b['ordem'];
        });
        for ($i = 0, $l = count($aPersistir); $i < $l; $i++) {
            $aPersistir[$i]['ordem'] = $i + 1;
        }

        // Os criterios que receberam alteração, devem ser inativados
        $aInativar = [];

        for ($a = 0, $la = count($salvos); $a < $la; $a++) {
            $salvo = $salvos[$a];
            $existe = false;
            for ($b = 0, $lb = count($aPersistir); $b < $lb; $b++) {
                $novo = $aPersistir[$b];
                if (!isset($novo->id)) {
                    // Novo não possui id, nada a fazer, será salvo na base de dados
                    continue;
                }

                if ($salvo->id !== $novo->id) {
                    // Não é o mesmo item
                    continue;
                }

                $existe = true;

                if ($salvo->ordem != $novo->ordem || $salvo->descricao != $novo->descricao) {
                    // Item sofreu modificação
                    array_push($aInativar, $salvo);
                } else {
                    // Item já está salvo na base sem alteração, nao precisa modificação
                    unset($aPersistir[$b]);
                }
                break;
            }

            if (!$existe) {
                //Item foi removido da lista
                array_push($aInativar, $salvo);
            }
        }

        $app->getEm()->transactional(function ($em) use ($aPersistir, $aInativar) {
            // Inativa todos os critérios atuais
            foreach ($aInativar as $criterio) {
                $criterio->ativo = 'f';
                $em->persist($criterio);
            }

            // Salva a nova lista de critérios
            foreach ($aPersistir as $cData) {
                $criterio = new \CulturaViva\Entities\Criterio();
                $criterio->ativo = 't';
                $criterio->ordem = $cData['ordem'];
                $criterio->descricao = $cData['descricao'];
                $criterio->tsCriacao = new \DateTime(date('Y-m-d H:i:s'));
                $em->persist($criterio);
            }
        });

        $this->json(App::i()->repo('\CulturaViva\Entities\Criterio')->findBy(['ativo' => true]));
    }

}
