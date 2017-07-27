<?php
namespace core;

/**
 * simple method to encrypt or decrypt a plain text string
 * initialization vector(IV) has to be the same when encrypting and decrypting.
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 * @param mixed  $key
 * @param mixed  $iv
 *
 * @return string
 */
 class crypt
 {
     private static $key;
     private static $iv;

     public function __construct($key, $iv)
     {
         $this::$key = hash('sha256', $key);
         $this::$iv = substr(hash('sha256', $iv), 0, 16);
     }

     public function encrypt_decrypt($action, $string)
     {
         $output = false;
         $encrypt_method = 'AES-256-CBC';

         if ($action === 'encrypt') {
             $output = openssl_encrypt($string, $encrypt_method, $this::$key, 0, $this::$iv);
             $output = base64_encode($output);
         } elseif ($action === 'decrypt') {
             $output = openssl_decrypt(base64_decode($string, true), $encrypt_method, $this::$key, 0, $this::$iv);
         }

         return $output;
     }

     public function validMd5($hash)
     {
         if ((string) $this->encrypt_decrypt('encrypt', $this->encrypt_decrypt('decrypt', (string) $hash)) === (string) $hash) {
             return true;
         }

         return false;
     }
 }
