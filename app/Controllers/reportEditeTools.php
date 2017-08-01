<?php

namespace app\Controllers;

require_once '../extendClass/Autoloader.php';

class reportEditeTools extends infoLoaderSuperClass
{
    public function getJobType($selector)
    {
        $select = $this->chkProp($selector, 'select');

        $result = $this->cacheData(24 * 60 * 60, $this->getSQL('query', __FUNCTION__), $select);

        foreach ($result as $row) {
            echo '<option '.((!empty($select) && $select === $row['name']) ? ' selected="selected"' : '')." value='".$row['id']."'>".$row['name'].'</option>';
        }
    }

    public function getObjectName($valObj)
    {
        list($name, $type) = $this->chkProp($valObj, ['name', 'type']);

        $type = ((int) $type === 5) ? 'ALL' : $type;

        $sql = $this->getSQL('query', __FUNCTION__);
        eval("\$sql = \"$sql\";");

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
            if ($value instanceof stdClass) {
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

        foreach ($val['date'] as $val) {
            $value = $this->getSQL('value', __FUNCTION__);
            $sql = $this->getSQL('query', __FUNCTION__);
            eval("\$value = \"$value\";");
            eval("\$sql = \"$sql\";");

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
            $setStat .= $DBColumns[$i - 1]."='".$val[$i]."',";
        }

        $setStat = substr($setStat, 0, -1);

        $sql = $this->getSQL('query', __FUNCTION__);
        eval("\$sql = \"$sql\";");
        $this->returnQuery($sql);
    }

    public function removeRecord($recToDel)
    {
        $id = $this->chkProp($recToDel, 'rec');

        $sql = $this->getSQL('query', __FUNCTION__);
        eval("\$sql = \"$sql\";");
        $this->returnQuery($sql);
    }
}

$getResult = new reportEditeTools();
$getResult
    ->auto()
    ->runVisiter();
