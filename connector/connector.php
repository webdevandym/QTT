<?php

namespace connector;

use core\logSystem\colorSyntax;
use core\logSystem\logHTMLAdv;
use Exception as Exception;

class connector
{
    private static $server;
    private static $database;
    private static $user;
    private static $pass;
    private static $db = null;

    public function __construct($server, $database, $user, $pass)
    {
        $this::$server = $server;
        $this::$database = $database;
        $this::$user = $user;
        $this::$pass = $pass;
    }

    public function getConnection()
    {
        try {
            if (!(self::$db instanceof \PDO)) {
                $dns = sprintf("dblib:host={$this::$server};dbname={$this::$database};ConnectionPooling=0;LoginTimeout=5");
                self::$db = new \PDO(
                    $dns,
                    $this::$user,
                    $this::$pass,
                    [
                        // PDO::ATTR_PERSISTENT => true,
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        // PDO::ATTR_CASE => PDO::CASE_LOWER,
                    ]
                );
            }
        } catch (\PDOException $e) {
            echo '<div id = "dberrorMessage">'.$e->getMessage().'</div>';
        }

        self::checkConnect();
    }

    public static function checkConnect()
    {
        if (!isset(self::$db)) {
            echo <<<__END
    <head>
         <link rel="stylesheet" type="text/css" href="{$_COOKIE['curPath']}/../assets/css/mincss/siteStyle-min.css">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    </head>
    <body>
        <div id = 'dbstatus' class ='dberror'>
             <i class="fa fa-database" aria-hidden="true"></i>
        </div>
        <script> document.body.setAttribute("class",'loaded'); </script>
__END;
            die('</body>');
        }
    }

    public function queryMysql($query, $shadow = false)
    {
        try {
            $result = self::$db->query($query);
        } catch (\PDOException $e) {
            $this->close();
            echo $e->getMessage();
        }

        $flink = __DIR__.'/../log/query.html';
        $templFile = __DIR__.'/../log/template.txt';

        $query = htmlentities(preg_replace('/[\s\t\n]+/', ' ', $query));

        $syntax = new colorSyntax('sql', $query);
        $newquery = $syntax->clrSyntax();

        $context = '<span>'.((!empty($result)) ? '<span class = "logSucc">success' : '<span class = "logFail">failure').' => </span>'.$newquery.((strlen($query) > 200) ? '...' : '').'</span>';

        if ($shadow) {
            $context = preg_replace('/(name|pass|password)[= \']+\S+\'/', '***', $context);
            $level = 2;
        }

        $log = new logHTMLAdv($flink, $context, true, $templFile, 'Europe/Kiev');
        $log->writeLog();

        if (empty($result)) {
            die;
        }

        return $result;
    }

    public function existsTable($table, $type)
    {
        $sql = " (select count(*) as cnt from sysobjects where type = '$type' and name = '$table')";
        try {
            $res = $this->queryMysql($sql);
        } catch (Exception $e) {
            echo '<div id = "SQLerror"><span>'.$e->getMessage().'</span></div>';
        }

        foreach ($res as  $value) {
            if (!empty($value['cnt'])) {
                return true;
            }
        }
    }

    public function close()
    {
        self::$db = null;
    }

    public function sanitizeString($var)
    {
        if ($var) {
            if (!empty(self::$db)) {
                $var = stripslashes(htmlentities(strip_tags($var)));

                return substr(self::$db->quote($var), 1, -1);
            }
            throw new Exception('Check you connect to DB!');
        }

        return '';
    }

    public function rowCounter($array)
    {
        $rowCounter = 0;

        foreach ($array as $value) {
            ++$rowCounter;
        }

        return $rowCounter;
    }
}
