<?php

/*this template contains the content for creating the initial invoice
//create the heading of the template
/*the heading is created by creating an instance of the heading class
  there after the heading is set to type 2
  and is then assigned the title -- WEB BASED INVOICING SYSTEM*/

$this->objMainheading =& $this->getObject('htmlheading','htmlelements');
$this->objMainheading->type=2;
$this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_webbasedinvoicingsystem','onlineinvoice');

/*create all language elements for labels*/
$dateRange = $objLanguage->languageText('mod_onlineinvoice_whatisthedaterangeofyourinvoice?','onlineinvoice');
$beginDate = $objLanguage->languageText('phrase_begindate');
$endDate  = $objLanguage->languageText('phrase_enddate');
$travelExpenses = $objLanguage->languageText('mod_onlineinvoice_travelexpensesupdate','onlineinvoice');
$btnSubmit  = $this->objLanguage->languageText('word_submit');
$str1 = strtoupper($btnSubmit);
$btnEdit  = $this->objLanguage->languageText('word_edit');
$createTEV  = $this->objLanguage->languageText('phrase_yescreatenewtev');
$nextCategory  = $this->objLanguage->languageText('phrase_nomovetonextcategory');
$error_message = $this->objLanguage->languageText('phrase_dateerror');
$strerror  =  strtoupper($error_message);
$strsucessfull = $this->objLanguage->languageText('mod_onlineinvoice_valuessubmitted','onlineinvoice');
$sucessfull = strtoupper($strsucessfull);

/*********************************************************************************************************************************************************/

/*create a link to move to tev_template*/
/*$this->objTEVlink  =& $this->newobject('link','htmlelements');
$this->objTEVlink->link($this->uri(array('action'=>'createtev')));
$this->objTEVlink->link = $createTEV;*/

$urltext = 'YES - Create TEV';
$content = 'Complete a travel expense voucher';
$caption = '';
$url = $this->uri(array('action'=>'createtev'));
$this->objcreatelink  = & $this->newObject('mouseoverpopup','htmlelements');
$this->objcreatelink->mouseoverpopup($urltext,$content,$caption,$url);

$urltext = 'NO - Move To Next Category';
$content = 'Complete any service expenses';
$caption = '';
$url = $this->uri(array('action'=>'NULL'));
$this->objnextlink  = & $this->newObject('mouseoverpopup','htmlelements');
$this->objnextlink->mouseoverpopup($urltext,$content,$caption,$url);

/*$this->objnextCategorylink  = $this->newobject('link','htmlelements');
$this->objnextCategorylink->link($this->uri(array('action'=>'NULL')));
$this->objnextCategorylink->link = $nextCategory ;*/

/*********************************************************************************************************************************************************/

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

/*********************************************************************************************************************************************************/

/*create all text input boxes */
/*$this->objtxtbegin = $this->newObject('textinput','htmlelements');
$this->objtxtbegin->name   = "txtBeginDate";
$this->objtxtbegin->value  = "";

create an instance of the calendar control
$name = 'txtbegindate';
$mth  = '08';
$day  = '2';
$year = '2006';
$this->objbegindate = $this->newObject('calendar','htmlelements');
$this->objbegindate->caledar($name,$value=null);
$this->objbegindate->setDate($mth,$day,$year);*/

/*create an instance of the datepicker class to create a calendar control*/
$this->objbegindate = $this->newObject('datepicker','htmlelements');
$name = 'txtbegindate';
$date = '2006-01-01';
$format = 'YYYY-MM-DD';
$this->objbegindate->setName($name);
$this->objbegindate->setDefaultDate($date);
$this->objbegindate->setDateFormat($format);

$this->objenddate = $this->newObject('datepicker','htmlelements');
$name = 'txtenddate';
$date = '2006-01-01';
$format = 'YYYY-MM-DD';
$this->objenddate->setName($name);
$this->objenddate->setDefaultDate($date);
$this->objenddate->setDateFormat($format);

