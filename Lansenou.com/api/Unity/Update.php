<?php
namespace Unity {
    use Silex\Application;

    class Update {
        static function UpdateUser(Application $app, $userID, $token) {
            $result = Verify::VerifyUser($app, $userID, $token);

            if (!$result->wasSuccess) {
                return $result;
            }

            $app['db']->executeUpdate('UPDATE users SET timestamp = NULL where ID = ?', array($userID));
            $result->wasSuccess = true;
            return $result;
        }
    }
}
