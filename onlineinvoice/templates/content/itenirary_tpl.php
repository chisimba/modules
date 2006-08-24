<?php
  /**
   *create a template to display the the travellers iteninary
   */
   
   
   /**
    *create all languge elements for all form labels
    */
           
    $deptDate  = $this->objLanguage->languageText('phrase_departuredate');
    $deptTime  = $this->objLanguage->languageText('phrase_departuretime');
    $deptCity  = $this->objLanguage->languageText('phrase_departurecity');
    $arrivalDate  = $this->objLanguage->languageText('phrase_arrivaldate');
    $arrivalTime  = $this->objLanguage->languageText('phrase_arrivaltime');
    $arrivalCity  = $this->objLanguage->languageText('phrase_arrivalcity');
    $btnsave  = $this->objLanguage->languageText('word_save');
    $return = $this->objLanguage->languageText('mod_onlineinvoice_returntotravelexpense','onlineinvoice');
/************************************************************************************************************************************************/
  /**
  *create heading -- travel itenirary
  */  
    $this->objIteninary =& $this->newObject('htmlheading','htmlelements');
    $this->objIteninary->type = 3;
    $this->objIteninary->str=$objLanguage->languageText('phrase_traveleritenirary');

/************************************************************************************************************************************************/
   
   /**
    *create all label elements
    */
           
    $lblDeparturedate = $Departuredate;
    $this->objdeparturedate  = $this->newObject('label','htmlelements');
    $this->objdeparturedate->label($deptDate,$lblDeparturedate);

    $lblDeparturetime= $Time;
    $this->objdeparturetime  = $this->newObject('label','htmlelements');
    $this->objdeparturetime->label($deptTime,$lblDeparturetime);

    $lblDeparturecity= $City;
    $this->objdeparturecity  = $this->newObject('label','htmlelements');
    $this->objdeparturecity->label($deptCity,$lblDeparturetime);

    $lblArrivaldate = $Adate;
    $this->objarrivaldate  = $this->newObject('label','htmlelements');
    $this->objarrivaldate->label($arrivalDate,$lblArrivaldate);
  
    $lblArrivaltime= $ATime;
    $this->objarrivaltime  = $this->newObject('label','htmlelements');
    $this->objarrivaltime->label($arrivalTime,$lblArrivaltime);

    $lblArrivalecity  = $ACity;
    $this->objarrivalcity  = $this->newObject('label','htmlelements');
    $this->objarrivalcity->label($arrivalCity,$lblArrivalecity);
/************************************************************************************************************************************************/
  /**
   *  create an instance of the datepicker class
   */
   
    $this->objdeptdate = $this->newObject('datepicker','htmlelements');
    $name = 'txtdeptddate';
    $date = '01-01-2006';
    $format = 'DD-MM-YYYY';
    $this->objdeptdate->setName($name);
    $this->objdeptdate->setDefaultDate($date);
    $this->objdeptdate->setDateFormat($format);

    $this->objarrivaldateobj = $this->newObject('datepicker','htmlelements');
    $name = 'txtarraivaldate';
    $date = '01-01-2006';
    $format = 'DD-MM-YYYY';
    $this->objarrivaldateobj->setName($name);
    $this->objarrivaldateobj->setDefaultDate($date);
    $this->objarrivaldateobj->setDateFormat($format);
/************************************************************************************************************************************************/
  /**
   *create all text inputs 
   */     
   
    $this->objtxtdepttime = $this->newObject('textinput','htmlelements');
    $this->objtxtdepttime->name   = "txtdepttime";
    $this->objtxtdepttime->value  = "";

    $this->objtxtdeptcity = $this->newObject('textinput','htmlelements');
    $this->objtxtdeptcity->name   = "txttxtdeptcity";
    $this->objtxtdeptcity->value  = "";

    $this->objtxtarrivtime = $this->newObject('textinput','htmlelements');
    $this->objtxtarrivtime->name   = "txtarrivtime";
    $this->objtxtarrivtime->value  = "";

    $this->objtxtarrivcity = $this->newObject('textinput','htmlelements');
    $this->objtxtarrivcity->name   = "txtarrivcity";
    $this->objtxtarrivcity->value  = "";
/************************************************************************************************************************************************/  
  /**
   *create all form buttons 
   */
   
    $this->objButtonSave  = $this->newobject('button','htmlelements');
    $this->objButtonSave->setValue($btnsave);
    $this->objButtonSave->setToSubmit();

    
    
    /**
     *create a link to return to the tev emplate
     */         
    
    $this->objreturn  =& $this->newobject('link','htmlelements');
    $this->objreturn->link($this->uri(array('action'=>'createtev'))); /*takes user to the next template -- per diem expense*/
    $this->objreturn->link = $return;
/************************************************************************************************************************************************/   
    /**
     *create table to place all form elements for the itenirary template 
    */
      
        $myTabIten  = $this->newObject('htmltable','htmlelements');
        $myTabIten->width='60%';
        $myTabIten->border='0';
        $myTabIten->cellspacing = '1';
        $myTabIten->cellpadding ='10';

        $myTabIten->startRow();
        $myTabIten->addCell($this->objdeparturedate->show());
        $myTabIten->addCell($this->objdeptdate->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objdeparturetime->show());
        $myTabIten->addCell($this->objtxtdepttime->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objdeparturecity->show());
        $myTabIten->addCell($this->objtxtdeptcity->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objarrivaldate->show());
        $myTabIten->addCell($this->objarrivaldateobj->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objarrivaltime->show());
        $myTabIten->addCell($this->objtxtarrivtime->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objarrivalcity->show());
        $myTabIten->addCell($this->objtxtarrivcity->show());
        $myTabIten->endRow();

        $myTabIten->startRow();
        $myTabIten->addCell($this->objButtonSave->show());
        $myTabIten->addCell($this->objreturn->show());
        $myTabIten->endRow();
        
/************************************************************************************************************************************************/
        
$this->loadClass('tabbedbox', 'htmlelements');
$objonewayitinerary = new tabbedbox();
$objonewayitinerary->addTabLabel('One Way Trip');
$objonewayitinerary->addBoxContent('<br>' . $myTabIten->show() . '<br>');               

        
/************************************************************************************************************************************************/
    /**
     *create the form for a one-way-trip itenirary template
     */         
      
      $this->loadClass('form','htmlelements');
      $objiteniraryForm = new form('lodging',$this->uri(array('action'=>'submititinerary')));
      $objiteniraryForm->displayType = 3;
      $objiteniraryForm->addToForm($objonewayitinerary->show());	
      //$objLodgeForm->addRule('txtDate', 'Must be number','required'); 

/************************************************************************************************************************************************/    

    /**
    *display the form / output
    */

    echo  "<div align=\"center\">" . $this->objIteninary->show() . "</div>";        
    echo  $objiteniraryForm->show();  
?>
