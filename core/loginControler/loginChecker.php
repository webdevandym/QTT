<?php
namespace core\loginControler;

require_once "../../app/extendClass/Autoloader.php";
use connector\fastConnect;

fastConnect::inst()->conn($connection);

if (($q = $connection->sanitizeString($_GET['q'])) === '') {
    return;
}

$sendResult = $connection->sanitizeString($_GET['sendResult']);
$query = "SELECT name FROM proj_users WHERE name = '$q' and disabled = '0'";

$result = $connection->queryMysql($query, true);
$rowCount = $connection->rowCounter($result);

if ($sendResult !== 'true') {
    if ($rowCount) {
        echo '<i class="fa fa-check" aria-hidden="true"></i>';
    } else {
        echo '<i class="fa fa-times" aria-hidden="true" style = "color:#e51212;text-shadow: 0px 2px 8px  rgba(201, 12, 34, 0.8);"></i>';
    }
} else {
    if ($rowCount) {
        echo 'true';
    }
}