/*$this->objtxtEnd = $this->newObject('textinput','htmlelements');
$this->objtxtEnd->name   = "txtEndDate";
$this->objtxtEnd->value  = "";*/
/*********************************************************************************************************************************************************/
/*create all form buttons*/
/*button -submit*/
$this->objButtonSubmit  = $this->newobject('button','htmlelements');
$this->objButtonSubmit->setValue($str1);
$this->objButtonSubmit->setToSubmit();


	$onClick = 'var list_from = document.invoice.txtbegindate;
					    var list_to = document.invoice.txtenddate;
					 
					 
					 
					    var acceptance = true;
					   //value of the begin date
  					 var value_begin = list_from.value;
	   				 //value of the end date
		  			 var value_end = list_to.value;
					 
					 
					 //checks if dates are right
					 if(value_begin > value_end){
					 	acceptance = false;
						
					 }
					 
							 
					 //check final condition
					 if(!acceptance){
					 	alert(\''.$strerror .'\');
						acceptance = true;
						return false;
					 }else{
           alert(\''.$sucessfull.'\')
           }';
				$this->objButtonSubmit->extra = sprintf(' onClick ="javascript: %s"', $onClick );
 
$this->objButtonEdit  = $this->newobject('button','htmlelements');
$this->objButtonEdit->setValue($btnEdit);
//$this->objButton->setOnClick('alert(\'An onclick Event\')');
/*********************************************************************************************************************************************************/ 

/*create table to place form elements in  --  date values*/
        $myTable=$this->newObject('htmltable','htmlelements');
        $myTable->width='40%';
        $myTable->border='0';
        $myTable->cellspacing='1';
        $myTable->cellpadding='10';

        $myTable->startRow();
        $myTable->addCell($this->objBegin->show());
        $myTable->addCell($this->objbegindate->show());
        //$myTable->addCell($this->objtxtbegin->show());
        $myTable->endRow();

        $myTable->startRow();
        $myTable->addCell($this->objEndDate->show());
        $myTable->addCell($this->objenddate->show());
        //$myTable->addCell($this->objtxtEnd->show());
        $myTable->endRow();

        $myTable->startRow();
        $myTable->endRow();

        $myTable->startRow();
        $myTable->endRow();

        $myTable->startRow();
        $myTable->endRow();

        $myTable->startRow();
        $myTable->endRow();

        $myTable->startRow();
        $myTable->addCell($this->objButtonSubmit->show());
        //$myTable->addCell($this->objButtonEdit->show());
        $myTable->endRow();

/*********************************************************************************************************************************************************/        

/*create table to place form elements in  --  create TEV OR move to next category*/        

        $myTab=$this->newObject('htmltable','htmlelements');
        $myTab->width='40%';
        $myTab->border='0';
        $myTab->cellspacing='10';
        $myTab->cellpadding='10';

        $myTab->startRow();
        $myTab->addCell($this->objcreatelink->show());
        $myTab->addCell($this->objnextlink->show());
        $myTab->endRow();

/*********************************************************************************************************************************************************/        

$this->loadClass('form','htmlelements');
$objcreateInvoiceForm = new form('invoice',$this->uri(array('action'=>'submitinvoicedates')));
$objcreateInvoiceForm->displayType = 3;
$objcreateInvoiceForm->addToForm($myTable->show()  .  '<br>' . '<br>' . $this->objtravelExpenses->show()  . '<br>'  . $myTab->show());

/*********************************************************************************************************************************************************/
/*create tabbox for intial invoice*/
$this->loadClass('tabbedbox', 'htmlelements');
$objcreateinvtab = new tabbedbox();
$objcreateinvtab->addTabLabel('Create Invoice');
$objcreateinvtab->addBoxContent('<br>'  . $this->objDateRange->show() . '<br>'. '<br>'  . $objcreateInvoiceForm->show());

/*********************************************************************************************************************************************************/

/*Display output to screen*/
echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
echo  '<br>';
echo  '<br>';
echo  '<br>'  . $objcreateinvtab->show();

?>



