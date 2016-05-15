<?php
return array(
    '_root_' => 'root/index',
    '_404_' => 'root/error/404',
    'login' => 'root/login',
    'login/(:any)' => 'root/login/$1',
    'callback' => 'root/callback',
    'logout' => 'root/logout',
    //'(:any)' => 'root/$1',
    //'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
