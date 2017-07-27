<?php
namespace web\form\site;

use core\messStore;

$langStore = ['error',
            'placeh' => 'placeHolders',
            'label' => 'labelsTS',
            ['labelsTS', 'deleteApp'],
            ];
$store = messStore::genLinks($langStore);

$linkDomText = __DIR__.'./../../template/langContent/tsDOMText';
$selBlock = messStore::genLinks('selBlock', $linkDomText, true);
$modal = messStore::genLinks(['e' => 'modalEdit', 'd' => 'modalDel'], $linkDomText);

?>
<div class="content_start_TS">
<div class="container">
    <div class="row">
      <div class="SQLerror">
        <button id = 'closeError' title = "<?php echo $store->label->closeError; ?>"><i class="fa fa-times" aria-hidden="true"></i></button>
        <span></span>
      </div>
        <div class="extraOption" title="<?php echo $store->label->extraOption; ?>">
          <input type="hidden"  id = 'nowDate'>

          <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-caret-right" aria-hidden="true"></i></button>
            <ul class="dropdown-menu">
              <li id = 'multiDelete' title = "<?php echo $store->label->multiDelete; ?>"><a href = "#">Multi Delete <i class="fa fa-trash" aria-hidden="true"></i> <span class = "ioDeleteOFF"></span></a></li>
              <li id = 'multiReport' title = "<?php echo $store->label->multiReport; ?>"><a href = "#">Quick report <i class="fa fa-files-o" aria-hidden="true"></i><span class = "ioDeleteOFF"></span></a></li>
              <li id = 'lastReport' title = "<?php echo $store->label->lastReport; ?>"><a href = "#">Show last report <i class="fa fa-crosshairs" aria-hidden="true"></i></a></li>
            </ul>
          </div>
          <div class="btn-group-vertical deleteApp">
            <button class="btn btn-danger" type="button" onclick="genDeleteList();" title = '<?php echo $store->deleteApp->delete; ?>'><i class="fa fa-trash" aria-hidden="true"></i></button>
            <button class="btn btn-primary" type="button" onclick="flashSelectRecords();" title = '<?php echo $store->deleteApp->flashChoose; ?>' id = 'flashSelect'><i class="fa fa-bolt" aria-hidden="true"></i></i></button>
            <button class="btn btn-default" type="button"  onclick="toggleSelectElemetnTable();" title = '<?php echo $store->deleteApp->exit; ?>'><i class="fa fa-undo" aria-hidden="true"></i></button>
          </div>
        </div>
        <div id="calendar" class="col-md-3">
          <input type="hidden" id = 'weekSEchk'>

        </div>
        <div class="col-md-9">
          <input type="hidden"  id = 'objectNameStore'>
            <div class="programerNameTS">
                <span class='labelStyle'><?php echo $selBlock->pname; ?></span>
                <select id="programmerName">
                </select>
            </div>
            <section id="projectSelecterTimeSheet">
                <div class="customerBlockTS">
                    <span class='labelStyle'><?php echo $selBlock->cust; ?></span>
                    <select class='customerTS' id="customerID" onchange="$('#projectID').getPjName(this.value);">
                    </select>
                </div>
                <div class="pjNameBlockTimeSheet">
                    <span class='labelStyle'><?php echo $selBlock->projname; ?></span>
                    <select class='projectNameTS' id="projectID">
                    </select>
                </div>
                <div class="jopType">
                    <span class='labelStyle'><?php echo $selBlock->jobt; ?></span>
                    <select class='projJobTypeTS' id="jobType">
                    </select>
                </div>
                <div class="hours">

                    <span class='labelStyle'><?php echo $selBlock->hour; ?> <span id = "errorHour"><i class="fa fa-exclamation" aria-hidden="true"></i> <?php echo $store->error->errorHour; ?></span></span>
                    <input class='projHoursTS' id="hours" type='text' onblur="ValidDesc($(this),'#errorHour',1);" placeholder = '<?php echo $store->placeh->hours; ?>'
                     onkeypress="return hoursChecker(0,null,event)"  onkeyup = "hoursChecker(1,$(this))" maxlength='3'>
                </div>
            </section>
            <section class="objectTS">
                <div class="objectTypeTS">
                    <span class='labelStyle'><?php echo $selBlock->oType; ?></span>
                    <select id="objectType">
                    </select>
                </div>
                <div class="objectNameTS">
                    <span class='labelStyle'><?php echo $selBlock->oName; ?> <span id = "errorObjectName"><i class="fa fa-exclamation" aria-hidden="true"></i> <?php echo $store->error->errorObjectName; ?></span></span>
                    <select class='objNameTS' id="objName">
                    </select>
                </div>
                <div class="addDescTS">
                    <label id = 'lableAddToD' title="<?php echo $store->label->addDescTS; ?>">
                        <input type="checkbox" name="checkDesk" id="checkDesk" checked><span class='labelStyleDesc'>Add To Description</span></label>
                </div>
            </section>
        </div>
        <section class="descrTS">
            <legend id = "descLegend"><i class="fa fa-exclamation" aria-hidden="true"></i> <?php echo $store->error->descLegend; ?></legend>
            <textarea id='descrTextTS' type='text' placeholder = '<?php echo $store->placeh->descrTextTS; ?>' onblur= "dscrValidator($(this),['#jobType','#descLegend']);"></textarea>
            <div class="buttonBlock">
              <div class = 'buttonAddReport'>
                     <button class='btn btn-success' id="addTS" onclick="initAddAlg();"><i class="fa fa-plus-square" aria-hidden="true"></i> Add</button>
                    <div class="loading_bg">
                    <div class="loading_val"></div>
                  </div>
              </div>

                <button class='btn btn-success' id="exportTS" disabled><i class="fa fa-table" aria-hidden="true"></i> Export To Excel</button>
            </div>
        </section>
        <section id="ReportWeek">

        </section>

        <div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $modal->e->title; ?></h4>
      </div>
      <div class="modal-body">

      </div>
         <div class="modal-footer">
          <div class="buttonPlace">
           <div class = 'buttonWraper'>
              <button type="button" class='btn btn-success' id = "updateReport"><?php echo $modal->e->upd; ?> <i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                <div class="bg">
                  <div class="el"></div>
                </div>
            </div>
            <button type="button" class="btn btn-danger" data-dismiss="modal" id = 'removeReportLine'><?php echo $modal->e->close; ?> <i class="fa fa-times" aria-hidden="true"></i></button>

        </div>
      </div>
    </div>

  </div>
</div>


<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $modal->d->close; ?></h4>
      </div>
      <div class="modal-body">
       <div class="deleteButton">
          <input type='hidden' id = 'hiddenVal'>
          <button type="button" class='btn btn-danger' ><?php echo $modal->d->del; ?> <i class="fa fa-trash-o" aria-hidden="true"></i></button>
          <div class = 'buttonWraper_delete' >
             <div class="bgDel">
                    <div class="el" id ='elDel'></div>
                  </div>
              </div>

          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $modal->d->close; ?> <i class="fa fa-times" aria-hidden="true"></i></button>

        </div>
      </div>
       <div class="modal-footer">

      </div>
    </div>

  </div>
</div>
  <div id ="notifyTS">
    <span class = 'text'></span>
  </div>
    </div>
</div>
</div>
