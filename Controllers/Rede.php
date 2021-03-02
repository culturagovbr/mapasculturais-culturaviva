<?php
namespace CulturaViva\Controllers;

use MapasCulturais\App;

class Rede extends \MapasCulturais\Controller{
    function GET_index(){
        $this->render('index');
    }

    function GET_entrada(){
        $this->requireAuthentication();
        $app = App::i();
        if($app->user->redeCulturaViva){
           $app->redirect($app->createUrl('cadastro','index'));
        }else{
            $this->render('entrada');
        }
    }

    function GET_faq(){
        $this->render('faq');
    }

   function GET_consulta(){
        $this->requireAuthentication();
        $this->render('consulta');
    }

    function GET_layoutPDF(){
        $this->requireAuthentication();
        $this->render('layoutPDF');
     }
}
