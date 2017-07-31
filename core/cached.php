<?php

namespace core;

use connector\fastConnect;
use core\logSystem\logHTMLAdv;
use Memcache as Memcache;

abstract class cachedInit
{
    public $time;
    public static $memcache = null;
    protected static $db;
    protected static $validConn = false;
    protected static $hashKey;

    private $localhost = 'localhost';
    private $port = 11211;

    public function __construct($time)
    {
        $this->time = $time;

        if (!$this::$memcache instanceof Memcache) {
            $this::$memcache = new Memcache();
        }

        if ($this->validCacheConnect()) {
            $this::$memcache->connect($this->localhost, $this->port);
        } else {
            $this::$memcache = '';
        }
    }

    public function closeCache()
    {
        if ($this::$memcache instanceof Memcache) {
            $this::$memcache->close();
        }

        if ($this::$db instanceof connector) {
            $this::$db->close();
        }
    }

    public function getConect(&$conn)
    {
        fastConnect::inst()->conn($conn);
        $this::$db = $conn;
    }

    protected function setToCache($res)
    {
        if ($this::$validConn) {
            $this::$memcache->set($this::$hashKey, serialize($res), 0, $this->time);
        }

        $this->closeCache();

        return $res;
    }

    protected function getCacheFromStore($key)
    {
        if ($this::$validConn) {
            try {
                $this::$hashKey = hashGen::md5($key);
                $var_key = $this::$memcache->get($this::$hashKey);
            } catch (Exception $e) {
                echo '<div class = "SQLerror"><span>'.$e->getMessage().'</span></div>';
            }

            $this->log($this::$hashKey." ===> [{$key}]", $var_key);

            if (!empty($var_key)) {
                return  unserialize($var_key);
            }
        }

        return false;
    }

    protected function validCacheConnect()
    {
        if ($this::$validConn) {
            return true;
        }
        if ($this::$memcache instanceof Memcache) {
            $this::$memcache->addServer($this->localhost, $this->port);
            $statuses = $this::$memcache->getStats();

            if (is_array($statuses)) {
                $this::$validConn = true;

                return true;
            }
        }

        return false;
    }

    private function log($key, $res)
    {
        $context = $key.(is_object($this::$memcache) && !empty($res) ? ' - success' : ' - run SQL query!').'<br>'.$res;
        $log = new logHTMLAdv(__DIR__.'/../log/log.html', $context, false, __DIR__.'/../log/template.txt', 'Europe/Kiev');
        $log->writeLog();
    }
}

class cached extends cachedInit
{
    public function getCacheSQL($key, $query)
    {
        $key = 'query'.$key;
        $c = $this->getCacheFromStore($key);
        if ($c) {
            return $c;
        }

        $result = $this::$db->queryMysql($query);
        $r = $result->fetchAll(\PDO::FETCH_ASSOC);

        return $this->setToCache($r);
    }

    public function getJSONFileToCache($path, $lang)
    {
        preg_match('/[\w\d]+$/i', $path, $match);
        $key = 'json'.$match[0].$lang;

        $c = $this->getCacheFromStore($key);
        if ($c) {
            return $c;
        }

        $res = json_decode(file_get_contents($path.$lang.'.json'));

        return $this->setToCache($res);
    }
}
