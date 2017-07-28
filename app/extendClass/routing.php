<?php
namespace app\extendClass;

use core\loginControler\login ;

class routing
{
    protected static $instance;

    private function __construct()
    {
        # code...
    }

    public function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function initSession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }


        if (!isset($_COOKIE['curPath'])) {
            echo $curPath = 'http://'.preg_replace("/\/\w+\.php/", '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

            setcookie('curPath', $curPath, time() + 60 * 60 * 24 * 7, '/');
        }

        return self::$instance;
    }

    public function validateUser()
    {
        if (!isset($_COOKIE['user'])) {
            login::validUser();
        } else {
            $path = __DIR__ .  '/../../web/template/';

            self::$instance->massRequire([
                'header',
                'navpanel',
                "<section class='inputHere' id = 'contentStore'></section>",
                'connectjs',
                'footer'], $path, true);

            echo "</body>";
        }

        return self::$instance;
    }

    public function setSessionVal()
    {
    }

    private function massRequire(array $val, $path, $print = false)
    {
        foreach ($val as $key => $value) {
            $file = $path.$value. ".php";
            if (file_exists($file)) {
                require_once $file;
            } elseif ($print) {
                echo $value;
            }
        }
    }
}
