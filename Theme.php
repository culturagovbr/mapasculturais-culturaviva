<?php

namespace CulturaViva;

use CulturaViva\Controller\Cadastro;
use MapasCulturais\AssetManager;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\Definitions\Metadata;
use MapasCulturais\Definitions\Taxonomy;
use MapasCulturais\Entities\Registration;
use MapasCulturais\Themes\BaseV1;
use MapasCulturais\App;

class Theme extends BaseV1\Theme
{

    /**
     * Controller Cadastro
     *
     * @var Cadastro
     */
    protected $_cadastro;

    public function __construct(AssetManager $asset_manager)
    {
        parent::__construct($asset_manager);
        $app = App::i();
        $view = $this;
        $app->hook('mapasculturais.run:before', function () use ($view) {
            $view->initUsermeta();
        });
    }

    function initUsermeta() {
        $app = App::i();

        if (!$app->user->is('guest')) {
            $this->_usermeta = json_decode($app->user->redeCulturaViva);

            if ($this->_usermeta) {
                $this->_inscricao = $app->repo('Registration')->find($this->_usermeta->inscricao);
                $this->_responsavel = $app->repo('Agent')->find($this->_usermeta->agenteIndividual);
                $this->_entidade = $app->repo('Agent')->find($this->_usermeta->agenteEntidade);
                $this->_ponto = $app->repo('Agent')->find($this->_usermeta->agentePonto);
            }
        }
    }

    protected static function _getTexts() {
        return array(
            'site: owner' => 'Ministério do Turismo',
            'site: by the site owner' => 'pelo Ministério do Turismo',
            'search: verified results' => 'Pontos Certificados',
            'search: verified' => "Certificados",
        );
    }

    static function getThemeFolder() {
        return __DIR__;
    }

    function aprovado() {
        $inscricao = $this->_cadastro->getInscricao();
        return $inscricao->status === Registration::STATUS_APPROVED;
    }

    protected function _init() {
        parent::_init();
        $app = App::i();

        $this->_cadastro = Controllers\Cadastro::i();

        $this->_enqueueStyles();
        $this->_enqueueScripts();
        $this->_publishAssets();
        $this->_adminAssets();
        $this->assetManager->publishAsset('img/icon-diaspora.png', 'img/icon-diaspora.png');
        $this->assetManager->publishAsset('img/icon-telegram.png', 'img/icon-telegram.png');
        $this->assetManager->publishAsset('img/icon-instagram.png', 'img/icon-instagram.png');
        $this->assetManager->publishAsset('img/icon-whatsapp.png', 'img/icon-whatsapp.png');
        $this->assetManager->publishAsset('img/icon-culturadigital.png', 'img/icon-culturadigital.png');

        $app->hook('GET(site.index):before', function() use ($app) {
            $app->redirect($app->createUrl('cadastro', 'index'));
        });

        $redeCulturaViva = $this->_cadastro->getUsermeta();
        if ($redeCulturaViva) {
            $this->jsObject['redeCulturaViva'] = $redeCulturaViva;
            $inscricao = $this->_cadastro->getInscricao();

            $this->jsObject['redeCulturaViva']->statusInscricao = $inscricao->status;
        }

        $this->assetManager->publishAsset('img/bg.png', 'img/bg.png');
        $this->assetManager->publishAsset('img/slider-home-topo/Home01.jpg', 'img/slider-home-topo/Home01.jpg');
        $this->assetManager->publishAsset('img/banner-home2.jpg', 'img/banner-home2.jpg');
        $this->assetManager->publishAsset('img/certificado.png', 'img/certificado.png');

        $app->hook('view.render(site/search):before', function() use($app) {
            $this->jsObject['searchFilters'] = [
                'agent' => ['rcv_tipo' => 'EQ(ponto)','homologado_rcv' => 'EQ(1)'],
                'event' => ['subsite' => "NULL()"]
            ];
        });

        $app->hook('view.render(cadastro/<<*>>):before', function() use($app) {
            $this->jsObject['templateUrl']['taxonomyCheckboxes'] = $this->asset('js/directives/taxonomy-checkboxes.html', false);
            $area = $app->getRegisteredTaxonomy('MapasCulturais\Entities\Agent', 'area');
            $this->jsObject['areasDeAtuacao'] = array_values($area->restrictedTerms);

            $this->jsObject['assets']['pinShadow'] = $this->asset('img/pin-sombra.png', false);
            $this->jsObject['assets']['pinMarker'] = $this->asset('img/marker-icon.png', false);

            $this->jsObject['assets']['pinAgent'] = $this->asset('img/pin-agente.png', false);
        });

        $app->hook('view.render(<<*>>):before', function() use($app) {
            $this->jsObject['apiCNPJ'] = $app->config['rcv.apiCNPJ'];
            $this->jsObject['apiCNPJRF'] = $app->config['rcv.apiCNPJRF'];
            $this->jsObject['apiHeader'] = $app->config['rcv.apiHeader'];
        });

        $app->hook('entity(agent).file(gallery).insert:after', function() {
            $this->transform('avatarBig');
        });

        $app->hook('view.render(admin/<<*>>):before', function() use($app) {
            if ($this->controller->id === 'admin' && $this->controller->action == 'index') {
                $this->setLayout('clear');
            }
        });

        $app->hook('search.filters', function(&$filters) use($app) {
            unset($filters['agent']['verificados']);
        });

        /** DESABILITANDO ROTAS  * */
        return;
        if (!$app->user->is('admin') && !$app->user->is('guest')) {
            $ids = json_decode($app->user->redeCulturaViva);
            $inscricao = $app->repo('Registration')->find($ids->inscricao);


            // ROTAS DESLIGADAS PARA USUÁRIOS QUE NÃO TIVERAM SUA INSCRIÇÃO APROVADA
            if ($inscricao->status <= 0) {
                // desabilita o painel
                $app->hook('GET(panel.<<*>>):before', function() use($app) {
                    $app->redirect($app->createUrl('cadastro', 'index'), 307);
                });

                // desabilita criação de agentes e espaços
                $app->hook('GET(<<<project|event>>.<<create|edit>>):before', function() use($app) {
                    $app->pass();
                });

                $app->hook('POST(<<project|event>>.index):before', function() use($app) {
                    $app->pass();
                });
            }

            // desabilita criação de agentes e espaços para usuários não admin
            $app->hook('GET(<<agent|space>>.<<create|edit>>):before', function() use($app) {
                $app->pass();
            });

            $app->hook('POST(<<agent|space>>.index):before', function() use($app) {
                $app->pass();
            });
        }
    }

