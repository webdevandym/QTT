<?php
namespace core;

use core\cached;

// require_once __DIR__.'/cached.php';

class messStore
{
    private static $lang = 'EN';
    private static $inst;
    private static $linkStore = '';
    private static $level;
    private static $file = __DIR__.'./../web/template/langContent/messageStore';

    private function __construct()
    {
    }

    public static function instance($key = null, $path = '')
    {
        if (!self::$inst) {
            self::$inst = new self();
        }

        self::$file = $path ? $path : self::$file;

        return self::$inst->get($key);
    }

    public static function genLinks($val, $path = '', $level = false)
    {
        if (!$val) {
            return;
        }

        if (!is_array($val)) {
            $valSt[] = $val;
        } else {
            $valSt = $val;
        }

        self::$level = $level;
        self::$linkStore = new \stdClass();

        foreach ($valSt  as $key => $value) {
            $variable = preg_match('/\D/', $key) ? $key : (is_array($value) ? end($value) : $value);

            if ($level) {
                self::$linkStore = self::instance($value ? $value : $key, $path);
            } else {
                self::$linkStore->$variable = self::instance($value ? $value : $key, $path);
            }
        }

        return self::$linkStore;
    }

    private function get($key)
    {
        $cache = new cached(24 * 60 * 60);
        $res = $cache->getJSONFileToCache(self::$file, self::$lang);

        if ($key) {
            if (is_array($key)) {
                foreach ($key as  $value) {
                    $res = $res->$value;
                }
            } else {
                $res = $res->$key;
            }
        }

        return $res;
    }
}
