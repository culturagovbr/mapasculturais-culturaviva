<?php

namespace CulturaViva\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registra os Critérios usados para avaliação de uma Inscrição
 *
 * Um registro de critério não pode sofrer alteração, deve ser inserido um novo registro
 * sempre que sofrer alterção, pois a avaliação das inscrições serão feitos
 * pelos critérios existentes na epoca da finalização do cadastro pela entidade.
 *
 * @ORM\Entity
 * @ORM\Table(name="culturaviva.criterio")
 * @ORM\entity(repositoryClass="MapasCulturais\Repository")
 */
class Criterio extends \MapasCulturais\Entity {

    /**
     * Identificador do critério.
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="culturaviva.criterio_id_seq", allocationSize=1, initialValue=1)
     *
     * @var integer
     */
    protected $id;

    /**
     * Informa a ordem de exibição deste critério
     *
     * @ORM\Column(name="ordem", type="integer", nullable=false)
     *
     * @var integer
     */
    protected $ordem;

    /**
     * Informa se este critério está ativo
     *
     * @ORM\Column(name="ativo", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $ativo;

    /**
     * Texto descritivo do critério
     *
     * @ORM\Column(name="descricao", type="string", nullable=false)
     *
     * @var string
     */
    protected $descricao;

    /**
     * Quando o registro foi criado
     *
     * @ORM\Column(name="ts_criacao", type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $tsCriacao;

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
     * Somente usuários com perfil AGENTE DA AREA pode fazer alterações nos Critérios de avaliação
     *
     * @param \MapasCulturais\Entities\User $user
     * @return boolean
     */
    protected function canUserCreate($user) {
        return $user->is("rcv_agente_area");
    }

    /**
     * Somente usuários com perfil AGENTE DA AREA pode fazer alterações nos Critérios de avaliação
     *
     * @param \MapasCulturais\Entities\User $user
     * @return boolean
     */
    protected function canUserModify($user) {
        return $user->is("rcv_agente_area");
    }

}
