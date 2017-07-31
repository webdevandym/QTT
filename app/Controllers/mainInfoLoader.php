<?php

namespace app\Controllers;

require_once '../extendClass/Autoloader.php';
use core\messStore;

class selectBlockConnector extends infoLoaderSuperClass
{
    public function getUserName($switch)
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $switcher = $this->chkProp($switch, 'edit');

        $result = $this->cacheData(24 * 60 * 60, $this->getSQL('query', __FUNCTION__), $switcher);

        $showName = !empty($switcher) ? 'name' : 'f_name';
        $getTask = !empty($switcher) ? $switch : $_SESSION['user'];

        $a = [];
        $i = 0;

        foreach ($result as $row) {
            $a[$i++] = '<option'.($getTask === $row['name'] ? " selected = 'selected' " : '')." value = '".$row['name']."' idnum = '".$row['id']."'>".$row[$showName].'</option>';
        }

        return $a;
    }

    public function getObjectType($val)
    {
        $result = $this->cacheData(24 * 60 * 60, $this->getSQL('query', __FUNCTION__));
        $i = 0;
        foreach ($result as $row) {
            if ((int) $row['id'] !== 5) {
                $a[$i++] = "<option value = '".$row['id']."'>".$row['name'].'</option>';
            }
        }

        asort($a);

        return $a;
    }

    public function getProject($query)
    {
        list($q, $switch) = $this->chkProp($query, ['query', 'switch']);

        $id = 'name';
        $a = [];
        $first = false;

        $id = ($q === 'Customer') ? 'id' : 'name';
        $key = ($q === 'Customer') ? 'query'.$q : 'query';

        $sql = $this->getSQL($key, __FUNCTION__);
        eval("\$sql = \"$sql\";");

        $result = $this->cacheData(60 * 60, $sql, $q.$switch);

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
        list($name, $startDate, $endDate) = $this->chkProp($userInfo, ['name', 'startDate', 'endDate']);

        $retDate = $name.','.$startDate.','.$endDate;

        $a = ['name', 'userName', 'account', 'descr', 'jobType', 'dateJob', 'hoursJob'];
        $table = $result = '';

        /*all query in json file: see location in parent class"*/

        $sql = $this->getSQL('query', __FUNCTION__);
        eval("\$sql = \"$sql\";");

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
            exit("<div id = 'noRecords' class ='alert alert-success'><span>No Records</span></div></div>");
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

$getResult = new selectBlockConnector();
$getResult
    ->auto()
    ->runVisiter();