    protected function _enqueueStyles() {
        $this->enqueueStyle('culturaviva', 'circle', 'css/circle.css');
        $this->enqueueStyle('culturaviva', 'fonts-culturavivaiicon', 'css/fonts-icon-culturaviva.css');
        $this->enqueueStyle('vendor', 'ngDialog-style', 'css/ngDialog.min.css');
        $this->enqueueStyle('vendor', 'ngDialog-theme', 'css/ngDialog-theme-default.min.css');
    }

    protected function _enqueueScripts() {
        $app = App::i();

        $this->enqueueScript('vendor', 'ng-file-upload', 'vendor/ng-file-upload.js', ['angular']);
        $this->enqueueScript('vendor', 'ngDialog', 'vendor/ngDialog.min.js');
        $this->enqueueScript('vendor', 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $app->config['app.googleApiKey']);
        $this->enqueueScript('vendor', 'angularQR', 'vendor/angular-qr.js', ['QR']);
        $this->enqueueScript('vendor', 'QR', 'vendor/qrcode.min.js');
        $this->enqueueScript('vendor', 'jsPDF', 'vendor/jspdf.min.js');
        $this->enqueueScript('vendor', 'dropdown', 'vendor/dropdown.js');

        $this->enqueueScript('culturaviva', 'angular-resource', 'vendor/angular-resource.js');
        $this->enqueueScript('culturaviva', 'angular-messages', 'vendor/angular-1.5.5/angular-messages.min.js');
        $this->enqueueScript('culturaviva', 'ui-mask', 'vendor/mask.js');

        $this->enqueueScript('culturaviva', 'cadastro-app', 'js/cadastro-app.js', ['angular-resource']);
        $this->enqueueScript('culturaviva', 'cadastro-controller', 'js/cadastro-controller.js', ['cadastro-app']);
        $this->enqueueScript('culturaviva', 'cadastro-service', 'js/cadastro-service.js', ['cadastro-app']);
        $this->enqueueScript('culturaviva', 'cadastro-directive', 'js/cadastro-directive.js', ['cadastro-app']);

        $this->enqueueScript('culturaviva', 'cadastro-culturaviva', 'js/culturaviva.js');
        $this->enqueueScript('culturaviva', 'FileSaver', 'js/FileSaver.min.js');
    }

