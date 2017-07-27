
<nav class="navbar navbar-default navbar-fixed-top ">
    <div class="container-fluid">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo $basePath; ?>web/content" id= "brand">
            QTT Panel <i class="fa fa-database" aria-hidden="true"></i>
        </a>
        <canvas height="50" width="450" id = 'time'></canvas>
        <!-- <span id = 'time'></span> -->
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#" onclick="refrSwitch();$('body').removeClass('loaded');" id='refreshPage'><i class="fa fa-refresh" aria-hidden="true"></i></a> </li>
            <li class = 'menuBorder' ></li>
            <li><a href="timeSheet" onclick="renderWeb($(this).attr('href'));"><i class="fa fa-clock-o"></i> Time Sheet</a></li>
            <li><a href="content" onclick="renderWeb($(this).attr('href'));"><i class="fa fa-users"></i> Tracker</a></li>
            <li><a href="#" onclick="$.get('<?php echo $basePath; ?>core/loginControler/logout.php',(data) =>{$('body').html(data)})"><i class="fa fa-external-link"></i> Log out</a></li>

        </ul>

        </div>
    </div>
</nav>

<div class = 'loadScreen' id= 'loader-wrapper'>
    <div class='loader'></div>
    <div id = 'innerLoader'></div>
    <div class='loader-section section-right'></div>
    <div class='loader-section section-left'></div>
    <div class="loader-status">Loading<div class="loader-progress"></div></div>
</div>
