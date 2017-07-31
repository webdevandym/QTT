<?php

namespace connector;

use core\crypt;

class fastConnect
{
    protected static $inst;

    private function __construct()
    {
    }

    public static function inst()
    {
        if (!self::$inst) {
            self::$inst = new self();
        }

        return self::$inst;
    }

    public function conn(&$connVar)
    {
        if (empty($connVar)) {
            $file = __DIR__.'/config.xml';
            $xml = simplexml_load_file($file);
            $pass = (string) $xml->dbpass->item;

            require_once __DIR__.'./../core/openssl_encrypt_decrypt.php';
            $crypt = new crypt($xml->cryptKey, $xml->secret_iv);

            if (!$crypt->validMd5($pass)) {
                $cpass = $crypt->encrypt_decrypt('encrypt', $pass);
                $xml->dbpass->item = $cpass;
                file_put_contents($file, $xml->asXML());
                unset($cpass);
            } else {
                $pass = $crypt->encrypt_decrypt('decrypt', $pass);
            }

            $connVar = new connector($xml->dbhost->item, $xml->dbname->item, $xml->dbuser->item, $pass);
            $connVar->getConnection();

            unset($pass);
        }
    }
}
//facade
