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
  $btnsave  = $this->objLanguage->languageText('word_save');
  $strsave  = ucfirst($btnsave);
  $btnEdit  = $this->objLanguage->languageText('word_edit');
  $description = $this->objLanguage->languageText('mod_onlineinvoice_descriptionoftravelpurpose','onlineinvoice'); 
  $exit  = $this->objLanguage->languageText('phrase_exit');
  $next = $this->objLanguage->languageText('phrase_next');
  $itenirary  = $this->objLanguage->languageText('mod_onlineinvoice_completeitinerary','onlineinvoice');
  $iteniraryM  = $this->objLanguage->languageText('mod_onlineinvoice_completeitinerarym','onlineinvoice');
  $showitenirary = $this->objLanguage->languageText('word_itinerary');
  $showitenirarymulti = $this->objLanguage->languageText('phrase_itinerarymulti');
  $oneway = $this->objLanguage->languageText('phrase_oneway');
  $multidestination = $this->objLanguage->languageText('phrase_multidestination');
  $information  = $this->objLanguage->languageText('mod_onlineinvoice_requiredfields','onlineinvoice');
  $strinfo  = strtoupper($information);
  $itineraryinfo  = $this->objLanguage->languageText('mod_onlineinvoice_itineraryinfo','onlineinvoice');
  $valname  = $this->objLanguage->languageText('mod_onlineinvoice_entername','onlineinvoice');
  $valtitle  = $this->objLanguage->languageText('mod_onlineinvoice_entertitle','onlineinvoice');
  $valaddress = $this->objLanguage->languageText('mod_onlineinvoice_enteraddress','onlineinvoice');
  $valcity  = $this->objLanguage->languageText('mod_onlineinvoice_entercity','onlineinvoice');
  $valprovince  = $this->objLanguage->languageText('mod_onlineinvoice_enterprovince','onlineinvoice');
  $valpostal  = $this->objLanguage->languageText('mod_onlineinvoice_enterpostal','onlineinvoice');
  $valcountry = $this->objLanguage->languageText('mod_onlineinvoice_entercountry','onlineinvoice');
  $travpurpose  = $this->objLanguage->languageText('mod_onlineinvoice_enterpurpose','onlineinvoice');
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
   
   $this->loadClass('textinput', 'htmlelements');

  //$this->objtxtname = $this->newObject('textinput','htmlelements');
  //$this->objtxtname->name   = 'txtClaimantName';
  $this->objtxtname = new textinput('txtClaimantName');
  $this->objtxtname->id = 'txtClaimantName';
  //$this->objtxtname->value  = "";
  //$this->objpopup = $this->newObject('mousepopup','htmlelements');
  //$this->objpopup->mouseoverpopup('name','enter name','enter name',$this->objtxtname); 
  
  $this->objtxttitle = $this->newObject('textinput','htmlelements');
  $this->objtxttitle->name   = 'txtTitle';
  $this->objtxttitle->value  = "";

  $this->objtxtaddress = $this->newObject('textinput','htmlelements');
  $this->objtxtaddress->name   = 'txtAddress';
  $this->objtxtaddress->value  = "";

  $this->objtxtcity = $this->newObject('textinput','htmlelements');
  $this->objtxtcity->name   = 'txtCity';
  $this->objtxtcity->value  = "";

  $this->objtxtprovince = $this->newObject('textinput','htmlelements');
  $this->objtxtprovince->name   = 'txtprovince';
  $this->objtxtprovince->value  = "";

  $this->objtxtpostalcode = $this->newObject('textinput','htmlelements');
  $this->objtxtpostalcode->name   = 'txtpostalcode';
  $this->objtxtpostalcode->value  = "";

  $this->objtxtcountry = $this->newObject('textinput','htmlelements');
  $this->objtxtcountry->name   = 'txtcountry';
  $this->objtxtcountry->value  = "";


