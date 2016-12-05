<?php

namespace Unity\EndSession
{
    class Result
    {
        public $wasSuccess = false;
        public $errorMessage = null;
    }
}
namespace Unity {

    use Silex\Application;
    use Unity\EndSession\Result;

    class EndSession {
        static function LogUserOut(Application $app, $token, $userID) {
            $endSessionResult = new Result();
            if (empty($token) || empty($userID)) {   // Check for correct $_POST values
                $endSessionResult->errorMessage = "Empty token or userID";
                return $endSessionResult;
            }

            $result = $app['db']->fetchAssoc("SELECT * FROM users where id = ? LIMIT 1", array($userID));
            if (!$result) {
                $endSessionResult->errorMessage = "Account does not exist!";
                return $endSessionResult;
            }

            if (password_verify($token, $result['token'])) {
                $app['db']->executeUpdate("UPDATE users SET token = null WHERE (id) = ?", array($userID));
                $endSessionResult->wasSuccess = true;
            } else {
                $endSessionResult->errorMessage = "Token check failed!";
            }

            return $endSessionResult;
        }
    }
}