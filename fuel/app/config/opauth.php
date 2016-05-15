<?php
$gitlab = \Fuel\Core\Config::load("gitlab");
return [
    'link_multiple_providers' => false,
    'auto_registration' => true,
    'security_salt' => 'ldsjfkhkjW43frekhfjgaevT43A',
    'Strategy' => array(
        'GitLab' => [
            'client_id' => $gitlab['id'],
            'client_secret' => $gitlab['secret'],
            'client_uri' => $gitlab['uri']
        ]
    ),
];