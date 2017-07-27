<?php

namespace app;

require_once "./app/extendClass/Autoloader.php";

use app\extendClass\routing;

routing::instance()
  ->initSession()
  ->validateUser();
