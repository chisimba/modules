<?php
//load class
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');
$grid = $this->objFaculty->getAllFaculty();

// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';
$commentsadmin = '<script language="JavaScript" src="'.$this->getResourceUri('js/commentsadmin.js').'" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);
$this->appendArrayVar('headerParams', $commentsadmin);

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$addComment = new button('addModerator', 'Add Comment');
$addComment->setId('addcomment-btn');

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
//$rightSideColumn =  '<h1>Faculty Listing</h1>';
$rightSideColumn ='<div id ="commentlist">'.$addComment->show();
$renderSurface .= '<div id="addcomment-win" class="x-hidden"><div class="x-window-header"></div></div>';
$renderSurface .= '<div id="facultyListing"></div>';
$rightSideColumn .=$renderSurface;
$rightSideColumn .= '</div>';
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();
$allcommentdata=$this->objCommentAdmin->getComments();
$commentData = "";
$count = 1;
$total=count($allcommentdata);

foreach($allcommentdata as $data) {
    $commentData.="['".$data['comment_desc']."','".$this->objUser->fullname($data['userid']);
    if($count < $total){
        $commentData.=",";
    }
    $count++;
}


$mainjs =
"<script type=\"text/javascript\">
    Ext.onReady(function(){
        var mData = [".$commentData."];
       showCommentAdmin(mData);
       initCommentaddWin();
    });
</script>";
/*
 * 
 */
$this->appendArrayVar('headerParams', $mainjs);
echo $grid;
?>
