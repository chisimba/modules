<?php
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
$this->loadClass('link', 'htmlelements');
$content='';
$content.='<div class="subcolumns" id="latestuploads">';//subcolumns starts
$content.='  <div class="latestuploads_container">';//latest container starts


//$content.= $tagCloudContent;
$content.='<div class="c85r">';
$content.='<div id="lu_wrapper">';
$content.='<div id="lu_inner">';
$content.='<div class="ec_item" id="lu1">';
//$content.=$objLatestFiles->getLatestUploads();
$content.='</div>'; //lul
$content.='</div>';//li inner
$content.='</div>';//wrapper
$content.='</div>';//c85

$content.='</div>';//latest uploads container ends
$content.='</div>';//subcolum ends
//start the content

$content.='<div id="contentwrapper" class="subcolumns">';
$content.='     <div id="homepagecontent" class="main">';
$content.='          <div class="subcolumns">';
$content.=$this->objViewerUtils->getTopicContent($id);
$content.='             </div>'; //subcol
//$content.=$this->objViewerUtils->createCell('c50l');
$content.='         </div>';//main
$content.='     </div>';//su


echo $content;
?>
