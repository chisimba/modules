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
    
    $tevinfo  = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_tevinfo','onlineinvoice'));
    $example  = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_example','onlineinvoice'));
    $travpurpose  = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_travpurpose','onlineinvoice'));
    $helpstring = $tevinfo . '<br />' . $example . '<br />' . $travpurpose;
    
    $this->objHelp=& $this->getObject('helplink','help');
    $displayhelp  = $this->objHelp->show($helpstring);
    

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
  
  $lblName  = 'lblcname';
  $this->objcname  = $this->newObject('label','htmlelements');
  $this->objcname->setLabel($name);
  $this->objcname->setForId($lblName);

  $lblTitle = 'lbltitle';
  $this->objtitle  = $this->newObject('label','htmlelements');
  $this->objtitle->label($title,$lblTitle);

  $lblAddress = 'lbladdress';
  $this->objaddress  = $this->newObject('label','htmlelements');
  $this->objaddress->label($address,$lblAddress);

  $lblCity = 'lblcity';
  $this->objcity  = $this->newObject('label','htmlelements');
  $this->objcity->label($city,$lblCity);

  $lblProvince = 'lblprovince';
  $this->objprovince  = $this->newObject('label','htmlelements');
  $this->objprovince->label($province,$lblProvince);

  $lblPostalcode = 'lblpostalcode';
  $this->objpostalcode  = $this->newObject('label','htmlelements');
  $this->objpostalcode->label($postalcode,$lblPostalcode);

  $lblCountry = 'lblCountry';
  $this->objCountry  = $this->newObject('label','htmlelements');
  $this->objCountry->label($country,$lblCountry);

  $lblDescription = strtoupper($description);
  $this->objDescription  = $this->newObject('label','htmlelements');
  $this->objDescription->label($description,$lblDescription);

/************************************************************************************************************************************************/

  /**
   *create all text input boxes
   */
   
   //$claimantinfo = array();
   
   $claimantinfo  = $this->getSession('claimantdata');
        $claimantname = '';
        $claimanttitle  = '';
        $claimantaddress = '';
        $claimantcity = '';
        $claimantprovince = '';
        $claimantpostalcode = '';
        $country  = '';
        $purposearea  = '';
        
        
        if(!empty($claimantinfo)){         
              while(list($subkey,$subval) = each($claimantinfo))
              {
                  if($subkey == 'name') {
                 $claimantname = $subval;
                  }
                  if($subkey == 'title') {
                  $claimanttitle = $subval;
                  }
                  if($subkey == 'address') {
                  $claimantaddress = $subval;
                  }
                  if($subkey == 'city') {
                  $claimantcity = $subval;
                  }
                  if($subkey == 'province') {
                  $claimantprovince = $subval;
                  }
                  if($subkey == 'postalcode') {
                  $claimantpostalcode = $subval;
                  }
                  if($subkey == 'country') {
                  $country = $subval;
                  }
                  if($subkey == 'travelpurpose') {
                  $purposearea = $subval;
                  }
              }
          }
 
  $this->loadClass('textinput', 'htmlelements');

  $this->objtxtname = new textinput('txtClaimantName');
  //$this->objtxtname->id = 'txtClaimantName';
  $this->objtxtname->value = $claimantname ;
  
  $this->objtxttitle = new textinput('txtTitle');
  $this->objtxttitle->value  = $claimanttitle ;

  $this->objtxtcity = new textinput('txtCity');
  $this->objtxtcity->value  = $claimantcity;

  $this->objtxtprovince = new textinput('txtprovince');
  $this->objtxtprovince->value  = $claimantprovince;

  $this->objtxtpostalcode = new textinput('txtpostalcode');
  $this->objtxtpostalcode->value  = $claimantpostalcode;

  //$this->objtxtcountry = new textinput('txtcountry');
  //$this->objtxtcountry->value  = $country;

