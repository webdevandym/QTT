<?php

namespace web;

require_once "../app/extendClass/Autoloader.php";

use core\messStor;
use connector\fastConnect;

define('__ROOT__', __DIR__.'/../');
// require_once __ROOT__.'core/messStor.php';

class RenderPage
{
    private static $conn;
    private static $page;
    private static $query;
    private $path = __ROOT__.'./web/forms/site/';
    private $jsonPath = __ROOT__.'./web/forms/site/jsonData/';

    public function __construct(&$query)
    {
        $this::$query = $query;
        unset($_GET);
    }

    public function getPage()
    {
        try {
            if (!(isset($this::$conn))) {
                fastConnect::inst()->conn(self::$conn);
            }

            $this::$page = $this::$conn->sanitizeString($this::$query);
            $this::$page = ($this::$page !== 'undefined' && !empty($this::$page)) ? $this::$page : 'timeSheet';
            $this::$conn->close();
        } catch (Exception $e) {
            throw new Exception('Error Processing Request to DB '.$e);
        }

        $jsFile = $this->jsonPath."{$this::$page}.json";
        $phpFile = $this->path."{$this::$page}.php";

        if (file_exists($jsFile)) {
            $con = json_decode(file_get_contents($jsFile));
            echo $con;
        } else {
            $this->createJSONContent($phpFile, $jsFile);
        }
    }

    private function createJSONContent($phpFile, $jsFile)
    {
        $con = file_get_contents($phpFile);

        if (stripos($con, '?php') !== false) {
            ob_start();
            require_once $phpFile;
            file_put_contents($jsFile, preg_replace('/  /', '', json_encode(ob_get_contents())));
        } else {
            $con = file_get_contents($phpFile);
            file_put_contents($jsFile, preg_replace('/  /', '', json_encode($con)));
            echo $con;
        }
    }
}

$page = new RenderPage($_GET['q']);
$page->getPage();
