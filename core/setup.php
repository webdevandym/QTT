<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sutup Data Base</title>
</head>
<body>
    <h3>Setting up...</h3> <!-- setup -->
<?php
require_once __DIR__.'/../connector/connector.php';
//
// $sql = 'CREATE VIEW report_view AS SELECT j.id, j.object_id, n.name AS name, cust.name AS customer, s.name AS userName, a.account_name AS account, j.job_descr AS descr, jt.name AS jobType, CONVERT( DATE, j.job_date ) AS dateJob, CONVERT( INT, j.job_hours ) AS hoursJob
//   FROM proj_names n, proj_users s, proj_accounts a, proj_jobs j, proj_jobtypes jt, proj_customers cust, proj_objects obj
//   WHERE s.id = j.user_id
//   AND a.id = j.account_id
//   AND jt.id = j.jobtype_id
//   AND obj.id = j.object_id
//   AND n.id = obj.proj_id
//   AND cust.id = n.customer_id';

fastConnect($conn);
echo $conn->existsTable('report_view', 'V');
$conn->close();
?>
<br>...done. <!-- end create table -->
</body>
</html>


<!-- $sql = "SELECT j.id, j.object_id, n.name AS name, cust.name AS customer, s.name AS userName, a.account_name AS account, j.job_descr AS descr, jt.name AS jobType, CONVERT( DATE, j.job_date ) AS dateJob, CONVERT( INT, j.job_hours ) AS hoursJob
      FROM proj_names n, proj_users s, proj_accounts a, proj_jobs j, proj_jobtypes jt, proj_customers cust, proj_objects obj
      WHERE s.name =  '$name'
      AND s.id = j.user_id
      AND a.id = j.account_id
      AND jt.id = j.jobtype_id
      AND obj.id = j.object_id
      AND n.id = obj.proj_id
      AND (j.job_date BETWEEN  '$startDate' AND  '$endDate')
      AND cust.id = n.customer_id
      ORDER BY j.job_date, n.name, j.job_hours";

$result = $this->returnQuery($sql); -->
