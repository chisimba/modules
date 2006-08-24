<?php

//create tev template


  /**  create the heading -- Travel expense voucher
   *   the heading is created by creating an instance of the heading class
   *   there after the heading is set to type 2
   *   and is then assigned the title -- travel expense voucher
   */

  $this->objMainheading = $this->newObject('htmlheading','htmlelements');
  $this->objMainheading->type = 2;
  $this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_travelexpensevoucher','onlineinvoice');

  /**
   *create heading -- traveler details
   */
   
  $this->objheading =& $this->newObject('htmlheading','htmlelements');
  $this->objheading->type = 3;
  $this->objheading->str=$objLanguage->languageText('mod_onlineinvoice_travelerinformation','onlineinvoice');

  /**
  *create heading -- travel itenirary
  */  
    $this->objIteninary =& $this->newObject('htmlheading','htmlelements');
    $this->objIteninary->type = 3;
    $this->objIteninary->str=$objLanguage->languageText('phrase_traveleritenirary');

/************************************************************************************************************************************************/

  /**
   *create all language elements for labels
   */
  $name = $this->objLanguage->languageText('phrase_claimantname');
  $title  = $this->objLanguage->languageText('phrase_claimanttitle');
  $address  = $this->objLanguage->languageText('phrase_mailingaddress');
  $city = $this->objLanguage->languageText('word_city');
  $province = $this->objLanguage->languageText('word_province');
  $postalcode = $this->objLanguage->languageText('phrase_postalcode');
  $country  = $this->objLanguage->languageText('word_country');
  $btnSave  = $this->objLanguage->languageText('word_save');
  $btnEdit  = $this->objLanguage->languageText('word_edit');
  $description = $this->objLanguage->languageText('mod_onlineinvoice_descriptionoftravelpurpose','onlineinvoice'); 
  $exit  = $this->objLanguage->languageText('phrase_exit');
  $next = $this->objLanguage->languageText('phrase_next');
  $itenirary  = $this->objLanguage->languageText('mod_onlineinvoice_completeitinerary','onlineinvoice');
  $iteniraryM  = $this->objLanguage->languageText('mod_onlineinvoice_completeitinerarym','onlineinvoice');
  $showitenirary = $this->objLanguage->languageText('word_itinerary');
  $showitenirarymulti = $this->objLanguage->languageText('phrase_itinerarymulti');
/************************************************************************************************************************************************/

  /**
   *create new instance of the label class for each form label*
   */

  /**
   *Create all labels-- for Claimant details
   */
  
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

  $lblDescription = strtoupper($description);
  $this->objDescription  = $this->newObject('label','htmlelements');
  $this->objDescription->label($description,$lblDescription);

/************************************************************************************************************************************************/

  /**
   *create all text input boxes
   */

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


/************************************************************************************************************************************************/

  /**
   *create form buttons
   */

  $this->objButtonSubmit  = $this->newobject('button','htmlelements');
  $this->objButtonSubmit->setValue($btnSave);
  $this->objButtonSubmit->setToSubmit();

  $this->objButtonEdit  = $this->newobject('button','htmlelements');
  $this->objButtonEdit->setValue($btnEdit);
  $this->objButtonEdit->setOnClick('alert(\'An onclick Event\')');

/************************************************************************************************************************************************/

  /**
   *create links for exit and next page
   */
  $this->objexit  =& $this->newobject('link','htmlelements');
  $this->objexit->link($this->uri(array('action'=>'NULL')));  /* -- returns banck to original invoice template*/
  $this->objexit->link = $exit;

  $this->objnext  =& $this->newobject('link','htmlelements');
  $this->objnext->link($this->uri(array('action'=>'createexpenses'))); /*takes user to the next template -- per diem expense*/
  $this->objnext->link = $next;

  $this->objcompleteitenirary  =& $this->newobject('link','htmlelements');
  $this->objcompleteitenirary->link($this->uri(array('action'=>'createitenirary'))); /*shows the intenirary template*/
  $this->objcompleteitenirary->link = $showitenirary;
  
  $this->objcompleteitenirarymulti  =& $this->newobject('link','htmlelements');
  $this->objcompleteitenirarymulti->link($this->uri(array('action'=>'createmultiitenirary'))); /*shows the intenirary multi template*/
  $this->objcompleteitenirarymulti->link = $showitenirarymulti;

/************************************************************************************************************************************************/

  /**
   *create text area for travel purpose
   */

  $textArea = 'travel';
  $this->objPurposeArea = $this->newobject('textarea','htmlelements');
  $this->objPurposeArea->setRows(2);
  $this->objPurposeArea->setColumns(30);
  $this->objPurposeArea->setName($textArea);
  $this->objPurposeArea->setContent("");

/************************************************************************************************************************************************/
  /**
   *create radiobuttons for itenirary choice
   */
  
    $this->objradio = $this->newobject('radio','htmlelements');
    $this->objradio->addOption('o','One Way');    
    $this->objradio->addOption('m','Multi Destination -- requires more thatn one destination');
 	  $this->objradio->setSelected('o');    
/************************************************************************************************************************************************/

  /**
   *create table to place form elements in for travel expense voucher "tev-template"   
   */

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
        $myTable->addCell($this->objDescription->show());
        $myTable->addCell($this->objPurposeArea->show());
        $myTable->endRow();
        

        $myTable->startRow();
        $myTable->addCell($itenirary);
        $myTable->addCell($this->objcompleteitenirary->show());
        $myTable->addCell($iteniraryM);
        $myTable->addCell($this->objcompleteitenirarymulti->show());
        $myTable->endRow();

        $myTable->startRow();
        $myTable->addCell($this->objButtonSubmit->show());
        $myTable->endRow();
        
/************************************************************************************************************************************************/        
  /**
   *create tabbox for traveler information
   */

$this->loadClass('tabbedbox', 'htmlelements');
$objtraveler = new tabbedbox();
$objtraveler->addTabLabel('Traveler Information');
$objtraveler->addBoxContent($myTable->show()  . ',<br>' );

$this->loadClass('tabbedbox', 'htmlelements');
$objitinerary = new tabbedbox();
$objitinerary->addTabLabel('Itinerary');
$objitinerary->addBoxContent('<br>'. $this->objradio->show() . '<br>'  . '<br>'  . $this->objexit->show() . " "  . $this->objnext->show());
        
/************************************************************************************************************************************************/
  /**
   *create form to place all elements in
   */
              
  $this->loadClass('form','htmlelements');
  $objtevForm = new form('lodging',$this->uri(array('action'=>'createtev')));
  $objtevForm->displayType = 3;
  $objtevForm->addToForm($objtraveler->show() . '<br>'  . $objitinerary->show());	
  //$objLodgeForm->addRule('txtDate', 'Must be number','required'); 

/************************************************************************************************************************************************/ 

  /**
   *display output to screen
   */

  echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
  /*echo  '<br>'.$this->objheading->show();*/ 
  echo  $objtevForm->show();





?>

