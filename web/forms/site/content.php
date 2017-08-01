<?php
namespace web\forms\site;

use core\messStore;

$linkDomText = __DIR__.'./../../template/langContent/tsDOMText';
$selBlock = messStore::genLinks('selBlock', $linkDomText, true);

?>
<div class="content_start">
    <section id = "projectSelecter">
        <div class="customerBlock">
            <span class = 'labelStyle'><?php echo $selBlock->cust; ?></span>
            <select class = 'customer'  id = "customerID" onchange="getPjName(this.value);" name = 'customer'>
            </select>
        </div>
        <div class="pjNameBlock">
            <span class = 'labelStyle'><?php echo $selBlock->projname; ?></span>
            <select class = 'projectName' id = "projectID" name = 'projectName'>
            </select>
        </div>
    </section>
    <section id="objectBlock">
        <div class="objectProgName">
            <div class="objectType">
                <span class = 'labelStyle'><?php echo $selBlock->oType; ?></span>
                <select id = "objectType" name = 'oType'>

                </select>
            </div>
            <div class="programerName">
                <span class = 'labelStyle'><?php echo $selBlock->projname; ?></span>
                <select id = "programmerName" name = 'pName'>
                </select>
            </div>
        </div>
        <div class="objectfilter">
            <div class="objectName">
                <span class = 'labelStyle'><?php echo $selBlock->oName; ?></span>
                <select type="text" name="objName" id = "objName">

                </select>
            </div>
            <div class="objectTitle">
                <span class = 'labelStyle'><?php echo $selBlock->oTitle; ?></span>
                <input type="text" name="objTitle" id = "objTitle">
            </div>
            <div class="objectButton">
                <button id = "show" class = 'btn btn-success'>Show</button>
            </div>
        </div>
    </section>

</div>
