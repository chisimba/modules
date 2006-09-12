<?php



/**
 * create objects of classes to be user
 */
   
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
//$help = & $this->newObject('')
$this->sideMenuBar=& $this->getObject('sidemenu','toolbar');
//$this->loadClass('helplink','help');
//$objhelplink  = new helplink($helpinformer,$val);
//$displayhelp  = $objhelplink->show(helpinformer);

$this->objHelp=& $this->getObject('helplink','help');
//help_onlineinvoice_invoicedate
$displayhelp  = $this->objHelp->show('help_onlineinvoice_about','onlineinvoice'); // check wot to place as parameter

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

