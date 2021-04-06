<?php

namespace CulturaViva\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registra as Inscrições originadas pelo cadastro feito pelo Pontão/Ponto de Cultura
 *
 * As Inscrições serão avaliadas pelos Certificadores
 *
 * @ORM\Entity
 * @ORM\Table(name="culturaviva.inscricao")
 * @ORM\entity(repositoryClass="MapasCulturais\Repository")
 */
class Uf extends \MapasCulturais\Entity {


    /**
     * Identificador da Inscrição
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="culturaviva.uf_id_seq", allocationSize=1, initialValue=1)
     *
     * @var integer
     */
    protected $id;


    /**
     * Sigla do estado
     * @ORM\Column(name="sigla", type="string", length=2, nullable=false)
     *
     * @var string
     */
    protected $sigla;

    /**
     * Sigla do estado
     * @ORM\Column(name="nome", type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $nome;

    /**
     * Sigla do estado
     * @ORM\Column(name="redistribuicao", type="boolean", nullable=false)
     *
     * @var string
     */
    protected $redistribuicao;



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

}
