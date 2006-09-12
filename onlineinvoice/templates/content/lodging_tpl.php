<?php

/**
  *create a template for lodge expenses the files
  */
       

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
$expensesdate = $this->objLanguage->languageText('word_date');
$vendor = $this->objLanguage->languageText('word_vendor');
$currency = $this->objLanguage->languageText('word_currency');
$rate = $this->objLanguage->languageText('mod_onlineinvoice_exchangerate','onlineinvoice');
$next = ucfirst($this->objLanguage->languageText('phrase_next'));
$exit = ucfirst($this->objLanguage->languageText('phrase_exit'));
$back = ucfirst($this->objLanguage->languageText('word_back'));
$ratevalue = $this->objLanguage->languageText('word_rate');
$lblchoice  = $this->objLanguage->languageText('mod_onlineinvoice_verifyexchangerate','onlineinvoice');
$addreceipt = $this->objLanguage->languageText('mod_onlineinvoice_addreceipt','onlineinvoice');
$quotesource  = $this->objLanguage->languageText('mod_onlineinvoice_quotesource','onlineinvoice');
$strquote = ucfirst($quotesource);
$receipt  = $this->objLanguage->languageText('mod_onlineinvoice_uploadreceipts','onlineinvoice');
$upload = $this->objLanguage->languageText('mod_onlineinvoice_uploadbutton','onlineinvoice');
$create = $this->objLanguage->languageText('mod_onlineinvoice_create','onlineinvoice');


$this->objInfoIcon = $this->newObject('geticon','htmlelements');
$this->objInfoIcon->setModuleIcon('freemind');
    

/*********************************************************************************************************************************************************************/

$lblDate = lbldate;
$this->objdate  = $this->newObject('label','htmlelements');
$this->objdate->label($expensesdate,$lblDate);


$this->objlodgedate = $this->newObject('datepicker','htmlelements');
$name = 'txtlodgedate';
$date = date('Y-m-d');
$format = 'YYYY-M-DD';
$this->objlodgedate->setName($name);
$this->objlodgedate->setDefaultDate($date);
$this->objlodgedate->setDateFormat($format);

/**
 *create all text input boxes
 */ 

$this->objtxtvendor = $this->newobject('textinput','htmlelements');
$this->objtxtvendor->textinput("txtvendor","",'text');

$this->objtxtcost  = $this->newobject('textinput','htmlelements');
$this->objtxtcost->textinput("txtcost","0.00","text");

$this->objtxtexchange = $this->newobject('textinput','htmlelements');
$this->objtxtexchange->textinput("txtexchange","","text");

$this->loadClass('textinput', 'htmlelements');
$this->objtxtfilerate = new textinput('upload',' ','FILE');
$this->objtxtfilerate->id = 'txtfilereceipt';

$this->loadClass('textinput', 'htmlelements');
$this->objtxtfilereceipt = new textinput('upload',' ','FILE');
$this->objtxtfilereceipt->id = 'txtfilerate';

//$strupload = ucfirst($upload);
$this->objtxtquotesource  = new textinput('txtquotesource', ' ','text');
$this->objtxtquotesource->id = 'txtquotesource';

$this->objtxtcreateaffidavit  = new textinput('txtcreateaffidavit', ' ','text');
$this->objtxtcreateaffidavit->id = 'txtcreateaffidavit';

