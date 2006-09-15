<?php



/**
 * create objects of classes to be user
 */
   
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$this->sideMenuBar=& $this->getObject('sidemenu','toolbar');
$cssLayout->setNumColumns(3);

$this->objHelp=& $this->getObject('helplink','help');

/**
 *define all items to display within the help popup window
 */
 
$instructions = $this->objLanguage->languageText('mod_onlineinvoice_helpinstruction','onlineinvoice');
$createinv  = $this->objLanguage->languageText('mod_onlineinvoice_createinv','onlineinvoice');
$tev  = $this->objLanguage->languageText('mod_onlineinvoice_tev','onlineinvoice');
$itinerary  = $this->objLanguage->languageText('mod_onlineinvoice_itinerary','onlineinvoice');
$perdiem  = $this->objLanguage->languageText('mod_onlineinvoice_perdiem','onlineinvoice');
$lodge  = $this->objLanguage->languageText('mod_onlineinvoice_lodge','onlineinvoice');
$incident = $this->objLanguage->languageText('mod_onlineinvoice_incident','onlineinvoice');

//$this->objlink  =& $this->newObject('link','htmlelements');
//$this->objlink->link($this->uri(array('NULL')));
//$this->objlink->link = $instructions;
//$def = $this->objlink->show();
$displayhelp  = $this->objHelp->show('mod_onlineinvoice_helpinstruction');  

/*************************************************************
 * want to add links to help object 
 * each link has a popup window to show info
 * but gettin a problems adding links to popup
 * else?? 
 * how to add lots of content to popup
 * ************************************************************     


$cssLayout->setNumColumns(3);
$sideMenuBar=& $this->getObject('sidemenu','toolbar');

/**
 *create a log out link and add to rightcolumn
 */
$logout    = ucfirst($this->objLanguage->languageText('word_logout'));
$logmessage = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_logoutsystem','onlineinvoice'));
$help = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_helptext','onlineinvoice'));
 
$urltext = $logout;
$content = $logmessage;
$caption = '';
$url = $this->uri(array('action'=>NULL));
$this->objlogoutlink  = & $this->newObject('mouseoverpopup','htmlelements');
$this->objlogoutlink->mouseoverpopup($urltext,$content,$caption,$url);

$rightcolumn = $this->objlogoutlink->show();  


$this->loadClass('featurebox','navigation');
$objfeature = new featurebox($val,$val2);
$displaylink = $objfeature->show($rightcolumn);



$objfeaturehelp = new featurebox($val,$val2);
$display = $objfeaturehelp->show($help  . " " .'<br />' . $displayhelp);

$objuserdetails = new featurebox($val,$val2);
$displayleft = $objuserdetails->show($this->sideMenuBar->userDetails());
        

/**
 * Set the Content of left side column and right side column and middle column
 */  
$cssLayout->setLeftColumnContent($displayleft);
$cssLayout->setRightColumnContent($displaylink . '<br />' . '<br />' . '<br />' .$display);
$cssLayout->setMiddleColumnContent($this->getContent()); 

/**
 * Display the Layout
 */  
echo $cssLayout->show();

/****************************************************************************************************************/

?>