    protected function _publishAssets() {

    }

    /**
     * Adicionas os styles e scripts usados nas telas de administração
     */
    protected function _adminAssets() {
        // App:components
        // Templates de componentes e controllers da aplicação certificação
        $this->getAssetManager()->publishFolder('admin/dist/', 'admin');
    }

    function head() {
        $assetsGroup = null;
        if ($this->controller->id == 'admin' && $this->controller->action == 'index') {
            // faz nada
        } else {
            // Não renderiza os estilos do MapasCulturais na tela de certificação, ele atrapalha toda personalização
            parent::head();

            if ($this->controller->id === 'cadastro' || $this->controller->id == 'rede' || $this->controller->id == 'admin') {
                $assetsGroup = 'culturaviva';
            }
        }

        if ($assetsGroup) {
            $this->printStyles($assetsGroup);
            $this->printScripts($assetsGroup);
        }
    }

    public function addDocumentMetas() {
        parent::addDocumentMetas();
        if (in_array($this->controller->action, ['single', 'edit'])) {
            return;
        }
        $app = App::i();
        foreach ($this->documentMeta as $key => $meta) {
            if (isset($meta['property']) && ($meta['property'] === 'og:image' || $meta['property'] === 'og:image:url')) {
                $this->documentMeta[$key] = array('property' => $meta['property'], 'content' => $app->view->asset('img/cultura-viva-share.png', false));
            }
        }
    }

