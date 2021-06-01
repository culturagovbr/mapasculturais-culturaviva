

-- Role gestor para o adm
DELETE from role
WHERE usr_id = (SELECT id FROM usr WHERE usr.email = 'Admin@local')
AND name = 'rcv_agente_area';

INSERT INTO role(usr_id, name)
	SELECT id, 'rcv_agente_area'
	FROM usr
	WHERE usr.email = 'Admin@local';