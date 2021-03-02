<?php
namespace CulturaViva\Controllers;

use MapasCulturais\App;

class Cadastro extends \MapasCulturais\Controller{
    /**
     * Objeto com os ids dos agentes, inscrição e se o usuário informou um cnpj ou não.
     *
     * @var stdClass
     */
    protected $_usermeta = null;

    /**
     * Inscrição no edital Cultura Viva
     * @var \MapasCulturais\Entities\Registration
     */
    protected $_inscricao = null;

    /**
     * Agente individual (Responsável pelo Ponto de Cultura)
     * @var \MapasCulturais\Entities\Agent
     */
    protected $_responsavel = null;

    /**
     * Agente coletivo (Entidade)
     * @var \MapasCulturais\Entities\Agent
     */
    protected $_entidade = null;

    /**
     * Agente coletivo (Ponto/Pontão de Cultura)
     * @var \MapasCulturais\Entities\Agent
     */
    protected $_ponto = null;

    protected function __construct() {
        $app = App::i();

        if (!$app->user->is('guest')) {
            $this->_usermeta = json_decode($app->user->redeCulturaViva);

            if($this->_usermeta) {

                $this->_inscricao   = $app->repo('Registration')->find($this->_usermeta->inscricao);
                $this->_responsavel = $app->repo('Agent')->find($this->_usermeta->agenteIndividual);
                $this->_entidade    = $app->repo('Agent')->find($this->_usermeta->agenteEntidade);
                $this->_ponto       = $app->repo('Agent')->find($this->_usermeta->agentePonto);
            }
        }
    }

    /**
     * Objeto com os ids dos agentes, inscrição e se o usuário informou um cnpj ou não.
     *
     * @return stdClass
     */
    function getUsermeta (){
        return $this->_usermeta;
    }

    /**
     * Inscrição no edital Cultura Viva
     * @return \MapasCulturais\Entities\Registration
     */
    function getInscricao(){
        return $this->_inscricao;
    }

    /**
     * Agente individual (Responsável pelo Ponto de Cultura)
     * @return \MapasCulturais\Entities\Agent
     */
    function getResponsavel(){
        return $this->_responsavel;
    }

    /**
     * Agente coletivo (Entidade)
     * @return \MapasCulturais\Entities\Agent
     */
    function getEntidade(){
        return $this->_entidade;
    }

    /**
     * Agente coletivo (Ponto/Pontão de Cultura)
     * @return \MapasCulturais\Entities\Agent
     */
    function getPonto(){
        return $this->_ponto;
    }

    /**
     * Propriedades obrigatórias do Ponto/Pontão de Cultura
     * @return array
     */
    function getPontoRequiredProperties(){
	     $agent = $this->getPonto();
       $entidadeAgent = $this->getEntidade();
        $required_properties = [
            'name',
            'shortDescription',
            'tem_sede',
            'pais',
            'location', // ponto no mapa


            //portifólio

            //'atividadesEmRealizacao'
	    //'atividadesEmRealizacaoLink'


//            'atividadesEmRealizacao'

        ];

        if($agent->pais === 'Brasil'){
            $required_properties[] = 'En_Estado';
            $required_properties[] = 'En_Municipio';
            $required_properties[] = 'En_Bairro';
            $required_properties[] = 'En_Nome_Logradouro';
            $required_properties[] = 'En_Num';
            $required_properties[] = 'cep';
        }else{
            $required_properties[] = 'En_Municipio';
        }

        if($entidadeAgent->tipoPontoCulturaDesejado === "pontao"){
          $required_properties = [
            'participacaoMovPolitico',
            'participacaoForumCultura',
            'parceriaPoderPublico',
          ];
            if($agent->participacaoMovPolitico === "1"){
           		$required_properties[] = 'simMovimentoPoliticoCultural';
         	  }

            if($agent->participacaoForumCultura === "1"){
              $required_properties[] = 'simForumCultural';
            }

            if($agent->parceriaPoderPublico === "1"){
              $required_properties[] = 'simPoderPublico';
            }
        }

  	return $required_properties;
      }