/*********************************************************************************************************************************************************************/
    /**
     *create a dropdown list to hold all currency values
     */         
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
   $this->objcurrencydropdown->addOption('GEL','GEL') ;
   $this->objcurrencydropdown->addOption('GTQ','GTQ') ;
   $this->objcurrencydropdown->addOption('HKD','HKD') ;
   $this->objcurrencydropdown->addOption('HNL','HNL') ;
   $this->objcurrencydropdown->addOption('HRK','HRK') ;
   $this->objcurrencydropdown->addOption('HUF','HUF') ;
   $this->objcurrencydropdown->addOption('IDR','IDR') ;
   $this->objcurrencydropdown->addOption('ILS','ILS') ;
   $this->objcurrencydropdown->addOption('IQD','IQD') ;
   $this->objcurrencydropdown->addOption('IRR','IRR') ;
   $this->objcurrencydropdown->addOption('ISK','ISK') ;
   $this->objcurrencydropdown->addOption('JMD','JMD') ;
   $this->objcurrencydropdown->addOption('JOD','JOD') ;
   $this->objcurrencydropdown->addOption('JPY','JPY') ;
   $this->objcurrencydropdown->addOption('KES','KES') ;
   $this->objcurrencydropdown->addOption('KRW','KRW') ;
   $this->objcurrencydropdown->addOption('KWD','KWD') ;
   $this->objcurrencydropdown->addOption('KZT','KZT') ;
   $this->objcurrencydropdown->addOption('LBP','LBP') ;
   $this->objcurrencydropdown->addOption('LTL','LTL') ;
   $this->objcurrencydropdown->addOption('LVL','LVL') ;
   $this->objcurrencydropdown->addOption('LYD','LYD') ;
   $this->objcurrencydropdown->addOption('MAD','MAD') ;
   $this->objcurrencydropdown->addOption('MKD','MKD') ;
   $this->objcurrencydropdown->addOption('MDP','MDP') ;
   $this->objcurrencydropdown->addOption('MXN','MXN') ;
   $this->objcurrencydropdown->addOption('NIO','NIO') ;
   $this->objcurrencydropdown->addOption('NOK','NOK') ;
   $this->objcurrencydropdown->addOption('NZD','NZD') ;
   $this->objcurrencydropdown->addOption('OMR','OMR') ;
   $this->objcurrencydropdown->addOption('PAB','PAB') ;
   $this->objcurrencydropdown->addOption('PEN','PEN') ;
   $this->objcurrencydropdown->addOption('PHP','PHP') ;
   $this->objcurrencydropdown->addOption('PKR','PKR') ;
   $this->objcurrencydropdown->addOption('PLN','PLN') ;
   $this->objcurrencydropdown->addOption('PYG','PYG') ;
   $this->objcurrencydropdown->addOption('QAR','QAR') ;
   $this->objcurrencydropdown->addOption('ROL','ROL') ;
   $this->objcurrencydropdown->addOption('RUR','RUR') ;
   $this->objcurrencydropdown->addOption('SAR','SAR') ;
   $this->objcurrencydropdown->addOption('SEK','SEK') ;
   $this->objcurrencydropdown->addOption('SGD','SGD') ;
   $this->objcurrencydropdown->addOption('SIT','SIT') ;
   $this->objcurrencydropdown->addOption('SKK','SKK') ;
   $this->objcurrencydropdown->addOption('SVC','SVC') ;
   $this->objcurrencydropdown->addOption('SYP','SYP') ;
   $this->objcurrencydropdown->addOption('THB','THB') ;
   $this->objcurrencydropdown->addOption('TND','TND') ;
   $this->objcurrencydropdown->addOption('TRL','TRL') ;
   $this->objcurrencydropdown->addOption('TTD','TTD') ;
   $this->objcurrencydropdown->addOption('TRL','TRL') ;
   $this->objcurrencydropdown->addOption('TTD','TTD') ;
   $this->objcurrencydropdown->addOption('TWD','TWD') ;
   $this->objcurrencydropdown->addOption('UAK','UAK') ;
   $this->objcurrencydropdown->addOption('USD','USD') ;
   $this->objcurrencydropdown->addOption('UYU','UYU') ;
   $this->objcurrencydropdown->addOption('UZS','UZS') ;
   $this->objcurrencydropdown->addOption('VEB','VEB') ;
   $this->objcurrencydropdown->addOption('VNP','VNP') ;
   $this->objcurrencydropdown->addOption('YER','YER') ;
   $this->objcurrencydropdown->addOption('YUM','YUM') ;
   $this->objcurrencydropdown->addOption('ZAR','ZAR') ;
   $this->objcurrencydropdown->addOption('ZWD','ZWD') ;
   $this->objcurrencydropdown->size = 50;
   
   

/***********************************************************************************************************************************************************************/   

/*create all button elements*/


$this->loadclass('button','htmlelements');
$this->objnext  = new button('next', $next);
$this->objnext->setToSubmit();

$this->objexit  = new button('exit', $exit);
$this->objexit->setToSubmit();

$this->objBack  = new button('back', $back);
$this->objBack->setToSubmit();


/*$strfile = ucfirst($addreceipt);
$this->objButtonAdd  = new button('addreceipt', $strfile);
$this->objButtonAdd->setToSubmit();

$strquote = ucfirst($quotesource);
$this->objButtonQuote  = new button('quotesource', $strquote);
$this->objButtonQuote->setToSubmit();

$strupload = ucfirst($upload);
$this->objButtonUploadReceipt  = new button('uploadfiles', $strupload);
$this->objButtonUploadReceipt->setToSubmit();

$btnsave  = $this->objLanguage->languageText('word_save');
$strsave  = ucfirst($btnsave);
$this->objButtonSubmit  = new button('submit', $strsave);
$this->objButtonSubmit->setToSubmit();


/*********************************************************************************************************************************************************************/
/**
 *create a table for lodge details  - place all form headings in*/

        $myTabLodgeheading  = $this->newObject('htmltable','htmlelements');
        $myTabLodgeheading->width='100%';
        $myTabLodgeheading->border='0';
        $myTabLodgeheading->cellspacing = '10';
        $myTabLodgeheading->cellpadding ='10';

        $myTabLodgeheading->startRow();
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">" ."<div class=\"error\">" . $this->objInfoIcon->show()  . $lodgeExchangeRate  . "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">"  .'<b />'. $lodgeSuggestedExRate . "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">"  . '<b />'. $lodgeExpenditures  . "</div>");
        $myTabLodgeheading->endRow();
