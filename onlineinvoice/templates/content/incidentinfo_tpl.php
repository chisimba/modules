<?php
      /**
       *create a template for incident expenses
       */
       
       /*create template for lodging expenses*/
$this->objlodgeHeading = $this->newObject('htmlheading','htmlelements');
$this->objlodgeHeading->type = 2;
$this->objlodgeHeading->str=$objLanguage->languageText('mod_onlineinvoice_incidentexpense','onlineinvoice');
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
$uploadreceipt = $this->objLanguage->languageText('mod_onlineinvoice_uploadbutton','onlineinvoice');
$create = $this->objLanguage->languageText('mod_onlineinvoice_create','onlineinvoice');
$next = ucfirst($this->objLanguage->languageText('phrase_next'));
$exit = ucfirst($this->objLanguage->languageText('phrase_exit'));
$back = ucfirst($this->objLanguage->languageText('word_back'));

$this->objInfoIcon = $this->newObject('geticon','htmlelements');
$this->objInfoIcon->setModuleIcon('freemind');

/*********************************************************************************************************************************************************************/
$expensesdate = $this->objLanguage->languageText('word_date');
$lblDate = lbldate;
$this->objdate  = $this->newObject('label','htmlelements');
$this->objdate->label($expensesdate,$lblDate);



$this->objlodgedate = $this->newObject('datepicker','htmlelements');
$name = 'txtlodgedate';
$date = date('Y-m-d');
$format = 'YYYY-MM-DD';
$this->objlodgedate->setName($name);
$this->objlodgedate->setDefaultDate($date);
$this->objlodgedate->setDateFormat($format);



$this->objtxtvendor = $this->newobject('textinput','htmlelements');
$this->objtxtvendor->textinput("txtvendor","",'text');

$this->objtxtquotesource  = new textinput('txtquotesource', ' ','text');
$this->objtxtquotesource->id = 'txtquotesource';

$this->objtxtcost  = $this->newobject('textinput','htmlelements');
$this->objtxtcost->textinput("txtcost","","text");

$this->objtxtexchange = $this->newobject('textinput','htmlelements');
$this->objtxtexchange->textinput("txtexchange","","text");

$textArea = 'description';
$this->objdescription = $this->newobject('textArea','htmlelements');
$this->objdescription->setRows(1);
$this->objdescription->setColumns(16);
$this->objdescription->setName($textArea);
$this->objdescription->setContent("");


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
   
/*********************************************************************************************************************************************************************/
$btnSubmit  = $this->objLanguage->languageText('word_save');
$strsubmit  = ucfirst($btnSubmit);
$this->objButtonSubmit  = new button('submit', $strsubmit);
$this->objButtonSubmit->setToSubmit();

$this->loadclass('button','htmlelements');
$this->objnext  = new button('next', $next);
$this->objnext->setToSubmit();

$this->objexit  = new button('exit', $exit);
$this->objexit->setToSubmit();

$this->objBack  = new button('back', $back);
$this->objBack->setToSubmit();

/**********************************************************************************************************************************************************************/
/*create a table for lodge details*/

        $myTabLodgeheading  = $this->newObject('htmltable','htmlelements');
        $myTabLodgeheading->width='100%';
        $myTabLodgeheading->border='0';
        $myTabLodgeheading->cellspacing = '10';
        $myTabLodgeheading->cellpadding ='10';

        $myTabLodgeheading->startRow();
       // $myTabLodgeheading->addCell("<div align=\"left\">" .$lodgeHint. "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">" ."<div class=\"error\">" . $this->objInfoIcon->show()  . $lodgeExchangeRate  . "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">" . '<b />' . $lodgeSuggestedExRate. "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">" . '<b />' .$lodgeExpenditures. "</div>");
        $myTabLodgeheading->endRow();
/*********************************************************************************************************************************************************************/
        $this->loadClass('textinput', 'htmlelements');
        $this->objtxtfilerate = new textinput('upload',' ','FILE');
        $this->objtxtfilerate->id = 'txtfilerate';
        
/*********************************************************************************************************************************************************************/
/*create a table for lodge details*/

        $myTabLodge  = $this->newObject('htmltable','htmlelements');
        $myTabLodge->width='100%';
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
        $myTabLodge->addCell('Description');
        $myTabLodge->addCell($this->objdescription->show());
        $myTabLodge->endRow();
        
        $myTabLodge->startRow();
        $myTabLodge->addCell($currency);
        $myTabLodge->addCell($this->objcurrencydropdown->show());
        $myTabLodge->endRow();
                
        $myTabLodge->startRow();
        $myTabLodge->addCell($cost);
        $myTabLodge->addCell($this->objtxtcost->show());
        $myTabLodge->endRow();
        
        $myTabLodge->startRow();
        $myTabLodge->addCell($rate);
        $myTabLodge->addCell($this->objtxtexchange->show());
        $myTabLodge->endRow();

        
/**********************************************************************************************************************************************************************/
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
/**********************************************************************************************************************************************************************/        


/*create tabbox for lodge information*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabbedbox = new tabbedbox();
$objtabbedbox->addTabLabel('Incident Information');
$objtabbedbox->addBoxContent($myTabLodge->show());

/*create tabbox for attaching lodge echange rate file*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabexchange = new tabbedbox();
$objtabexchange->addTabLabel('Verify Echange Rate');
$objtabexchange->addBoxContent("<div align=\"center\">" ."<div class=\"error\">" .'Verify exchange rate by attachning a file or quote a reliable source' ."</div". "</div". $myTabExchange->show());

/***********************************************************************************************************************************************************************/
$this->loadClass('form','htmlelements');
$objLodgeIncident = new form('incident',$this->uri(array('action'=>'submitincidentinfo')));
$objLodgeIncident->displayType = 3;
$objLodgeIncident->addToForm($objtabbedbox->show()  .  '<br>' . $objtabexchange->show()  . '<br />' .'<br />' . "<div align=\"center\">"  . $this->objBack->show(). $this->objnext->show().' ' . $this->objexit->show() . "</div".'<br />');
$objLodgeIncident->addRule('txtvendor', 'Please enter vendor name','required');
$objLodgeIncident->addRule('description', 'Please enter a brief description of the accident','required');
$objLodgeIncident->addRule('txtcost', 'Please enter cost amount','required');
$objLodgeIncident->addRule('txtcost', 'Please enter a numerical value for cost amount','numeric');
$objLodgeIncident->addRule('txtexchange', 'Please enter exchange rate','required');
$objLodgeIncident->addRule('txtexchange', 'Please enter a numerical value for exchange rate','numeric');
$objLodgeIncident->addRule('upload', 'You need to insert a file','required');
$objLodgeIncident->extra="enctype='multipart/form-data'";

/***********************************************************************************************************************************************************************/
//display screen content
echo "<div align=\"center\">" . $this->objlodgeHeading->show()  . "</div>";
echo "<div align=\"center\">" .'<br>'  . $myTabLodgeheading->show() . "</div>";
echo  "<div align=\"left\">"  . $objLodgeIncident->show() . "</div";
             
?>