    /**
    * Taxonomias obrigatórias do Ponto
    * @return array
    */
    function getPontoRequiredTaxonomies(){
      $agent = $this->getPonto();
      $entidadeAgent = $this->getEntidade();
      $required_taxonomies = [];

      if($entidadeAgent->tipoPontoCulturaDesejado === "pontao"){
        $required_taxonomies = [
          'contemplado_edital',
          'acao_estruturante',
          'area',
          'publico_participante',
          'area_atuacao',
          'instancia_representacao_minc',
          'rede_pertencente'
        ];
      }

      return $required_taxonomies;

    }

    /*
    * Arquivos obrigatórios do Ponto
    * @return array
    */
    function getPontoRequiredFiles(){
        $agent = $this->getPonto();
        $agentEntidade= $this->getEntidade();
        $required_files = [];

        $required_files = [
            'portifolio',
            'carta1',
            'carta2',
        ];

        if ($agentEntidade->tipoOrganizacao === 'coletivo') {
          $required_files [] = 'ata';
        }

        return $required_files;
    }
    /**
     * Propriedades obrigatórias da Entidade
     * @return array
     */
    function getEntidadeRequiredProperties(){
        $agent = $this->getEntidade();

        // 'tipoOrganizacao',
        // 'responsavel_cargo',
        // 'emailPrivado',
        // 'telefone1',
        // 'foiFomentado'

        $required_properties = [
            'tipoPonto',
            'name',
            'emailPrivado',
            'responsavel_nome',
            'responsavel_email',
            'responsavel_telefone',
            'pais'
        ];

        if($agent->pais === 'Brasil'){
            $required_properties[] = 'En_Estado';
            $required_properties[] = 'En_Municipio';
            $required_properties[] = 'En_Bairro';
            $required_properties[] = 'En_Nome_Logradouro';
            $required_properties[] = 'En_Num';
            $required_properties[] = 'cep';
        }

        if($agent->tipoPonto === 'ponto_entidade' || $agent->tipoPonto === 'pontao'){
            $required_properties[] = 'cnpj';
            $required_properties[] = 'representanteLegal';
        }


        /*if($agent->foiFomentado){
            $required_properties[] = 'tipoFomento';
            if($agent->tipoFomento === 'outros'){
                $required_properties[] = 'tipoFomentoOutros';
            }

            $required_properties[] = 'tipoReconhecimento';
            $required_properties[] = 'edital_num';
            $required_properties[] = 'edital_ano';
            $required_properties[] = 'edital_projeto_nome';
            $required_properties[] = 'edital_localRealizacao';
            $required_properties[] = 'edital_proponente';
            $required_properties[] = 'edital_projeto_resumo';
            $required_properties[] = 'edital_projeto_etapa';

            if($agent->edital_projeto_etapa === 'executado'){
                $required_properties[] = 'edital_prestacaoContas_envio';


                if($agent->edital_prestacaoContas_envio === 'enviada'){
                    $required_properties[] = 'edital_prestacaoContas_status';
                }
            }

            $required_properties[] = 'edital_projeto_vigencia_inicio';
            $required_properties[] = 'edital_projeto_vigencia_fim';

        }*/

        return $required_properties;
    }

    /**
     * Propriedades Obrigatórias do Agente Responsável
     * @return array
     */
    function getResponsavelRequiredProperties(){
        return [
            'nomeCompleto',
            'relacaoPonto',
            'cpf',
            'emailPrivado',
            'telefone1',
        ];
    }

    /**
     * Verifica se o usuário está logado e tem o metadado 'redeCulturaViva'.
     * Se não tiver redireciona para a action rede.index
     */
    protected function _validateUser(){
        $this->requireAuthentication();

        $app = App::i();

//        var_dump($app->user);
//        exit(0);

        if(!$app->user->redeCulturaViva){
            $app->redirect($app->createUrl('rede', 'entrada'), 307);
        }
    }

