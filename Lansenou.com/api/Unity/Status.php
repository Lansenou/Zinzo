<?php
namespace Unity {

    use Silex\Application;
    use Unity\Status\Result;

    class Status
    {
        static function GetStatus(Application $app)
        {
            $result = new Result();
            $error = $app['db']->errorCode();
            if ($error > 0 && $error <= 6)
            {
                $result->connectionResult = "Offline";
                $result->connectionError = "Connection failure";
            }
            else
            {
                $result->connectionResult = "Online";
            }
            return $result;
        }
    }
}

namespace Unity\Status
{
    class Result
    {
        public $connectionResult = "";
        public $connectionError = null;
    }
}
