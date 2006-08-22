<?php
//create tev template

/*create the heading -- Travel expense voucher*/
/*the heading is created by creating an instance of the heading class
  there after the heading is set to type 2
  and is then assigned the title -- travel expense voucher*/
    
$this->objMainheading = $this->newObject('htmlheading','htmlelements');
$this->objMainheading->type = 2;
$this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_travelexpensevoucher','onlineinvoice');

/*create heading -- traveler information*/
$this->objheading =& $this->newObject('htmlheading','htmlelements');
$this->objheading->type = 3;
$this->objheading->str=$objLanguage->languageText('mod_onlineinvoice_travelerinformation','onlineinvoice');

/*create heading -- travel information*/
$this->objTravel =& $this->newObject('htmlheading','htmlelements');
$this->objTravel->type = 3;
$this->objTravel->str=$objLanguage->languageText('mod_onlineinvoice_travelinformation','onlineinvoice');

/*create heading -- travel information*/
$this->objIteninary =& $this->newObject('htmlheading','htmlelements');
$this->objIteninary->type = 3;
$this->objIteninary->str=$objLanguage->languageText('phrase_traveleritenirary');

/************************************************************************************************************************************************/
/*create all language elements for labels*/
$name = $this->objLanguage->languageText('phrase_claimantname');
$title  = $this->objLanguage->languageText('phrase_claimanttitle');
$address  = $this->objLanguage->languageText('phrase_mailingaddress');
$city = $this->objLanguage->languageText('word_city');
$province = $this->objLanguage->languageText('word_province');
$postalcode = $this->objLanguage->languageText('phrase_postalcode');
$country  = $this->objLanguage->languageText('word_country');
$btnSubmit  = $this->objLanguage->languageText('word_submit');
$btnEdit  = $this->objLanguage->languageText('word_edit');
$description = $this->objLanguage->languageText('mod_onlineinvoice_descriptionoftravelpurpose','onlineinvoice'); 
$deptDate  = $this->objLanguage->languageText('phrase_departuredate');
$deptTime  = $this->objLanguage->languageText('phrase_departuretime');
$deptCity  = $this->objLanguage->languageText('phrase_departurecity');
$arrivalDate  = $this->objLanguage->languageText('phrase_arrivaldate');
$arrivalTime  = $this->objLanguage->languageText('phrase_arrivaltime');
$arrivalCity  = $this->objLanguage->languageText('phrase_arrivalcity');
$exit  = $this->objLanguage->languageText('phrase_exit');
$next = $this->objLanguage->languageText('phrase_next');

/************************************************************************************************************************************************/
/*create new instance of the label class for each form label*/
/*Label -- Claimant Name*/
$lblName  = lblcname;
$this->objcname  = $this->newObject('label','htmlelements');
$this->objcname->setLabel($name);
$this->objcname->setForId($lblName);

$lblTitle = lbltitle;
$this->objtitle  = $this->newObject('label','htmlelements');
$this->objtitle->label($title,$lblTitle);

$lblAddress = lbladdress;
$this->objaddress  = $this->newObject('label','htmlelements');
$this->objaddress->label($address,$lblAddress);

$lblCity = lblcity;
$this->objcity  = $this->newObject('label','htmlelements');
$this->objcity->label($city,$lblCity);

$lblProvince = lblprovince;
$this->objprovince  = $this->newObject('label','htmlelements');
$this->objprovince->label($province,$lblProvince);

$lblPostalcode = lblpostalcode;
$this->objpostalcode  = $this->newObject('label','htmlelements');
$this->objpostalcode->label($postalcode,$lblPostalcode);

$lblCountry = $lblCountry;
$this->objCountry  = $this->newObject('label','htmlelements');
$this->objCountry->label($country,$lblCountry);

$lblDescription = $description;
$this->objDescription  = $this->newObject('label','htmlelements');
$this->objDescription->label($description,$lblDescription);

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
/*create all text input boxes */
$this->objtxtname = $this->newObject('textinput','htmlelements');
$this->objtxtname->name   = "txtClaimantName";
$this->objtxtname->value  = "";

$this->objtxttitle = $this->newObject('textinput','htmlelements');
$this->objtxttitle->name   = "txtTitle";
$this->objtxttitle->value  = "";

$this->objtxtaddress = $this->newObject('textinput','htmlelements');
$this->objtxtaddress->name   = "txtAddress";
$this->objtxtaddress->value  = "";

$this->objtxtcity = $this->newObject('textinput','htmlelements');
$this->objtxtcity->name   = "txtCity";
$this->objtxtcity->value  = "";

$this->objtxtprovince = $this->newObject('textinput','htmlelements');
$this->objtxtprovince->name   = "txtprovince";
$this->objtxtprovince->value  = "";

$this->objtxtpostalcode = $this->newObject('textinput','htmlelements');
$this->objtxtpostalcode->name   = "txtpostalcode";
$this->objtxtpostalcode->value  = "";

$this->objtxtcountry = $this->newObject('textinput','htmlelements');
$this->objtxtcountry->name   = "txtcountry";
$this->objtxtcountry->value  = "";

