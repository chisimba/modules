<?php

/*create template for lodging expenses*/
$this->objlodgeHeading = $this->newObject('htmlheading','htmlelements');
$this->objlodgeHeading->type = 2;
$this->objlodgeHeading->str=$objLanguage->languageText('mod_onlineinvoice_travellodgingexpenses','onlineinvoice');
/*********************************************************************************************************************************************************************/

$exchangeRate  = '<a href=http://www.oanda.com/convert/classic/>www.oanda.com/convert/classic</a>';
$lodgeHint  = $this->objLanguage->languageText('mod_onlineinvoice_pleasemouseover','onlineinvoice');
$lodgeExchangeRate = $this->objLanguage->languageText('mod_onlineinvoice_anyexchangerate','onlineinvoice');
$lodgeSuggestedExRate = $this->objLanguage->languageText('mod_onlineinvoice_suggestedexchangerate','onlineinvoice')  . ' ' . $exchangeRate;
$lodgeExpenditures = $this->objLanguage->languageText('mod_onlineinvoice_itemizedexpenditures','onlineinvoice');
$vendor = $this->objLanguage->languageText('word_vendor');
$currency = $this->objLanguage->languageText('word_currency');
$cost = $this->objLanguage->languageText('word_cost');
$rate = $this->objLanguage->languageText('mod_onlineinvoice_exchangerate','onlineinvoice');  
$lblchoice  = $this->objLanguage->languageText('mod_onlineinvoice_verifyexchangerate','onlineinvoice');
$attachfile = $this->objLanguage->languageText('mod_onlineinvoice_attachfile','onlineinvoice');
$quotesource  = $this->objLanguage->languageText('mod_onlineinvoice_quotesource','onlineinvoice');
$strquote = ucfirst($quotesource);
$receipt  = $this->objLanguage->languageText('mod_onlineinvoice_uploadreceipts','onlineinvoice');
$uploadbutton = $this->objLanguage->languageText('mod_onlineinvoice_uploadbutton','onlineinvoice');
$create = $this->objLanguage->languageText('mod_onlineinvoice_create','onlineinvoice');
$next = $this->objLanguage->languageText('phrase_next');

/*********************************************************************************************************************************************************************/
$expensesdate = $this->objLanguage->languageText('word_date');
$lblDate = lbldate;
$this->objdate  = $this->newObject('label','htmlelements');
$this->objdate->label($expensesdate,$lblDate);



$this->objlodgedate = $this->newObject('datepicker','htmlelements');
$name = 'txtlodgedate';
$date = '01-01-2006';
$format = 'DD-MM-YYYY';
$this->objlodgedate->setName($name);
$this->objlodgedate->setDefaultDate($date);
$this->objlodgedate->setDateFormat($format);



$this->objtxtvendor = $this->newobject('textinput','htmlelements');
$this->objtxtvendor->textinput("txtvendor","",'text');

//$this->objtxtcurrency  = $this->newobject('textinput','htmlelements');
//$this->objtxtcurrency->textinput("txtcurrency","","text");

$this->objtxtcost  = $this->newobject('textinput','htmlelements');
$this->objtxtcost->textinput("txtcost","","text");

$this->objtxtexchange = $this->newobject('textinput','htmlelements');
$this->objtxtexchange->textinput("txtexchange","","text");

