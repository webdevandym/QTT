<?php
// session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userName = $_SESSION['userName'];
} elseif (isset($_COOKIE['user'])) {
    $_SESSION['user'] = $_COOKIE['user'];
    $_SESSION['userName'] = $_COOKIE['userName'];
    $user = $_SESSION['user'];
}

$basePath = $_COOKIE['curPath'].'/';
?>


<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta basePath= "<?php echo $basePath; ?>">
    <meta name = 'curentPage' value = ''>
    <link rel="shortcut icon" href="<?php echo $basePath; ?>favicon.ico" type="image/x-icon">
    <title>QTT Panel [<?php echo $user; ?>]</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" media="bogus" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css" media="bogus">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" media="bogus">
    <link rel="stylesheet" type="text/css" href="<?php echo $basePath; ?>assets/css/mincss/siteStyle-min.css" media="bogus">
</head>

<body>
