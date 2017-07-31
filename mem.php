<?php
namespace root;

use \Memcache as Memcache;

//Создаём новый объект. Также можно писать и в процедурном стиле
   $memcache_obj = new Memcache();

   //Соединяемся с нашим сервером
   $memcache_obj->connect('localhost', 11211) or die('Could not connect');

   //Попытаемся получить объект с ключом our_var
   $var_key = @$memcache_obj->get('our_var');

   if (!empty($var_key)) {
       //Если объект закэширован, выводим его значение
       echo $var_key;
   } else {
       //Если в кэше нет объекта с ключом our_var, создадим его
       //Объект our_var будет храниться 5 секунд и не будет сжат
       $memcache_obj->set('our_var', date('G:i:s'), false, 5);

       //Выведем закэшированные данные
       echo $memcache_obj->get('out_var');
   }

   //Закрываем соединение с сервером Memcached
   $memcache_obj->close();
