<?php if($this->isEditable() && $entity->canUser('verify')): ?>
<input id="is-verified-input" type="hidden" class="js-editable" data-edit="isVerified" data-value="<?php echo $entity->isVerified ? '1' : '0'?>"/>
<div class="widget widget-verified">
    <a href="#"
       class="verified-seal editable js-verified hltip <?php if($entity->isVerified) echo ' active'; ?>"
       data-verify-url="<?php echo $this->controller->createUrl('verify', array($entity->id)) ?>"
       data-remove-verification-url="<?php echo $this->controller->createUrl('removeVerification', array($entity->id)) ?>"
       title="Clique para marcar/desmarcar este <?php echo $entity->entityType ?>."
    ></a>
</div>
<?php elseif($entity->homologado_rcv): ?>
<div class="widget widget-verified">
    <a class="certified-icon hltip active" title="Este Ponto é certificado." href="#"></a>
</div>
<?php endif; ?>
