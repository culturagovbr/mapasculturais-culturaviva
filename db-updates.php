<?php

$app = MapasCulturais\App::i();
$em = $app->em;
$conn = $em->getConnection();

return [
    'create project rede cultura viva' => function() use ($app) {
        $project = new MapasCulturais\Entities\Project;
        $owner = $app->repo('Agent')->find(1); // usuario admin
        $project->owner = $owner;
        $project->name = 'Rede Cultura Viva';
        $project->useRegistrations = true;
        $project->categories = ['Ponto de Cultura', 'Pontão de Cultura'];
        $project->registrationFrom = new \DateTime('2015-01-01');
        $project->registrationTo = new \DateTime('2099-12-31');
        $project->type = 9;
        $project->save(true);
    },

    'recreate agent metadata rcv_tipo' => function() use($conn) {
        $conn->executeQuery("DELETE FROM agent_meta WHERE key = 'rcv_tipo'");
        $rs = $conn->fetchAll("SELECT * FROM user_meta WHERE key = 'redeCulturaViva'");

        foreach ($rs as $r) {
            $ids = json_decode($r['value']);

            $conn->executeQuery("INSERT INTO agent_meta (object_id, key, value) VALUES ('{$ids->agenteIndividual}', 'rcv_tipo', 'responsavel')");
            $conn->executeQuery("INSERT INTO agent_meta (object_id, key, value) VALUES ('{$ids->agenteEntidade}', 'rcv_tipo', 'entidade')");
            $conn->executeQuery("INSERT INTO agent_meta (object_id, key, value) VALUES ('{$ids->agentePonto}', 'rcv_tipo', 'ponto')");
        }
    },


    'migra avatar dos pontos para o agente responsável' => function() use($app, $conn) {
        $umeta = $conn->fetchAll("SELECT value FROM user_meta WHERE key = 'redeCulturaViva';");

        $move_file = function($file, $to_agent_id) use ($conn) {
            $id = $file['id'];
            $grp = $file['grp'];
            $name = $file['name'];
            $owner_id = $file['object_id'];
            $parent_id = $file['parent_id'];


            if ($parent_id) {
                $original_file = "files/agent/{$owner_id}/file/{$parent_id}/{$name}";
            } else {
                $original_file = "files/agent/{$owner_id}/{$name}";
            }

            if (file_exists(BASE_PATH . $original_file)) {
                $new_name = $name;

                // encontra um nome de arquivo válido
                $fcount = 2;
                if ($parent_id) {
                    $destination_file = "files/agent/{$to_agent_id}/file/{$parent_id}/{$new_name}";
                } else {
                    $destination_file = "files/agent/{$to_agent_id}/{$new_name}";
                }

                while (file_exists(BASE_PATH . $destination_file)) {
                    $new_name = preg_replace("#(\.[[:alnum:]]+)$#i", '-' . $fcount . '$1', $name);
                    if ($parent_id) {
                        $destination_file = "files/agent/{$to_agent_id}/file/{$parent_id}/{$new_name}";
                    } else {
                        $destination_file = "files/agent/{$to_agent_id}/{$new_name}";
                    }
                    $fcount++;
                }

                echo "
====================

Movendo arquivo '$grp' do agente {$owner_id} para o agente {$to_agent_id}:
      DE: $original_file
    PARA: $destination_file\n";

                $conn->executeQuery("
                    UPDATE file SET name = '{$new_name}', object_id = {$to_agent_id} WHERE id = {$id}
                ");

                $original_file = BASE_PATH . $original_file;
                $destination_file = BASE_PATH . $destination_file;

                // cria a pasta do destino se ela não existir
                if (!is_dir(dirname($destination_file))) {
                    mkdir(dirname($destination_file), 0755, true);
                }

                rename($original_file, $destination_file);
            }
        };

        foreach ($umeta as $meta) {
            $obj = json_decode($meta['value']);

            $avatar = $conn->fetchAssoc("
                SELECT
                    *
                FROM
                    file
                WHERE
                    object_type = 'MapasCulturais\Entities\Agent' AND
                    object_id = {$obj->agentePonto} AND
                    grp = 'avatar'
            ");
            if ($avatar) {
                $fid = $avatar['id'];
                $thumbs = $conn->fetchAll("
                    SELECT
                        *
                    FROM
                        file
                    WHERE
                        object_type = 'MapasCulturais\Entities\Agent' AND
                        object_id = {$obj->agentePonto} AND
                        parent_id = {$fid}
                ");

                $move_file($avatar, $obj->agenteIndividual);

                foreach ($thumbs as $thumb) {
                    $move_file($thumb, $obj->agenteIndividual);
                }
            }
        }
        echo "\nrenomeando grupo logoponto para avatar\n";
        $conn->executeQuery("UPDATE file SET grp = 'avatar' WHERE grp = 'logoponto'");
    },
    'rcv: add default seal' => function() use($app, $conn) {
        $agent_id = $app->config['rcv.admin'];
        echo "criando selo \"Ponto de Cultura\" to user $agent_id'";
        $conn->executeQuery("
            INSERT INTO seal (id, agent_id, name, short_description, valid_period, create_timestamp, status, update_timestamp )
            VALUES (nextval('seal_id_seq'), $agent_id, 'Ponto de Cultura', 'Ponto de Cultura', 0, CURRENT_TIMESTAMP, 1, CURRENT_TIMESTAMP);");
    },
    'rcv: add default seal culturaviva to verified agents' => function() use($app, $conn) {
        echo 'Adicionando o selo "Ponto de Cultura" para as entidades verificadas';
        $agent_id = $app->config['rcv.admin'];
        $seal_id = $conn->fetchColumn("SELECT id FROM seal WHERE agent_id = $agent_id and name = 'Ponto de Cultura'");
        // Remove auto created seals in other updates ("Selo Mapas") to agents with rcv_tipo = 'ponto'
        $conn->executeQuery("
            DELETE FROM seal_relation
            WHERE
                object_type = 'MapasCulturais\Entities\Agent'
                AND object_id IN (
                    SELECT s.id
                    FROM agent s
                        JOIN agent_meta sm
                            ON sm.object_id = s.id
                            AND sm.key = 'rcv_tipo'
                            AND sm.value = 'ponto'
                    WHERE s.is_verified = 't'
                )"
        );

        // Insert new default seals to agent with rcv_tipo = 'ponto'
        $conn->executeQuery("
            INSERT INTO seal_relation
            SELECT
                nextval('seal_relation_id_seq'),
                $seal_id,
                s.id,
                CURRENT_TIMESTAMP,
                1,
                'MapasCulturais\Entities\Agent',
                $agent_id
            FROM agent s
                JOIN agent_meta sm
                    ON sm.object_id = s.id
                    AND sm.key = 'rcv_tipo'
                    AND sm.value = 'ponto'
            WHERE s.is_verified = 't';"
        );
    },
    'rcv: create schema' => function() use($app, $conn) {
        echo "Criando tabelas da Rede Cultura Viva";
        $conn->executeUpdate(file_get_contents(__DIR__ . '/scripts/db/culturaviva-schema.sql'));
    },
    'rcv: create schema log' => function() use($app, $conn) {
        echo "Criando tabelas de Log";
        $conn->executeUpdate(file_get_contents(__DIR__ . '/scripts/db/culturaviva_log-schema.sql'));
    },
    'rcv: atualizacao schema' => function() use($app, $conn) {
        echo "Atualizando as tabelas de certificação da Rede Cultura Viva";
        $conn->executeUpdate(file_get_contents(__DIR__ . '/scripts/db/culturaviva-update-schema-1.sql'));
    },

    'rcv: remove unneeded evaluations' => function() use($app, $conn) {
        // Apagar registros na tabela inscricao nos seguintes casos:
        // Inscrição com agente que não possui registration
        // Inscrição com estado 'P' ou 'R' e registration com status 0 ou 10
        $insc_id = $conn->query("
            SELECT
                i.id
            FROM
                culturaviva.inscricao i
            LEFT JOIN
                (
                    SELECT DISTINCT ON(agent_id) *
                    FROM registration
                    WHERE subsite_id is null
                    ORDER BY agent_id, status desc
                ) r ON i.agente_id = r.agent_id AND r.opportunity_id = 1
            WHERE
                i.estado IN ('P','R')
                AND r.status in (0,10)
                OR r.id is null
            ORDER BY
                i.agente_id"
        )->fetchAll(\PDO::FETCH_COLUMN);

        $insc_id = implode(',', $insc_id);

        // Mudar o status na tabela registration para -10
        // Inscrição feitas pelo subsite do Mapas Culturais (não possuem agent_relation)
        $subsite_id = $conn->query("
            SELECT
                r.id
            FROM
                culturaviva.inscricao i
            LEFT JOIN
                registration r ON i.agente_id = r.agent_id AND r.opportunity_id = 1
            WHERE
                i.estado IN ('P','R')
                AND r.subsite_id=4"
        )->fetchAll(\PDO::FETCH_COLUMN);

        $subsite_id = implode(",", $subsite_id);

        $conn->beginTransaction();

        try {
            $conn->executeQuery("
                DELETE FROM
                    culturaviva.avaliacao_criterio
                WHERE inscricao_id IN ({$insc_id})"
            );

            $conn->executeQuery("
                DELETE FROM
                    culturaviva.inscricao_criterio
                WHERE inscricao_id IN ({$insc_id})"
            );

            $conn->executeQuery("
                DELETE FROM
                    culturaviva.avaliacao
                WHERE inscricao_id IN ({$insc_id})"
            );

            $conn->executeQuery("
                DELETE FROM
                    culturaviva.inscricao
                WHERE id IN ({$insc_id})"
            );

            $conn->executeQuery("
                UPDATE
                    registration
                SET
                    status=-10
                WHERE
                    id IN ({$subsite_id})"
                );

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }
];

