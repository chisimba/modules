<?php
//create template for per diem expenses




//create main heading
$this->objMainheading = $this->newObject('htmlheading','htmlelements');
$this->objMainheading->type = 2;
$this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_travelperdiemexpenses','onlineinvoice');

$foreignRate  = '<a href=http://www.state.gov/m/a/als/prdm/>www.state.gov/m/a/als/prdm</a>';
$this->objforeignheading = $this->newObject('htmlheading','htmlelements');
$this->objforeignheading->type = 4;
$this->objforeignheading->str=$objLanguage->languageText('mod_onlineinvoice_foreignrateperdiem','onlineinvoice')." " .$foreignRate;

$domesticRate  = '<a href=http://www.gsa.gov/>www.gsa.gov</a>';
$this->objdomesticheading = $this->newObject('htmlheading','htmlelements');
$this->objdomesticheading->type = 4;
$this->objdomesticheading->str=$objLanguage->languageText('mod_onlineinvoice_domesticrateperdiem','onlineinvoice') .' ' .$domesticRate;

$exit  = $this->objLanguage->languageText('phrase_exit');
$next = $this->objLanguage->languageText('phrase_next');

//create all form labesl
/**************************************************************************************************************/
$expensesdate = $this->objLanguage->languageText('word_date');
$lblDate = lbldate;
$this->objdate  = $this->newObject('label','htmlelements');
$this->objdate->label($expensesdate,$lblDate);
/**************************************************************************************************************/
$breakfast  = $this->objLanguage->languageText('word_breakfast');
$lblBreakfast = lblbreakfast;
$this->objbreakfast  = $this->newObject('label','htmlelements');
$this->objbreakfast->label($breakfast,$lblBreakfast);
/**************************************************************************************************************/
$lunch  = $this->objLanguage->languageText('word_lunch');
$lblLunch = lblLunch;
$this->objlunch  = $this->newObject('label','htmlelements');
$this->objlunch->label($lunch,$lblLunch);
/**************************************************************************************************************/
$dinner = $this->objLanguage->languageText('word_dinner');
$lblDinner = lblDinner;
$this->objdinner = $this->newObject('label','htmlelements');
$this->objdinner->label($dinner,$lblDinner);
/**************************************************************************************************************/
$location = $this->objLanguage->languageText('word_location');
$lblLocation = lblLocation;
$this->objLocation = $this->newObject('label','htmlelements');
$this->objLocation->label($location,$lblLocation);
/**************************************************************************************************************/
$rate = $this->objLanguage->languageText('word_rate');
$lblRate = lblRate;
$this->objrate = $this->newObject('label','htmlelements');
$this->objrate->label($rate,$lblRate);
/**************************************************************************************************************/
//create all form buttons
$btnSubmit  = $this->objLanguage->languageText('word_submit');
$this->objButtonSubmit  = $this->newObject('button','htmlelements');
$this->objButtonSubmit->setValue($btnSubmit);
$this->objButtonSubmit->setToSubmit();
/**************************************************************************************************************/
$btnEdit  = $this->objLanguage->languageText('word_edit');
$this->objButtonEdit  = $this->newObject('button','htmlelements');
$this->objButtonEdit->setValue($btnEdit);
$this->objButtonEdit->setOnClick('alert(\'An onclick Event\')');
/**************************************************************************************************************/
$btnAdd  = $this->objLanguage->languageText('word_addanotherday');
$this->objButtonAdd  = $this->newObject('button','htmlelements');
$this->objButtonAdd->setValue($btnAdd);
$this->objButtonAdd->setOnClick('alert(\'An onclick Event\')');
/**************************************************************************************************************/

/*radio button group
$objElement1 = $this->getObject('radio','htmlelements');
$objElement1 = new radio('p');
$objElement1->addOption('b',$this->objbreakfast->show());
$objElement1->addOption('l',$this->objlunch->show());
$objElement1->addOption('s',$this->objdinner->show());
$objElement1->setSelected('b');*/

//create all checkboxes
$this->loadClass('checkbox', 'htmlelements');
$objB = new checkbox('b');
$objB->setLabel($breakfast);
$objB->setChecked(true);
$checkbreak= $objB->show();

$this->loadClass('checkbox', 'htmlelements');
$objL = new checkbox('l');
$objL->setLabel($lunch);
$objB->setChecked(true);
$checklunch= $objL->show();

$this->loadClass('checkbox', 'htmlelements');
$objD = new checkbox('d');
$objD->setLabel($dinner);
$objB->setChecked(true);
$checkdinner= $objD->show();


/*textbox -- date; location; rate*/
/*$this->objtxtdate = $this->newObject('textinput','htmlelements');
$this->objtxtdate->name   = "txtDate";
$this->objtxtdate->value  = "";*/

$this->objexpensesdate = $this->newObject('datepicker','htmlelements');
$name = 'txtexpensesdate';
$date = '01-01-2006';
$format = 'DD-MM-YYYY';
$this->objexpensesdate->setName($name);
$this->objexpensesdate->setDefaultDate($date);
$this->objexpensesdate->setDateFormat($format);

