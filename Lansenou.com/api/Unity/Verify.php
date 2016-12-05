<?php
namespace Unity{

    use Silex\Application;
    use Unity\Verify\Result;

    class Verify {
        static function VerifyUser(Application $app, $userID, $token) {
            $verifyResult = new Result();

            // Check if values are correct
            if (empty($userID) || empty($token)) {
                $verifyResult->errorMessage = "Empty Username or Token";
                return $verifyResult;
            }
            // Get account from database
            $result = $app['db']->fetchAssoc('SELECT * FROM users WHERE ID = ? LIMIT 1', array($userID));
            if (!$result) {
                $verifyResult->errorMessage = "Account does not exist!";
                return $verifyResult;
            }

            // Found Account
            if (password_verify($token, $result['token'])) {
                $verifyResult->wasSuccess = true;
            } else {
                $verifyResult->errorMessage = "Token verification failed.";
            }
            return $verifyResult;
        }
    }
}

namespace Unity\Verify {
    class Result
    {
        public $wasSuccess = false;
        public $errorMessage = null;
    }
}