    protected function _checkPermissionsToViewErrors($agent){
        $this->requireAuthentication();
        $agent->checkPermission('@control');
    }

    protected function _getErrors(\MapasCulturais\Entities\Agent $entity, array $required_properties, array $required_taxonomies = [], array $required_files = []){
        $errors = [];
        foreach($required_properties as $prop){
            if(is_null($entity->$prop) || $entity->$prop === ''){
                $errors[] = $prop;
            }
        }

        foreach($required_taxonomies as $prop){
            if(is_null($entity->terms[$prop]) || empty($entity->terms[$prop])){
                $errors[] = $prop;
            }
        }

        foreach($required_files as $prop){
            //$this->_ponto->files;
            if(!isset($entity->files[$prop])){
                $errors[] = $prop;
            }
        }

        return $errors;
    }

    function getErrorsResponsavel(){
        $agent = $this->getResponsavel();
        $this->_checkPermissionsToViewErrors($agent);
        $required_properties = $this->getResponsavelRequiredProperties();


        return $this->_getErrors($agent, $required_properties);
    }

    function getErrorsEntidade(){
        $agent = $this->getEntidade();
        $this->_checkPermissionsToViewErrors($agent);
        $required_properties = $this->getEntidadeRequiredProperties();

        return $this->_getErrors($agent, $required_properties);
    }

    function getErrorsPonto(){
        $agent = $this->getPonto();
        $this->_checkPermissionsToViewErrors($agent);
        $required_properties = $this->getPontoRequiredProperties();
        $required_taxonomies = $this->getPontoRequiredTaxonomies();
        $required_files = $this->getPontoRequiredFiles();

        return $this->_getErrors($agent, $required_properties, $required_taxonomies, $required_files);
    }

