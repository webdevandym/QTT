<?php

namespace app\Controllers;

use connector\fastConnect;
use core\cached;
use core\logSystem\logHTMLAdv;
use core\messStore;
use stdClass as stdClass;

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
    protected static $logDir = __DIR__.'/../../log/';

    public function clr($val)
    {
        if (!isset($this::$db)) {
            $this->initConn();
        }

        return $this::$db->sanitizeString($val);
    }

    public function initConn($chached = false, $time = 10)
    {
        if ($chached && !($this::$cache instanceof cached)) {
            $this::$cache = new cached($time, $chached);
            $this::$cache->getConect($conn);
            $this::$db = $conn;
        } elseif (!($this::$db  instanceof connector)) {
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

    protected function returnQuery($query)
    {
        if (!$this::$db) {
            $this->initConn();
        }

        $res = $this::$db->queryMysql($query);

        return $res;
    }

    protected static function printResult($result)
    {
        foreach ($result as $val) {
            echo $val;
        }
    }

    protected function cacheData($timeCh, $sql, $addKey = null)
    {
        $key = debug_backtrace()[1]['function'].$addKey;

        $timeCache = $timeCh;
        $this->initConn(true, $timeCache);

        return $this::$cache->getCacheSQL($key, $sql);
    }

    protected function chkProp($val, $prop)
    {
        if (!is_array($prop) && !is_object($prop)) {
            $store[] = $prop;
        } else {
            $store = $prop;
        }

        if (is_object($val)) {
            foreach ($store as $value) {
                if (property_exists($val, $value)) {
                    $a[] = $this->clr($val->$value);
                } else {
                    $a[] = '';
                }
            }

            return count($a) > 1 ? $a : $a[0];
        }

        return false;
    }

    protected function log($key, $res)
    {
        if (isset(self::$logDir)) {
            if ($res instanceof stdClass || is_array($res)) {
                $run = function ($res) {
                    $output = '';
                    foreach ($res as $key => $value) {
                        if ($value instanceof stdClass || is_array($value)) {
                            $run($value);
                        }
                        $output .= $key.' => '.$value.'<br>';
                    }

                    return $output;
                };

                $output = $run($res);
            } else {
                $output = $res;
            }

            $context = 'Key: '.$key.'<br>'.$output;
            $log = new logHTMLAdv(self::$logDir.'logRuner.html', $context, false, self::$logDir.'template.txt', 'Europe/Kiev');
            $log->writeLog();
        }
    }
}

class infoLoaderSuperClass extends infoLoader
{
    protected $child;
    protected static $method;
    protected static $queryList = __DIR__.'/../query/queryList';
    private static $transfVal;

    public function __construct()
    {
    }

    public function clsParent($obj)
    {
        $this->child = $obj;
    }

    public function runVisiter()
    {
        if (self::$method instanceof stdClass) {
            foreach (self::$method as $key => $value) {
                $keyclr = $this->clr((string) $key);

                if (!method_exists($this, $keyclr)) {
                    continue;
                }

                unset($load);
                $objectVal = $this->chkProp(self::$method->$keyclr, 'obj');

                $this->log($keyclr, $objectVal);
                $load = new visitLoader($this, $keyclr, $objectVal);

                self::$method->$keyclr->val = $load->runQuery();
            }
            echo json_encode(self::$method);
        } else {
            $clrMethod = $this->clr(self::$method);
            if (method_exists($this, $clrMethod)) {
                $obj = $this;
            } else {
                return $this::$db->close();
            }

            $load = new visitLoader($obj, $clrMethod, self::$transfVal);
            $res = $load->runQuery();

            if (is_array($res)) {
                foreach ($res as $key => $value) {
                    echo $value;
                }
            }
        }

        return $this::$db->close();
    }

    public function auto()
    {
        $this::initData($_GET['method'], isset($_GET['object']) ? $_GET['object'] : '');

        return $this;
    }

    protected static function initData($meth, $tval)
    {
        $m = json_decode($meth);
        self::$method = is_object($m) ? $m : $meth;

        self::$transfVal = json_decode($tval);
    }

    protected function getSQL($key, $func)
    {
        $sql = str_replace("''", "'", $this->chkProp(messStore::instance((string) $func, $this::$queryList, false), $key));

        return $sql;
    }
}
