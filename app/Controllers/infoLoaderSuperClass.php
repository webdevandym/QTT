<?php

namespace app\Controllers;

use app\Controllers\visitLoader;
use core\cached;
use connector\fastConnect;

define('__ROOT__', __DIR__.'/../../');

interface request
{
    public function clr($val);

    public function initConn($chached, $time);
}

abstract class infoLoader implements request
{
    public static $db;
    public static $cache;


    public function clr($val)
    {
        if (!$this::$db) {
            $this->initConn();
        }

        return $this::$db->sanitizeString($val);
    }

    public function initConn($chached = false, $time = 10)
    {
        if ($chached) {
            $this::$cache = new cached($time, $chached);
            $this::$cache->getConect($conn);
            $this::$db = $conn;
        } else {
            require_once __ROOT__.'./connector/connector.php';
            fastConnect::inst()->conn($conn);
            $this::$db = $conn;
        }
    }

    abstract public function runVisiter();

    protected function existsTable($table, $type)
    {
        if (!isset($_SESSION[$table])) {
            session_start();
        }

        if (!$this::$db) {
            $this->initConn();
        }

        if ((string) $_SESSION[$table] !== 'cheked') {
            $res = $this::$db->existsTable($table, $type);
        } else {
            return true;
        }

        if ($res) {
            $_SESSION[$table] = 'cheked';
        }

        return $res;
    }

    protected function createSQLProc($table, $body)
    {
        if (!$this->existsTable($table, 'P')) {
            $this->returnQuery($body, false);
        }

        return true;
    }

    protected function returnQuery($query, $close = true)
    {
        if (!$this::$db) {
            $this->initConn();
        }

        $res = $this::$db->queryMysql($query);

        if ($close) {
            $this::$db->close();
        }

        return $res;
    }

    protected static function printResult($result)
    {
        foreach ($result as $val) {
            echo $val;
        }
    }

    protected function cacheData($timeCh, $sql, $addKey = '')
    {
        $key = debug_backtrace()[1]['function'].$addKey;

        $timeCache = $timeCh;
        $this->initConn(true, $timeCache);

        return $this::$cache->getCacheSQL($key, $sql);
    }

    protected function chkProp($val, $prop)
    {
        if (is_object($val) && property_exists($val, $prop)) {
            return $this->clr($val->$prop);
        }

        return '';
    }
}

class infoLoaderSuperClass extends infoLoader
{
    protected $child;
    private static $method;
    private static $transfVal;

    public function __construct($meth, $tval)
    {
        $m = json_decode($meth);
        self::$method = is_object($m) ? $m : $meth;
        self::$transfVal = json_decode($tval);
    }

    public function clsParent($obj)
    {
        $this->child = $obj;
    }

    public function runVisiter()
    {
        if (self::$method instanceof \stdClass) {
            foreach (self::$method as $key => $value) {
                $keyclr = $this->clr((string) $key);

                if (!method_exists($this, $keyclr)) {
                    continue;
                }

                unset($load);

                $load = new visitLoader($this, $keyclr, $this->chkProp(self::$method->$keyclr, 'obj'));

                self::$method->$keyclr->val = $load->runQuery();
            }
            echo json_encode(self::$method);

            return;
        }

        $clrMethod = $this->clr(self::$method);
        if (method_exists($this, $clrMethod)) {
            $obj = $this;
        } else {
            return;
        }

        $load = new visitLoader($obj, $clrMethod, self::$transfVal);
        $res = $load->runQuery();

        if (is_array($res)) {
            foreach ($res as $key => $value) {
                echo $value;
            }
        }
    }
}
