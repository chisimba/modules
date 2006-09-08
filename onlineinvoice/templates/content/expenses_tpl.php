<?php

  /**
   *create template for per diem expenses
   */
  /**
   *create main heading
   */   

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
    $add  = $this->objLanguage->languageText('mod_onlineinvoice_addexpense','onlineinvoice');



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

//$btnsave  = $this->objLanguage->languageText('word_save');
//$strsave = ucfirst($btnsave);
//$this->loadclass('button','htmlelements');
//$this->objButtonSubmit  = new button('saveperdiem', $strsave);
//$this->objButtonSubmit->setToSubmit();

$next  = $this->objLanguage->languageText('phrase_next');
$strnext = ucfirst($next);
$this->loadclass('button','htmlelements');
$this->objButtonNext  = new button('saveperdiem', $strnext);
$this->objButtonNext->setToSubmit();

$exit  = $this->objLanguage->languageText('phrase_exit');
$strexit = ucfirst($exit);
$this->loadclass('button','htmlelements');
$this->objButtonExit  = new button('exitform', $strexit);
$this->objButtonExit->setToSubmit();


/**
 *button to add more expenses
 */
 $btnadd  = $this->objLanguage->LanguageText('mod_onlineinvoice_addperdiem','onlineinvoice');
 $stradd = ucfirst($btnadd);
 $this->objAddperdiem  = new button('addperdiem', $stradd);
 $this->objAddperdiem->setToSubmit();

  

/**************************************************************************************************************/


//create all checkboxes

$this->loadClass('checkbox', 'htmlelements');
$objB = new checkbox('b');
$objB->checkbox('breakfast',$breakfast,$ischecked=true);
$checkbreak= $objB->show();

$this->loadClass('checkbox', 'htmlelements');
$objL = new checkbox('l');
$objL->checkbox('lunch',$lunch,$ischecked=false);
$checklunch= $objL->show();

$this->loadClass('checkbox', 'htmlelements');
$objD = new checkbox('d');
$objL->checkbox('dinner',$dinner,$ischecked=false);
$checkdinner= $objD->show();


$this->objexpensesdate = $this->newObject('datepicker','htmlelements');
$name = 'txtexpensesdate';
$date = '2006-01-01';
$format = 'YYYY-MM-DD';
$this->objexpensesdate->setName($name);
$this->objexpensesdate->setDefaultDate($date);
$this->objexpensesdate->setDateFormat($format);

/************************************************************************************************************/
  /**
   *create all text inputs elements
   */     
$this->objtxtbreakfastloc = $this->newObject('textinput','htmlelements');
$this->objtxtbreakfastloc->name   = "txtbreakfastLocation";
$this->objtxtbreakfastloc->value  = "";

$this->objtxtbreakfastrate = $this->newObject('textinput','htmlelements');
$this->objtxtbreakfastrate->name   = "txtbreakfastRate";
$this->objtxtbreakfastrate->value  = "0.00";

$this->objtxtlunchloc = $this->newObject('textinput','htmlelements');
$this->objtxtlunchloc->name   = "txtlunchLocation";
$this->objtxtlunchloc->value  = "";

$this->objtxtlunchrate = $this->newObject('textinput','htmlelements');
$this->objtxtlunchrate->name   = "txtlunchRate";
$this->objtxtlunchrate->value  = "0.00";

$this->objtxtdinnerloc = $this->newObject('textinput','htmlelements');
$this->objtxtdinnerloc->name   = "txtdinnerLocation";
$this->objtxtdinnerloc->value  = "";

$this->objtxtdinnerrate = $this->newObject('textinput','htmlelements');
$this->objtxtdinnerrate->name   = "txtdinnerRate";
$this->objtxtdinnerrate->value  = "0.00";

//$this->objadditinerary  =& $this->newObject('link','htmlelements');
//$this->objadditinerary->link($this->uri(array('action' =>'createexpenses')));
//$this->objadditinerary->link = $add;
/**************************************************************************************************************/
//create a table to place form LABELS in

        $myTablabel  = $this->newObject('htmltable','htmlelements');
        $myTablabel->width='80%';
        $myTablabel->border='0';
        $myTablabel->cellspacing = '1';
        $myTablabel->cellpadding ='10';

        $myTablabel->startRow();
        $myTablabel->addCell("<div align=\"left\">" .$this->objforeignheading->show() . "</div>");
        $myTablabel->endRow();
        $myTablabel->startRow();
        $myTablabel->addCell("<div align=\"left\">" .$this->objdomesticheading->show() . "</div>");
        $myTablabel->endRow();

