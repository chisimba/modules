<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<?php
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
$content.=$this->objViewerUtils->getHomePageContent();
$content.='             </div>'; //subcol
//$content.=$this->objViewerUtils->createCell('c50l');
$content.='         </div>';//main
$content.='     </div>';//su


$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ocsinterface');
$leftSideColumn = $nav->getLeftContent();

$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div id="gtx"></div><div style="margin-left:50px; margin-right:50px;padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $this->objViewerUtils->getHomePageContent();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();
?>
