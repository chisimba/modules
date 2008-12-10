<?php
header("Content-Type: text/html;charset=utf-8");
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$refreshLink = $this->newObject('link', 'htmlelements');
$refreshIcon = $this->newObject('geticon', 'htmlelements');
$configLink = $this->newObject('link', 'htmlelements');
$configIcon = $this->newObject('geticon', 'htmlelements');
$loadIcon = $this->newObject('geticon', 'htmlelements');
$this->objFeatureBox = $this->getObject('featurebox', 'navigation');
$objWashout = $this->getObject('washout', 'utilities');
$this->objImOps = $this->getObject('dasops', 'das');
$objImView = $this->getObject('viewrender', 'das');
$middleColumn = NULL;
$leftColumn = NULL;
$rightColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = 'My Conversations' ;$this->objLanguage->languageText('mod_im_recentmessages', 'im');
$header->type = 3;

$refreshLink->href = $this->uri(null, 'das');
$refreshIcon->setIcon('refresh');
$refreshLink->link = $refreshIcon->show();



/*$objPagination = $this->newObject('pagination', 'navigation');
$objPagination->module = 'im';
$objPagination->action = 'viewallajax';
$objPagination->id = 'im';
$objPagination->numPageLinks = $pages;
$objPagination->currentPage = $pages - 1;
$middleColumn .= $objPagination->show();*/
if($this->objUser->inAdminGroup($this->objUser->userId()))
{
    $cid = $this->objUser->userId();
    
	$configIcon->setIcon('admin');
	$configLink->href = $this->uri(array('action' => 'viewcounsilors', 'das'));
	$configLink->link = $configIcon->show();
	$config = $configLink->show();
}else{
    $cid = $this->objUser->userId();
    
	$config = "";
}
$outof = '/'.$this->objDbImPres->numOfUserAssigned ($cid);
$msgs = $this->objDbIm->getMessagesByActiveUser ($cid);

$num = count($msgs);
$str = "$num$outof users";


$middleColumn .= $header->show().'<br/>'.$config.'  '.$refreshLink->show().'<br/>'.$str;
$middleColumn .= $objImView->renderOutputForBrowser($msgs);


if (!$this->objUser->isLoggedIn()) {
    $leftColumn .= $this->objImOps->loginBox(TRUE);
} else {
   
    $rightColumn .= $objImView->renderLinkList($msgs);
	$rightColumn .= $objImView->getStatsBox();
	$leftColumn .= $this->leftMenu->show();
    if($this->objUser->inAdminGroup($this->objUser->userId()))
    {
       $leftColumn .= $this->objImOps->massMessage();//(TRUE, TRUE);
    }
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
$cssLayout->setRightColumnContent($rightColumn);
echo $cssLayout->show();

?>
<script type = "text/javascript">
		function getHtml(){
			var output = $("output");
            var url = 'index.php';
             var pars = 'action=getmsgs'; //+escape($F('greeting-name'));
             var target = 'output';
             var myAjax = new Ajax.Updater(target, url, {method: 'get', parameters: pars});
            
			
		}
		
		</script>

<a href = "javascript:getHtml()">Update HTML</a></p>
<div id= "loading"><img src="http://localhost/php/framework/skins/_common/icons/loader.gif"></div>
<div id = "output">
	This is my output

	</div>