    public function register() {
        parent::register();

        $app = App::i();
        $app->registerController('rede', 'CulturaViva\Controllers\Rede');
        $app->registerController('cadastro', 'CulturaViva\Controllers\Cadastro');
        $app->registerController('admin', 'CulturaViva\Controllers\Admin');
        $app->registerController('criterio', 'CulturaViva\Controllers\Criterio');
        $app->registerController('avaliacao', 'CulturaViva\Controllers\Avaliacao');
        $app->registerController('certificador', 'CulturaViva\Controllers\Certificador');
        $app->registerController('relatorios', 'CulturaViva\Controllers\Relatorios');

//        $app->registerFileGroup('agent', new \MapasCulturais\Definitions\FileGroup('portifolio', ['^application\/pdf$'], 'O portifólio deve ser um arquivo pdf.', true));
        $app->registerFileGroup('agent', new FileGroup('portifolio', ['.*'], 'O portifólio deve ser um arquivo pdf.', true));
        $app->registerFileGroup('agent', new FileGroup('carta1', ['.*'], 'a carta deve ser um arquivo pdf.', true));
        $app->registerFileGroup('agent', new FileGroup('carta2', ['.*'], 'a carta deve ser um arquivo pdf.', true));
        $app->registerFileGroup('agent', new FileGroup('cartaReferencia1', ['.*'], 'a carta deve ser um arquivo pdf.', true));
        $app->registerFileGroup('agent', new FileGroup('cartaReferencia2', ['.*'], 'a carta deve ser um arquivo pdf.', true));
        $app->registerFileGroup('agent', new FileGroup('logoponto', ['.*'], 'O logotipo deve ser uma imagem.', true));
        $app->registerFileGroup('agent', new FileGroup('ata', ['.*'], 'a ata deve ser um arquivo pdf', true));

        $metadata = [
            'MapasCulturais\Entities\User' => [
                'redeCulturaViva' => [
                    //'private' => true,
                    'label' => 'Id do Agente, Agente Coletivo e Registro da inscrição'
                ]
            ],
            'MapasCulturais\Entities\Space' => [
                'En_Bairro' => [
                    'label' => 'Bairro',
//                  'required' => true,
                    'private' => true
                ],
                'En_Num' => [
                    'label' => 'Número',
//                  'required' => true,
                    'private' => true
                ],
                'En_Nome_Logradouro' => [
                    'label' => 'Logradouro',
//                  'required' => true,
                    'private' => true
                ],
                'En_Complemento' => [
                    'label' => 'Complemento',
//                  'required' => true,
                    'private' => true
                ]
            ],
            'MapasCulturais\Entities\Agent' => [
                'rcv_sede_spaceId' => [
                    'label' => 'Id do espaço linkado ao ponto de cultura',
                    'private' => true
                ],
                'rcv_tipo' => [
                    'label' => 'Tipo de agente da Rede Cultura Viva',
                    'private' => false
                ],
                // campos para salvar infos da base de pontos existente
                'rcv_Ds_Edital' => [
                    'label' => 'Ds_Edital',
                    'private' => true
                ],
                'rcv_Ds_Tipo_Ponto' => [
                    'label' => 'Ds_Edital',
                    'private' => true
                ],
                'rcv_Id_Tipo_Esfera' => [
                    'label' => 'Id_Tipo_Esfera',
                    'private' => true
                ],
                'rcv_Cod_pronac' => [
                    'label' => 'Cod_pronac',
                    'private' => true
                ],
                'rcv_Cod_salic' => [
                    'label' => 'Cod_salic',
                    'private' => true
                ],
                'rcv_Cod_scdc' => [
                    'label' => 'Cod_scdc',
                    'private' => true
                ],
                'emailPrivado2' => [
                    'label' => 'Email privado 2',
                    'private' => true
                ],
                'emailPrivado3' => [
                    'label' => 'Email privado 3',
                    'private' => true
                ],
                'rg' => [
                    'label' => 'RG',
//                  'required' => true,
                    'private' => true
                ],
                'rg_orgao' => [
                    'label' => 'Órgão Expedidor',
//                  'required' => true,
                    'private' => true
                ],
                'nomeCompleto' => [
                    'label' => 'Nome completo',
                    'private' =>  true
                ],
                'cpf' => [
                    'label' => 'CPF',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_cpf' => [
                    'label' => 'CPF responsável',
//                  'required' => true,
                    'private' => true
                ],
                'telefone1' => [
                    'label' => 'Telefone',
//                  'required' => true,
                    'private' => true,
                    'validations' => ['v::regex("#^\d{2}[ ]?\d{4,5}\d{4}$#")' => 'Por favor, informe o telefone público no formato xx xxxx xxxx.']
                ],
                'telefone1_operadora' => [
                    'label' => 'Operadora do Telefone 1',
//                  'required' => true,
                    'private' => true
                ],
                'relacaoPonto' => [
                    'label' => 'Relação com o Ponto de Cultura',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'responsavel' => 'Sou o responsável pelo Ponto/Pontão de Cultura',
                        'funcionario' => 'Trabalho no Ponto/Pontão de Cultura',
                        'parceiro' => 'Sou parceiro do Ponto/Pontão e estou ajudando a cadastrar'
                    )
                ],
                // Metados do Agente tipo Entidade
                'semCNPJ' => [
                    'label' => 'CNPJ',
//                  'required' => true,
                    'private' => true,
                    'type' => 'boolean'
                ],
                'tipoPontoCulturaDesejado' => [
                    'label' => 'Tipo de Ponto de Cultura',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'estadual' => 'Estadual',
                        'municipal' => 'Municipal',
                        'intermunicipal' => 'Intermunicipal',
                        'nao' => 'Não'
                    )
                ],
                'redePertencente' => [
                    'label' => 'Pertence ou pertenceu a alguma rede?',
                    'required' => true,
                    'private' => true,
                    'type' => 'multiselect',
                    'options' => array(
                        'estadual' => 'Estadual',
                        'municipal' => 'Municipal',
                        'intermunicipal' => 'Intermunicipal',
                        'nao' => 'Não'
                    )
                ],
                'esferaFomento' => [
                    'label' => 'Qual esfera do fomento?',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'municipal' => 'Municipal',
                        'estadual' => 'Estadual',
                        'federal' => 'Federal'
                    )
                ],
                'tipoOrganizacao' => [
                    'label' => 'Tipo de Organização',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'coletivo' => 'Coletivo Cultural',
                        'entidade' => 'Entidade Cultural'
                    )
                ],
                'tipoPonto' => [
                    'label' => 'Deseja ser',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'ponto_entidade' => 'Entidade',
                        'ponto_coletivo' => 'Coletivo',
                        'pontao' => 'Pontão'
                    )
                ],
                'mesmoEndereco' => [
                    'label' => 'O endereço da unidade é o mesmo do ponto ou pontão?',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'sim' => 'Sim',
                        'nao' => 'Não'
                    )
                ],
                'nomePonto' => [
                    'label' => 'Nome ponto',
//                  'required' => true,
                    'private' => true,
                ],
                /*'tipoOrganizacao' => [
                    'label' => 'Tipo de Organização',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'coletivo' => 'Coletivo Cultural',
                        'entidade' => 'Entidade Cultural'
                    )
                ],*/
                'cnpj' => [
                    'label' => 'CNPJ',
//                  'required' => true,
                    'private' => true
                ],
                'representanteLegal' => [
                    'label' => 'Representante Legal',
//                  'required' => true,
                    'private' => true
                ],
                'tipoCertificacao' => [
                    'label' => 'Tipo de Certificação',
//                  'required' => true,
                    'private' => true,
                    'options' => array(
                        'ponto_coletivo' => 'Ponto de Cultura - Grupo ou Coletivo',
                        'ponto_entidade' => 'Ponto de Cultura - Entidade',
                        'pontao_entidade' => 'Pontão de Cultura - Entidade'
                    )
                ],
                'foiFomentado' => [
                    'label' => 'Você já foi fomentado pelo Min. Cidadania',
//                  'required' => true,
                    'private' => true
                ],
                'tipoFomento' => [
                    'label' => 'Você já foi fomentado pelo Min. Cidadania',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'convenio' => 'Direto com o Min. Cidadania',
                        'tcc' => 'Estatual',
                        'bolsa' => 'Municipal',
                        'premio' => 'Intermunicipal',
                        'rouanet' => 'Intermunicipal',
                        'outros' => 'Outros'
                    )
                ],
                'tipoFomentoOutros' => [
                    'label' => 'Você já foi fomentado pelo Min. Cidadania',
//                  'required' => true,
                    'private' => true
                ],
                'tipoReconhecimento' => [
                    'label' => 'Tipo de Reconhecimento',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'minc' => 'Direto com o Min. Cidadania',
                        'estadual' => 'Estatual',
                        'municipal' => 'Municipal',
                        'intermunicpal' => 'Intermunicipal'
                    )
                ],
                'edital_num' => [
                    'label' => 'Número do Edital de Seleção',
//                  'required' => true,
                    'private' => true
                ],
                'edital_ano' => [
                    'label' => 'Ano do Edital de Seleção',
//                  'required' => true,
                    'private' => true
                ],
                'edital_projeto_nome' => [
                    'label' => 'Nome do Projeto',
//                  'required' => true,
                    'private' => true
                ],
                'edital_localRealizacao' => [
                    'label' => 'Local de Realização',
//                  'required' => true,
                    'private' => true
                ],
                'edital_projeto_etapa' => [
                    'label' => 'Etapa do Projeto',
//                  'required' => true,
                    'private' => true
                ],
                'edital_proponente' => [
                    'label' => 'Proponente',
//                  'required' => true,
                    'private' => true
                ],
                'edital_projeto_resumo' => [
                    'label' => 'Resumo do projeto (objeto)',
//                  'required' => true,
                    'private' => true
                ],
