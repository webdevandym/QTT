<?php

namespace core;

class hashGen
{
    public static function khash($data)
    {
        static $map = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = bcadd(sprintf('%u', crc32($data)), 0x100000000);
        $str = '';
        do {
            $str = $map[bcmod($hash, 62)].$str;
            $hash = bcdiv($hash, 62);
        } while ($hash >= 1);

        return $str;
    }

    public static function md5($data)
    {
        return hash('md5', $data, false);
    }
}
