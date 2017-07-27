<?php

namespace core\loginControler;

use connector\fastConnect;

class login
{
    public static function validUser()
    {
        fastConnect::inst()->conn($conn);
        $userstr = '(Guest)';

        if (isset($_GET['user'])) {
            $user = $conn->sanitizeString($_GET['user']);

            $result = $conn->queryMysql("SELECT DISTINCT name,  f_name FROM proj_users WHERE name = '$user' and disabled = '0'", true);

            foreach ($result as $val) {
                $userName = $val['f_name'];
            }

            $conn->close();

            if ($userName) {
                $userstr = $_SESSION['user'] = $user;
                $_SESSION['userName'] = $userName;

                if (isset($_SESSION['user'])) {
                    setcookie('user', $_SESSION['user'], time() + 60 * 60 * 24 * 7, '/');
                    setcookie('userName', $_SESSION['userName'], time() + 60 * 60 * 24 * 7, '/');
                }

                //die("You are now logged in. Please <a href = 'members.php?view=$user'>click here</a> to continue. <br><br>");
                echo "<script>window.location.href = '.';</script>";
                die;
            }
        }

        if (!$curPath) {
            $curPath = $_COOKIE['curPath'];
        }

        echo <<<__END

<html lang="ru">
<head>
            <title>
               QTT <?php echo $userstr; ?>
            </title>
            <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
            <link rel="stylesheet" type="text/css" href="$curPath/assets/css/mincss/siteStyle-min.css">
            <link href="https://fonts.googleapis.com/css?family=PT+Sans&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        </head>
        <body>

            <div id = 'dbstatus' class ='dbsucc'>
                 <i class="fa fa-database" aria-hidden="true"></i>
            </div>
        	<div class = 'loginFrame'><div class = 'main'>

                <canvas  id = 'logo' height = '100' width = '400'></canvas>
                <h3 id = 'fistPageText'>Enter domain username</h3>
                <!-- <form method = 'post' action = 'index'> -->
                    <span id = 'errorHolder'></span>
                    <span class = 'fieldname'> Username </span>
                    <input class = 'loginInput' type = 'text' maxlength="16" name = 'user' value = "$user">
                    <span id = "loginChecker"></span>
                    <br>
                    <!-- <span class = 'fieldname'> Password </span>
                    <input class = 'loginInput' type="text" name="pass" maxlength="16" value="<?php echo $pass; ?>">
                    <br> -->
                    <button class= 'submitLogin'>Войти</button>
                <!-- </form> -->
                <br>
            </div>
        </div>
</html>



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script> window.jQuery || document.write('<script src="/../assets/js/vendor/jquery-3.2.1.min.js"><\/script>')</script>
        <script src = "$curPath/assets/js/min/firstpagescripts.js"></script>

__END;
    }
}
