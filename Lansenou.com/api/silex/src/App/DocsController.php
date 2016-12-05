<?php
/**
 * Created by PhpStorm.
 * User: Lansenou
 * Date: 8/4/2016
 * Time: 22:03
 */

namespace App;

use App\Bundle\User;
use App\Bundle\UserType;
use Silex\Application as App;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Validator\Constraints as Assert;
use Unity\Register;

class DocsController implements ControllerProviderInterface
{
    public function connect(App $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers
            ->get('/', [$this, 'homepage'])
            ->bind('homepage');

        $controllers
            ->get('/login', [$this, 'login'])
            ->bind('login');

        $controllers
            ->match('/register', [$this, 'register'])
            ->bind('register');

        $controllers
            ->get('/users', [$this, 'users'])
            ->bind('users');

        return $controllers;
    }

    public function homepage(App $app)
    {
        //$app['session']->getFlashBag()->add('warning', 'Warning flash message');
        //$app['session']->getFlashBag()->add('info', 'Info flash message');
        //$app['session']->getFlashBag()->add('success', 'Success flash message');
        //$app['session']->getFlashBag()->add('danger', 'Danger flash message');
        return $app['twig']->render('index.html.twig');
    }

    public function login(App $app)
    {
        if ($app['security.authorization_checker']->isGranted('ROLE_USER'))
            return $app->redirect($app['url_generator']->generate('homepage'));

        return $app['twig']->render('login.html.twig', array(
            'error' => $app['security.utils']->getLastAuthenticationError(),
            'username' => $app['security.utils']->getLastUsername(),
        ));
    }

    public function register(App $app, Request $request)
    {
        $user = new User($app);
        $form = $app['form.factory']->createBuilder(UserType::class, $user)->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            echo 'Form submitted';
            if(preg_match('/[^a-z_\-0-9]/i', $user->getUsername()) || strlen($user->getUsername()) >= 20)
            {
                $app['session']->getFlashBag()->add('danger', 'Invalid Username or Username longer than 20 chars');
            }
            else
            {
                $result = Register::RegisterUser($app, $user->getUsername(), $user->getEmail(), $user->getPlainPassword());
                if ($result->success) {
                    $app['session']->getFlashBag()->add('success', 'User "'.$user->getUsername().'" was registered!');
                    return $app->redirect($app['url_generator']->generate('homepage'));
                }
                else
                {
                    $app['session']->getFlashBag()->add('danger', $result->registerMessage);
                }
            }
        }

        return $app['twig']->render('register.html.twig', array(
            'form' => $form->createView(),
            'error' => $app['security.utils']->getLastAuthenticationError(),
            'username' => $app['security.utils']->getLastUsername())
        );
    }

    public function users(App $app)
    {
        if ($app['security.authorization_checker']->isGranted('ROLE_STAFF')) {
            $hierarchy = new RoleHierarchy($app['security.role_hierarchy']);
            $currentRole = $app['security']->getToken()->getUser()->getRoles();

            $allRoles = array();
            foreach ($currentRole as $role) {
                $allRoles[] = $hierarchy->getReachableRoles(array(new Role($role)));
            }

            return $app['twig']->render('users.html.twig', array(
                'users' => $app['db']->fetchAll('SELECT username, roles FROM users'),
                'currentRole' => $currentRole[0],
                'allRoles' => $allRoles,
            ));
        }

        return new Response($app['twig']->render('error.html.twig', array(
            'error' => 'Error Code: 403',
            'message' => Response::$statusTexts[403],
        )), 403);
    }
}