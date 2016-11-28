<?php

use App\MyRoute;

$app['locale'] = 'en';

// Database Info
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'lansenou_com_unity',
        'user'      => 'lansenou_com_unity',
        'password'  => 'ICantThinkOffAPasswordJustLetMeIn',
        'port'      => '3306',
        'charset'   => 'utf8'
    ),
));

// Custom Route class
$app['route_class'] = new MyRoute();

// Security Hierarchy
$app['security.role_hierarchy'] = array(
    'ROLE_SUPER_ADMIN'=> array('ROLE_ADMIN', 'ROLE_MODERATOR'),
    'ROLE_ADMIN' => array('ROLE_MODERATOR'),
    'ROLE_MODERATOR' => array('ROLE_GAME_MODERATOR', 'ROLE_FORUM_MODERATOR', 'ROLE_BANNED'),
    'ROLE_GAME_MODERATOR' => array('ROLE_STAFF'),
    'ROLE_FORUM_MODERATOR' => array('ROLE_STAFF'),
    'ROLE_STAFF' => array('ROLE_USER'),
    'ROLE_USER' => array('ROLE_USER'),
    'ROLE_BANNED' => array('ROLE_BANNED'),
);