//                Este metadado é uma tabela no formulário. Precisamos estudar como vai ser.
//                'recursosProjeto' => [
//                    'label' => 'Recursos do Projeto Selecionado',
////                  'required' => true,
//                    'private' => true
//                ],
                'edital_prestacaoContas_envio' => [
                    'label' => 'Prestação de Contas - Envio',
//                  'required' => true,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'enviada' => 'Enviada',
                        'naoEnviada' => 'Não Enviada',
                        'premiado' => 'Ponto de Cultura Premiado'
                    )
                ],
                'edital_prestacaoContas_status' => [
                    'label' => 'Prestação de Contas - Status',
                    'required' => false,
                    'private' => true,
                    'type' => 'select',
                    'options' => array(
                        'aprovada' => 'Aprovada',
                        'naoaprovada' => 'Não Aprovada',
                        'analise' => 'Em análise'
                    )
                ],
                'edital_projeto_vigencia_inicio' => [
                    'label' => 'Vigência',
//                  'required' => true,
                    'private' => true
                ],
                'edital_projeto_vigencia_fim' => [
                    'label' => 'Vigência',
//                  'required' => true,
                    'private' => true
                ],
                'outrosFinanciamentos' => [
                    'label' => 'Recebe ou recebeu outros financiamentos? (apoios, patrocínios, prêmios, bolsas, convênios, etc)',
//                  'required' => true,
                    'private' => true
                ],
                'outrosFinanciamentos_descricao' => [
                    'label' => 'Descrição dos outros financiamentos (apoios, patrocínios, prêmios, bolsas, convênios, etc)',
                    'required' => false,
                    'private' => true
                ],
                'telefone2' => [
                    'label' => 'Telefone',
//                  'required' => true,
                    'private' => true,
                    'validations' => ['v::regex("#^\d{2}[ ]?\d{4,5}\d{4}$#")' => 'Por favor, informe o telefone público no formato xx xxxx xxxx.']
                ],
                'telefone2_operadora' => [
                    'label' => 'Operadora',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_nome' => [
                    'label' => 'Nome do responsável',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_cargo' => [
                    'label' => 'Cargo do responsável',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_email' => [
                    'label' => 'Email do responsável',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_telefone' => [
                    'label' => 'Telefone do responsável',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_operadora' => [
                    'label' => 'Operadora do telefone do responsável',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_telefone2' => [
                    'label' => 'Telefone do responsável',
//                  'required' => true,
                    'private' => true
                ],
                'responsavel_operadora2' => [
                    'label' => 'Operadora do telefone do responsável',
//                  'required' => true,
                    'private' => true
                ],
                'En_Bairro' => [
                    'label' => 'Bairro',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_Num' => [
                    'label' => 'Número',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_Nome_Logradouro' => [
                    'label' => 'Logradouro',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_Complemento' => [
                    'label' => 'Complemento',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    },
                ],
                // @TODO: comentar quando importar os shapefiles
                'En_Estado' => [
                    'label' => 'Estado',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    },
                    'options' => [
                        'AC' => 'Acre',
                        'AL' => 'Alagoas',
                        'AP' => 'Amapá',
                        'AM' => 'Amazonas',
                        'BA' => 'Bahia',
                        'CE' => 'Ceará',
                        'DF' => 'Distrito Federal',
                        'ES' => 'Espírito Santo',
                        'GO' => 'Goiás',
                        'MA' => 'Maranhão',
                        'MT' => 'Mato Grosso',
                        'MS' => 'Mato Grosso do Sul',
                        'MG' => 'Minas Gerais',
                        'PA' => 'Pará',
                        'PB' => 'Paraíba',
                        'PR' => 'Paraná',
                        'PE' => 'Pernambuco',
                        'PI' => 'Piauí',
                        'RJ' => 'Rio de Janeiro',
                        'RN' => 'Rio Grande do Norte',
                        'RS' => 'Rio Grande do Sul',
                        'RO' => 'Rondônia',
                        'RR' => 'Roraima',
                        'SC' => 'Santa Catarina',
                        'SP' => 'São Paulo',
                        'SE' => 'Sergipe',
                        'TO' => 'Tocantins',
                    ]
                ],
                'pais' => [
                    'label' => 'Pais',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_Municipio' => [
                    'label' => 'Município',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_BairroPontaPontao' => [
                    'label' => 'Bairro',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_NumPontaPontao' => [
                    'label' => 'Número',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_Nome_LogradouroPontaPontao' => [
                    'label' => 'Logradouro',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_ComplementoPontaPontao' => [
                    'label' => 'Complemento',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    },
                ],
                // @TODO: comentar quando importar os shapefiles
                'En_EstadoPontaPontao' => [
                    'label' => 'Estado',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    },
                    'options' => [
                        'AC' => 'Acre',
                        'AL' => 'Alagoas',
                        'AP' => 'Amapá',
                        'AM' => 'Amazonas',
                        'BA' => 'Bahia',
                        'CE' => 'Ceará',
                        'DF' => 'Distrito Federal',
                        'ES' => 'Espírito Santo',
                        'GO' => 'Goiás',
                        'MA' => 'Maranhão',
                        'MT' => 'Mato Grosso',
                        'MS' => 'Mato Grosso do Sul',
                        'MG' => 'Minas Gerais',
                        'PA' => 'Pará',
                        'PB' => 'Paraíba',
                        'PR' => 'Paraná',
                        'PE' => 'Pernambuco',
                        'PI' => 'Piauí',
                        'RJ' => 'Rio de Janeiro',
                        'RN' => 'Rio Grande do Norte',
                        'RS' => 'Rio Grande do Sul',
                        'RO' => 'Rondônia',
                        'RR' => 'Roraima',
                        'SC' => 'Santa Catarina',
                        'SP' => 'São Paulo',
                        'SE' => 'Sergipe',
                        'TO' => 'Tocantins',
                    ]
                ],
                'paisPontaPontao' => [
                    'label' => 'Pais',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                'En_MunicipioPontaPontao' => [
                    'label' => 'Município',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
                ],
                // Seu Ponto no Mapa
                'mesmoEndereco' => [
                    'label' => 'Mesmo Endereco',
                    'required' => false,
                    'private' => true
                ],
                'tem_sede' => [
                    'label' => 'Tem sede propria?',
//                    'required' => true
                ],
                'sede_realizaAtividades' => [
                    'label' => 'Realiza atividades culturais na sede',
//                    'required' => true
                ],
                'sede_cnpj' => [
                    'label' => 'O endereço da sede é o mesmo registrado para o CNPJ?',
                    'required' => false
                ],
                'cep' => [
                    'label' => 'CEP',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
//                    'validations' => array(
//                        'v::regex("#^\d\d\d\d\d-\d\d\d$#")' => 'Use cep no formato 99999-999'
//                    )
                ],
                'cepPontaPontao' => [
                    'label' => 'CEP',
//                  'required' => true,
                    'private' => function() {
                        return !$this->publicLocation;
                    }
//                    'validations' => array(
//                        'v::regex("#^\d\d\d\d\d-\d\d\d$#")' => 'Use cep no formato 99999-999'
//                    )
                ],
                'localRealizacao_estado' => [
                    'label' => 'Estado',
                    'required' => false
                ],
                'localRealizacao_cidade' => [
                    'label' => 'Cidade',
                    'required' => false
                ],
                'local_de_acao_espaco' => [
                    'label' => 'Espaço',
                    'required' => false
                ],
                // portifólio
                'atividadesEmRealizacao' => [
                    'label' => 'Atividades culturais em realização'
                ],
                'atividadesEmRealizacaoLink' => [
                    'label' => 'Link para suas atividades culturais em realização'
                ],
                'flickr' => [
                    'label' => 'Flickr',
                    'required' => false
                ],
                'diaspora' => [
                    'label' => 'Diáspora',
                    'required' => false
                ],
                'youtube' => [
                    'label' => 'Youtube',
                    'required' => false
                ],
                'telegram' => [
                    'label' => 'Telegram',
                    'required' => false
                ],
                'whatsapp' => [
                    'label' => 'WhatsApp',
                    'required' => false
                ],
                'culturadigital' => [
                    'label' => 'CulturaDigital',
                    'required' => false
                ],
                'instagram' => [
                    'label' => 'Instagram',
                    'required' => false
                ],
                // Ponto Articulação
                'participacaoMovPolitico' => [
                    'label' => '',
                    'required' => true,
                    'private' => true
                ],
                'participacaoForumCultura' => [
                    'label' => '',
                    'required' => true,
                    'private' => true
                ],
                'parceriaPoderPublico' => [
                    'label' => '',
                    'required' => true,
                    'private' => true
                ],
                'fomentoPublico' => [
                    'label' => 'Possui fomento público?',
                    'required' => false,
                    'private' => true
                ],
                'parceriaPrivada' => [
                    'label' => 'Possui parceria privada?',
                    'required' => true,
                    'private' => true
                ],
                'parceriaPrivadaQual' => [
                    'label' => 'Qual?',
                    'required' => true,
                    'private' => true
                ],
                'representacaoMinc' => [
                    'label' => 'Participa de instância de representação junto ao Ministério do Turismo?',
                    'required' => false,
                    'private' => true
                ],
                'simPoderPublico' => [
                    'label' => 'Quais para radio participa poder publico',
                    'required' => false,
                    'private' => true
                ],
                'simMovimentoPoliticoCultural' => [
                    'label' => 'Quais para radio participa movimento politico cultural',
                    'required' => false,
                    'private' => true
                ],
                'simForumCultural' => [
                    'label' => 'Quais para radio participa forum cultural',
                    'required' => false,
                    'private' => true
                ],
                // Economia Viva
                'pontoOutrosRecursosRede' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoNumPessoasNucleo' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoNumPessoasColaboradores' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoNumPessoasIndiretas' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoNumPessoasParceiros' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoNumPessoasApoiadores' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoNumRedes' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoRedesDescricao' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoMovimentos' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoEconomiaSolidaria' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoEconomiaSolidariaDescricao' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoEconomiaCultura' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoEconomiaCulturaDescricao' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoMoedaSocial' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoMoedaSocialDescricao' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoTrocasServicos' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoTrocasServicosOutros' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoContrataServicos' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoContrataServicosOutros' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoInvestimentosColetivos' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'pontoInvestColetivosOutros' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ], 'pontoCustoAnual' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                // Formação
                'formador1_nome' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_email' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_telefone' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_operadora' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_areaAtuacao' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_bio' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_facebook' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_twitter' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'formador1_google' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'espacoAprendizagem1_atuacao' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'espacoAprendizagem1_tipo' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'espacoAprendizagem1_desc' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'metodologia1_nome' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'metodologia1_desc' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'metodologia1_necessidades' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'metodologia1_capacidade' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'metodologia1_cargaHoraria' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'metodologia1_certificacao' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                'metodologia1_tipo' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                // Termos de uso
                'termos_de_uso' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                //Homologação
                'homologado_rcv' => [
                    'label' => '',
                    'required' => false,
                    //'private' => false
                ],
                'info_verdadeira' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                //Campo de observação
                'obs' => [
                    'label' => '',
                    'required' => false,
                    'private' => true
                ],
                //3° telefone
                'telefone3' => [
                    'label' => 'Telefone',
//                  'required' => true,
                    'private' => true,
                    'validations' => ['v::regex("#^\d{2}[ ]?\d{4,5}\d{4}$#")' => 'Por favor, informe o telefone público no formato xx xxxx xxxx.']
                ],
                'telefone3_operadora' => [
                    'label' => 'Operadora do telefone 3',
//                  'required' => true,
                    'private' => true
                ],
            ]
        ];

        foreach ($metadata as $entity_class => $metas) {
            foreach ($metas as $key => $cfg) {
                $def = new Metadata($key, $cfg);
                $app->registerMetadata($def, $entity_class);
            }
        }

        $taxonomies = [
            // Atuação e Articulação
//            'area' => 'São as áreas do Ponto/Pontão de Cultura',
            'contemplado_edital' => 'Editais do Ministério do Turismo em que foi contemplado',
            'acao_estruturante' => 'Ações Estruturantes',
            'publico_participante' => 'Públicos que participam das ações',
            'local_realizacao' => 'Locais onde são realizadas as ações culturais',
            'area_atuacao' => 'Área de experiência e temas',
            'instancia_representacao_minc' => 'Instância de representação junto ao Ministério do Turismo',
            // Economia Viva
            'ponto_infra_estrutura' => '',
            'ponto_equipamentos' => '',
            'ponto_recursos_humanos' => '',
            'ponto_hospedagem' => '',
            'ponto_deslocamento' => '',
            'ponto_comunicacao' => '',
            'ponto_sustentabilidade' => '',
            // Formação
            'metodologias_areas' => '',
            'rede_pertencente' => 'Pertence ou pertenceu a alguma rede?'
        ];

        $id = 10;

        foreach ($taxonomies as $slug => $description) {
            $id++;
            $def = new Taxonomy($id, $slug, $description);
            $app->registerTaxonomy('MapasCulturais\Entities\Agent', $def);
        }
    }

    function _getFilters()
    {
        $filters = parent::_getFilters();

        /*
        $filters['agent']['tipoOrganizacao'] = [
             'label' => 'Tipo organização',
             'placeholder' => 'Todos',
             'fieldType' => 'singleselect',
             'filter' => [
                 'param' => 'tipoOrganizacao',
                 // 'value' => 'EQ({val})&rcv_tipo=EQ(entidade)'
             ]
        ];
        */
        unset($filters['agent']['tipos']);

        $filters['agent']['tipoPonto'] = [
            'label' => 'Tipo de Organização',
            'placeholder' => 'Todas',
            'fieldType' => 'checklist',
            'filter' => [
                'param' => 'tipoPonto',
                'value' => 'ILIKE(*{val}*)'
            ]
        ];

        $filters['agent']['En_Estado'] = [
            'fieldType' => 'checklist',
            'label' => 'Estado',
            'placeholder' => 'Selecione os Estados',
            'filter' => [
                'param' => 'En_Estado',
                'value' => 'IN({val})'
            ],
        ];

        $filters['agent']['En_Municipio'] = [
            'fieldType' => 'text',
            'label' => 'Município',
            'isArray' => false,
            'placeholder' => 'Pesquisar por Município',
            'filter' => [
                'param' => 'En_Municipio',
                'value' => 'ILIKE(*{val}*)'
            ]
        ];

        $terms = App::i()->repo('Term')->getTermsAsString('publico_participante');

        $filters['agent']['publico_participante'] =
            [
                'label' => 'Público Alvo',
                'placeholder' => 'Selecione o público alvo',
                'type' => 'term',
                'isInline' => false,
                'filter' => [
                    'param' => 'publico_participante',
                    'value' => 'IN({val})'
                ],
            ];

        foreach($terms as $t)
            $filters['agent']['publico_participante']['options'][] = ['value' => $t, 'label' => $t];

        App::i()->applyHookBoundTo($this, 'search.filters', [&$filters]);

        return $filters;
    }

}
