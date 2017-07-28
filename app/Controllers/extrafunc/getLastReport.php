<?php

namespace app\Controllers\extrafunc;

require_once "../../extendClass/Autoloader.php";

use connector\fastConnect;

if (!(isset($conn))) {
    fastConnect::inst()->conn($conn);
}

$userName = $conn->sanitizeString($_GET['name']);

$query = "SELECT max(distinct job_date) as maxdate
			FROM proj_jobs
			WHERE user_id IN (
						SELECT id FROM proj_users WHERE name = '$userName')";

$result = $conn->queryMysql($query);

foreach ($result as $value) {
    echo $value['maxdate'];
}

$conn->close();