/*********************************************************************************************************************************************************************/
/*create a table for lodge details*/

        $myTabLodge  = $this->newObject('htmltable','htmlelements');
        $myTabLodge->width='70%';
        $myTabLodge->border='0';
        $myTabLodge->cellspacing = '5';
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
        $myTabLodge->addCell($ratevalue);
        $myTabLodge->addCell($this->objtxtcost->show());
        $myTabLodge->endRow();
        
        $myTabLodge->startRow();
        $myTabLodge->addCell($rate);
        $myTabLodge->addCell($this->objtxtexchange->show());
        $myTabLodge->endRow();
        
/*********************************************************************************************************************************************************************/

/*create table for exchangerate information*/
        
        $myTabExchange  = $this->newObject('htmltable','htmlelements');
        $myTabExchange->width='90%';
        $myTabExchange->border='0';
        $myTabExchange->cellspacing = '10';
        $myTabExchange->cellpadding ='10';


        $myTabExchange->startRow();
        $myTabExchange->addCell(ucfirst('Attach File')); 
        $myTabExchange->addCell($this->objtxtfilerate->show());
        $myTabExchange->endRow();
        
        $myTabExchange->startRow();
        $myTabExchange->addCell($strquote);
        $myTabExchange->addCell($this->objtxtquotesource->show());
        $myTabExchange->endRow();


/*create table for receipt information*/        

        $myTabReceipt  = $this->newObject('htmltable','htmlelements');
        $myTabReceipt->width='75%';
        $myTabReceipt->border='0';
        $myTabReceipt->cellspacing = '10';
        $myTabReceipt->cellpadding ='10';
        
        
        $myTabReceipt->startRow();
        $myTabReceipt->addCell('Upload Receipt');
        //$myTabReceipt->addCell($this->objtxtfilereceipt->show());
        $myTabReceipt->endRow();
        
        $myTabReceipt->startRow();
        $myTabReceipt->addCell('Create an Affidavit');
        $myTabReceipt->addCell($this->objtxtcreateaffidavit->show());
        $myTabReceipt->endRow();
        
        $myTabReceipt->startRow();
        $myTabReceipt->addCell(' ');
//        $myTabReceipt->addCell($this->objButtonUploadReceipt->show());
        $myTabReceipt->endRow();
        
        $myTabReceipt->startRow();
        $myTabReceipt->endRow();
        
        

/*********************************************************************************************************************************************************************/        
        

/*create tabbox for lodge information*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabbedbox = new tabbedbox();
$objtabbedbox->addTabLabel('Lodge Information');
$objtabbedbox->addBoxContent($myTabLodge->show());

/*create tabbox for attaching lodge echange rate file*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabexchange = new tabbedbox();
$objtabexchange->addTabLabel('Verify Echange Rate');
$objtabexchange->addBoxContent("<div align=\"center\">" ."<div class=\"error\">" .'Verify exchange rate by attachning a file or quote a reliable source' ."</div". "</div". $myTabExchange->show());

//$objtabreceipt = new tabbedbox();
//$objtabreceipt->addTabLabel('Receipt Information');
//$objtabreceipt->addBoxContent('<br>'  . '<b />' .$receipt  . '<br>' ."<div align=\"left\">"  .$myTabReceipt->show());


/*********************************************************************************************************************************************************************/

$objLodgeForm = new form('lodging',$this->uri(array('action'=>'submitlodgeexpenses')));
$objLodgeForm->displayType = 3;
$objLodgeForm->addToForm($objtabbedbox->show()  . '<br />' . '<br />' .$objtabexchange->show() . '<br />' .'<br />' . "<div align=\"center\">" . $this->objBack->show(). $this->objnext->show() . ' ' . $this->objexit->show()."</div");	
$objLodgeForm->addRule('txtvendor', 'Please enter vendor name','required');
$objLodgeForm->addRule('txtcost', 'Please enter cost amount','required');
$objLodgeForm->addRule('txtcost', 'Please enter a numerical value for cost amount','numeric');
$objLodgeForm->addRule('txtexchange', 'Please enter exchange rate','required');
$objLodgeForm->addRule('upload', 'You have to load a file','required');
$objLodgeForm->addRule('txtexchange', 'Please enter a numerical value for exchange rate','numeric');
$objLodgeForm->extra="enctype='multipart/form-data'";

/*********************************************************************************************************************************************************************/
//display screen content
echo "<div align=\"center\">" . $this->objlodgeHeading->show()  . "</div>";
echo "<div align=\"right\">" .'<br>'  . $myTabLodgeheading->show() . "</div>";
echo  "<div align=\"left\">"  . $objLodgeForm->show() . "</div";





                    
?>
