<?php
namespace app\Controllers;

require_once "../extendClass/Autoloader.php";

use app\Controllers\infoLoaderSuperClass;

class reportEditeTools extends infoLoaderSuperClass
{
    public function getJobType($selector)
    {
        $select = $this->chkProp($selector, 'select');

        $sql = 'SELECT name,id
				 FROM proj_jobtypes
				 ORDER BY id';

        $result = $this->cacheData(24 * 60 * 60, $sql, $select);

        foreach ($result as $row) {
            if (!empty($select) && $select === $row['name']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }

            echo "<option $selected value='".$row['id']."'>".$row['name'].'</option>';
        }
    }

    public function getObjectName($valObj)
    {
        $name = $this->chkProp($valObj, 'name');
        $type = $this->chkProp($valObj, 'type');

        $type = ((int) $type === 5) ? 'ALL' : $type;
        // $this->createSQLProc('pr_ObjName', 'CREATE PROC  pr_ObjName @type nvarchar(30), @name nvarchar(30) AS select object_num,id
        //             FROM proj_objects
        //             WHERE objtype_id = @type AND proj_id IN (
        //                     SELECT id FROM proj_names WHERE name = @name) ORDER BY object_num');

        $sql = "EXEC pr_ObjName @type = '$type', @name = '$name'";

        $result = $this->cacheData(24 * 60 * 60, $sql, $name.$type);

        $a = $select = $b = '';

        foreach ($result as $row) {
            if (strtolower($row['object_num']) === '-none-') {
                $b .= "<option value='".$row['id']."'>".$row['object_num'].'</option>';
            } else {
                $a .= "<option value='".$row['id']."'>".$row['object_num'].'</option>';
            }
        }

        $res = $b.$a;
        if ($res) {
            echo $res;
        } else {
            return false;
        }
    }

    public function addReport($updval)
    {
        foreach ($updval as $value) {
            if ($value instanceof \stdClass) {
                foreach ($value as $res) {
                    $val['date'][] = $res;
                }
            } else {
                $val[] = $this->clr($value);
            }
        }

        $val[3] = "'".$val[3]."'";

        $DBColumns = 'object_id, jobtype_id, user_id, job_descr, job_hours,account_id,job_date';
        $DBColArr = explode(',', $DBColumns);
        $setStat = '';

        for ($i = 0; $i < count($DBColArr) - 1; ++$i) {
            $setStat .= $val[$i].',';
        }

        foreach ($val['date'] as $value) {
            $value = "CONVERT(DATETIME,'".$value."')";
            $sql = "INSERT INTO  proj_jobs WITH (TABLOCKX) ($DBColumns) VALUES($setStat $value)";

            $this->returnQuery($sql, false);
        }
        // echo $sql;
        $this::$db->close();
    }

    public function UpDateDB($valStore)
    {
        foreach ($valStore as $value) {
            $val[] = $this->clr($value);
        }

        $specVal = explode('_', explode('it', $val[0])[1]);

        $DBColumns = ['object_id', 'jobtype_id', 'user_id', 'job_descr', 'job_date', 'job_hours'];
        $setStat = '';

        for ($i = 1; $i < count($val); ++$i) {
            $setStat .= $DBColumns[$i - 1]."='".$val[$i]."'";
            if (!($i === count($val) - 1)) {
                $setStat .= ', ';
            }
        }

        $sql = "UPDATE proj_jobs WITH (TABLOCKX) SET $setStat WHERE id = '$specVal[0]'";
        // echo $sql;
        $this->returnQuery($sql);
    }

    public function removeRecord($recToDel)
    {
        $id = $this->chkProp($recToDel, 'rec');

        // echo $id;
        $sql = "DELETE FROM proj_jobs WITH (TABLOCKX) WHERE id IN ($id)";

        $this->returnQuery($sql);
    }
}

$getResult = new reportEditeTools($_GET['method'], (isset($_GET['object'])?$_GET['object']:''));
$getResult->runVisiter();