/**************************************************************************************************************/

//create a table to place form elements in

        $myTabExpenses  = $this->newObject('htmltable','htmlelements');
        $myTabExpenses->width='20%';
        $myTabExpenses->border='0';
        $myTabExpenses->cellspacing = '10';
        $myTabExpenses->cellpadding ='10';

        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objdate->show());
        $myTabExpenses->addCell($this->objexpensesdate->show());
        $myTabExpenses->endRow();

        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objbreakfast->show());
        $myTabExpenses->addCell($checkbreak);
        $myTabExpenses->addCell($this->objLocation->show());
        $myTabExpenses->addCell($this->objtxtbreakfastloc->show());
        $myTabExpenses->addCell($this->objrate->show());
        $myTabExpenses->addCell($this->objtxtbreakfastrate->show());
        $myTabExpenses->endRow();

        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objlunch->show());
        $myTabExpenses->addCell($checklunch);
        $myTabExpenses->addCell($this->objLocation->show());
        $myTabExpenses->addCell($this->objtxtlunchloc->show());
        $myTabExpenses->addCell($this->objrate->show());
        $myTabExpenses->addCell($this->objtxtlunchrate->show());
        $myTabExpenses->endRow();

        $myTabExpenses->startRow();
        $myTabExpenses->addCell($this->objdinner->show());
        $myTabExpenses->addCell($checkdinner);
        $myTabExpenses->addCell($this->objLocation->show());
        $myTabExpenses->addCell($this->objtxtdinnerloc->show());
        $myTabExpenses->addCell($this->objrate->show());
        $myTabExpenses->addCell($this->objtxtdinnerrate->show());
        //$myTabExpenses->addCell($this->objAddperdiem->show());
        $myTabExpenses->endRow();

        $myTabExpenses->startRow();
        //$myTabExpenses->addCell($this->objButtonSubmit->show());
        $myTabExpenses->addCell('');
        $myTabExpenses->addCell('');
        $myTabExpenses->addCell('');
        $myTabExpenses->addCell('');
        $myTabExpenses->addCell('');
        $myTabExpenses->addCell($this->objAddperdiem->show());       
        $myTabExpenses->endRow();


        /*create a table to place buttons in*/

        $myTabButtons  = $this->newObject('htmltable','htmlelements');
        $myTabButtons->width='20%';
        $myTabButtons->border='0';
        $myTabButtons->cellspacing = '10';
        $myTabButtons->cellpadding ='10';
        
        $myTabButtons->startRow();
        $myTabButtons->addCell($this->objButtonNext->show());
        $myTabButtons->addCell($this->objAddperdiem->show());    

        //$myTabButtons->addCell($this->objButtonEdit->show());
        //$myTabButtons->addCell($this->objButtonAdd->show());
        $myTabButtons->endRow();
    
       // $myTabExpenses->startRow();    
       // $myTabButtons->addCell($this->objadditinerary->show());
       // $myTabButtons->endRow();

/**************************************************************************************************************/        

/*create all links -- exit and next*/

$this->objexit  =& $this->newObject('link','htmlelements');
$this->objexit->link($this->uri(array('action'=>'NULL')));
$this->objexit->link = $exit;

$this->objnext  =& $this->newObject('link','htmlelements');
$this->objnext->link($this->uri(array('action'=>'createlodging')));
$this->objnext->link = $next;


/**************************************************************************************************************/ 
/*create tabbox*/

$this->loadClass('tabbedbox', 'htmlelements');
$objtabbedbox = new tabbedbox();
$objtabbedbox->addTabLabel('Per Diem Expenses');
$objtabbedbox->addBoxContent($myTabExpenses->show(). '<br>' . '<br>'  . "<div align=\"left\">".$this->objButtonExit->show()  . " " .$this->objButtonNext->show().'<br>'.'<br>' . "</div>") ;

/**************************************************************************************************************/

$this->loadClass('form','htmlelements');
$objForm = new form('expenses',$this->uri(array('action'=>'submitexpenses')));
$objForm->displayType = 3;
$objForm->addToForm($objtabbedbox->show());	
//$objForm->addRule('txtDate', 'Must be number','required');        

/**************************************************************************************************************/        

//Display out to the screen

echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
echo  '<br>'  . '<br>';
echo  "<div align=\"right\">" . $myTablabel->show() . "</div>";
//echo  '<br>'  . "<div align=\"center\">" . $this->objdomesticheading->show() . "</div>";
echo  '<br>'  . '<br>'.'<br>';
echo  "<div align=\"left\">"  . $objForm->show() . "</div";




?>

