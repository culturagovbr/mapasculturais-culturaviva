<?php

return [
    'app.verifiedSealsIds' => [6],
    'auth_api_cnpj' => 'M2JmNDEwMDkxYmRkZjVlMjA5MmJlODYyYWEyNWZlMzQ6b3U4NTU3Nzg3ZVJOOURy',

    'auth.provider' => 'OpauthLoginCidadao',
    'auth.config' => array(
    'client_id' => '3_rzes4uxsgv4k4o8scscg8w40ks08c08sgg4488o4wskcko4wk',
    'client_secret' => '2k0i4p6zzvac0woww00g0o0ockgc84k04ogwo800sw4w8cwcgs',
    'auth_endpoint' => 'https://dev-id.cultura.gov.br/oauth/v2/auth',
    'token_endpoint' => 'https://dev-id.cultura.gov.br/oauth/v2/token',
    /*
    Nova ?
    'auth_endpoint' => 'https://id.cultura.gov.br/openid/connect/authorize',
    'token_endpoint' => 'https://id.cultura.gov.br/openid/connect/token',
    */
    'user_info_endpoint' => 'https://dev-id.cultura.gov.br/api/v1/person.json',
    'onCreateRedirectUrl' => 'http://dev.culturaviva.gov.br/rede/entrada'
    )
];
