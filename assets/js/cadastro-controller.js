(function(angular){
    'use strict';
    var app = angular.module('culturaviva.controllers', []);

    var agentsPontoDados = ["name",
                        "nomeCompleto",
                        "cnpj",
                        "representanteLegal",
                        "tipoPontoCulturaDesejado",
                        "tipoOrganizacao",
                        "emailPrivado",
                        "telefone1",
                        "telefone1_operadora",
                        "telefone2",
                        "telefone2_operadora",
                        "responsavel_nome",
                        "responsavel_email",
                        "responsavel_cargo",
                        "responsavel_telefone",
                        "geoEstado",
                        "geoMunicipio",
                        "pais",
                        "En_Bairro",
                        "En_Num",
                        "En_Nome_Logradouro",
                        "En_Complemento"
                      ];


    var agentPontoFinanciamento = [
                        "tipoCertificacao",
                        "foiFomentado",
                        "tipoFomento",
                        "tipoFomentoOutros",
                        "tipoReconhecimento",
                        "edital_num",
                        "edital_ano",
                        "edital_projeto_nome",
                        "edital_localRealizacao",
                        "edital_projeto_etapa",
                        "edital_proponente",
                        "edital_projeto_resumo",
                        "edital_prestacaoContas_envio",
                        "edital_prestacaoContas_status",
                        "edital_projeto_vigencia_inicio",
                        "edital_projeto_vigencia_fim",
                        "outrosFinanciamentos",
                        "outrosFinanciamentos_descricao",
                        "rcv_Ds_Edital"
                      ];

    var termos = {
            area: MapasCulturais.areasDeAtuacao,

            local_realizacao: [
                'Escolas',
                'Universidades',
                'Praças',
                'Salas',
                'CEUs',
                'Feiras',
                'Eventos'
            ],

            contemplado_edital: [
                'Ponto de Cultura',
                'Pontão de Cultura',
                'Ponto de Mídia Livre',
                'Ponto de Memória',
                'Ponto de Leitura',
                'Ponto de Cultura Indígena',
                'Pontinho de Cultura',
                'Pontão de Bens Registrados',
                'Ainda não fui contemplado'
            ],

            acao_estruturante: [
                'Conhecimentos tradicionais',
                'Cultura, comunicação e mídia livre',
                'Cultura e educação',
                'Economia criativa e solidária',
                'Cultura digital',
                'Cultura e juventude',
                'Intercâmbio e residências artístico-culturais',
                'Cultura e saúde',
                'Cultura e direitos humanos',
                'Livro, Leitura e literatura',
                'Memória e patrimônio cultural',
                'Cultura e meio ambiente',
                'Cultura, infância e adolescência',
                'Agente cultura viva',
                'Cultura circense'
            ],

            publico_participante: [
                'Afro-Brasileiros',
                'Ciganos',
                'Estudantes',
                'Grupos artísticos e culturais independentes',
                'Idosos ',
                'Imigrantes',
                'Indígenas',
                'Crianças e Adolescentes',
                'Juventude',
                'LGBT',
                'Mulheres',
                'Pescadores',
                'Pessoas com deficiência',
                'Pessoas em situação de sofrimento psíquico',
                'População de Rua',
                'População em regime prisional',
                'Povos e Comunidades Tradicionais de Matriz Africana',
                'Público em Geral',
                'Quilombolas',
                'Ribeirinhos',
                'População Rural',
                'População de Baixa Renda',
                'Grupos assentados de reforma agrária',
                'Mestres, praticantes, brincantes e grupos culturais populares, urbanos e rurais',
                'Pessoas ou grupos vítimas de violência',
                'População sem teto',
                'Populações atingida por barragens',
                'Populações de regiões fronteiriças',
                'Populações em áreas de vulnerabilidade social'
            ],

            area_atuacao: [
                'Produção',
                'Cultural',
                'Artes Cênicas',
                'Artes Visuais',
                'Artesanato',
                'Audiovisual',
                'Capacitação',
                'Capoeira',
                'Contador de Histórias',
                'Cultura Afro',
                'Cultura Alimentar',
                'Cultura Digital',
                'Culturas Indígenas',
                'Culturas Populares',
                'Comunicação Direitos Humanos',
                'Esporte',
                'Fotografia',
                'Gastronomia',
                'Gênero',
                'Hip Hop',
                'Juventude',
                'Literatura',
                'Meio Ambiente',
                'Moda',
                'Música',
                'Software Livre',
                'Tradição Oral',
                'Turismo',
                'Internacional'
            ],

            instancia_representacao_minc: [
                'Colegiados',
                'Fóruns',
                'Comissões',
                'Conferência Nacional de Cultura',
                'Grupo de Trabalho',
                'Conselhos'
            ],

            // Economia Viva
            'ponto_infra_estrutura': [
                'Acesso à internet',
                'Sala de aula Auditório',
                'Teatro',
                'Estúdio',
                'Palco',
                'Galpão',
                'Hackerspace',
                'Casa',
                'Apartamento',
                'Cozinha',
                'Garagem',
                'Jardim',
                'Bar',
                'Laboratório',
                'Gráfica',
                'Loja'
            ],
            'ponto_equipamentos':[
                'Câmera fotográfica',
                'Câmera filmadora',
                'Microfone',
                'Fone de Ouvido',
                'Boom',
                'Spot de luz',
                'Refletor',
                'Mesa de Som',
                'Caixa de Som',
                'Instrumento Musical',
                'Computador',
                'Mesa de Edição',
                'Impressora',
                'Scanner'
            ],
            'ponto_recursos_humanos':[
                'Ator / Atriz',
                'Dançarino / Dançarina',
                'Músico / Musicista',
                'Pesquisador',
                'Oficineiro',
                'Produtor',
                'Elaborador de Projeto',
                'Cultural',
                'Captador de Recursos',
                'Realizador audiovisual (Videomaker)',
                'Designer',
                'Fotógrafo',
                'Hacker',
                'Iluminador',
                'Sonorizador',
                'Maquiador',
                'Cenógrafo',
                'Eletricista',
                'Bombeiro',
                'Hidráulico',
                'Consultor',
                'Palestrante',
                'Rede',
                'Médica',
                'Solidária'
            ],
            'ponto_hospedagem':[
                'Convênio com Rede Hoteleira',
                'Hospedagem',
                'Solidária',
                'Camping'
            ],
            'ponto_deslocamento':[
                'Passagem Aérea',
                'Carona, Veículo',
                'Passagem Terrestre'
            ],
            'ponto_comunicacao':[
                'Assessoria de Imprensa',
                'Produção de Conteúdo e Mobilização nas Redes Sociais',
                'Produção de Conteúdo e Informação',
                'Jornalismo',
                'Audiovisual',
                'Fotografia',
                'Desenvolvimento Web',
                'Mídia',
                'Comunitária',
                'Design'
            ],
            'ponto_sustentabilidade':[
                'Prestação de serviços',
                'Venda de produtos',
                'Patrocínio',
                'Apoio/doação/colaboração',
                'Troca direta e indireta',
                'Empréstimo',
                'Emprego/salário',
                'Convênio com Órgão público',
                'Moeda complementar (social)'
            ],

            'metodologias_areas': [
               'Não formal',
               'Conhecimento popular',
               'Conhecimento empírico',
               'Acadêmica',
               'Ensino básico',
               'Ensino médio',
               'Ensino superior',
               'Graduação',
               'Pós-graduação'
            ]
        };

    function extendController($scope, $timeout, Entity, agent_id, $http){

        $scope.messages = {
            status: null,
            text: '',
            show: function(status, text){
                this.status = status;
                this.text = text;
            },
            hide: function(){
                this.status = null;
                this.text = '';
            }
        };

        $scope.$watch('messages.status', function(new_status, old_status){
            if(new_status === null){
                $scope.messages.text = '';
                return;
            }

            var timeout = 2500;

            if(new_status === 'erro'){
                timeout = 5000;
            }

            $timeout(function(){
                $scope.messages.hide();
            }, timeout);
        });

        if(Entity && agent_id){
            $scope.originalAgent = {};
            $scope.agent.$promise.then(function(agent){
                if(typeof agent.location === 'object'){
                    agent.location = [agent.location.longitude, agent.location.latitude];
                }
                $scope.originalAgent = JSON.parse(angular.toJson(agent));
            });

            $scope.save_field = function save_field(field) {
                if(angular.equals($scope.agent[field], $scope.originalAgent[field])){
                    return;
                }
                console.log($scope.agent[field], $scope.originalAgent[field]);

                $scope.originalAgent[field] = angular.copy($scope.agent[field]);

                var agent_update = {};
                agent_update[field] = $scope.agent[field];
                $scope.messages.show('enviando', 'salvando alterações');

                Entity.patch({'id': agent_id}, agent_update, function(agent){
                    $scope.messages.show('sucesso', 'alterações salvas');
                }, function(error){
                    try{
                        $scope.messages.show('erro', error.data.data[field].toString());
                    }catch(e){
                        $scope.messages.hide();
                    }
                });
            };
        }

        $scope.showInvalid = function(agentTipo, nomeForm){
          $scope.data = MapasCulturais.redeCulturaViva;
          $http.post(MapasCulturais.createUrl('cadastro','enviar')).
          error(function errorCallback(response) {
              if(response.error){
                  $scope.data.validationErrors = response.data;
              }
              switch (agentTipo) {
                case 'responsavel':
                  var form = $scope[nomeForm];
                  var erros_responsavel = $scope.data.validationErrors.responsavel;
                  erros_responsavel.forEach(function(elemento,index,array){
                    if(form[elemento]){
                      angular.element("[name='"+elemento+"']").addClass('input_erro');
                    }
                  });
                  break;
                case 'entidade':
                  var form = $scope[nomeForm];
                  var erros_entidade = $scope.data.validationErrors.entidade;
                  erros_entidade.forEach(function(elemento,index,array){
                    if(form[elemento]){
                      angular.element("[name='"+elemento+"']").addClass('input_erro');
                    }
                  });
                case 'ponto':
                  var form = $scope[nomeForm];
                  var erros_ponto = $scope.data.validationErrors.ponto;
                  erros_ponto.forEach(function(elemento,index,array){
                    if(form[elemento]){
                      angular.element("[name='"+elemento+"']").addClass('input_erro');
                    }
                  });
                break;
              }
          });
        }
    }

    app.controller('DashboardCtrl', ['$scope', 'Entity', 'MapasCulturais', '$http', '$timeout',
        function($scope, Entity, MapasCulturais, $http, $timeout){

            var agent_id = MapasCulturais.redeCulturaViva.agenteIndividual;

            var params = {
                'id': agent_id,
                '@select': 'id,singleUrl,name,rg,rg_orgao,relacaoPonto,cpf,geoEstado,terms,'+
                           'emailPrivado,telefone1,telefone1_operadora,nomeCompleto,'+
                           'geoMunicipio,facebook,twitter,googleplus,mesmoEndereco,shortDescription,' +
                           'termos_de_uso',

//                '@files':'(avatar.avatarBig,portifolio,gallery.avatarBig):url',
                '@permissions': 'view'
            };

            $scope.agent = Entity.get(params);

            extendController($scope, $timeout, Entity, agent_id);

            $scope.data = MapasCulturais.redeCulturaViva;
            $scope.enviar = function(){
                $http.post(MapasCulturais.createUrl('cadastro', 'enviar')).
                        success(function successCallback(response) {
                            $scope.data.statusInscricao = 1;
                            $scope.data.validationErrors = null;
                        }).
                        error(function errorCallback(response) {
                            if(response.error){
                              $scope.data.validationErrors = response.data;
                            }
                            var erroResponsavel = $scope.data.validationErrors.responsavel;
                            var erroPonto = $scope.data.validationErrors.ponto;
                            var erroEntidade= $scope.data.validationErrors.entidade;
                            if(erroResponsavel.length > 0){
                              $scope.data.mostrarErroResponsavel = "responsavel";
                            }
                            if(erroPonto.length > 0){
                              if((erroPonto.indexOf("atividadesEmRealizacaoLink") !== -1) || (erroPonto.indexOf("atividadesEmRealizacao") !== -1)){
                                $scope.data.mostrarErroPonto = "ponto_portifolio";
                              }else{
                                $scope.data.mostrarErroPontoMapa = "ponto_mapa";
                              }
                            }
                            if(erroEntidade.length > 0){
                              var i;
                              var j;
                              for(i = 0; i < erroEntidade.length; i++){
                                for(j = 0; j < agentsPontoDados.length; j++){
                                    if(erroEntidade[i] === agentsPontoDados[j]){
                                      $scope.data.mostrarErroEntidadeDado = "entidade_showdado";
                                    }
                                }
                              }
                            }
                        });
            };
        }
    ]);


    // TODO: Tranforma em diretiva
     app.controller('ImageUploadCtrl', ['$scope', 'Entity', 'MapasCulturais', 'Upload', '$timeout', '$http',
        function ImageUploadCtrl($scope, Entity, MapasCulturais, Upload, $timeout, $http) {

            // FIXME passar como parametro para generalizar
            var agent_id = MapasCulturais.redeCulturaViva.agentePonto;

            var params = {
                'id': agent_id,
                '@select': 'id,files',
                '@permissions': 'view'
            };

            $scope.agent = Entity.get(params);
            $scope.agent.$promise.then(function(){
                $scope.agent.files.gallery = $scope.agent.files.gallery || [];
            });

            $scope.config = {
                images: {
                    maxUploadSize: '2MB',
                    validation: 'image/(p?jpeg|png)'
                },
                pdf: {
                    maxUploadSize: '8MB',
                    validation: 'application/pdf'
                }
            };

            $scope.deleteFile = function(file){

                $http.delete(MapasCulturais.createUrl('file','single',[file.id])).then(function(){
                    if(file.group === 'gallery'){
                        $scope.agent.files.gallery.forEach(function(f, index){
                            if(file.id === f.id){
                                $scope.agent.files.gallery.splice(index,1);
                            }
                        });
                    }else{
                        $scope.agent.files[file.group] = null;
                    }
                }, function(a,b,c){
                    console.log('não foi possível apagar a imagem', a,b,c);
                });
            };

            $scope.uploadFile = function(file, group) {
                $scope.f = file;
                if (file && !file.$error) {
                    var data = {};
                    data[group] = file;
                    file.upload = Upload.upload({
                        url: MapasCulturais.createUrl('agent', 'upload', [agent_id]),
                        data: data
                    });

                    file.upload.then(function (response) {
                        if (response.data.error) {
                            alert(response.data.data.portifolio);
                            return;
                        }
                        if (group === 'gallery') {
                            $scope.agent.files.gallery.push(response.data.gallery[0]);
                        } else {
                            $scope.agent.files[group] = response.data[group];
                        }

                        $timeout(function () {
                            file.result = response.data;
                        });

                        $timeout(function(){
                            $scope.f = 0;
                        }, 1500);
                    }, function (response) {
                        if (response.status > 0)
                            $scope.errorMsg = response.status + ': ' + response.data;
                    });

                    file.upload.progress(function (evt) {
                        file.progress = Math.min(100, parseInt(100.0 *
                                                               evt.loaded / evt.total));
                    });
                }
            };

       }
    ]);


    // Controller do 'Informações do responsável'
    app.controller('ResponsibleCtrl', ['$scope', 'Entity', 'MapasCulturais', 'Upload', '$timeout', '$location', '$http',
        function ResponsibleCtrl($scope, Entity, MapasCulturais, Upload, $timeout, $location, $http)
        {
            var agent_id = MapasCulturais.redeCulturaViva.agenteIndividual;
//            BaseAgentCtrl.call(this, $scope, Agent, MapasCulturais, agent_id, Upload, $timeout);

            var params = {
                'id': agent_id,
                '@select': 'id,rcv_tipo,singleUrl,name,rg,rg_orgao,relacaoPonto,pais,cpf,geoEstado,terms,'+
                           'emailPrivado,telefone1,telefone1_operadora,nomeCompleto,'+
                           'geoMunicipio,facebook,twitter,googleplus,telegram,whatsapp,culturadigital,diaspora,instagram,mesmoEndereco,shortDescription',

                '@files':'(avatar.avatarBig,portifolio,gallery.avatarBig):url',
                '@permissions': 'view'
            };

            $scope.agent = Entity.get(params, function(){
              extendController($scope, $timeout, Entity, agent_id, $http);

              if($location.search().invalid === '1'){
                $scope.showInvalid($scope.agent.rcv_tipo, 'form_responsavel');
              }
            });

       }
    ]);

    app.controller('PortifolioCtrl', ['$scope', 'Entity', 'MapasCulturais', 'Upload', '$timeout', 'geocoder', 'cepcoder', '$location', '$http',
        function PortifolioCtrl($scope, Entity, MapasCulturais, Upload, $timeout, geocoder, cepcoder, $location, $http)
        {
            var agent_id = MapasCulturais.redeCulturaViva.agentePonto;

            var params = {
                'id': agent_id,
                '@select': 'id,rcv_tipo,longDescription,atividadesEmRealizacao,site,facebook,twitter,googleplus,flickr,diaspora,youtube,instagram,culturadigital,atividadesEmRealizacaoLink',
                '@files':'(avatar.avatarBig,portifolio,gallery.avatarBig,cartasRecomendacao):url',
                '@permissions': 'view'
            };

            $scope.agent = Entity.get(params, function(){
              extendController($scope, $timeout, Entity, agent_id, $http);

              if($location.search().invalid === '1'){
                $scope.showInvalid($scope.agent.rcv_tipo, 'form_portifolio');
              }
            });
        }
    ]);


    // Controller do 'Seu ponto no Mapa'
    app.controller('PointCtrl', ['$scope', 'Entity', 'MapasCulturais', 'Upload', '$timeout', 'geocoder', 'cepcoder', '$location', '$http',
        function PointCtrl($scope, Entity, MapasCulturais, Upload, $timeout, geocoder, cepcoder, $location, $http)
        {
            var agent_id = MapasCulturais.redeCulturaViva.agentePonto;

            var params = {
                'id': agent_id,
                '@select': 'id,rcv_tipo,terms,name,shortDescription,cep,tem_sede,sede_realizaAtividades,mesmoEndereco,pais,geoEstado,geoMunicipio,'+
                    'En_Bairro,En_Num,En_Nome_Logradouro,En_Complemento,localRealizacao_estado,localRealizacao_cidade,'+
                    'localRealizacao_cidade,localRealizacao_espaco,location',
                '@files':'(avatar.avatarBig,portifolio,gallery.avatarBig):url',
                '@permissions': 'view'
            };

            $scope.markers = {};
            $scope.agent = Entity.get(params, function(agent){
                $scope.markers.main = {
                    lat: agent.location.latitude,
                    lng: agent.location.longitude,
                    message: agent.endereco
                };
                extendController($scope, $timeout, Entity, agent_id, $http);

                if($location.search().invalid === '1'){
                  $scope.showInvalid($scope.agent.rcv_tipo, 'form_ponto');
                }
            });

            $scope.termos = termos;

            $scope.$watch('markers.main', function(point){
                if(point && point.lat && point.lng) {
                    $scope.agent['location'] = [point.lng, point.lat];
                    $scope.save_field('location');
                }
            }, true);

            $scope.cepcoder = {
                busy: false,
                code: function(cep){
                    $scope.agent.cep = cep;
                    $scope.save_field('cep');
                    $scope.cepcoder.busy = true;
                    cepcoder.code(cep).then(function(res){
                        var addr = res.data;
                        if(addr){
                            $scope.agent.geoEstado = addr.uf;
                            $scope.save_field('geoEstado');

                            $scope.agent.geoMunicipio = addr.localidade;
                            $scope.save_field('geoMunicipio');

                            $scope.agent.En_Bairro = addr.bairro;
                            $scope.save_field('En_Bairro');

                            $scope.agent.En_Nome_Logradouro = addr.logradouro;
                            $scope.save_field('En_Nome_Logradouro');

                            var string = (addr.logradouro ? addr.logradouro+', ':'') +
                                         (addr.bairro ? addr.bairro+', ':'') +
                                         (addr.localidade ? addr.localidade+', ':'') +
                                         (addr.uf ? addr.uf+' - ':'');

                        }

                        return geocoder.code(string);

                    }).then(function(point){
                        point.zoom = 14;
                        $scope.markers.main = point;
                    })['catch'](function(){
                        $scope.markers.main = undefined;
                    }).finally(function(){
                        $scope.cepcoder.busy = false;
                    });
                }
            };
        }
    ]);

    app.controller('PontoArticulacaoCtrl', ['$scope', 'Entity', 'MapasCulturais', '$timeout', '$location', '$http',
        function PontoArticulacaoCtrl($scope, Entity, MapasCulturais, $timeout, $location, $http)
        {
            var agent_id = MapasCulturais.redeCulturaViva.agentePonto;

            var params = {
                'id': agent_id,
                '@select': 'id,rcv_tipo,terms,participacaoMovPolitico,participacaoForumCultura,parceriaPoderPublico, simMovimentoPoliticoCultural, simForumCultural, simPoderPublico',
                '@permissions': 'view'
            };

            $scope.termos = termos;

            $scope.agent = Entity.get(params, function(){
              extendController($scope, $timeout, Entity, agent_id, $http);

              if($location.search().invalid === '1'){
                $scope.showInvalid($scope.agent.rcv_tipo, 'form_pontoArticulacao');
              }
            });

        }
    ]);

    app.controller('PontoEconomiaVivaCtrl', ['$scope', 'Entity', 'MapasCulturais', '$timeout', '$location', '$http',
        function PontoEconomiaVivaCtrl($scope, Entity, MapasCulturais, $timeout, $location, $http)
        {
            var agent_id = MapasCulturais.redeCulturaViva.agentePonto;

            var params = {
                'id': agent_id,
                '@select': 'id,rcv_tipo,terms,pontoOutrosRecursosRede,pontoNumPessoasNucleo,pontoNumPessoasColaboradores,' +
                           'pontoNumPessoasIndiretas,pontoNumPessoasParceiros,pontoNumPessoasApoiadores,pontoNumRedes,' +
                           'pontoRedesDescricao,pontoMovimentos,pontoEconomiaSolidaria,pontoEconomiaSolidariaDescricao,' +
                           'pontoEconomiaCultura,pontoEconomiaCulturaDescricao,pontoMoedaSocial,pontoMoedaSocialDescricao,' +
                           'pontoTrocasServicos,pontoTrocasServicosOutros,pontoContrataServicos,pontoContrataServicosOutros,' +
                           'pontoInvestimentosColetivos,pontoInvestColetivosOutros,pontoCustoAnual',
                '@permissions': 'view'
            };

            $scope.termos = termos;

            $scope.agent = Entity.get(params, function(){
              extendController($scope, $timeout, Entity, agent_id, $http);

              if($location.search().invalid === '1'){
                $scope.showInvalid($scope.agent.rcv_tipo, 'form_pontoEconomia');
              }
            });

        }
    ]);

    app.controller('PontoFormacaoCtrl', ['$scope', 'Entity', 'MapasCulturais', '$timeout', '$location', '$http',
        function PontoFormacaoCtrl($scope, Entity, MapasCulturais, $timeout, $location, $http)
        {
            var agent_id = MapasCulturais.redeCulturaViva.agentePonto;

            var params = {
                'id': agent_id,
                '@select': 'id,rcv_tipo,terms,formador1_nome,formador1_email,formador1_telefone,formador1_operadora,formador1_areaAtuacao,' +
                    'formador1_bio,formador1_facebook,formador1_twitter,formador1_google,espacoAprendizagem1_atuacao,espacoAprendizagem1_tipo,' +
                    'espacoAprendizagem1_desc,metodologia1_nome,metodologia1_desc,metodologia1_necessidades,metodologia1_capacidade,' +
                    'metodologia1_cargaHoraria,metodologia1_certificacao',
                '@permissions': 'view'
            };

            $scope.termos = termos;

            $scope.agent = Entity.get(params, function(){
              extendController($scope, $timeout, Entity, agent_id, $http);

              if($location.search().invalid === '1'){
                $scope.showInvalid($scope.agent.rcv_tipo, 'form_pontoFormacao');
              }
            });

        }
    ]);

    app.controller('EntityCtrl', ['$scope', '$timeout', 'Entity', 'MapasCulturais', '$location', '$http',
        function($scope, $timeout, Entity, MapasCulturais, $location, $http){
            var agent_id = MapasCulturais.redeCulturaViva.agenteEntidade;

            var params = {
                'id': agent_id,

                '@select': 'id,rcv_tipo,name,nomeCompleto,cnpj,representanteLegal,' +
                    'tipoPontoCulturaDesejado,tipoOrganizacao,' +
                    'emailPrivado,telefone1,telefone1_operadora,telefone2,telefone2_operadora,' +
                    'responsavel_nome,responsavel_email,responsavel_cargo,responsavel_telefone,' +
                    'geoEstado,geoMunicipio,pais,En_Bairro,En_Num,En_Nome_Logradouro,En_Complemento',

                '@permissions': 'view'
            };

            $scope.agent = Entity.get(params, function(){
              extendController($scope, $timeout, Entity, agent_id, $http);

              if($location.search().invalid === '1'){
                $scope.showInvalid($scope.agent.rcv_tipo, 'form_entity');
              }
            });


        }
    ]);

    app.controller('EntityContactCtrl', ['$scope', 'Entity', 'MapasCulturais', '$timeout', '$location', '$http',
        function($scope, Entity, MapasCulturais, $timeout, $location, $http){
            var agent_id = MapasCulturais.redeCulturaViva.agenteEntidade;

            var params = {
                'id': agent_id,

                '@select': 'id,rcv_tipo,tipoCertificacao,foiFomentado,tipoFomento,tipoFomentoOutros,tipoReconhecimento,edital_num,' +
                    'edital_ano,edital_projeto_nome,edital_localRealizacao,edital_projeto_etapa,' +
                    'edital_proponente,edital_projeto_resumo,edital_prestacaoContas_envio,' +
                    'edital_prestacaoContas_status,edital_projeto_vigencia_inicio,' +
                    'edital_projeto_vigencia_fim,outrosFinanciamentos,outrosFinanciamentos_descricao,' +
                    'rcv_Ds_Edital',

                '@permissions': 'view'
            };

            $scope.agent = Entity.get(params, function(){
              extendController($scope, $timeout, Entity, agent_id, $http);

              if($location.search().invalid === '1'){
                $scope.showInvalid($scope.agent.rcv_tipo, 'form_entityContact');
              }
            });

        }
    ]);

    app.controller('EntradaCtrl',['$scope', '$http', '$timeout', function($scope, $http, $timeout){
        $scope.data = {
            naoEncontrouCNPJ: false,
            encontrouCNPJ: false,
            cnpj: null,
            comCNPJ: false
        };
        extendController($scope, $timeout);
        $scope.consultaCNPJ = function(){
            $scope.messages.show('enviando', "Procurando CNPJ em nossa base");
            $http.get(MapasCulturais.apiCNPJ + '?action=get_cultura&cnpj=' + $scope.data.cnpj).
                    success(function success(data){
                        if(data.Id){
                            $scope.messages.show('sucesso', "CNPJ encontrado");

                            $scope.data.naoEncontrouCNPJ = false;
                            $scope.data.encontrouCNPJ = $scope.data.cnpj;

                            $scope.registrar();
                        }else{
                            $scope.messages.show('erro', "CNPJ não encontrado");

                            $scope.data.naoEncontrouCNPJ = true;
                            $scope.data.encontrouCNPJ = false;
                        }
                    });
        };

        $scope.registrar = function(){
            var data = {};
            if($scope.data.comCNPJ){
                data.comCNPJ = 1;
                if($scope.data.encontrouCNPJ){
                    data.CNPJ = $scope.data.encontrouCNPJ;
                }else{
                    data.CNPJ = $scope.data.cnpj;
                }
            }

            $scope.messages.show('enviando', "Registrando na rede");

            $http.post(MapasCulturais.createUrl('cadastro','registra'), data).
                    success(function(){
                        $scope.messages.show('sucesso', "Registrado com sucesso");
                        document.location = MapasCulturais.createUrl('cadastro','index');
                    }).
                    error(function(){
                        $scope.messages.show('erro', "Um erro inesperado aconteceu");
                    });
        };
    }]);

})(angular);