    protected function _populateAgents($responsavel, $entidade, $ponto){
        $app = App::i();

        $api_url = $app->config['rcv.apiCNPJ'] . '?action=get_cultura&cnpj=' . $this->data['CNPJ'];
        $d = json_decode(file_get_contents($api_url));

        if(is_object($d) && isset($d->Nm_Responsavel)){
            // responsável
            $responsavel->nomeCompleto  = $d->Nm_Responsavel;
            $responsavel->emailPrivado  = $d->Ee_email_reponsavel;

            // entidade
            $entidade->name             = $d->Nm_Entidade;
            $entidade->nomeCompleto     = $d->Nm_Entidade;

            $entidade->rcv_Cod_pronac   = $d->Cod_pronac;
            $entidade->rcv_Cod_salic    = $d->Cod_salic;
            $entidade->rcv_Cod_scdc     = $d->Cod_scdc;


            $entidade->foiFomentado = '1';
            $entidade->tipoPontoCulturaDesejado = $d->Ds_Instrumento === 'Pontão' ? 'pontao' : strtolower($d->Ds_Instrumento);

            if($d->Ds_Tipo_ponto === 'Direto'){
                $entidade->tipoReconhecimento = 'minc';
            }else if($d->Ds_Tipo_ponto === 'Rede'){
                $entidade->tipoReconhecimento = strtolower($d->Id_Tipo_Esfera);
            }else if($d->Id_Tipo_Esfera === 'Estadual' || $d->Id_Tipo_Esfera === 'Municipal'){
                $entidade->tipoReconhecimento = strtolower($d->Id_Tipo_Esfera);
            } else {
                $entidade->tipoReconhecimento = 'outros';
            }

            $_location = ['latitude' => (float)$d->Id_Latitude, 'longitude' => (float)$d->Id_Longitude];


            $entidade->rcv_Ds_Edital       = $d->Ds_Edital;
            $entidade->rcv_Ds_Tipo_ponto   = $d->Ds_Tipo_ponto;
            $entidade->rcv_Id_Tipo_Esfera  = $d->Id_Tipo_Esfera;


            $entidade->telefonePublico     = $d->Nr_DDD1 . ' ' . $d->Nr_Telefone1;
            $entidade->telefone1           = $d->Nr_DDD2 . ' ' . $d->Nr_Telefone2;
            $entidade->telefone2           = $d->Nr_DDD3 . ' ' . $d->Nr_Telefone3;

            $entidade->site                = $d->Lk_Site;

            $entidade->location            = $_location;
            $entidade->En_Nome_Logradouro  = $d->En_Nome_Logradouro;
            $entidade->En_Num              = $d->En_Km ? $d->En_Km : $d->En_Num;
            $entidade->En_Complemento      = $d->En_Complemento;
            $entidade->En_Bairro           = $d->En_Bairro;
            $entidade->cep                 = $d->End_CEP;
            $entidade->endereco            = $d->En_Endereco_Original;
            $entidade->En_Estado           = $d->Sg_UF;
            $entidade->En_Municipio        = $d->Nm_Municipio;
            $entidade->pais                = $d->pais;

            $entidade->emailPrivado        = $d->Ee_email1;
            $entidade->emailPrivado2       = $d->Ee_email2;
            $entidade->emailPrivado3       = $d->Ee_email3;


            // ponto
            $ponto->name                = $d->Nm_Ponto;

            $ponto->telefonePublico     = $d->Nr_DDD1 . ' ' . $d->Nr_Telefone1;
            $ponto->telefone1           = $d->Nr_DDD2 . ' ' . $d->Nr_Telefone2;
            $ponto->telefone2           = $d->Nr_DDD3 . ' ' . $d->Nr_Telefone3;

            $ponto->site                = $d->Lk_Site;

            $ponto->location            = $_location;
            $ponto->En_Nome_Logradouro  = $d->En_Nome_Logradouro;
            $ponto->En_Num              = $d->En_Km ? $d->En_Km : $d->En_Num;
            $ponto->En_Complemento      = $d->En_Complemento;
            $ponto->En_Bairro           = $d->En_Bairro;
            $ponto->cep                 = $d->End_CEP;
            $ponto->endereco            = $d->En_Endereco_Original;
            $ponto->En_Estado           = $d->Sg_UF;
            $ponto->En_Municipio        = $d->Nm_Municipio;
            $ponto->pais                = $d->pais;

            $ponto->emailPrivado        = $d->Ee_email1;
            $ponto->emailPrivado2       = $d->Ee_email2;
            $ponto->emailPrivado3       = $d->Ee_email3;
        }

    }


    function ALL_index(){
        $this->_validateUser();

        $this->render('index');
    }

    function GET_responsavel(){
        $this->_validateUser();

        $this->render('responsavel');
    }

    function GET_entidadeDados(){
        $this->_validateUser();

        $this->render('entidade-dados');
    }

    function GET_entidadeFinanciamento(){
        $this->_validateUser();

        $this->render('entidade-financiamento');
    }

    function GET_pontoMapa(){
        $this->_validateUser();

        $this->render('ponto-mapa');
    }

    function GET_portifolio(){
        $this->_validateUser();

        $this->render('portifolio');
    }

    function GET_articulacao(){
        $this->_validateUser();

        $this->render('ponto-articulacao');
    }

    function GET_economiaViva(){
        $this->_validateUser();

        $this->render('ponto-economia-viva');
    }

    function GET_formacao(){
        $this->_validateUser();

        $this->render('ponto-formacao');
    }

    function GET_CNPJcadastrado(){
        $this->render('CNPJcadastrado');
    }

