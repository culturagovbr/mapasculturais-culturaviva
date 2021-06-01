<?php

namespace CulturaViva\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registra os Agentes Certificadores do sistema
 *
 * @ORM\Entity
 * @ORM\Table(name="culturaviva.certificador")
 * @ORM\entity(repositoryClass="MapasCulturais\Repository")
 */
class Certificador extends \MapasCulturais\Entity {

    /**
     * Representa Agente Certificador Membro do Poder Publico
     */
    const TP_PUBLICO = 'P';

    /**
     * Representa Agente Certificador Pessoa da Sociedade Civil
     */
    const TP_CIVIL = 'C';

    /**
     * Representa Agente Certificador com Voto de Minerva
     */
    const TP_MINERVA = 'M';

    /**
     * Função de Agente Certificador do Poder Público
     */
    const ROLE_PUBLICO = 'rcv_certificador_publico';

    /**
     * Função de Agente Certificador da Sociedade Civil
     */
    const ROLE_CIVIL = 'rcv_certificador_civil';

    /**
     * Função de Agente Certificador com Voto de Minerva
     */
    const ROLE_MINERVA = 'rcv_certificador_minerva';

    /**
     * Identificador do certificador
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=true)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="culturaviva.certificador_id_seq", allocationSize=1, initialValue=1)
     *
     * @var integer
     */
    protected $id;

    /**
     * Referencia para o usuário AGENT cadastrado no schema do MapasCulturais
     *
     * @ORM\Column(name="agente_id", type="integer", nullable=false)
     *
     * @var integer
     */
    protected $agenteId;

    /**
     * Informa se este certificadro está ativo
     *
     * @ORM\Column(name="ativo", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $ativo;

    /**
     * Identifica o Tipo de Certificador
     *
     * C - Pessoa da Sociedade Civil
     * P - Membro do Poder Publico
     * M - Certificador com Voto de Minerva
     *
     * @ORM\Column(name="tipo", type="string", length=1, nullable=false)
     *
     * @var string
     */
    protected $tipo;

    /**
     * Informa se este certificador é TITULAR ou SUPLENTE
     *
     * @ORM\Column(name="titular", type="boolean", nullable=false)
     *
     * @var string
     */
    protected $titular;

    /**
     * Quando o registro foi criado
     *
     * @ORM\Column(name="ts_criacao", type="string", nullable=false)
     *
     * @var \DateTime
     */
    protected $tsCriacao;

    /**
     * Quando o registro foi atualizado
     *
     * @ORM\Column(name="ts_atualizacao", type="string", nullable=true)
     *
     * @var \DateTime
     */
    protected $tsAtualizacao;

    //============================================================= //
    // The following lines ara used by MapasCulturais hook system.
    // Please do not change them.
    // ============================================================ //

    /** @ORM\PrePersist */
    public function prePersist($args = null) {
        parent::prePersist($args);
    }

    /** @ORM\PostPersist */
    public function postPersist($args = null) {
        parent::postPersist($args);
    }

    /** @ORM\PreRemove */
    public function preRemove($args = null) {
        parent::preRemove($args);
    }

    /** @ORM\PostRemove */
    public function postRemove($args = null) {
        parent::postRemove($args);
    }

    /** @ORM\PreUpdate */
    public function preUpdate($args = null) {
        parent::preUpdate($args);
    }

    /** @ORM\PostUpdate */
    public function postUpdate($args = null) {
        parent::postUpdate($args);
    }

    //============================================================= //
    // Controle de permissão da entidade
    // ============================================================ //

    /**
     * Somente usuários com perfil AGENTE DA AREA pode fazer alterações nos certificadores
     *
     * @param \MapasCulturais\Entities\User $user
     * @return boolean
     */
    protected function canUserCreate($user) {
        return $user->is("rcv_agente_area");
    }

    /**
     * Somente usuários com perfil AGENTE DA AREA pode fazer alterações nos certificadores
     *
     * @param \MapasCulturais\Entities\User $user
     * @return boolean
     */
    protected function canUserModify($user) {
        return $user->is("rcv_agente_area");
    }

}
