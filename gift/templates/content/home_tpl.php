<?php

//load class
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');

// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';

$listingjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/gift.js','gift').'" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);

$this->appendArrayVar('headerParams', $listingjs);
$addGift = $this->uri(array('action'=>'submitadd'));
$userExists = $this->uri(array('action'=>'userexists'));
$saveUserUrl = $this->uri(array('action'=>'saveuser'));

$data='';

$dbdata=$this->objDbGift->getMyGifts($data);
$objIcon->setIcon('edit');

foreach($dbdata as $row) {
    $editGift =new link($this->uri(array('action'=>'submitedit','id'=>$row['id']))."#");
    $editGiftUrl = $this->uri(array('action'=>'submitedit','id'=>$row['id']));
    $editGift->link=$objIcon->show();
    $gift =$row['giftname'];
    $decrb =$row['description'];
    $don =$row['donor'];
    $val =$row['value'];
	
    $action ="showEditGiftWin(\'".$editGiftUrl."\',\'".$gift."\',\'".$decrb."\',\'".$don."\',\'".$val."\');return false";
    $editGift->extra = 'onClick="'.$action.'"';
    
    $data.="[";
    $data.="'".$row['giftname']."',";
    //$data.=$editGift->show()."',";
    $data.="'".$row['description']."',";
    $data.="'".$row['donor']."',";
    $data.="'".$row['recipient']."',";
    $data.="'".$row['value']."',";
    $data.="'".$editGift->show()."'";
   
    $data.="],";
}
$lastChar = $data[strlen($data)-1];
$len=strlen($data);
if($lastChar == ',') {
    $data=substr($data, 0, (strlen ($data)) - (strlen (strrchr($data,','))));
}

$mainjs = "
              Ext.onReady(function(){
                Ext.QuickTips.init();
                var url='".str_replace("amp;", "", $addGift)."',
					myUserCheckUrl ='".str_replace("amp;", "", $userExists)."',
					saveUserUrl = '".str_replace("amp;", "", $saveUserUrl)."';
				
                var xdata=[$data];
                initGrid(xdata,url,myUserCheckUrl,saveUserUrl);
                });";


$homeWelcome = $this->objHome->homePage();

$toSelect = ""; // Nothing to select

// get the links on the left
$form = $this->objHome->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('archived'));

// Create an instance of the css layout class
$cssLayout = &$this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

// links are displayed on the left
$leftSideColumn = $form;
$cssLayout->setLeftColumnContent($leftSideColumn);
echo '<script type="text/javascript">'.$mainjs.'</script>';
$heading = new htmlheading($this->objLanguage->languageText('mod_homeWelcome_heading','gift'),1);
$body    = $this->objLanguage->languageText('mod_homeWelcome_body','gift');
$notice  = $this->objLanguage->languageText('mod_homeWelcome_warning','gift');
$policy  = $this->objLanguage->languageText('mod_home_policylink','gift');

$objpolicyLink = new link($this->uri(array('action'=>'viewpolicy')));
$objpolicyLink-> link = 'Click here';
//$objLink->extra = 'onClick="showGiftPolicy()"';

$rightSideColumn.=$heading->show().$body.'<br/>'.$notice.'<br/>'.'<br/>'.$objpolicyLink->show().$policy;
$rightSideColumn .='

<div id="add-gift-surface"></div>
<div id="edit-gift-surface"></div>
<div id="grouping-grid"></div>
<div id="hello-win" class="x-hidden">
    <div class="x-window-header">Hello Dialog</div>    
</div>
';

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

// Output the content to the page
echo $cssLayout->show();
?>