    function ALL_registra(){
        $this->requireAuthentication();
        $app = App::i();

        if(!$app->user->redeCulturaViva){
            $user = $app->user;

            $user->profile->rcv_tipo = 'responsavel';
            $user->profile->status = \MapasCulturais\Entities\Agent::STATUS_ENABLED;
            $user->profile->save(true);

            $opportunity = $app->repo('Opportunity')->find($app->config['redeCulturaViva.projectId']); //By(['owner' => 1], ['id' => 'asc'], 1);
            //
            // define o agente padrão (profile) como rascunho
            $app->disableAccessControl(); // não sei se é necessário desabilitar

            // criando o agente coletivo vazio
            $entidade = new \MapasCulturais\Entities\Agent;
            $entidade->type = 2;
            $entidade->parent = $user->profile;
            $entidade->name = '';
            $entidade->status = \MapasCulturais\Entities\Agent::STATUS_ENABLED;
            $entidade->rcv_tipo = 'entidade';


            // criando o agente coletivo vazio
            $ponto = new \MapasCulturais\Entities\Agent;
            $ponto->publicLocation = 1;
            $ponto->type = 2;
            $ponto->parent = $user->profile;
            $ponto->name = '';
            $ponto->status = \MapasCulturais\Entities\Agent::STATUS_ENABLED;
            $ponto->rcv_tipo = 'ponto';

            if(isset($this->data['comCNPJ']) && $this->data['comCNPJ'] && isset($this->data['CNPJ']) && $this->data['CNPJ']){
                $entidade->cnpj = $this->data['CNPJ'];
                $entidade->tipoOrganizacao = 'entidade';
                $this->_populateAgents($app->user->profile, $entidade, $ponto);
            }

            $ponto->save(true);
            $entidade->save(true);
            $app->user->profile->save(true);

            // criando a inscrição

            // relaciona o agente responsável, que é o proprietário da inscrição
            $registration = new \MapasCulturais\Entities\Registration;
            $registration->owner = $user->profile;
            $registration->opportunity = $opportunity;

            // inserir que as inscricoes online estao ativadas

            $registration->save(true);

            $user->redeCulturaViva = json_encode([
                'agenteIndividual' => $user->profile->id,
                'agenteEntidade' => $entidade->id,
                'agentePonto' => $ponto->id,
                'inscricao' => $registration->id,
                'comCNPJ' => isset($this->data['comCNPJ']) && $this->data['comCNPJ']
            ]);

            $user->save(true);

            // relaciona o agente coletivo
            $registration->createAgentRelation($entidade, 'entidade', false, true, true);
            $registration->createAgentRelation($ponto, 'ponto', false, true, true);

            $app->enableAccessControl();
        }

        if($app->request->isAjax()){
            $this->json(true);
        }else{
            $app->redirect($app->createUrl('cadastro','index'),307);
        }
    }

    function GET_errosResponsavel(){
        $this->json($this->getErrorsResponsavel());
    }

    function GET_errosEntidade(){
        $this->json($this->getErrorsEntidade());
    }

    function GET_errosPonto(){
        $this->json($this->getErrorsPonto());
    }