/************************************************************************************************************************************************/

  /**
   *create form buttons
   */
  
  $this->objButtonSubmit  = $this->newobject('button','htmlelements');
  $this->objButtonSubmit->setValue($strsave);
  $this->objButtonSubmit->name = 'submit';
  $this->objButtonSubmit->setToSubmit();

  $this->objButtonEdit  = $this->newobject('button','htmlelements');
  $this->objButtonEdit->setValue($btnEdit);
  $this->objButtonEdit->setOnClick('alert(\'An onclick Event\')');

/************************************************************************************************************************************************/

  /**
   *create links for exit and next page
   */

  
/*  $this->objcompleteitenirarymulti  =& $this->newobject('link','htmlelements');
  $strshowitenirary = strtoupper($showitenirary); 
  $this->objcompleteitenirarymulti->link($this->uri(array('action'=>'createmultiitenirary'))); /*shows the intenirary multi template*/
  //$this->objcompleteitenirarymulti->link = $strshowitenirary;/*$showitenirarymulti;*/
  
  $urltext = 'Itinerary';
  $content = 'Complete itinerary for the travel';
  $caption = '';
  $url = $this->uri(array('action'=>'createmultiitenirary'));
  $this->objitinerarylink  = & $this->newObject('mouseoverpopup','htmlelements');
  $this->objitinerarylink->mouseoverpopup($urltext,$content,$caption,$url);

  
/************************************************************************************************************************************************/

  /**
   *create text area for travel purpose
   */

  $textArea = 'travel';
  $this->objPurposeArea = $this->newobject('textArea','htmlelements');
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
        $myTable->width='100%';
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
        

        /*$myTable->startRow();
        $myTable->addCell($itenirary);
        $myTable->addCell($this->objcompleteitenirary->show());
        $myTable->addCell($iteniraryM);
        $myTable->addCell($this->objcompleteitenirarymulti->show());
        $myTable->endRow();*/

        $myTable->startRow();
        $myTable->addCell($this->objButtonSubmit->show());
        $myTable->endRow();
        
/************************************************************************************************************************************************/        
  /**
   *create tabbox for traveler information
   */
//$this->loadclass('navbuttons','htmlelements');
//$objicon = $this->newObject('navbuttons','htmlelements');
//$objicon = new navButtons();
//$objicon->putInfoButton();   

$striconinfo = $information ;//. $objicon->show(); 
$this->loadClass('tabbedbox', 'htmlelements');
$objtraveler = new tabbedbox();
$objtraveler->addTabLabel('Traveler Information');
$objtraveler->addBoxContent('<br>'  . "<div align=\"center\">" .$striconinfo . "</div>" . '<br>'  . $myTable->show()  . '<br>' );

$this->loadClass('tabbedbox', 'htmlelements');
$objitinerary = new tabbedbox();
$objitinerary->addTabLabel('Itinerary');
$objitinerary->addBoxContent('<br>'.'<br>'.$itineraryinfo.'<br>'. ' ' .$this->objitinerarylink->show(). '<br>'  . '<br>'); /* . '<br>'  . $this->objexit->show() . " "  . $this->objnext->show());*/
        //$this->objcompleteitenirary->show() . 
/************************************************************************************************************************************************/
  /**
   *create form to place all elements in
   */
              
  $this->loadClass('form','htmlelements');
  $objtevForm = new form('tev',$this->uri(array('action'=>'submitclaimantinfo')));
  $objtevForm->id = 'tev';
  $objtevForm->displayType = 3;
  $objtevForm->addToForm($objtraveler->show() . '<br>'  . $objitinerary->show());
  $objtevForm->addRule('txtClaimantName',$valname,'required');
  $objtevForm->addRule('txtTitle', $valtitle,'required');
  $objtevForm->addRule('txtAddress',$valaddress,'required');
  $objtevForm->addRule('txtCity',$valcity,'required');
  $objtevForm->addRule('txtprovince',$valprovince,'required');
  $objtevForm->addRule('txtpostalcode',$valpostal,'required');
  $objtevForm->addRule('txtcountry',$valcountry,'required');
  $objtevForm->addRule('travel',$travpurpose,'required');
/************************************************************************************************************************************************/ 

  /**
   *display output to screen
   */

  echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
  echo  $objtevForm->show();





?>

