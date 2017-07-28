<?php
namespace app\Controllers;

require_once "../extendClass/Autoloader.php";

use app\Controllers\infoLoaderSuperClass;
use core\messStore;

class selectBlockConnector extends infoLoaderSuperClass
{
    public function getUserName($switch)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $switcher = !isset($switch)? $switch->edit : '';


        $sql = 'SELECT f_name,name,id FROM proj_users WHERE disabled = 0 order by f_name';
        $result = $this->cacheData(24 * 60 * 60, $sql, $switcher);

        $showName = !empty($switcher) ? 'name' : 'f_name';
        $getTask = !empty($switcher) ? $switch : $_SESSION['user'];

        $a = [];
        $i = 0;

        foreach ($result as $row) {
            if ($getTask !== $row['name']) {
                $a[$i++] = "<option value = '".$row['name']."' idnum = '".$row['id']."'>".$row[$showName].'</option>';
            } else {
                $a[$i++] = "<option selected = 'selected' value = '".$row['name']."' idnum = '".$row['id']."'>".$row[$showName].'</option>';
            }
        }

        // $this::printResult($a);
        return $a;
    }

    public function getObjectType()
    {
        $sql = 'SELECT name,id FROM proj_objtypes order by id';

        $result = $this->cacheData(24 * 60 * 60, $sql);
        // $result = $this->returnQuery('SELECT name,id FROM proj_objtypes order by id');
        $i = 0;
        foreach ($result as $row) {
            if ((int) $row['id'] !== 5) {
                $a[$i++] = "<option value = '".$row['id']."'>".$row['name'].'</option>';
            }
        }

        asort($a);
        // $this::printResult($a);
        return $a;
    }

    public function getProject($query)
    {
        $q = $this->chkProp($query, 'query');
        $switch = $this->chkProp($query, 'switch');


        $id = 'name';
        $a = [];
        $first = false;

        $id = ($q === 'Customer') ? 'id' : 'name';

        if ($q === 'Customer') {
            $sql = 'SELECT distinct name, id FROM proj_customers ORDER BY name';
        } else {
            $sql = "SELECT name,id FROM proj_names WHERE customer_id ='$q' ORDER BY name";
        }

        $result = $this->cacheData(60 * 60, $sql, $q.$switch);
        // $result = $this->returnQuery($sql);

        foreach ($result as $row) {
            if ((!$first && !$switch) || ($switch === $row['name'])) {
                $a[] = "<option selected='selected' value = '".$row["$id"]."'".'>'.$row['name'].'</option>';
                $first = true;
                $switch = false;
            } else {
                $a[] = "<option value = '".$row["$id"]."'".'>'.$row['name'].'</option>';
            }
        }

        return $a;
    }

    public function getReportWeek($userInfo)
    {
        $name = $this->chkProp($userInfo, 'name');
        $startDate = $this->chkProp($userInfo, 'startDate');
        $endDate = $this->chkProp($userInfo, 'endDate');

        $retDate = $name.','.$startDate.','.$endDate;

        $a = ['name', 'userName', 'account', 'descr', 'jobType', 'dateJob', 'hoursJob'];
        $table = $result = '';

        // if (!$this->existsTable('V_report', 'V')) {
        //     $sql = 'CREATE VIEW V_report AS SELECT j.id, j.object_id, n.name AS name, cust.name AS customer, s.name AS userName, a.account_name AS account, j.job_descr AS descr, jt.name AS jobType, CONVERT( DATE, j.job_date ) AS dateJob, CONVERT( float, j.job_hours ) AS hoursJob
        //     FROM proj_names n, proj_users s, proj_accounts a, proj_jobs j, proj_jobtypes jt, proj_customers cust, proj_objects obj
        //     WHERE s.id = j.user_id
        //     AND a.id = j.account_id
        //     AND jt.id = j.jobtype_id
        //     AND obj.id = j.object_id
        //     AND n.id = obj.proj_id
        //     AND cust.id = n.customer_id';
        //
        //     $this->returnQuery($sql, false);
        // }

        // $this->createSQLProc('pr_Report', 'CREATE PROC pr_Report @name nvarchar(30), @startDate DATE, @endDate DATE AS select *
        //     FROM V_report
        //     WHERE userName =  @name
        //     AND (dateJob BETWEEN  @startDate AND  @endDate)
        //     ORDER BY dateJob, userName, hoursJob');

        $sql = "EXEC pr_Report @name = '$name',@startDate = '$startDate', @endDate = '$endDate' ";

        $result = $this->returnQuery($sql);

        echo "<div class='container'>";

        foreach ($result as $row) {
            $table .= <<<_END
	<tr id = "it{$row['id']}_{$row['object_id']}">
  <td colspan = '2' class = 'chekerDelete' delrec = ''><i class="fa fa-chevron-down " aria-hidden="true"></i></td>
	<td class = 'editRow' data-toggle="modal" data-target="#editModal" onclick = "insertData(this)"><i class="fa fa-pencil" aria-hidden="true"></i></td>
	<td class = 'deleteRow' data-toggle="modal" data-target="#deleteModal" onclick = "getTDID(this);"><i class="fa fa-times" aria-hidden="true"></i></td>
_END;

            foreach ($a as $item) {
                if ($item === 'hoursJob') {
                    $row[$item] = (float) $row[$item];
                }
                if ($item === 'name') {
                    $table .= "<td class = '$item'>".str_replace('/ /', '', $row[$item])."<br><span id = 'custStyle'>".$row['customer'].'</span></td>';
                } else {
                    $table .= "<td class = '$item'>".$row[$item].'</td>';
                }
            }
            $table .= '</tr>';
        }

        if ($table) {
            if (empty($title)) {
                // if (!class_exists('messStore', false)) {
                //     require_once __DIR__.'./../../core/messStor.php';
                // }
                $linkDomText = __DIR__.'/../../web/template/langContent/tsDOMText';
                $title = messStore::genLinks('reportTitle', $linkDomText, true);
            }

            echo <<<_END
<table class="table table-hover" id = "tableReport">
    <thead>
      <tr style="font-weight: bold;">
      <th></th>
      <th></th>
        <th attr = "name">$title->proj</th>
        <th style = 'text-align: center;' attr = "userName">$title->pname</th>
        <th attr = "account">$title->acc</th>
        <th style = "width: 30%;" attr = "descr">$title->desc</th>
        <th attr = "jobType">$title->jtype</th>
        <th attr = "dateJob">$title->jdate</th>
        <th style = 'text-align: center;' attr = "hoursJob">$title->jhour</th>
      </tr>
    </thead>
    <tbody>
_END;

            $table .= '</tbody></table>';

            echo $table;
        } else {
            echo "<div id = 'noRecords' class ='alert alert-success'><span>No Records</span></div></div>";
            exit();
        }

        echo <<<_END
	</div>

	<script>
		tableSortAlg()
		tableMassDeletePicker()
		function returnDate() {
    		return "$retDate";
		}
	</script>
_END;
    }
}

$getResult = new selectBlockConnector($_GET['method'], (isset($_GET['object'])?$_GET['object']:''));
$getResult->runVisiter();
