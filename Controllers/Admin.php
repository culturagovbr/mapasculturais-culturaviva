<?php

namespace CulturaViva\Controllers;

use MapasCulturais\App;

/**
 * Controle principal das funcionalidades administrativas do Cultura Viva
 */
class Admin extends \MapasCulturais\Controller {

    protected $buscaAnterior = null;

    /**
     * Tela inicial do App Administracao.
     */
    function GET_index() {
        $this->requireAuthentication();
        $this->render('index');
    }

    /**
     * Obtém as informações do usuário logado
     */
    function GET_user() {
        $app = App::i();
        $usuario = $app->user;
        //$project = $app->repo('Project')->find($app->config['redeCulturaViva.projectId']);

        $roles = [];
        if (!$usuario->is('guest')) {
            foreach ($usuario->roles as $role) {
                $roles[] = $role->name;
            }
        }

        $this->json([
            'id' => $usuario->id,
            'agentId' => $usuario->profile->id,
            'name' => $usuario->profile ? $usuario->profile->name : null,
            'roles' => $roles
        ]);
    }

    function GET_cadastro() {
        $this->requireAuthentication();
        $this->render('cadastro');
    }

    function GET_entidade() {
        $_user = App::i()->repo('User')->find($this->getUrlData()['id']);
        if ($_user) {
            $this->json($_user);
        } else {
            $this->json(['erro' => "usuario nao encontrado"], 400);
        }
    }

}
