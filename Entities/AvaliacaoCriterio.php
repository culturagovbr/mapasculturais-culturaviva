<?php

namespace CulturaViva\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registra os valores para os Critérios de uma Inscrição Avaliados por um Certificador
 *
 * @ORM\Entity
 * @ORM\Table(name="culturaviva.avaliacao_criterio")
 * @ORM\entity(repositoryClass="MapasCulturais\Repository")
 */
class AvaliacaoCriterio extends \MapasCulturais\Entity {

    /**
     * Referencia para a Avaliação da Inscrição
     *
     * @ORM\Id
     * @ORM\Column(name="avaliacao_id", type="integer", nullable=false)
     *
     * @var integer
     */
    protected $avaliacaoId;

    /**
     * Referencia para a Incrição do Pontao/Ponto de Cultura
     *
     * @ORM\Id
     * @ORM\Column(name="inscricao_id", type="integer", nullable=false)
     *
     * @var integer
     */
    protected $inscricaoId;

    /**
     * Referencia para o Critério de Avaliação
     *
     * @ORM\Id
     * @ORM\Column(name="criterio_id", type="integer", nullable=false)
     *
     * @var integer
     */
    protected $criterioId;

    /**
     * Informa se o critério foi marcado como APROVADO pelo Avaliador
     *
     * @ORM\Column(name="aprovado", type="boolean", nullable=false)
     *
     * @var bool
     */
    protected $aprovado;

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
     * Somente Certificadores pode alterar valores para critérios da avaliação
     *
     * @param \MapasCulturais\Entities\User $user
     * @return boolean
     */
    protected function canUserModify($user) {
        $rolesCertificador = [
            Certificador::ROLE_PUBLICO,
            Certificador::ROLE_CIVIL,
            Certificador::ROLE_MINERVA
        ];
        foreach ($rolesCertificador as $role) {
            if ($user->is($role)) {
                return true;
            }
        }
        return false;
    }
}