    function ALL_enviar(){
        $inscricao = $this->getInscricao();

        if($inscricao){
            $inscricao->checkPermission('send');
        }else{
            $this->errorJson('O usuário ainda não fez o cadastro na rede', 400);
        }

        $erros_responsavel = $this->getErrorsResponsavel();
        $erros_entidade = $this->getErrorsEntidade();

        if(!$erros_responsavel && !$erros_entidade){
            $responsavel = $this->getResponsavel();
            $entidade = $this->getEntidade();
            $ponto = $this->getPonto();

            $responsavel->publish(true);
            $entidade->publish(true);
            $ponto->publish(true);

            $espaco = new \MapasCulturais\Entities\Space;
            $espaco->type = 125; // ponto de cultura
            $espaco->owner = $entidade;
            $espaco->name = $entidade->name;
            $espaco->nomeCompleto = $entidade->nomeCompleto;
            $espaco->shortDescription = $entidade->shortDescription;
            $espaco->longDescription = $entidade->longDescription;
            $espaco->location = $entidade->location;
            $espaco->En_Estado = $entidade->En_Estado;
            $espaco->En_Municipio = $entidade->En_Municipio;
            $espaco->En_Bairro = $entidade->En_Bairro;
            $espaco->En_Num = $entidade->En_Num;
            $espaco->En_Nome_Logradouro = $entidade->En_Nome_Logradouro;
            $espaco->En_Complemento = $entidade->En_Complemento;
            $espaco->endereco = "{$espaco->En_Nome_Logradouro} {$espaco->En_Num}, {$espaco->En_Bairro}, {$espaco->En_Municipio}, {$espaco->En_Estado}";
            //$espaco->terms = $ponto->terms;

            $ponto->owner = $entidade;
            $ponto->name = $entidade->name;
            $ponto->nomeCompleto = $entidade->nomeCompleto;
            $ponto->shortDescription = $entidade->shortDescription;
            $ponto->longDescription = $entidade->longDescription;
            $ponto->location = $entidade->location;
            $ponto->En_Estado = $entidade->En_Estado;
            $ponto->En_Municipio = $entidade->En_Municipio;
            $ponto->En_Bairro = $entidade->En_Bairro;
            $ponto->En_Num = $entidade->En_Num;
            $ponto->En_Nome_Logradouro = $entidade->En_Nome_Logradouro;
            $ponto->En_Complemento = $entidade->En_Complemento;
            $ponto->endereco = "{$espaco->En_Nome_Logradouro} {$espaco->En_Num}, {$espaco->En_Bairro}, {$espaco->En_Municipio}, {$espaco->En_Estado}";
            $ponto->tipoPonto = $entidade->tipoPonto;

            $espaco->save(true);
            $entidade->save(true);
            $ponto->save(true);

            $inscricao->send();
            $app = \MapasCulturais\App::i();
            $message = $app->renderMailerTemplate('cadastro_enviado', ['name' => $app->user->profile->name]);
            $app->createAndSendMailMessage([
                'from' => $app->config['mailer.from'],
                'to' => $app->user->email,
                'subject' => $message['title'],
                'body' => $message['body']
            ]);

            $this->json($inscricao);
        } else {
            $this->errorJson([
                'responsavel' => $erros_responsavel,
                'entidade' => $erros_entidade
            ], 400);
        }
    }

    function GET_validaCNPJ(){
        $app = App::i();

        $api_urlRF = $app->config['rcv.apiCNPJRF'] . $this->data['cnpj'];
        if(strlen($this->data['cnpj']) !== 14){
            $this->errorJson('CNPJ invalido', 401);
        }else{

            $ch = curl_init();
            $header[] = 'Authorization: Basic ' . $app->config['auth_api_cnpj'];
            curl_setopt($ch, CURLOPT_URL, $api_urlRF);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $f = json_decode($result, true);
            if ( $f == null OR array_key_exists('erro', $f) ) {
				//if (strcmp($f["erro"], 'CNPJ Inválido') === 0) {
				$this->errorJson('CNPJ invalido', 401);
				//}
			}else if(strpos($f["naturezaJuridica"]["cdNaturezaJuridica"], '1') === 0){
                $this->Json($f["naturezaJuridica"]);

            }else if(strpos($f["naturezaJuridica"]["cdNaturezaJuridica"], '3') === 0){
                $this->Json($f["naturezaJuridica"]);

            }else if(strcmp($f["naturezaJuridica"]["cdNaturezaJuridica"], '2143') === 0){
                $this->Json($f["naturezaJuridica"]);

            }else{
                $this->errorJson('CNPJ com fins lucrativos', 400);
            }
        }
    }

    function GET_buscaNaturezaJuridica(){
        $app = App::i();

        $api_urlRF = $app->config['rcv.apiCNPJRF'] . $this->data['cnpj'];

        $ch = curl_init();
        $header[] = 'Authorization: Basic M2JmNDEwMDkxYmRkZjVlMjA5MmJlODYyYWEyNWZlMzQ6MTIzNDU2';
        curl_setopt($ch, CURLOPT_URL, $api_urlRF);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $f = json_decode($result, true);

        if(array_key_exists("erro", $f)){
            $this->errorJson('CNPJ invalido', 401);
        }else{
            $this->Json($f["naturezaJuridica"]["cdNaturezaJuridica"]);
        }
    }

}
