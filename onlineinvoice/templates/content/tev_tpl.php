<?php

  /**
   *create tev template
   */   
/***********************************************************************************************************************************************/   

  /**  create the heading -- Travel expense voucher
   *   the heading is created by creating an instance of the heading class
   *   there after the heading is set to type 2
   *   and is then assigned the title -- travel expense voucher
   */

  $this->objMainheading = $this->newObject('htmlheading','htmlelements');
  $this->objMainheading->type = 1;
  $this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_travelexpensevoucher','onlineinvoice');

  /**
   *create heading -- traveler details
   */
   
  $this->objheading =& $this->newObject('htmlheading','htmlelements');
  $this->objheading->type = 3;
  $this->objheading->str=$objLanguage->languageText('mod_onlineinvoice_travelerinformation','onlineinvoice');

  
  /**
   *getIcon for information
   */     
  $this->objInfoIcon = $this->newObject('geticon','htmlelements');
  $this->objInfoIcon->setModuleIcon('freemind');
    
  /**
    *help information
    */   
    $this->objHelp=& $this->getObject('helplink','help');
    $displayhelp  = $this->objHelp->show('mod_onlineinvoice_helpinstruction');

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
  $strnext  = ucfirst($next);
/************************************************************************************************************************************************/
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

  $this->objtxtname = new textinput('txtClaimantName');
  $this->objtxtname->id = 'txtClaimantName';
  
  $this->objtxttitle = new textinput('txtTitle');
  $this->objtxttitle->value  = "";

  $this->objtxtaddress = new textinput('txtAddress');
  $this->objtxtaddress->value  = "";

  $this->objtxtcity = new textinput('txtCity');
  $this->objtxtcity->value  = "";

  $this->objtxtprovince = new textinput('txtprovince');
  $this->objtxtprovince->value  = "";

  $this->objtxtpostalcode = new textinput('txtpostalcode');
  $this->objtxtpostalcode->value  = "";

  $this->objtxtcountry = new textinput('txtcountry');
  $this->objtxtcountry->value  = "";


/************************************************************************************************************************************************/
  $this->objButtonNext  = $this->newobject('button','htmlelements');
  $this->objButtonNext->setValue($strnext);
  $this->objButtonNext->name = 'next';
  $this->objButtonNext->setToSubmit();

/************************************************************************************************************************************************/

  /**
   *create text area for travel purpose
   */

  $textArea = 'travel';
  $this->objPurposeArea =& $this->newobject('textArea','htmlelements');
  $this->objPurposeArea->setRows(1);
  $this->objPurposeArea->setColumns(16);
  $this->objPurposeArea->setName($textArea);
  $this->objPurposeArea->setContent("");

  $textAreaaddy = 'address';
  $this->objAdressArea = $this->newobject('textArea','htmlelements');
  $this->objAdressArea->setRows(1);
  $this->objAdressArea->setColumns(16);
  $this->objAdressArea->setName($textAreaaddy);
  $this->objAdressArea->setContent("");

/************************************************************************************************************************************************/

  /**
   *create table to place form elements in for travel expense voucher "tev-template"   
   */

        $myTable=& $this->newObject('htmltable','htmlelements');
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
        $myTable->addCell($this->objAdressArea->show());
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
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->endRow();

        $myTable->startRow();
        $myTable->addCell("<div align=\"right\">".$this->objButtonNext->show()."</div>" );
        $myTable->endRow();
        
/************************************************************************************************************************************************/        
   

$striconinfo = $information ; 
$this->loadClass('tabbedbox', 'htmlelements');
$objtraveler = new tabbedbox();
$objtraveler->addTabLabel('Traveler Information');
$objtraveler->addBoxContent('<br />' ."<div align=\"center\">".  "<div class=\"error\">".$this->objInfoIcon->show() .$striconinfo .' '. $displayhelp. "</div>" . "</div>".'<br />'  . $myTable->show()  . '<br/>' );
/************************************************************************************************************************************************/
  /**
   *create form to place all elements in
   *create validation on these fields, required and maxlength   
   */
              
  $this->loadClass('form','htmlelements');
  $objtevForm = new form('tev',$this->uri(array('action'=>'submitclaimantinfo')));
  $objtevForm->id = 'tev';
  $objtevForm->displayType = 3;
  $objtevForm->addToForm($objtraveler->show()); //. '<br>'  . $objitinerary->show());
  $objtevForm->addRule('txtClaimantName',$valname,'required');
  $objtevForm->addRule('txtTitle', $valtitle,'required');
  $objtevForm->addRule('address',$valaddress,'required');
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

