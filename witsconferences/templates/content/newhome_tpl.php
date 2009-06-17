<?php

$objLatestFiles= $this->getObject('viewerutils','witsconferences');
$this->loadClass('link', 'htmlelements');
$content='';
$content.='<div class="subcolumns" id="conferencebanner"></div>';


$content.='<div class="subcolumns" id="latestuploads">';//subcolumns starts
$content.='  <div class="latestuploads_container">';//latest container starts

//$tagCloud = $this->objTags->getTagCloud();
$tagCloudLink = new link ($this->uri(array('action'=>'tagcloud')));
$tagCloudLink->link = 'View All Tags';
$tagCloudContent = '<span style="text-align:center">' . $tagCloud . '</span><br />'.$tagCloudLink->show();

//$content.= $tagCloudContent;
$content.='<div class="c85r">';
$content.='<div id="lu_wrapper">';
$content.='<div id="lu_inner">';
$content.='<div class="ec_item" id="lu1">';
//$content.=$objLatestFiles->getLatestUploads();
$content.='</div>';
$content.='</div>';
$content.='</div>';
$content.='</div>';

$content.='</div>';//latest uploads container ends
$content.='</div>';//subcolum ends
//start the content

$content.='<div id="contentwrapper" class="subcolumns">';
$content.='     <div id="homepagecontent" class="c85l">';
$content.='          <div class="subcolumns">';
$content.='            <div class="c59l" id="homepagestats">';
$content.=$objLatestFiles->getLatestNews();
$content.='             </div>';
$content.=$objLatestFiles->getTagCloudContent($tagCloudContent);

$content.='         </div>';
$content.='     </div>';
$content.=$objLatestFiles->getArchives();


$content.='</div>';

echo $content;
?>
