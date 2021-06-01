<?php
$this->bodyProperties['ng-app'] = "culturaviva";
?>

<div ng-controller="DetailCtrl">
  <!-- <div class="page-homologacao">
  <div class="homologacao">
  <header>
    <h3 class="H3">Homologação</h3>
  </header>
<label class="colunmH1">
    <input type="radio"
           value="1"
           ng-change="save_field('homologado_rcv')"
           ng-model="agent.homologado_rcv"> Ponto Homologado
</label>
<label class="colunmH2">
    <input type="radio"
           value="0"
           ng-change="save_field('homologado_rcv')"
           ng-model="agent.homologado_rcv"> Ponto NÃO Homologado
</label>
</div>
</div> -->

  <div class="page-responsavel page-base-form">

      <header>
          <h3> <div class="icon icon-user"></div> 1. Identificação do Responsável pelo Cadastro</h3>
      </header>

      <div class="btn_voltar_topo">
         <a href="<?php echo $app->createUrl('cadastro', 'index'); ?>" target="_self">voltar ao início <i class="icon icon-home"></i></a>
      </div>
      <?php echo $this->part('detail/responsavel'); ?>
  </div>

  <div class="page-dados-entidade page-base-form">
      <header>
          <h3> <div class="icon icon-home"></div> 2. Dados da Entidade ou Coletivo Cultural</h3>
      </header>
      <?php echo $this->part('detail/entidade-dados'); ?>
  </div>

  <div class="page-portfolio page-base-form">

      <header>
          <h3> <div class="icon icon-picture"></div> 3. Portfólio e Anexos</h3>
      </header>
      <?php echo $this->part('detail/portfolio'); ?>
  </div>

  <div class="page-ponto-mais page-base-form">

      <header>
          <h3><div class="icon icon-chat"></div> 4. Atuação e Articulação</h3>
      </header>
      <?php echo $this->part('detail/ponto-articulacao'); ?>
  </div>

  <div class="page-economia-viva page-base-form">

      <header>
          <h3><div class="icon icon-dollar"></div> 5. Selos Rede Viva</h3>
      </header>
      <?php echo $this->part('detail/ponto-economia-viva'); ?>
  </div>

  <div class="page-formacao page-base-form">
      <?php echo $this->part('detail/ponto-formacao'); ?>
      <div class="clear"></div>
  </div>
</div>
<?php echo $this->part('footer');