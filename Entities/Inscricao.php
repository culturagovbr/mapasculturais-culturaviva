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
class Inscricao extends \MapasCulturais\Entity {

    /**
     * Indica que a avaliação da Inscrição ainda está sendo feita pelos Certificadores
     */
    const ST_PENDENTE = 'P';

    /**
     * Indica que a Inscrição foi avaliada e Julgada como APTA pelos Certificadores
     */
    const ST_CERTIFICADO = 'C';

    /**
     * Indica que a Inscrição foi avaliada e Julgada como INAPTA pelos Certificadores
     */
    const ST_NAO_CERTIFICADO = 'N';

    /**
     * Indica que a Inscrição anterior do mesmo Pontão/Ponto de Cultura foi rejeitada pelos certificadores. Após isso,
     * o cadastro foi alterado pelo Pontão/Ponto de Cultura e uma NOVA INSCRIÇÃO criada para reavaliação
     */
    const ST_RESUBMISSAO = 'R';

    /**
     * Identificador da Inscrição
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="culturaviva.inscricao_id_seq", allocationSize=1, initialValue=1)
     *
     * @var integer
     */
    protected $id;

    /**
     * Referencia para o Pontão/Ponto de Cultura solicitante
     *
     * @ORM\ManyToOne(targetEntity="MapasCulturais\Entities\Agent", fetch="EAGER")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="agente_id", referencedColumnName="id")})
     *
     * @var \MapasCulturais\Entities\Agent
     */
    protected $agente;

    /**
     * Estados da inscrição
     *
     * P - Pendente
     * C - Certificado
     * N - Não Certificado
     * R - Re Submissão - Inscrição rejeitada pelos certificadores, cadastro alterado pelo Ponto de Cultura e
     * nova Inscrição criada para reavaliação
     *
     * @ORM\Column(name="estado", type="string", length=1, nullable=false)
     *
     * @var string
     */
    protected $estado;

    /**
     * Quando o registro foi criado
     *
     * @ORM\Column(name="ts_criacao", type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $tsCriacao;

    /**
     * Quando a avaliação da inscrição foi finalizada, alterando o estado da inscrição para "C - Certificado"
     * ou "N - Não Certificado"
     *
     * @ORM\Column(name="ts_finalizacao", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $tsFinalizacao;


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