/*create all text inputs for departure and arrival dates*/

/*create an instance of the datepicker class*/
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


/*$this->objtxtdeptdate = $this->newObject('textinput','htmlelements');
$this->objtxtdeptdate->name   = "txtdeptdate";
$this->objtxtdeptdate->value  = "";*/

$this->objtxtdepttime = $this->newObject('textinput','htmlelements');
$this->objtxtdepttime->name   = "txtdepttime";
$this->objtxtdepttime->value  = "";

$this->objtxtdeptcity = $this->newObject('textinput','htmlelements');
$this->objtxtdeptcity->name   = "txttxtdeptcity";
$this->objtxtdeptcity->value  = "";

/*$this->objtxtarrivdate = $this->newObject('textinput','htmlelements');
$this->objtxtarrivdate->name   = "txtarrivdate";
$this->objtxtarrivdate->value  = "";*/

$this->objtxtarrivtime = $this->newObject('textinput','htmlelements');
$this->objtxtarrivtime->name   = "txtarrivtime";
$this->objtxtarrivtime->value  = "";

$this->objtxtarrivcity = $this->newObject('textinput','htmlelements');
$this->objtxtarrivcity->name   = "txtarrivcity";
$this->objtxtarrivcity->value  = "";
/************************************************************************************************************************************************/

/*create form buttons*/
$this->objButtonSubmit  = $this->newobject('button','htmlelements');
$this->objButtonSubmit->setValue($btnSubmit);
$this->objButtonSubmit->setToSubmit();
/*button-edit*/
$this->objButtonEdit  = $this->newobject('button','htmlelements');
$this->objButtonEdit->setValue($btnEdit);
$this->objButtonEdit->setOnClick('alert(\'An onclick Event\')');
/************************************************************************************************************************************************/
/*create links for exit and next page*/
$this->objexit  =& $this->newobject('link','htmlelements');
$this->objexit->link($this->uri(array('action'=>'NULL')));
$this->objexit->link = $exit;
/************************************************************************************************************************************************/
$this->objnext  =& $this->newobject('link','htmlelements');
$this->objnext->link($this->uri(array('action'=>'createexpenses')));
$this->objnext->link = $next;

/************************************************************************************************************************************************/
/*create text are for travel purpose*/
$textArea = 'travel';
$this->objPurposeArea = $this->newobject('textarea','htmlelements');
$this->objPurposeArea->setRows(5);
$this->objPurposeArea->setColumns(30);
$this->objPurposeArea->setName($textArea);
$this->objPurposeArea->setContent("");

/************************************************************************************************************************************************/
/*create table to place form elements in  --  date values*/

        $myTable=$this->newObject('htmltable','htmlelements');
        $myTable->width='60%';
        $myTable->border='0';
        $myTable->cellspacing='1';
        $myTable->cellpadding='10';
            
        $myTable->startRow();
        $myTable->addCell($this->objcname->show());
        $myTable->addCell($this->objtxtname->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objtitle->show());
        $myTable->addCell($this->objtxttitle->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objaddress->show());
        $myTable->addCell($this->objtxtaddress->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objcity->show());
        $myTable->addCell($this->objtxtcity->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objprovince->show());
        $myTable->addCell($this->objtxtprovince->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objpostalcode->show());
        $myTable->addCell($this->objtxtpostalcode->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objCountry->show());
        $myTable->addCell($this->objtxtcountry->show());
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->addCell($this->objButtonSubmit->show());
        $myTable->addCell($this->objButtonEdit->show());
        $myTable->endRow();
        
        /*create table for travel purpose*/
        $myTab=$this->newObject('htmltable','htmlelements');
        $myTab->width='60%';
        $myTab->border='0';
        $myTab->cellspacing='35';
        $myTab->cellpadding='20';
               
         
        $myTab->startRow();
        $myTab->addCell($this->objDescription->show());
        $myTab->addCell($this->objPurposeArea->show());
        $myTab->endRow();
/************************************************************************************************************************************************/        
        /*create table for iteninary*/
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
        $myTabIten->addCell($this->objButtonSubmit->show());
        $myTabIten->addCell($this->objButtonEdit->show());
        $myTabIten->endRow();
        
        $myTabIten->startRow();
        $myTabIten->addCell($this->objexit->show());
        $myTabIten->addCell($this->objnext->show());
        $myTabIten->endRow();
/************************************************************************************************************************************************/
$this->loadClass('form','htmlelements');
$objtevForm = new form('lodging',$this->uri(array('action'=>'submitlodgeexpenses')));
$objtevForm->displayType = 3;
$objtevForm->addToForm($myTable->show()  .  '<br>' . $this->objTravel->show()  . '<br>'  . $myTab->show()  . '<br>'  . '<br>'.$this->objIteninary->show() . $myTabIten->show() );	
//$objLodgeForm->addRule('txtDate', 'Must be number','required'); 
/************************************************************************************************************************************************/ 
/*display output to screen*/
echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
echo  '<br>'.$this->objTravel->show(); 
echo  $objtevForm->show();


?>
