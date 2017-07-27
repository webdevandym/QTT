<?php
namespace core;

use connector\fastConnect;

abstract class cachedInit
{
    public $time;
    public $memcache;
    protected static $db;
    protected static $validConn = false;
    private $localhost = 'localhost';
    private $port = 11211;

    public function __construct($time)
    {
        $this->time = $time;

        $this->memcache = new \Memcache();

        if ($this->validCacheConnect()) {
            $this->memcache->connect($this->localhost, $this->port);
        } else {
            $this->memcache = '';
        }
    }

    public function closeCache()
    {
        $this->memcache->close();
        $this::$db->close();
    }

    public function getConect(&$conn)
    {
        fastConnect::inst()->conn($conn);
        $this::$db = $conn;
    }

    protected function setToCache($key, $res)
    {
        if ($this::$validConn) {
            $this->memcache->set($key, serialize($res), 0, $this->time);
            $this->closeCache();
        } else {
            $this::$db = $this->getConect($conn);
        }

        return $res;
    }

    protected function getCacheFromStore($key)
    {
        if ($this::$validConn) {
            try {
                $var_key = $this->memcache->get($key);
            } catch (Exception $e) {
                echo '<div class = "SQLerror"><span>'.$e->getMessage().'</span></div>';
            }

            if ($var_key) {
                return  unserialize($var_key);
            }
        }

        return false;
    }

    private function validCacheConnect()
    {
        if ($this->memcache instanceof \Memcache && !$this::$validConn) {
            $this->memcache->addServer($this->localhost, $this->port);
            $statuses = $this->memcache->getStats();

            if (isset($statuses[$this->localhost.':'.$this->port])) {
                $this::$validConn = true;
            }
        }
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

        return $this->setToCache($key, $r);
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

        return $this->setToCache($key, $res);
    }
}