$this->objtxtbrowse = $this->newobject('textinput','htmlelements');
$this->objtxtbrowse->textinput("txtexchange","","text");
$this->objtxtbrowse->size = 40;
/*********************************************************************************************************************************************************************/

   $currencyvals  = 'currency';
   $this->objcurrencydropdown  = $this->newObject('dropdown','htmlelements');
   $this->objcurrencydropdown->dropdown($currencyvals);
   $this->objcurrencydropdown->addOption('AED','AED') ;
   $this->objcurrencydropdown->addOption('ALL','ALL') ;
   $this->objcurrencydropdown->addOption('AMD','AMD') ;
   $this->objcurrencydropdown->addOption('ARS','ARS') ;
   $this->objcurrencydropdown->addOption('AUD','AUD') ;
   $this->objcurrencydropdown->addOption('AZM','AZM') ;
   $this->objcurrencydropdown->addOption('BGL','BGL') ;
   $this->objcurrencydropdown->addOption('BHD','BHD') ;
   $this->objcurrencydropdown->addOption('BND','BND') ;
   $this->objcurrencydropdown->addOption('BOB','BOB') ;
   $this->objcurrencydropdown->addOption('BRL','BRL') ;
   $this->objcurrencydropdown->addOption('BYB','BYB') ;
   $this->objcurrencydropdown->addOption('BZD','BZD') ;
   $this->objcurrencydropdown->addOption('CAD','CAD') ;
   $this->objcurrencydropdown->addOption('CHF','CHF') ;
   $this->objcurrencydropdown->addOption('CLP','CLP') ;
   $this->objcurrencydropdown->addOption('BRL','BRL') ;
   $this->objcurrencydropdown->addOption('CNY','CNY') ;
   $this->objcurrencydropdown->addOption('COP','COP') ;
   $this->objcurrencydropdown->addOption('CRC','CRC') ;
   $this->objcurrencydropdown->addOption('CZK','CZK') ;
   $this->objcurrencydropdown->addOption('DKK','DKK') ;
   $this->objcurrencydropdown->addOption('DOP','DOP') ;
   $this->objcurrencydropdown->addOption('DZD','DZD') ;
   $this->objcurrencydropdown->addOption('ECS','ECS') ;
   $this->objcurrencydropdown->addOption('EEK','EEK') ;
   $this->objcurrencydropdown->addOption('EGP','EGP') ;
   $this->objcurrencydropdown->addOption('EUR','EUR') ;
   $this->objcurrencydropdown->addOption('GBP','GBP') ;
   $this->objcurrencydropdown->size = 50;
   

/***********************************************************************************************************************************************************************/   

/*create all button elements*/
$this->objButtonAttach  = $this->newObject('button','htmlelements');
$this->objButtonAttach->setValue($attachfile);
$this->objButtonAttach->setOnClick('alert(\'An onclick Event\')');



$this->objButtonQuote  = $this->newObject('button','htmlelements');
$this->objButtonQuote->setValue($quotesource);
$this->objButtonQuote->setOnClick('alert(\'An onclick Event\')');


$this->objButtonUploadReceipt  = $this->newObject('button','htmlelements');
$this->objButtonUploadReceipt->setValue($uploadbutton);
$this->objButtonUploadReceipt->setOnClick('alert(\'An onclick Event\')');

$btnbrowse  = $this->objLanguage->languageText('word_browse');
$strbrowse = ucfirst($btnbrowse);
$this->objbuttonbrowse  = $this->newObject('button','htmlelements');
$this->objbuttonbrowse->setValue($strbrowse);
    

$this->objButtonCreate  = $this->newObject('button','htmlelements');
$this->objButtonCreate->setValue($create);
$this->objButtonCreate->setOnClick('alert(\'An onclick Event\')');
/*********************************************************************************************************************************************************************/
//$this->objnext->link($this->uri(array('action'=>'createlodging')));
$this->objnext  =& $this->newobject('link','htmlelements');
$this->objnext->link($this->uri(array('action'=>'showclaimantoutput')));
$this->objnext->link = $next;

/*********************************************************************************************************************************************************************/
/*create a table for lodge details*/

        $myTabLodgeheading  = $this->newObject('htmltable','htmlelements');
        $myTabLodgeheading->width='80%';
        $myTabLodgeheading->border='0';
        $myTabLodgeheading->cellspacing = '10';
        $myTabLodgeheading->cellpadding ='10';

        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell($lodgeHint);
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell($lodgeExchangeRate);
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell($lodgeSuggestedExRate);
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell($lodgeExpenditures);
        $myTabLodgeheading->endRow();
/*********************************************************************************************************************************************************************/
/*create a table for lodge details*/

        $myTabLodge  = $this->newObject('htmltable','htmlelements');
        $myTabLodge->width='80%';
        $myTabLodge->border='0';
        $myTabLodge->cellspacing = '10';
        $myTabLodge->cellpadding ='10';

        $myTabLodge->startRow();
        $myTabLodge->addCell($expensesdate);
        $myTabLodge->addCell($this->objlodgedate->show());
        $myTabLodge->endRow();

        $myTabLodge->startRow();
        $myTabLodge->addCell($vendor);
        $myTabLodge->addCell($this->objtxtvendor->show());
        $myTabLodge->endRow();

        $myTabLodge->startRow();
        $myTabLodge->addCell($currency);
        $myTabLodge->addCell($this->objcurrencydropdown->show());
        $myTabLodge->endRow();

        $myTabLodge->startRow();
        $myTabLodge->addCell($cost);
        $myTabLodge->addCell($this->objtxtcost->show());
        $myTabLodge->endRow();

        

