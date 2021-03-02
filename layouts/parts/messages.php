<style>
    .messages {
        position: fixed;
        top:5px;
        left:5px;
        right:5px;
        text-align: center;
        z-index:999999999;
    }

    .messages span{
        padding:10px;
        min-width:250px;
        display:inline-block;

    }

    .messages.sucesso span {
        background: #afa;
    }
    .messages.erro span {
        background: #faa;
    }
    .messages.enviando span {
        background: #ffa;
    }
</style>

<div ng-show="messages.status !== null" class="messages" ng-class="{sucesso: messages.status === 'sucesso', erro: messages.status === 'erro', enviando: messages.status === 'enviando'}"><span>{{messages.text}}</span></div>

<script type="text/ng-template" id="modalDesejaSer">
    <h4><b>Informações</b></h4>
    <h5>Entidade cultural</h5>
    <p style="font-size: 13px;">
        Pessoa jurídica de direito privado sem fins lucrativos, de natureza ou finalidade cultural,
        que desenvolva e articule atividades culturais em suas comunidades;
    </p>

    <h5>Coletivo Cultural</h5>
    <p style="font-size: 13px;">
        Povo, comunidade, grupo e núcleo social comunitário sem constituição jurídica, de natureza ou finalidade cultural,
        rede e movimento sociocultural, que desenvolvam e articulem atividades culturais em suas comunidades.
    </p>

    <h5>Pontão de Cultura</h5>
    <p style="font-size: 13px;">
        Entidades com constituição jurídica, de natureza/finalidade cultural e/ou educativa,
        que desenvolvam, acompanhem e articulem atividades culturais,
        em parceria com as redes regionais, indenitárias e temáticas de pontos de cultura e outras redes temáticas,
        que se destinam à mobilização, à troca de experiências,
        ao desenvolvimento de ações conjuntas com governos locais e à articulação entre os diferentes pontos
        de cultura que poderão se agrupar em nível estadual e/ou regional ou por áreas temáticas de interesse
        comum, visando à capacitação, ao mapeamento e a ações conjuntas.
    </p>
</script>
