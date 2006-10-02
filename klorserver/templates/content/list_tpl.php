<?php

/**
* Method that formats the date to DDMMYYY
* @param string $date The date
* @return string A formatted date string
*/
function formatDate($date)
	{
		
        if (isset($date)) {
        $date = getdate(strtotime($date)); 
		
		return ($date['mday'].' '.$date['month'].' '. $date['year']);
        }
	}
/**
* Method that formats the time to hours:minutes
* @param string $time The time
* @return string A formatted time string
*/    
function formatTime($time)
	{
		$time = getdate(strtotime($time)); 
		
		return ($time['hours'].':'.$time['minutes']);
	}
    
$headerParams=$this->getJavascriptFile('x.js','postlogin');
$headerParams.="
<script type=\"text/javascript\">        

function adjustLayout()
{
     var leftnavHeight = 0;
     var rightnavHeight = 0;
     var contentHeight = 0;
     
     if (document.getElementById('leftnav')) {
         leftnavHeight = document.getElementById('leftnav').offsetHeight;
     }
     
     if (document.getElementById('rightnav')) {
         rightnavHeight = document.getElementById('rightnav').offsetHeight;
     }
     
     if (document.getElementById('content')) {
         contentHeight = document.getElementById('content').offsetHeight;
     }
     
     biggestHeight = Math.max(leftnavHeight, rightnavHeight, contentHeight);
     
     
     if (biggestHeight > contentHeight) {
         document.getElementById('content').style.height = biggestHeight+\"px\";
    } 
}

window.onload = function()
{
  xAddEventListener(window, \"resize\",
    adjustLayout, false);
  adjustLayout();
}

</script>";
$this->appendArrayVar('headerParams',$headerParams);

$objLink=$this->newObject('link','htmlelements');
$fieldset=&$this->newObject('fieldset','htmlelements');
$table=&$this->newObject('htmltable','htmlelements');        
$heading=&$this->newObject('htmlheading','htmlelements');
$loggedInUsers=&$this->newObject('loggedin','communications');
$str='';

// Load the Link Class
$this->loadClass('link', 'htmlelements');

/*****Online User for Context*****/
// --- Jonathan Abrahams 14 Febuary 2005 --- Online user counter
$objOnlineCount = $this->getObject('onlinecount','contextgroups');

$objOnlineCount->setContextGroup('Lecturers');
$lect = $objOnlineCount->show();

$objOnlineCount->setContextGroup('Students');
$studs = $objOnlineCount->show();

$objOnlineCount->setContextGroup('Guest');
$guest = $objOnlineCount->show();

$arrOnline = array( $lect, $studs, $guest );
$onlineUsers=implode('<BR>',$arrOnline);
/***END OF Online USERS****/






//RIGHT
$shift=$this->getParam('shift', 0);

$moduleCheck=$this->newObject('modulesAdmin','modulelist');
$module = $moduleCheck->getModuleInfo('calendar');

if ($module['isreg']) {
    $calendar =& $this->newObject('contextcalendar','calendar');
    
    $str = $calendar->show();
} else {
    $str = '';
}


$str.='<h5>'.$this->objLanguage->languageText("mod_context_whoson").'</h5>'.$onlineUsers;
$right=$str;
$this->rightNav = &$this->newObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $str;
//echo $this->rightNav->addToLayer();

//Center
$heading->str=$this->objLanguage->languageText("mod_context_welcome").' '.$this->objDBContext->getTitle();
$heading->type=2;
$strCenter=$heading->show();
$about=stripslashes($this->objDBContext->getField("about"));



$centre=$strCenter.'<BR>'.$about;
$this->contentNav = &$this->newObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->height='1000'; 
$this->contentNav->str = $centre;
//echo $this->contentNav->addToLayer();

$objLink->href=$this->URI(array('action'=>'logoff'),'security');
$objLink->link=$this->objLanguage->languageText("word_logout");

$str=$this->objLanguage->languageText("mod_context_loggedinas").'</span>&nbsp;<strong>'.$this->objUser->fullname().
'</strong>&nbsp; ('.$objLink->show().')';
 $this->setVar('footerStr',$str);
 
 $this->leftNav = &$this->newObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->userMenuBar=& $this->getObject('contextmenu','toolbar');
$this->leftNav->addToStr($this->userMenuBar->show());
echo $this->leftNav->show(); 

$this->rightNav = &$this->newObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->addToStr($right);
echo $this->rightNav->show(); 

$this->contentNav = &$this->newObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->addToStr($centre);
 echo $this->contentNav->show();

// ???
//echo "[$about]<br/>";
?>
