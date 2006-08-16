<?php
/*this template contains the content for creating the initial invoice

//create the heading of the template
/*the heading is created by creating an instance of the heading class
  there after the heading is set to type 2
  and is then assigned the title -- WEB BASED INVOICING SYSTEM*/
    
$this->objMainheading =& $this->getObject('htmlheading','htmlelements');
$this->objMainheading->type=2;
$this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_webbasedinvoicingsystem');

/*create all language elements for labels*/
$dateRange = $this->objLanguage->languageText('mod_onlineinvoice_whatisthedaterangeofyourinvoice?');
$beginDate = $this->objLanguage->languageText('phrase_begindate');
$endDate  = $this->objLanguage->languageText('phrase_enddate');
$travelExpenses = $this->objLanguage->languageText('mod_onlineinvoice_doyouhaveanytravelexpensestoupdateatthisstage');
$btnSubmit  = $this->objLanguage->languageText('word_submit');
$btnEdit  = $this->objLanguage->languageText('word_edit');
$createTEV  = $this->objLanguage->languageText('phrase_yescreatenewtev');
$nextCategory  = $this->objLanguage->languageText('phrase_nomovetonextcategory');

/*create a link to move to tev_template*/
$this->objTEVlink  =& $this->newobject('link','htmlelements');
$this->objTEVlink->link($this->uri(array('action'=>'createtev')));
$this->objTEVlink->link = $createTEV;

$this->objnextCategorylink  = $this->newobject('link','htmlelements');
$this->objnextCategorylink->link($this->uri(array('action'=>'NULL')));
$this->objnextCategorylink->link = $nextCategory ;



/*create new instance of the label class for each form label*/
//Label -- What is the date range of your invoice
$lblName  = lbldate;
$this->objDateRange  = $this->newObject('label','htmlelements');
$this->objDateRange->setLabel($dateRange);
$this->objDateRange->setForId($lblName);
//Label --  Begin Date
$lblBName  = lblBegin;
$this->objBegin  = $this->newObject('label','htmlelements');
$this->objBegin->setLabel($beginDate);
$this->objBegin->setForId($lblBName);
//label --  End Date
$lblTravelID = lblEnd;
$this->objEndDate  = $this->newObject('label','htmlelements');
$this->objEndDate->label($endDate,$lblTravelID);
//Label -- Do you have travel expenses to input at this time
$lblTravelID = lblTravel;
$this->objtravelExpenses  = $this->newObject('label','htmlelements');
$this->objtravelExpenses->label($travelExpenses,$lblTravelID);


/*create all text input boxes */
$this->objtxtbegin = $this->newObject('textinput','htmlelements');
$this->objtxtbegin->name   = "txtBeginDate";
$this->objtxtbegin->value  = "";

$this->objtxtEnd = $this->newObject('textinput','htmlelements');
$this->objtxtEnd->name   = "txtEndDate";
$this->objtxtEnd->value  = "";

/*create all form buttons*/
/*button -submit*/
$this->objButtonSubmit  = $this->newobject('button','htmlelements');
$this->objButtonSubmit->setValue($btnSubmit);
$this->objButtonSubmit->setToSubmit();
/*button-edit*/
$this->objButtonEdit  = $this->newobject('button','htmlelements');
$this->objButtonEdit->setValue($btnEdit);
 
/*create table to place form elements in  --  date values*/

        $myTable=$this->newObject('htmltable','htmlelements');
        $myTable->width='40%';
        $myTable->border='0';
        $myTable->cellspacing='1';
        $myTable->cellpadding='10';
            
        
        $myTable->startRow();
        $myTable->addCell($this->objBegin->show());
        $myTable->addCell($this->objtxtbegin->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objEndDate->show());
        $myTable->addCell($this->objtxtEnd->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objButtonSubmit->show());
        $myTable->addCell($this->objButtonEdit->show());
        $myTable->endRow();
        

        
/*create table to place form elements in  --  create TEV OR move to next category*/        
        $myTab=$this->newObject('htmltable','htmlelements');
        $myTab->width='40%';
        $myTab->border='0';
        $myTab->cellspacing='10';
        $myTab->cellpadding='10';
        
        $myTab->startRow();
        $myTab->addCell($this->objTEVlink->show());
        $myTab->addCell($this->objnextCategorylink->show());
        $myTab->endRow();
        
/*create link elements* -- TO DO*/
//$this->objcreateLink =$this->newObject('link','htmlelements');
//$this->objcreateLink->buildLink();
//$this->objcreateLink->show();



/*Display output to screen*/
echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
echo  '<br>';
echo  '<br>'.$this->objDateRange->show(); 
echo  '<br>';
echo  '<br>'."<div align=\"left\">" . $myTable->show() . "</div>";
echo  '<br>';
echo  '<br>'.$this->objtravelExpenses->show();
echo  '<br>';
echo  '<br>'."<div align=\"left\">" . $myTab->show() . "</div>";


?>

