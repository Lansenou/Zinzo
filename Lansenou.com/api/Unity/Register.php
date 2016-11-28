<?php

namespace Unity {

    use Silex\Application;
    use Unity\Register\Result;

    class Register
    {
        public static function RegisterUser(Application $app, $username, $email, $password)
        {
            $result = new Result();

            if ($app['db']->fetchAssoc('SELECT * FROM users WHERE UPPER(username) = UPPER(?) LIMIT 1', array($username))) {
                $result->registerMessage = "Username already exists!";
                return $result;
            }

            $app['db']->insert('users', array(
                'USERNAME' => $username,
                'PASSWORD' => password_hash($password, PASSWORD_DEFAULT),
                'EMAIL' => $email));

            $result->registerMessage = "Welcome " . $username . "!";
            $result->success = true;
            $result->username = $username;
            $result->userID = $app['db']->fetchAssoc('SELECT * FROM users WHERE UPPER(username) = UPPER(?) LIMIT 1', array($username))['id'];

            return $result;
        }
    }
}

namespace Unity\Register {
    class Result
    {
        public $success = false;
        public $registerMessage = "";
        public $username = "";
        public $userID = "";
    }
}