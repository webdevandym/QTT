<?php

namespace app\extendClass;

use core\loginControler\login;

class routing
{
    protected static $instance;
    protected static $templatePath = __DIR__.'/../../web/template/';

    private function __construct()
    {
        // code...
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
            $curPath = 'http://'.preg_replace("/\/\w+\.php/", '', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

            setcookie('curPath', $curPath, time() + 60 * 60 * 24 * 7, '/');
        }

        return self::$instance;
    }

    public function validateUser()
    {
        if (!isset($_COOKIE['user'])) {
            login::validUser();
        } else {
            $pageConstruct = [
                'header',
                'navpanel',
                "<section class='inputHere' id = 'contentStore'></section>",
                'connectjs',
                'footer', ];

            self::$instance::renderPage($pageConstruct);
        }

        return self::$instance;
    }

    public function setSessionVal()
    {
    }

    private static function massRequire(array $val, $path, $print = false)
    {
        foreach ($val as $key => $value) {
            $file = $path.$value.'.php';
            if (file_exists($file)) {
                require_once $file;
            } elseif ($print) {
                echo $value;
            }
        }
    }

    private static function renderPage(array $pagelist)
    {
        self::$instance::massRequire($pagelist, self::$templatePath, true);

        echo '</body></html>';
    }
}
