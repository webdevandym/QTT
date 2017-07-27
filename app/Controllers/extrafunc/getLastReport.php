<?php

require_once __DIR__.'/../../../connector/connector.php';

if (!(isset($conn))) {
    fastConnect($conn);
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
