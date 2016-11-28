<?php
namespace Unity {

    use Silex\Application;
    use Unity\Login\Result;

    class Login
    {
        /**
         * @param Application $app
         * @param $username
         * @param $password
         * @return Result
         */
        static function LogUserIn(Application $app, $username, $password) {
            $loginResult = new Result();

            if (empty($username) || empty($password)) {   // Check for correct $_POST values
                $loginResult->errorMessage = "Empty Username or Password";
                return $loginResult;
            }

            $result = $app['db']->fetchAssoc("SELECT * FROM users WHERE UPPER(USERNAME) = UPPER(?) LIMIT 1", array($username));
            if (!$result) {
                $loginResult->errorMessage = "Invalid Login!";
                return $loginResult;
            }
            if (!password_verify($password, $result['password'])) {
                $loginResult->errorMessage = "Invalid Login!";
                return $loginResult;
            }
            // Create a new session token and a hash of it
            $token = md5(uniqid($username, true));
            $tokenHash = password_hash($token, PASSWORD_DEFAULT);

            //Banned
            if ($result['token'] === 'BANNED')
            {
                $loginResult->errorMessage = "This account has been banned.";
                return $loginResult;
            }

            // Already logged in
            $time = strtotime($result['timestamp']);
            $curTime = time();
            if (!empty($result['token']) && ($curTime-$time) < 90) { // Time oud  if currentTime under 90 secs / 1.5 mins
                // Put info in the result class
                $loginResult->errorMessage = "Already logged in!";
                return $loginResult;
            }
            // Insert and return new token
            $app['db']->executeUpdate("UPDATE users SET TOKEN = ? WHERE ID = ?", array($tokenHash, $result['id']));
            $loginResult->wasSuccess = true;
            $loginResult->userID = $result['id'];
            $loginResult->userToken = $token;
            return $loginResult;
        }
    }
}

namespace Unity\Login
{
    /**
     * Class Result
     * @package Unity\Login
     */
    class Result
    {
        public $wasSuccess = false;
        public $errorMessage = null;
        public $userID = null;
        public $userToken = null;
    }
}