/*create table for exchangerate information*/

        $myTabExchange  = $this->newObject('htmltable','htmlelements');
        $myTabExchange->width='80%';
        $myTabExchange->border='0';
        $myTabExchange->cellspacing = '10';
        $myTabExchange->cellpadding ='10';

        $myTabExchange->startRow();
        $myTabExchange->addCell($rate);
        $myTabExchange->addCell($this->objtxtexchange->show());
        $myTabExchange->endRow();

        $myTabExchange->startRow();
        $myTabExchange->addCell(ucfirst($attachfile));
        $myTabExchange->addCell($this->objtxtbrowse->show());
        $myTabExchange->addCell($this->objbuttonbrowse->show());
        $myTabExchange->addCell($this->objButtonAttach->show());
        $myTabExchange->endRow();
        
        $myTabExchange->startRow();
        $myTabExchange->addCell($strquote);
        $myTabExchange->addCell($this->objButtonQuote->show());
        //$myTabExchange->addCell($this->objbuttonbrowse->show());
        //$myTabExchange->addCell($this->objButtonAttach->show());
        $myTabExchange->endRow();


/*create table for exchangerate information*/        

    /** $myTabReceipt  = $this->newObject('htmltable','htmlelements');
        $myTabReceipt->width='20%';
        $myTabReceipt->border='0';
        $myTabReceipt->cellspacing = '10';
        $myTabReceipt->cellpadding ='10';

        $myTabReceipt->startRow();
        $myTabReceipt->addCell($receipt);
        $myTabReceipt->addCell($this->objtxtdate->show());
        $myTabReceipt->endRow();
/*      $myTabReceipt->startRow();
        $myTabReceipt->addCell($rate);
        $myTabReceipt->addCell($this->objtxtdate->show());
        $myTabReceipt->endRow();      */  

$btnSubmit  = $this->objLanguage->languageText('word_submit');
$this->objButtonSubmit  = $this->newObject('button','htmlelements');
$this->objButtonSubmit->setValue($btnSubmit);
$this->objButtonSubmit->setToSubmit();

//$this->objButtonbrowse->setToSubmit();

/*********************************************************************************************************************************************************************/        

/*create tabbox for lodge information*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabbedbox = new tabbedbox();
$objtabbedbox->addTabLabel('Lodge Information');
$objtabbedbox->addBoxContent($myTabLodge->show());



/*create tabbox for attaching lodge echange rate file*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabexchange = new tabbedbox();
$objtabexchange->addTabLabel('Exchange Rate Information');
$objtabexchange->addBoxContent($myTabExchange->show());// . $lblchoice  . '<br>'. "<div align=\"left\">" . $this->objButtonAttach->show() . " " . $this->objButtonQuote->show() . "</div>" );



/*create tabbox for receipt information*/

$this->loadClass('tabbedbox', 'htmlelements');

$objtabreceipt = new tabbedbox();

$objtabreceipt->addTabLabel('Receipt Information');

$objtabreceipt->addBoxContent('<br>'  . $receipt  . '<br>' ."<div align=\"left\">"  .$this->objButtonUploadReceipt->show() . " " . $this->objButtonCreate->show(). "</div>" );

/*********************************************************************************************************************************************************************/

$this->loadClass('form','htmlelements');

$objLodgeForm = new form('lodging',$this->uri(array('action'=>'submitlodgeexpenses')));

$objLodgeForm->displayType = 3;

$objLodgeForm->addToForm($objtabbedbox->show()  .  '<br>' . $objtabexchange->show()  . '<br>'  . $objtabreceipt->show() . '<br>' . $this->objButtonSubmit->show());	

//$objLodgeForm->addRule('txtDate', 'Must be number','required'); 









//display screen content

echo "<div align=\"center\">" . $this->objlodgeHeading->show()  . "</div>";

echo "<div align=\"center\">" .'<br>'  . $myTabLodgeheading->show() . "</div>";

//echo "<div align=\"center\">" .'<br>'  . $lodgeExchangeRate . "</div>";

//echo "<div align=\"center\">" .'<br>'  . $lodgeSuggestedExRate  . "</div>";

//echo "<div align=\"center\">" .'<br>'  . $lodgeExpenditures . "</div>";

/*echo '<br>' . $objtabbedbox->show();

echo  '<br>';

echo  '<br>'  . $objtabexchange->show();

echo  '<br>';

echo  '<br>'  . $objtabreceipt->show();*/

echo  "<div align=\"left\">"  . $objLodgeForm->show() . "</div";

echo  '<br>'  . $this->objnext->show();;



?>

