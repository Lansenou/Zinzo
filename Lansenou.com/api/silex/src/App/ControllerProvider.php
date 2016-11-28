<?php

namespace App;

use Silex\Application as App;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class ControllerProvider implements ControllerProviderInterface
{
    private $app;

    public function connect(App $app)
    {
        $this->app = $app;

        $app->error([$this, 'error']);

        $controllers = $app['controllers_factory'];

        $app->mount('/docs', new DocsController($app));

        return $controllers;
    }

    public function error(\Exception $e, $code)
    {
        if ($this->app['debug']) {
            return;
        }

        switch ($code) {
            case 404:
                $message = 'The requested page couldn\'t be located. Checkout for any URL misspelling or <a href='.$this->app->url('homepage').'>return to the homepage</a>.';
                break;
            default:
                $message = Response::$statusTexts[$code];
        }

        return new Response($this->app['twig']->render('error.html.twig', array(
            'error' => 'Error Code: '.$code,
            'message' => $message,
        )), $code);
    }
}