/************************************************************************************************************************************************/
/**
 *coutries
 */ 
    $countryvals  = 'coutryvals';           
   $this->objcountrydropdown  = $this->newObject('dropdown','htmlelements');
   $this->objcountrydropdown->dropdown($countryvals);
   $this->objcountrydropdown->addOption('Afghanistan Afghani','Afghanistan Afghani') ;
   $this->objcountrydropdown->addOption('Albanian Lek','Albanian Lek ') ;
   $this->objcountrydropdown->addOption('Algerian Dinar','Algerian Dinar') ;
   $this->objcountrydropdown->addOption('Andorran Franc','Andorran Franc') ;
   $this->objcountrydropdown->addOption('Andorran Peseta','Andorran Peseta') ;
   $this->objcountrydropdown->addOption('Angolan Kwanza','Angolan Kwanza') ;
   $this->objcountrydropdown->addOption('Angolan New Kwanza','Angolan New Kwanza') ;
   $this->objcountrydropdown->addOption('Argentine Peso','Argentine Peso ') ;
   $this->objcountrydropdown->addOption('Armenian Dram','Armenian Dram') ;
   $this->objcountrydropdown->addOption('Aruban Florin ','Aruban Florin ') ;
   $this->objcountrydropdown->addOption('Australian Dollar','Australian Dollar') ;
   $this->objcountrydropdown->addOption('Azerbaijan Manat','Azerbaijan Manat') ;
   $this->objcountrydropdown->addOption('Austrian Schilling','Austrian Schilling') ;
   $this->objcountrydropdown->addOption('Azerbaijan New Manat','Azerbaijan New Manat') ;
   $this->objcountrydropdown->addOption('Bahamian Dollar','Bahamian Dollar') ;
   $this->objcountrydropdown->addOption('Bahraini Dinar','Bahraini Dinar') ;
   $this->objcountrydropdown->addOption('Bangladeshi Taka','Bangladeshi Taka') ;
   $this->objcountrydropdown->addOption('Barbados Dollar','Barbados Dollar') ;
   $this->objcountrydropdown->addOption('Belarusian Ruble','Belarusian Ruble') ;
   $this->objcountrydropdown->addOption('Belgian Franc','Belgian Franc') ;
   $this->objcountrydropdown->addOption('Belize Dollar','Belize Dollar') ;
   $this->objcountrydropdown->addOption('Bermudian Dollar','Bermudian Dollar') ;
   $this->objcountrydropdown->addOption('Bhutan Ngultrum','Bhutan Ngultrum') ;
   $this->objcountrydropdown->addOption('Bolivian Boliviano','Bolivian Boliviano') ;
   $this->objcountrydropdown->addOption('Bosnian Mark','Bosnian Mark') ;
   $this->objcountrydropdown->addOption('Botswana Pula','Botswana Pula') ;
   $this->objcountrydropdown->addOption('Brazilian Real','Brazilian Real') ;
   $this->objcountrydropdown->addOption('British Pound','British Pound') ;
   $this->objcountrydropdown->addOption('Brunei Dollar','Brunei Dollar') ;
   $this->objcountrydropdown->addOption('Bulgarian Lev','Bulgarian Lev') ;
   $this->objcountrydropdown->addOption('Burundi Franc','Burundi Franc') ;
   $this->objcountrydropdown->addOption('CFA Franc BCEAO','CFA Franc BCEAO') ;
   $this->objcountrydropdown->addOption('CFA Franc BEAC','CFA Franc BEAC') ;
   $this->objcountrydropdown->addOption('CFP Franc','CFP Franc') ;
   $this->objcountrydropdown->addOption('Cambodian Riel','Cambodian Riel') ;
   $this->objcountrydropdown->addOption('Canadian Dollar','Canadian Dollar ') ;
   $this->objcountrydropdown->addOption('Cape Verde Escudo ','Cape Verde Escudo') ;
   $this->objcountrydropdown->addOption('Cayman Islands','Cayman Islands') ;
   $this->objcountrydropdown->addOption('Chilean Peso','Chilean Peso') ;
   $this->objcountrydropdown->addOption('Chinese Yuan Renminbi . CNY','Chinese Yuan Renminbi . CNY') ;
   $this->objcountrydropdown->addOption('Colombian Peso . COP','Colombian Peso . COP') ;
   $this->objcountrydropdown->addOption('Comoros Franc . KMF','Comoros Franc . KMF') ;
   $this->objcountrydropdown->addOption('Congolese Franc . CDF','Congolese Franc . CDF') ;
   $this->objcountrydropdown->addOption('Costa Rican Colon . CRC','Costa Rican Colon . CRC') ;
   $this->objcountrydropdown->addOption('Croatian Kuna . HRK','Croatian Kuna . HRK') ;
   $this->objcountrydropdown->addOption('Cuban Convertible Peso . CUC','Cuban Convertible Peso . CUC') ;
   $this->objcountrydropdown->addOption('Cuban Peso . CUP','Cuban Peso . CUP') ;
   $this->objcountrydropdown->addOption('Cyprus Pound . CYP','Cyprus Pound . CYP') ;
   $this->objcountrydropdown->addOption('Czech Koruna . CZK','Czech Koruna . CZK') ;
   $this->objcountrydropdown->addOption('Danish Krone . DKK','Danish Krone . DKK') ;
   $this->objcountrydropdown->addOption('Djibouti Franc . DJF','Djibouti Franc . DJF') ;
   $this->objcountrydropdown->addOption('Dominican R. Peso . DOP','Dominican R. Peso . DOP') ;
   $this->objcountrydropdown->addOption('Dutch Guilder . NLG','Dutch Guilder . NLG') ;
   $this->objcountrydropdown->addOption('ECU . XEU','ECU . XEU') ;
   $this->objcountrydropdown->addOption('East Caribbean Dollar . XCD','East Caribbean Dollar . XCD') ;
   $this->objcountrydropdown->addOption('Ecuador Sucre . ECS','Ecuador Sucre . ECS') ;
   $this->objcountrydropdown->addOption('Egyptian Pound . EGP','Egyptian Pound . EGP') ;
   $this->objcountrydropdown->addOption('El Salvador Colon . SVC','El Salvador Colon . SVC') ;
   $this->objcountrydropdown->addOption('Estonian Kroon . EEK','Estonian Kroon . EEK') ;
   $this->objcountrydropdown->addOption('Ethiopian Birr . ETB','Ethiopian Birr . ETB') ;
   $this->objcountrydropdown->addOption('Euro . EUR','Euro . EUR') ;
   $this->objcountrydropdown->addOption('Falkland Islands Pound . FKP','Falkland Islands Pound . FKP') ;
   $this->objcountrydropdown->addOption('Fiji Dollar . FJD','Fiji Dollar . FJD') ;
   $this->objcountrydropdown->addOption('Finnish Markka . FIM','Finnish Markka . FIM') ;
   $this->objcountrydropdown->addOption('French Franc . FRF','French Franc . FRF') ;
   $this->objcountrydropdown->addOption('Gambian Dalasi . GMD','Gambian Dalasi . GMD') ;
   $this->objcountrydropdown->addOption('Georgian Lari . GEL','Georgian Lari . GEL') ;
   $this->objcountrydropdown->addOption('German Mark . DEM','German Mark . DEM') ;
   $this->objcountrydropdown->addOption('Ghanaian Cedi . GHC','Ghanaian Cedi . GHC') ;
   $this->objcountrydropdown->addOption('Gibraltar Pound . GIP','Gibraltar Pound . GIP') ;
   $this->objcountrydropdown->addOption('Gold (oz.) . XAU','Gold (oz.) . XAU') ;
   $this->objcountrydropdown->addOption('Greek Drachma . GRD','Greek Drachma . GRD') ;
   $this->objcountrydropdown->addOption('Guatemalan Quetzal . GTQ','Guatemalan Quetzal . GTQ') ;
   $this->objcountrydropdown->addOption('Guinea Franc . GNF','Guinea Franc . GNF') ;
   $this->objcountrydropdown->addOption('Guyanese Dollar . GYD','Guyanese Dollar . GYD') ;
   $this->objcountrydropdown->addOption('Haitian Gourde . HTG','Haitian Gourde . HTG') ;
   $this->objcountrydropdown->addOption('Honduran Lempira . HNL','Honduran Lempira . HNL') ;
   $this->objcountrydropdown->addOption('Hong Kong Dollar . HKD','Hong Kong Dollar . HKD') ;
   $this->objcountrydropdown->addOption('Hungarian Forint . HUF','Hungarian Forint . HUF') ;
   $this->objcountrydropdown->addOption('Iceland Krona . ISK','Iceland Krona . ISK') ;
   $this->objcountrydropdown->addOption('Indian Rupee . INR','Indian Rupee . INR') ;
   $this->objcountrydropdown->addOption('Indonesian Rupiah . IDR','Indonesian Rupiah . IDR') ;
   $this->objcountrydropdown->addOption('Iranian Rial . IRR','Iranian Rial . IRR') ;
   $this->objcountrydropdown->addOption('Iraqi Dinar . IQD','Iraqi Dinar . IQD') ;
   $this->objcountrydropdown->addOption('Irish Punt . IEP','Irish Punt . IEP') ;
   $this->objcountrydropdown->addOption('Israeli New Shekel . ILS','Israeli New Shekel . ILS') ;
   $this->objcountrydropdown->addOption('Italian Lira . ITL','Italian Lira . ITL') ;
   $this->objcountrydropdown->addOption('Jamaican Dollar . JMD','Jamaican Dollar . JMD') ;
   $this->objcountrydropdown->addOption('Japanese Yen . JPY','Japanese Yen . JPY') ;
   $this->objcountrydropdown->addOption('Jordanian Dinar . JOD','Jordanian Dinar . JOD') ;
   $this->objcountrydropdown->addOption('Kazakhstan Tenge . KZT','Kazakhstan Tenge . KZT') ;
   $this->objcountrydropdown->addOption('Kenyan Shilling . KES','Kenyan Shilling . KES') ;
   $this->objcountrydropdown->addOption('Kuwaiti Dinar . KWD','Kuwaiti Dinar . KWD') ;
   $this->objcountrydropdown->addOption('Kyrgyzstanian Som . KGS','Kyrgyzstanian Som . KGS') ;
   $this->objcountrydropdown->addOption('Lao Kip . LAK','Lao Kip . LAK') ;
   $this->objcountrydropdown->addOption('Latvian Lats . LVL','Latvian Lats . LVL') ;
   $this->objcountrydropdown->addOption('Lebanese Pound . LBP','Lebanese Pound . LBP') ;
   $this->objcountrydropdown->addOption('Lesotho Loti . LSL','Lesotho Loti . LSL') ;
   $this->objcountrydropdown->addOption('Liberian Dollar . LRD','Liberian Dollar . LRD') ;
   $this->objcountrydropdown->addOption('Libyan Dinar . LYD','Libyan Dinar . LYD') ;
   $this->objcountrydropdown->addOption('Lithuanian Litas . LTL','Lithuanian Litas . LTL') ;
   $this->objcountrydropdown->addOption('Luxembourg Franc . LUF','Luxembourg Franc . LUF') ;
   $this->objcountrydropdown->addOption('Macau Pataca . MOP','Macau Pataca . MOP') ;
   $this->objcountrydropdown->addOption('Macedonian Denar . MKD','Macedonian Denar . MKD') ;
   $this->objcountrydropdown->addOption('Malagasy Ariary . MGA','Malagasy Ariary . MGA') ;
   $this->objcountrydropdown->addOption('Malagasy Franc . MGF','Malagasy Franc . MGF') ;
   $this->objcountrydropdown->addOption('Malawi Kwacha . MWK','Malawi Kwacha . MWK') ;
   $this->objcountrydropdown->addOption('Malaysian Ringgit . MYRS','Malaysian Ringgit . MYR') ;
   $this->objcountrydropdown->addOption('Maldive Rufiyaa . MVR','Maldive Rufiyaa . MVR') ;
   $this->objcountrydropdown->addOption('Maltese Lira . MTL','Maltese Lira . MTL') ;
   $this->objcountrydropdown->addOption('Mauritanian Ouguiya . MRO','Mauritanian Ouguiya . MRO') ;
   $this->objcountrydropdown->addOption('Mauritius Rupee . MUR','Mauritius Rupee . MUR') ;
   $this->objcountrydropdown->addOption('Mexican Peso . MXN','Mexican Peso . MXN') ;
   $this->objcountrydropdown->addOption('Moldovan Leu . MDL','Moldovan Leu . MDL') ;
   $this->objcountrydropdown->addOption('Mongolian Tugrik . MNT','Mongolian Tugrik . MNT') ;
   $this->objcountrydropdown->addOption('Moroccan Dirham . MAD','Moroccan Dirham . MAD') ;
   $this->objcountrydropdown->addOption('Mozambique Metical . MZM','Mozambique Metical . MZM') ;
   $this->objcountrydropdown->addOption('Mozambique New Metical . MZN','Mozambique New Metical . MZN') ;
   $this->objcountrydropdown->addOption('Myanmar Kyat . MMK','Myanmar Kyat . MMK') ;
   $this->objcountrydropdown->addOption('NL Antillian Guilder . ANG','NL Antillian Guilder . ANG') ;
   $this->objcountrydropdown->addOption('Namibia Dollar . NAD','Namibia Dollar . NAD') ;
   $this->objcountrydropdown->addOption('Nepalese Rupee . NPR','Nepalese Rupee . NPR') ;
   $this->objcountrydropdown->addOption('New Zealand Dollar . NZD','New Zealand Dollar . NZD') ;
   $this->objcountrydropdown->addOption('Nicaraguan Cordoba Oro . NIO','Nicaraguan Cordoba Oro . NIO') ;
   $this->objcountrydropdown->addOption('Nigerian Naira . NGN','Nigerian Naira . NGN') ;
   $this->objcountrydropdown->addOption('North Korean Won . KPW','North Korean Won . KPW') ;
   $this->objcountrydropdown->addOption('Norwegian Kroner . NOK','Norwegian Kroner . NOK') ;
   $this->objcountrydropdown->addOption('Omani Rial . OMR','Omani Rial . OMR') ;
   $this->objcountrydropdown->addOption('Pakistan Rupee . PKR','Pakistan Rupee . PKR') ;
   $this->objcountrydropdown->addOption('Palladium (oz.) . XPD','Palladium (oz.) . XPD') ;
   $this->objcountrydropdown->addOption('Panamanian Balboa . PAB','Panamanian Balboa . PAB') ;
   $this->objcountrydropdown->addOption('Papua New Guinea Kina . PGK','Papua New Guinea Kina . PGK') ;
   $this->objcountrydropdown->addOption('Paraguay Guarani . PYG','Paraguay Guarani . PYG') ;
   $this->objcountrydropdown->addOption('Peruvian Nuevo Sol . PEN','Peruvian Nuevo Sol . PEN') ;
   $this->objcountrydropdown->addOption('Philippine Peso . PHP','Philippine Peso . PHP') ;
   $this->objcountrydropdown->addOption('Platinum (oz.) . XPT','Platinum (oz.) . XPT') ;
   $this->objcountrydropdown->addOption('Polish Zloty . PLN','Polish Zloty . PLN') ;
   $this->objcountrydropdown->addOption('Portuguese Escudo . PTE','Portuguese Escudo . PTE') ;
   $this->objcountrydropdown->addOption('Qatari Rial . QAR','Qatari Rial . QAR') ;
   $this->objcountrydropdown->addOption('Romanian Lei . ROL','Romanian Lei . ROL') ;
   $this->objcountrydropdown->addOption('Romanian New Lei . RON','Romanian New Lei . RON') ;
   $this->objcountrydropdown->addOption('Russian Rouble . RUB','Russian Rouble . RUB') ;
   $this->objcountrydropdown->addOption('Rwandan Franc . RWF','Rwandan Franc . RWF') ;
   $this->objcountrydropdown->addOption('Samoan Tala . WST','Samoan Tala . WST') ;
   $this->objcountrydropdown->addOption('Sao Tome/Principe Dobra . STD','Sao Tome/Principe Dobra . STD') ;
   $this->objcountrydropdown->addOption('Saudi Riyal . SAR','Saudi Riyal . SAR') ;
   $this->objcountrydropdown->addOption('Serbian Dinar . CSD','Serbian Dinar . CSD') ;
   $this->objcountrydropdown->addOption('Seychelles Rupee . SCR','Seychelles Rupee . SCR') ;
   $this->objcountrydropdown->addOption('Sierra Leone Leone . SLL','Sierra Leone Leone . SLL') ;
   $this->objcountrydropdown->addOption('Silver (oz.) . XAG','Silver (oz.) . XAG') ;
   $this->objcountrydropdown->addOption('Singapore Dollar . SGD','Singapore Dollar . SGD') ;
   $this->objcountrydropdown->addOption('Slovak Koruna . SKK','Slovak Koruna . SKK') ;
   $this->objcountrydropdown->addOption('Slovenian Tolar . SIT','Slovenian Tolar . SIT') ;
   $this->objcountrydropdown->addOption('Solomon Islands Dollar . SBD','Solomon Islands Dollar . SBD') ;
   $this->objcountrydropdown->addOption('Somali Shilling . SOS','Somali Shilling . SOS') ;
   $this->objcountrydropdown->addOption('South African Rand . ZAR','South African Rand . ZAR') ;
   $this->objcountrydropdown->addOption('South-Korean Won . KRW','South-Korean Won . KRW') ;
   $this->objcountrydropdown->addOption('Spanish Peseta . ESP','Spanish Peseta . ESP') ;
   $this->objcountrydropdown->addOption('Sri Lanka Rupee . LKR','Sri Lanka Rupee . LKR') ;
   $this->objcountrydropdown->addOption('St. Helena Pound . SHP','St. Helena Pound . SHP') ;
   $this->objcountrydropdown->addOption('Sudanese Dinar . SDD','Sudanese Dinar . SDD') ;
   $this->objcountrydropdown->addOption('Sudanese Pound . SDP','Sudanese Pound . SDP') ;
   $this->objcountrydropdown->addOption('Suriname Dollar . SRD','Suriname Dollar . SRD') ;
   $this->objcountrydropdown->addOption('Suriname Guilder . SRG','Suriname Guilder . SRG') ;
   $this->objcountrydropdown->addOption('Swaziland Lilangeni . SZL','Swaziland Lilangeni . SZL') ;
   $this->objcountrydropdown->addOption('Swedish Krona . SEK','Swedish Krona . SEK') ;
   $this->objcountrydropdown->addOption('Swiss Franc . CHF','Swiss Franc . CHF') ;
   $this->objcountrydropdown->addOption('Syrian Pound . SYP','Syrian Pound . SYP') ;
   $this->objcountrydropdown->addOption('Taiwan Dollar . TWD','Taiwan Dollar . TWD') ;
   $this->objcountrydropdown->addOption('Tanzanian Shilling . TZS','Tanzanian Shilling . TZS') ;
   $this->objcountrydropdown->addOption('Thai Baht . THB','Thai Baht . THB') ;
   $this->objcountrydropdown->addOption('Tonga Paanga . TOP','Tonga Paanga . TOP') ;
   $this->objcountrydropdown->addOption('Trinidad/Tobago Dollar . TTD','Trinidad/Tobago Dollar . TTD') ;
   $this->objcountrydropdown->addOption('Tunisian Dinar . TND','Tunisian Dinar . TND') ;
   $this->objcountrydropdown->addOption('Turkish Lira . TRL','Turkish Lira . TRL') ;
   $this->objcountrydropdown->addOption('Turkish New Lira . TRY','Turkish New Lira . TRY') ;
   $this->objcountrydropdown->addOption('Turkmenistan Manat . TMM','Turkmenistan Manat . TMM') ;
   $this->objcountrydropdown->addOption('Uganda Shilling . UGX','Uganda Shilling . UGX') ;
   $this->objcountrydropdown->addOption('Ukraine Hryvnia . UAH','Ukraine Hryvnia . UAH') ;
   $this->objcountrydropdown->addOption('Uruguayan Peso . UYU','Uruguayan Peso . UYU') ;
   $this->objcountrydropdown->addOption('Utd. Arab Emir. Dirham . AED','Utd. Arab Emir. Dirham . AED') ;
   $this->objcountrydropdown->addOption('Vanuatu Vatu . VUV','Vanuatu Vatu . VUV') ;
   $this->objcountrydropdown->addOption('Venezuelan Bolivar . VEB','Venezuelan Bolivar . VEB') ;
   $this->objcountrydropdown->addOption('Vietnamese Dong . VND','Vietnamese Dong . VND') ;
   $this->objcountrydropdown->addOption('Yemeni Rial . YER','Yemeni Rial . YER') ;
   $this->objcountrydropdown->addOption('Yugoslav Dinar . YUN','Yugoslav Dinar . YUN') ;
   $this->objcountrydropdown->addOption('Zambian Kwacha . ZMK','Zambian Kwacha . ZMK') ;
   $this->objcountrydropdown->addOption('Zimbabwe Dollar . ZWD','Zimbabwe Dollar . ZWD') ;
   $this->objcountrydropdown->addOption('Zimbabwe New Dollar . ZWN','Zimbabwe New Dollar . ZWN') ;
   $this->objcountrydropdown->size = 50;

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
  $this->objPurposeArea->setContent($purposearea);

  $textAreaaddy = 'address';
  $this->objAdressArea = $this->newobject('textArea','htmlelements');
  $this->objAdressArea->setRows(1);
  $this->objAdressArea->setColumns(16);
  $this->objAdressArea->setName($textAreaaddy);
  $this->objAdressArea->setContent($claimantaddress);

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
        $myTable->addCell($this->objcountrydropdown->show());
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
        $myTable->endRow();
        
        $myTable->startRow();
        $myTable->endRow();

        $myTable->startRow();
        $myTable->addCell(" " );
        $myTable->addCell("<div align=\"left\">".$this->objButtonNext->show()."</div>" );
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
  $objtevForm->addRule('travel',$travpurpose,'required');
  
  
/************************************************************************************************************************************************/ 



  /**
   *display output to screen
   */

  echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
  echo  $objtevForm->show();

?>
