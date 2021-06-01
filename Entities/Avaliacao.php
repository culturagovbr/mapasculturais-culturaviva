<?php

namespace CulturaViva\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registra as avaliações feitas pelos certificadores sobre as Inscrições
 *
 * @ORM\Entity
 * @ORM\Table(name="culturaviva.avaliacao")
 * @ORM\entity(repositoryClass="MapasCulturais\Repository")
 */
class Avaliacao extends \MapasCulturais\Entity {

    /**
     * Indica que a análise da Inscrição não foi iniciada pelo Certificador
     */
    const ST_PENDENTE = 'P';

    /**
     * Indica que o Certificador já a análise da Inscrição
     */
    const ST_EM_ANALISE = 'A';

    /**
     * Indica que o Certificador finalizou a análise da Inscrição como DEFERIDO
     */
    const ST_DEFERIDO = 'D';

    /**
     * Indica que o Certificador finalizou a análise da Inscrição como INDEFERIDO
     */
    const ST_INDEFERIDO = 'I';

    /**
     * Indica a Avaliação foi cancelada.
     *
     * Se um Certificador for inativado, as Avaliações com estado "Pendente" e "Em Análise" deste certificador serão
     * cancelados e redistribuidos para outro certificador ativo
     */
    const ST_CANCELADO = 'C';

    /**
     * Identificador da avaliação
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="culturaviva.avaliacao_id_seq", allocationSize=1, initialValue=1)
     *
     * @var integer
     */
    protected $id;

    /**
     * Referencia para a Incrição do Pontao/Ponto de Cultura
     *
     * @ORM\Column(name="inscricao_id", type="integer", nullable=false)
     *
     * @var integer
     */
    protected $inscricaoId;

    /**
     * Referência para o Certificador responsável
     *
     * @ORM\Column(name="certificador_id", type="integer", nullable=false)
     *
     * @var integer
     */
    protected $certificadorId;

    /**
     * Estado da Avaliação.
     *
     * P - Pendente
     * A - Em Analise
     * D - Deferido
     * I - Indeferido
     * C - Cancelado - Se um certificador for inativado, as avaliações com estado "Pendente" e "Em Análise" deste certificador
     * serão cancelados e redistribuidos para outro certificador ativo
     *
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=false)
     *
     * @var string
     */
    protected $estado;

    /**
     * Comentários adicionados pelo Certificador
     *
     * @ORM\Column(name="observacoes", type="string", nullable=false)
     *
     * @var string
     */
    protected $observacoes;

    /**
     * Quando a Avaliação foi Finalizada pelo Certificador
     *
     * @ORM\Column(name="ts_finalizacao", type="string")
     *
     * @var \DateTime
     */
    protected $tsFinalizacao;

    /**
     * Quando o registro foi criado
     *
     * @ORM\Column(name="ts_criacao", type="string", nullable=false)
     *
     * @var \DateTime
     */
    protected $tsCriacao;

    /**
     * Quando o registro sofreu atualização
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
     * Somente Certificadores pode alterar avaliações
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
