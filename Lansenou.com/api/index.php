<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Unity\EndSession;
use Unity\Login;
use Unity\Status;
use Unity\Update;
use Unity\Verify;

$loader = require __DIR__ . '/silex/vendor/autoload.php';
$loader->add('Unity', __DIR__);

$app = new App\Application('prod');

$app->get('/', function () use ($app) {
    return $app->redirect("docs");
});

$app->get('/status', function() use ($app) {
    return $app->json(Status::GetStatus($app), JsonResponse::HTTP_OK)->setEncodingOptions(JSON_PRETTY_PRINT);
});

$app->get('/user/{id}', function ($id) use ($app) {
    return $app->json($app['db']->fetchAssoc('SELECT * FROM users WHERE id = ? LIMIT 1', array($id)), JsonResponse::HTTP_OK)->setEncodingOptions(JSON_PRETTY_PRINT);
})->secure("ROLE_ADMIN");

$app->get('/user/{range1}/{range2}', function ($range1, $range2) use ($app) {
    return $app->json($app['db']->fetchAll('SELECT * FROM users WHERE id BETWEEN ? AND ?', array($range1, $range2)), JsonResponse::HTTP_OK)->setEncodingOptions(JSON_PRETTY_PRINT);
})->secure("ROLE_ADMIN");

$app->post('/verify', function(Request $request) use($app) {
    return $app->json(Verify::VerifyUser($app, $request->get('userID'), $request->get('token')), JsonResponse::HTTP_OK)->setEncodingOptions(JSON_PRETTY_PRINT);
})->secure('ROLE_ADMIN');

$app->post('/login', function(Request $request) use($app) {
    return $app->json(Login::LogUserIn($app, $request->get('username'), $request->get('password')),
        JsonResponse::HTTP_OK)->setEncodingOptions(JSON_PRETTY_PRINT);
});

$app->post('/endsession', function(Request $request) use($app) {
    return $app->json(EndSession::LogUserOut($app, $request->get('userID'), $request->get('token')), JsonResponse::HTTP_OK)->setEncodingOptions(JSON_PRETTY_PRINT);
});

$app->post('/update', function(Request $request) use($app) {
    return $app->json(Update::UpdateUser($app, $request->get('userID'), $request->get('token')), JsonResponse::HTTP_OK)->setEncodingOptions(JSON_PRETTY_PRINT);
});

$app['debug'] = false;
$app['http_cache']->run();