$this->objtxtlocation = $this->newObject('textinput','htmlelements');
$this->objtxtlocation->name   = "txtlunchLocation";
$this->objtxtlocation->value  = "";

$this->objtxtrate = $this->newObject('textinput','htmlelements');
$this->objtxtrate->name   = "txtlunchRate";
$this->objtxtrate->value  = "";

$this->objtxtlocation = $this->newObject('textinput','htmlelements');
$this->objtxtlocation->name   = "txtbreakfastLocation";
$this->objtxtlocation->value  = "";

$this->objtxtrate = $this->newObject('textinput','htmlelements');
$this->objtxtrate->name   = "txtbreakfastRate";
$this->objtxtrate->value  = "";

$this->objtxtlocation = $this->newObject('textinput','htmlelements');
$this->objtxtlocation->name   = "txtdinnerLocation";
$this->objtxtlocation->value  = "";

$this->objtxtrate = $this->newObject('textinput','htmlelements');
$this->objtxtrate->name   = "txtdinnerRate";
$this->objtxtrate->value  = "";


/**************************************************************************************************************/

	
//create a table to place form elements in
        $myTabExpenses  = $this->newObject('htmltable','htmlelements');
        $myTabExpenses->width='20%';
        $myTabExpenses->border='0';
        $myTabExpenses->cellspacing = '10';
        $myTabExpenses->cellpadding ='10';
        
        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objdate->show());
        //$myTabExpenses->addCell($this->objtxtdate->show());
        $myTabExpenses->addCell($this->objexpensesdate->show());
        $myTabExpenses->endRow();

       
        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objbreakfast->show());
        $myTabExpenses->addCell($checkbreak);
        $myTabExpenses->addCell($this->objLocation->show());
        $myTabExpenses->addCell($this->objtxtlocation->show());
        $myTabExpenses->addCell($this->objrate->show());
        $myTabExpenses->addCell($this->objtxtrate->show());
        $myTabExpenses->endRow();
        
        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objlunch->show());
        $myTabExpenses->addCell($checklunch);
        $myTabExpenses->addCell($this->objLocation->show());
        $myTabExpenses->addCell($this->objtxtlocation->show());
        $myTabExpenses->addCell($this->objrate->show());
        $myTabExpenses->addCell($this->objtxtrate->show());
        $myTabExpenses->endRow();
        
        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objdinner->show());
        $myTabExpenses->addCell($checkdinner);
        $myTabExpenses->addCell($this->objLocation->show());
        $myTabExpenses->addCell($this->objtxtlocation->show());
        $myTabExpenses->addCell($this->objrate->show());
        $myTabExpenses->addCell($this->objtxtrate->show());
        $myTabExpenses->endRow();
               

        /*create a table to place buttons in*/
        $myTabButtons  = $this->newObject('htmltable','htmlelements');
        $myTabButtons->width='20%';
        $myTabButtons->border='0';
        $myTabButtons->cellspacing = '10';
        $myTabButtons->cellpadding ='10';
        
        $myTabButtons->addCell($this->objButtonSubmit->show());
        $myTabButtons->addCell($this->objButtonEdit->show());
        $myTabButtons->addCell($this->objButtonAdd->show());
        $myTabButtons->endRow();
/**************************************************************************************************************/        
/*create all links -- exit and next*/
$this->objexit  =& $this->newobject('link','htmlelements');
$this->objexit->link($this->uri(array('action'=>'NULL')));
$this->objexit->link = $exit;

$this->objnext  =& $this->newobject('link','htmlelements');
$this->objnext->link($this->uri(array('action'=>'createlodging')));
$this->objnext->link = $next;
/*create tabbox*/
/*$this->loadClass('tabbedbox', 'htmlelements');
$objtabbedbox = new tabbedbox();
$objtabbedbox->addTabLabel('Breakfast Items');
$objtabbedbox->addBoxContent($myTabExpenses->show());*/
/**************************************************************************************************************/
$this->loadClass('form','htmlelements');
$objForm = new form('expenses',$this->uri(array('action'=>'submitexpenses')));
$objForm->displayType = 3;
$objForm->addToForm($myTabExpenses->show()  . $myTabButtons->show());	
//$objForm->addRule('txtDate', 'Must be number','required');        
/**************************************************************************************************************/        
//Display out to the screen
echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
echo  '<br>'  . '<br>';
echo  "<div align=\"center\">" . $this->objforeignheading->show() . "</div>";
echo  '<br>'  . "<div align=\"center\">" . $this->objdomesticheading->show() . "</div>";
echo  '<br>'  . '<br>'.'<br>';
echo  "<div align=\"left\">"  . $objForm->show() . "</div";
//echo  "<div align=\"left\">"  . $myTabButtons->show()  . "</div";
echo  '<br>'  . $this->objexit->show()  . " " .$this->objnext->show();

?>
