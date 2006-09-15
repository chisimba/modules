<?php
      /**
       *create a template for incident expenses
       */
       
       /*create template for lodging expenses*/
$this->objlodgeHeading = $this->newObject('htmlheading','htmlelements');
$this->objlodgeHeading->type = 2;
$this->objlodgeHeading->str=$objLanguage->languageText('mod_onlineinvoice_incidentexpense','onlineinvoice');
/*********************************************************************************************************************************************************************/

$exchangeRate  = '<a href=http://www.oanda.com/convert/classic/>www.oanda.com/convert/classic</a>';
$lodgeHint  = $this->objLanguage->languageText('mod_onlineinvoice_pleasemouseover','onlineinvoice');
$lodgeExchangeRate = $this->objLanguage->languageText('mod_onlineinvoice_anyexchangerate','onlineinvoice');
$lodgeSuggestedExRate = $this->objLanguage->languageText('mod_onlineinvoice_suggestedexchangerate','onlineinvoice')  . ' ' . $exchangeRate;
$lodgeExpenditures = $this->objLanguage->languageText('mod_onlineinvoice_itemizedexpenditures','onlineinvoice');
$vendor = $this->objLanguage->languageText('word_vendor');
$currency = $this->objLanguage->languageText('word_currency');
$cost = $this->objLanguage->languageText('word_cost');
$rate = $this->objLanguage->languageText('mod_onlineinvoice_exchangerate','onlineinvoice');  
$lblchoice  = $this->objLanguage->languageText('mod_onlineinvoice_verifyexchangerate','onlineinvoice');
$attachfile = $this->objLanguage->languageText('mod_onlineinvoice_attachfile','onlineinvoice');
$quotesource  = $this->objLanguage->languageText('mod_onlineinvoice_quotesource','onlineinvoice');
$strquote = ucfirst($quotesource);
$receipt  = $this->objLanguage->languageText('mod_onlineinvoice_uploadreceipts','onlineinvoice');
$uploadreceipt = $this->objLanguage->languageText('mod_onlineinvoice_uploadbutton','onlineinvoice');
$create = $this->objLanguage->languageText('mod_onlineinvoice_create','onlineinvoice');
$next = ucfirst($this->objLanguage->languageText('phrase_next'));
$exit = ucfirst($this->objLanguage->languageText('phrase_exit'));
$back = ucfirst($this->objLanguage->languageText('word_back'));

$this->objInfoIcon = $this->newObject('geticon','htmlelements');
$this->objInfoIcon->setModuleIcon('freemind');

/*********************************************************************************************************************************************************************/
$expensesdate = $this->objLanguage->languageText('word_date');
$lblDate = lbldate;
$this->objdate  = $this->newObject('label','htmlelements');
$this->objdate->label($expensesdate,$lblDate);



$this->objlodgedate = $this->newObject('datepicker','htmlelements');
$name = 'txtlodgedate';
$date = date('Y-m-d');
$format = 'YYYY-MM-DD';
$this->objlodgedate->setName($name);
$this->objlodgedate->setDefaultDate($date);
$this->objlodgedate->setDateFormat($format);



$this->objtxtvendor = $this->newobject('textinput','htmlelements');
$this->objtxtvendor->textinput("txtvendor","",'text');

$this->objtxtquotesource  = new textinput('txtquotesource', ' ','text');
$this->objtxtquotesource->id = 'txtquotesource';

$this->objtxtcost  = $this->newobject('textinput','htmlelements');
$this->objtxtcost->textinput("txtcost","","text");

$this->objtxtexchange = $this->newobject('textinput','htmlelements');
$this->objtxtexchange->textinput("txtexchange","","text");

$textArea = 'description';
$this->objdescription = $this->newobject('textArea','htmlelements');
$this->objdescription->setRows(1);
$this->objdescription->setColumns(16);
$this->objdescription->setName($textArea);
$this->objdescription->setContent("");


/*********************************************************************************************************************************************************************/
   $currencyvals  = 'currency';
    $this->objcurrencydropdown  = $this->newObject('dropdown','htmlelements');
   $this->objcurrencydropdown->dropdown($currencyvals);
   $this->objcurrencydropdown->addOption('Afghanistan Afghani . AFA','Afghanistan Afghani . AFA') ;
   $this->objcurrencydropdown->addOption('Albanian Lek . ALL','Albanian Lek . ALL') ;
   $this->objcurrencydropdown->addOption('Algerian Dinar . DZD','Algerian Dinar . DZD') ;
   $this->objcurrencydropdown->addOption('Andorran Franc . ADF','Andorran Franc . ADF') ;
   $this->objcurrencydropdown->addOption('Andorran Peseta . ADP','Andorran Peseta . ADP') ;
   $this->objcurrencydropdown->addOption('Angolan Kwanza . AOA','Angolan Kwanza . AOA') ;
   $this->objcurrencydropdown->addOption('Angolan New Kwanza . AON','Angolan New Kwanza . AON') ;
   $this->objcurrencydropdown->addOption('Argentine Peso . ARS','Argentine Peso . ARS') ;
   $this->objcurrencydropdown->addOption('Armenian Dram . AMD','Armenian Dram . AMD') ;
   $this->objcurrencydropdown->addOption('Aruban Florin . AWG','Aruban Florin . AWG') ;
   $this->objcurrencydropdown->addOption('Australian Dollar . AUD','Australian Dollar . AUD') ;
   $this->objcurrencydropdown->addOption('Azerbaijan Manat . AZM','Azerbaijan Manat . AZM') ;
   $this->objcurrencydropdown->addOption('Austrian Schilling . ATS','Austrian Schilling . ATS') ;
   $this->objcurrencydropdown->addOption('Azerbaijan New Manat . AZN','Azerbaijan New Manat . AZN') ;
   $this->objcurrencydropdown->addOption('Bahamian Dollar . BSD','Bahamian Dollar . BSD') ;
   $this->objcurrencydropdown->addOption('Bahraini Dinar . BHD','Bahraini Dinar . BHD') ;
   $this->objcurrencydropdown->addOption('Bangladeshi Taka . BDT','Bangladeshi Taka . BDT') ;
   $this->objcurrencydropdown->addOption('Barbados Dollar . BBD','Barbados Dollar . BBD') ;
   $this->objcurrencydropdown->addOption('Belarusian Ruble . BYR','Belarusian Ruble . BYR') ;
   $this->objcurrencydropdown->addOption('Belgian Franc . BEF','Belgian Franc . BEF') ;
   $this->objcurrencydropdown->addOption('Belize Dollar . BZD','Belize Dollar . BZD') ;
   $this->objcurrencydropdown->addOption('Bermudian Dollar . BMD','Bermudian Dollar . BMD') ;
   $this->objcurrencydropdown->addOption('Bhutan Ngultrum . BTN','Bhutan Ngultrum . BTN') ;
   $this->objcurrencydropdown->addOption('Bolivian Boliviano . BOB','Bolivian Boliviano . BOB') ;
   $this->objcurrencydropdown->addOption('Bosnian Mark . BAM','Bosnian Mark . BAM') ;
   $this->objcurrencydropdown->addOption('Botswana Pula . BWP','Botswana Pula . BWP') ;
   $this->objcurrencydropdown->addOption('Brazilian Real . BRL','Brazilian Real . BRL') ;
   $this->objcurrencydropdown->addOption('British Pound . GBP','British Pound . GBP') ;
   $this->objcurrencydropdown->addOption('Brunei Dollar . BND','Brunei Dollar . BND') ;
   $this->objcurrencydropdown->addOption('Bulgarian Lev . BGN','Bulgarian Lev . BGN') ;
   $this->objcurrencydropdown->addOption('Burundi Franc . BIF','Burundi Franc . BIF') ;
   $this->objcurrencydropdown->addOption('CFA Franc BCEAO . XOF','CFA Franc BCEAO . XOF') ;
   $this->objcurrencydropdown->addOption('CFA Franc BEAC . XAF','CFA Franc BEAC . XAF') ;
   $this->objcurrencydropdown->addOption('CFP Franc . XPF','CFP Franc . XPF') ;
   $this->objcurrencydropdown->addOption('Cambodian Riel . KHR','Cambodian Riel . KHR') ;
   $this->objcurrencydropdown->addOption('Canadian Dollar . CAD','Canadian Dollar . CAD') ;
   $this->objcurrencydropdown->addOption('Cape Verde Escudo . CVE','Cape Verde Escudo . CVE') ;
   $this->objcurrencydropdown->addOption('Cayman Islands Dollar . KYD','Cayman Islands Dollar . KYD') ;
   $this->objcurrencydropdown->addOption('Chilean Peso . CLP','Chilean Peso . CLP') ;
   $this->objcurrencydropdown->addOption('Chinese Yuan Renminbi . CNY','Chinese Yuan Renminbi . CNY') ;
   $this->objcurrencydropdown->addOption('Colombian Peso . COP','Colombian Peso . COP') ;
   $this->objcurrencydropdown->addOption('Comoros Franc . KMF','Comoros Franc . KMF') ;
   $this->objcurrencydropdown->addOption('Congolese Franc . CDF','Congolese Franc . CDF') ;
   $this->objcurrencydropdown->addOption('Costa Rican Colon . CRC','Costa Rican Colon . CRC') ;
   $this->objcurrencydropdown->addOption('Croatian Kuna . HRK','Croatian Kuna . HRK') ;
   $this->objcurrencydropdown->addOption('Cuban Convertible Peso . CUC','Cuban Convertible Peso . CUC') ;
   $this->objcurrencydropdown->addOption('Cuban Peso . CUP','Cuban Peso . CUP') ;
   $this->objcurrencydropdown->addOption('Cyprus Pound . CYP','Cyprus Pound . CYP') ;
   $this->objcurrencydropdown->addOption('Czech Koruna . CZK','Czech Koruna . CZK') ;
   $this->objcurrencydropdown->addOption('Danish Krone . DKK','Danish Krone . DKK') ;
   $this->objcurrencydropdown->addOption('Djibouti Franc . DJF','Djibouti Franc . DJF') ;
   $this->objcurrencydropdown->addOption('Dominican R. Peso . DOP','Dominican R. Peso . DOP') ;
   $this->objcurrencydropdown->addOption('Dutch Guilder . NLG','Dutch Guilder . NLG') ;
   $this->objcurrencydropdown->addOption('ECU . XEU','ECU . XEU') ;
   $this->objcurrencydropdown->addOption('East Caribbean Dollar . XCD','East Caribbean Dollar . XCD') ;
   $this->objcurrencydropdown->addOption('Ecuador Sucre . ECS','Ecuador Sucre . ECS') ;
   $this->objcurrencydropdown->addOption('Egyptian Pound . EGP','Egyptian Pound . EGP') ;
   $this->objcurrencydropdown->addOption('El Salvador Colon . SVC','El Salvador Colon . SVC') ;
   $this->objcurrencydropdown->addOption('Estonian Kroon . EEK','Estonian Kroon . EEK') ;
   $this->objcurrencydropdown->addOption('Ethiopian Birr . ETB','Ethiopian Birr . ETB') ;
   $this->objcurrencydropdown->addOption('Euro . EUR','Euro . EUR') ;
   $this->objcurrencydropdown->addOption('Falkland Islands Pound . FKP','Falkland Islands Pound . FKP') ;
   $this->objcurrencydropdown->addOption('Fiji Dollar . FJD','Fiji Dollar . FJD') ;
   $this->objcurrencydropdown->addOption('Finnish Markka . FIM','Finnish Markka . FIM') ;
   $this->objcurrencydropdown->addOption('French Franc . FRF','French Franc . FRF') ;
   $this->objcurrencydropdown->addOption('Gambian Dalasi . GMD','Gambian Dalasi . GMD') ;
   $this->objcurrencydropdown->addOption('Georgian Lari . GEL','Georgian Lari . GEL') ;
   $this->objcurrencydropdown->addOption('German Mark . DEM','German Mark . DEM') ;
   $this->objcurrencydropdown->addOption('Ghanaian Cedi . GHC','Ghanaian Cedi . GHC') ;
   $this->objcurrencydropdown->addOption('Gibraltar Pound . GIP','Gibraltar Pound . GIP') ;
   $this->objcurrencydropdown->addOption('Gold (oz.) . XAU','Gold (oz.) . XAU') ;
   $this->objcurrencydropdown->addOption('Greek Drachma . GRD','Greek Drachma . GRD') ;
   $this->objcurrencydropdown->addOption('Guatemalan Quetzal . GTQ','Guatemalan Quetzal . GTQ') ;
   $this->objcurrencydropdown->addOption('Guinea Franc . GNF','Guinea Franc . GNF') ;
   $this->objcurrencydropdown->addOption('Guyanese Dollar . GYD','Guyanese Dollar . GYD') ;
   $this->objcurrencydropdown->addOption('Haitian Gourde . HTG','Haitian Gourde . HTG') ;
   $this->objcurrencydropdown->addOption('Honduran Lempira . HNL','Honduran Lempira . HNL') ;
   $this->objcurrencydropdown->addOption('Hong Kong Dollar . HKD','Hong Kong Dollar . HKD') ;
   $this->objcurrencydropdown->addOption('Hungarian Forint . HUF','Hungarian Forint . HUF') ;
   $this->objcurrencydropdown->addOption('Iceland Krona . ISK','Iceland Krona . ISK') ;
   $this->objcurrencydropdown->addOption('Indian Rupee . INR','Indian Rupee . INR') ;
   $this->objcurrencydropdown->addOption('Indonesian Rupiah . IDR','Indonesian Rupiah . IDR') ;
   $this->objcurrencydropdown->addOption('Iranian Rial . IRR','Iranian Rial . IRR') ;
   $this->objcurrencydropdown->addOption('Iraqi Dinar . IQD','Iraqi Dinar . IQD') ;
   $this->objcurrencydropdown->addOption('Irish Punt . IEP','Irish Punt . IEP') ;
   $this->objcurrencydropdown->addOption('Israeli New Shekel . ILS','Israeli New Shekel . ILS') ;
   $this->objcurrencydropdown->addOption('Italian Lira . ITL','Italian Lira . ITL') ;
   $this->objcurrencydropdown->addOption('Jamaican Dollar . JMD','Jamaican Dollar . JMD') ;
   $this->objcurrencydropdown->addOption('Japanese Yen . JPY','Japanese Yen . JPY') ;
   $this->objcurrencydropdown->addOption('Jordanian Dinar . JOD','Jordanian Dinar . JOD') ;
   $this->objcurrencydropdown->addOption('Kazakhstan Tenge . KZT','Kazakhstan Tenge . KZT') ;
   $this->objcurrencydropdown->addOption('Kenyan Shilling . KES','Kenyan Shilling . KES') ;
   $this->objcurrencydropdown->addOption('Kuwaiti Dinar . KWD','Kuwaiti Dinar . KWD') ;
   $this->objcurrencydropdown->addOption('Kyrgyzstanian Som . KGS','Kyrgyzstanian Som . KGS') ;
   $this->objcurrencydropdown->addOption('Lao Kip . LAK','Lao Kip . LAK') ;
   $this->objcurrencydropdown->addOption('Latvian Lats . LVL','Latvian Lats . LVL') ;
   $this->objcurrencydropdown->addOption('Lebanese Pound . LBP','Lebanese Pound . LBP') ;
   $this->objcurrencydropdown->addOption('Lesotho Loti . LSL','Lesotho Loti . LSL') ;
   $this->objcurrencydropdown->addOption('Liberian Dollar . LRD','Liberian Dollar . LRD') ;
   $this->objcurrencydropdown->addOption('Libyan Dinar . LYD','Libyan Dinar . LYD') ;
   $this->objcurrencydropdown->addOption('Lithuanian Litas . LTL','Lithuanian Litas . LTL') ;
   $this->objcurrencydropdown->addOption('Luxembourg Franc . LUF','Luxembourg Franc . LUF') ;
   $this->objcurrencydropdown->addOption('Macau Pataca . MOP','Macau Pataca . MOP') ;
   $this->objcurrencydropdown->addOption('Macedonian Denar . MKD','Macedonian Denar . MKD') ;
   $this->objcurrencydropdown->addOption('Malagasy Ariary . MGA','Malagasy Ariary . MGA') ;
   $this->objcurrencydropdown->addOption('Malagasy Franc . MGF','Malagasy Franc . MGF') ;
   $this->objcurrencydropdown->addOption('Malawi Kwacha . MWK','Malawi Kwacha . MWK') ;
   $this->objcurrencydropdown->addOption('Malaysian Ringgit . MYRS','Malaysian Ringgit . MYR') ;
   $this->objcurrencydropdown->addOption('Maldive Rufiyaa . MVR','Maldive Rufiyaa . MVR') ;
   $this->objcurrencydropdown->addOption('Maltese Lira . MTL','Maltese Lira . MTL') ;
   $this->objcurrencydropdown->addOption('Mauritanian Ouguiya . MRO','Mauritanian Ouguiya . MRO') ;
   $this->objcurrencydropdown->addOption('Mauritius Rupee . MUR','Mauritius Rupee . MUR') ;
   $this->objcurrencydropdown->addOption('Mexican Peso . MXN','Mexican Peso . MXN') ;
   $this->objcurrencydropdown->addOption('Moldovan Leu . MDL','Moldovan Leu . MDL') ;
   $this->objcurrencydropdown->addOption('Mongolian Tugrik . MNT','Mongolian Tugrik . MNT') ;
   $this->objcurrencydropdown->addOption('Moroccan Dirham . MAD','Moroccan Dirham . MAD') ;
   $this->objcurrencydropdown->addOption('Mozambique Metical . MZM','Mozambique Metical . MZM') ;
   $this->objcurrencydropdown->addOption('Mozambique New Metical . MZN','Mozambique New Metical . MZN') ;
   $this->objcurrencydropdown->addOption('Myanmar Kyat . MMK','Myanmar Kyat . MMK') ;
   $this->objcurrencydropdown->addOption('NL Antillian Guilder . ANG','NL Antillian Guilder . ANG') ;
   $this->objcurrencydropdown->addOption('Namibia Dollar . NAD','Namibia Dollar . NAD') ;
   $this->objcurrencydropdown->addOption('Nepalese Rupee . NPR','Nepalese Rupee . NPR') ;
   $this->objcurrencydropdown->addOption('New Zealand Dollar . NZD','New Zealand Dollar . NZD') ;
   $this->objcurrencydropdown->addOption('Nicaraguan Cordoba Oro . NIO','Nicaraguan Cordoba Oro . NIO') ;
   $this->objcurrencydropdown->addOption('Nigerian Naira . NGN','Nigerian Naira . NGN') ;
   $this->objcurrencydropdown->addOption('North Korean Won . KPW','North Korean Won . KPW') ;
   $this->objcurrencydropdown->addOption('Norwegian Kroner . NOK','Norwegian Kroner . NOK') ;
   $this->objcurrencydropdown->addOption('Omani Rial . OMR','Omani Rial . OMR') ;
   $this->objcurrencydropdown->addOption('Pakistan Rupee . PKR','Pakistan Rupee . PKR') ;
   $this->objcurrencydropdown->addOption('Palladium (oz.) . XPD','Palladium (oz.) . XPD') ;
   $this->objcurrencydropdown->addOption('Panamanian Balboa . PAB','Panamanian Balboa . PAB') ;
   $this->objcurrencydropdown->addOption('Papua New Guinea Kina . PGK','Papua New Guinea Kina . PGK') ;
   $this->objcurrencydropdown->addOption('Paraguay Guarani . PYG','Paraguay Guarani . PYG') ;
   $this->objcurrencydropdown->addOption('Peruvian Nuevo Sol . PEN','Peruvian Nuevo Sol . PEN') ;
   $this->objcurrencydropdown->addOption('Philippine Peso . PHP','Philippine Peso . PHP') ;
   $this->objcurrencydropdown->addOption('Platinum (oz.) . XPT','Platinum (oz.) . XPT') ;
   $this->objcurrencydropdown->addOption('Polish Zloty . PLN','Polish Zloty . PLN') ;
   $this->objcurrencydropdown->addOption('Portuguese Escudo . PTE','Portuguese Escudo . PTE') ;
   $this->objcurrencydropdown->addOption('Qatari Rial . QAR','Qatari Rial . QAR') ;
   $this->objcurrencydropdown->addOption('Romanian Lei . ROL','Romanian Lei . ROL') ;
   $this->objcurrencydropdown->addOption('Romanian New Lei . RON','Romanian New Lei . RON') ;
   $this->objcurrencydropdown->addOption('Russian Rouble . RUB','Russian Rouble . RUB') ;
   $this->objcurrencydropdown->addOption('Rwandan Franc . RWF','Rwandan Franc . RWF') ;
   $this->objcurrencydropdown->addOption('Samoan Tala . WST','Samoan Tala . WST') ;
   $this->objcurrencydropdown->addOption('Sao Tome/Principe Dobra . STD','Sao Tome/Principe Dobra . STD') ;
   $this->objcurrencydropdown->addOption('Saudi Riyal . SAR','Saudi Riyal . SAR') ;
   $this->objcurrencydropdown->addOption('Serbian Dinar . CSD','Serbian Dinar . CSD') ;
   $this->objcurrencydropdown->addOption('Seychelles Rupee . SCR','Seychelles Rupee . SCR') ;
   $this->objcurrencydropdown->addOption('Sierra Leone Leone . SLL','Sierra Leone Leone . SLL') ;
   $this->objcurrencydropdown->addOption('Silver (oz.) . XAG','Silver (oz.) . XAG') ;
   $this->objcurrencydropdown->addOption('Singapore Dollar . SGD','Singapore Dollar . SGD') ;
   $this->objcurrencydropdown->addOption('Slovak Koruna . SKK','Slovak Koruna . SKK') ;
   $this->objcurrencydropdown->addOption('Slovenian Tolar . SIT','Slovenian Tolar . SIT') ;
   $this->objcurrencydropdown->addOption('Solomon Islands Dollar . SBD','Solomon Islands Dollar . SBD') ;
   $this->objcurrencydropdown->addOption('Somali Shilling . SOS','Somali Shilling . SOS') ;
   $this->objcurrencydropdown->addOption('South African Rand . ZAR','South African Rand . ZAR') ;
   $this->objcurrencydropdown->addOption('South-Korean Won . KRW','South-Korean Won . KRW') ;
   $this->objcurrencydropdown->addOption('Spanish Peseta . ESP','Spanish Peseta . ESP') ;
   $this->objcurrencydropdown->addOption('Sri Lanka Rupee . LKR','Sri Lanka Rupee . LKR') ;
   $this->objcurrencydropdown->addOption('St. Helena Pound . SHP','St. Helena Pound . SHP') ;
   $this->objcurrencydropdown->addOption('Sudanese Dinar . SDD','Sudanese Dinar . SDD') ;
   $this->objcurrencydropdown->addOption('Sudanese Pound . SDP','Sudanese Pound . SDP') ;
   $this->objcurrencydropdown->addOption('Suriname Dollar . SRD','Suriname Dollar . SRD') ;
   $this->objcurrencydropdown->addOption('Suriname Guilder . SRG','Suriname Guilder . SRG') ;
   $this->objcurrencydropdown->addOption('Swaziland Lilangeni . SZL','Swaziland Lilangeni . SZL') ;
   $this->objcurrencydropdown->addOption('Swedish Krona . SEK','Swedish Krona . SEK') ;
   $this->objcurrencydropdown->addOption('Swiss Franc . CHF','Swiss Franc . CHF') ;
   $this->objcurrencydropdown->addOption('Syrian Pound . SYP','Syrian Pound . SYP') ;
   $this->objcurrencydropdown->addOption('Taiwan Dollar . TWD','Taiwan Dollar . TWD') ;
   $this->objcurrencydropdown->addOption('Tanzanian Shilling . TZS','Tanzanian Shilling . TZS') ;
   $this->objcurrencydropdown->addOption('Thai Baht . THB','Thai Baht . THB') ;
   $this->objcurrencydropdown->addOption('Tonga Paanga . TOP','Tonga Paanga . TOP') ;
   $this->objcurrencydropdown->addOption('Trinidad/Tobago Dollar . TTD','Trinidad/Tobago Dollar . TTD') ;
   $this->objcurrencydropdown->addOption('Tunisian Dinar . TND','Tunisian Dinar . TND') ;
   $this->objcurrencydropdown->addOption('Turkish Lira . TRL','Turkish Lira . TRL') ;
   $this->objcurrencydropdown->addOption('Turkish New Lira . TRY','Turkish New Lira . TRY') ;
   $this->objcurrencydropdown->addOption('Turkmenistan Manat . TMM','Turkmenistan Manat . TMM') ;
   $this->objcurrencydropdown->addOption('Uganda Shilling . UGX','Uganda Shilling . UGX') ;
   $this->objcurrencydropdown->addOption('Ukraine Hryvnia . UAH','Ukraine Hryvnia . UAH') ;
   $this->objcurrencydropdown->addOption('Uruguayan Peso . UYU','Uruguayan Peso . UYU') ;
   $this->objcurrencydropdown->addOption('Utd. Arab Emir. Dirham . AED','Utd. Arab Emir. Dirham . AED') ;
   $this->objcurrencydropdown->addOption('Vanuatu Vatu . VUV','Vanuatu Vatu . VUV') ;
   $this->objcurrencydropdown->addOption('Venezuelan Bolivar . VEB','Venezuelan Bolivar . VEB') ;
   $this->objcurrencydropdown->addOption('Vietnamese Dong . VND','Vietnamese Dong . VND') ;
   $this->objcurrencydropdown->addOption('Yemeni Rial . YER','Yemeni Rial . YER') ;
   $this->objcurrencydropdown->addOption('Yugoslav Dinar . YUN','Yugoslav Dinar . YUN') ;
   $this->objcurrencydropdown->addOption('Zambian Kwacha . ZMK','Zambian Kwacha . ZMK') ;
   $this->objcurrencydropdown->addOption('Zimbabwe Dollar . ZWD','Zimbabwe Dollar . ZWD') ;
   $this->objcurrencydropdown->addOption('Zimbabwe New Dollar . ZWN','Zimbabwe New Dollar . ZWN') ;
   $this->objcurrencydropdown->size = 50; 
/*********************************************************************************************************************************************************************/
$btnSubmit  = $this->objLanguage->languageText('word_save');
$strsubmit  = ucfirst($btnSubmit);
$this->objButtonSubmit  = new button('submit', $strsubmit);
$this->objButtonSubmit->setToSubmit();

$this->loadclass('button','htmlelements');
$this->objnext  = new button('next', $next);
$this->objnext->setToSubmit();

$this->objexit  = new button('exit', $exit);
$this->objexit->setToSubmit();

$this->objBack  = new button('back', $back);
$this->objBack->setToSubmit();

/**********************************************************************************************************************************************************************/
/*create a table for lodge details*/

        $myTabLodgeheading  = $this->newObject('htmltable','htmlelements');
        $myTabLodgeheading->width='100%';
        $myTabLodgeheading->border='0';
        $myTabLodgeheading->cellspacing = '10';
        $myTabLodgeheading->cellpadding ='10';

        $myTabLodgeheading->startRow();
       // $myTabLodgeheading->addCell("<div align=\"left\">" .$lodgeHint. "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">" ."<div class=\"error\">" . $this->objInfoIcon->show()  . $lodgeExchangeRate  . "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">" . '<b />' . $lodgeSuggestedExRate. "</div>");
        $myTabLodgeheading->endRow();
        
        $myTabLodgeheading->startRow();
        $myTabLodgeheading->addCell("<div align=\"left\">" . '<b />' .$lodgeExpenditures. "</div>");
        $myTabLodgeheading->endRow();
/*********************************************************************************************************************************************************************/
        $this->loadClass('textinput', 'htmlelements');
        $this->objtxtfilerate = new textinput('upload',' ','FILE');
        $this->objtxtfilerate->id = 'txtfilerate';
        
        /**
          *create all file info 
          */
       $objIncidentRateFile = $this->newObject('selectfile', 'filemanager');
       $objIncidentRateFile->name = 'incidentratefile'; 
       $objIncidentRateFile->context = false;
       $objIncidentRateFile->workgroup = false;         
        
/*********************************************************************************************************************************************************************/
/*create a table for lodge details*/

        $myTabLodge  = $this->newObject('htmltable','htmlelements');
        $myTabLodge->width='100%';
        $myTabLodge->border='0';
        $myTabLodge->cellspacing = '10';
        $myTabLodge->cellpadding ='10';

        $myTabLodge->startRow();
        $myTabLodge->addCell($expensesdate);
        $myTabLodge->addCell($this->objlodgedate->show());
        $myTabLodge->endRow();

        $myTabLodge->startRow();
        $myTabLodge->addCell($vendor);
        $myTabLodge->addCell($this->objtxtvendor->show());
        $myTabLodge->endRow();

        $myTabLodge->startRow();
        $myTabLodge->addCell('Description');
        $myTabLodge->addCell($this->objdescription->show());
        $myTabLodge->endRow();
        
        $myTabLodge->startRow();
        $myTabLodge->addCell($currency);
        $myTabLodge->addCell($this->objcurrencydropdown->show());
        $myTabLodge->endRow();
                
        $myTabLodge->startRow();
        $myTabLodge->addCell($cost);
        $myTabLodge->addCell($this->objtxtcost->show());
        $myTabLodge->endRow();
        
        $myTabLodge->startRow();
        $myTabLodge->addCell($rate);
        $myTabLodge->addCell($this->objtxtexchange->show());
        $myTabLodge->endRow();

        
/**********************************************************************************************************************************************************************/
/*create table for exchangerate information*/
        
        $myTabExchange  = $this->newObject('htmltable','htmlelements');
        $myTabExchange->width='90%';
        $myTabExchange->border='0';
        $myTabExchange->cellspacing = '10';
        $myTabExchange->cellpadding ='10';


        $myTabExchange->startRow();
        $myTabExchange->addCell(ucfirst('Attach File')); 
        $myTabExchange->addCell($objIncidentRateFile->show());
        $myTabExchange->endRow();
        
        $myTabExchange->startRow();
        $myTabExchange->addCell($strquote);
        $myTabExchange->addCell($this->objtxtquotesource->show());
        $myTabExchange->endRow();
/**********************************************************************************************************************************************************************/        


/*create tabbox for lodge information*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabbedbox = new tabbedbox();
$objtabbedbox->addTabLabel('Incident Information');
$objtabbedbox->addBoxContent($myTabLodge->show());

/*create tabbox for attaching lodge echange rate file*/
$this->loadClass('tabbedbox', 'htmlelements');
$objtabexchange = new tabbedbox();
$objtabexchange->addTabLabel('Verify Echange Rate');
$objtabexchange->addBoxContent("<div align=\"center\">" ."<div class=\"error\">" .'Verify exchange rate by attachning a file or quote a reliable source' ."</div". "</div". $myTabExchange->show());

/***********************************************************************************************************************************************************************/
$this->loadClass('form','htmlelements');
$objLodgeIncident = new form('incident',$this->uri(array('action'=>'submitincidentinfo')));
$objLodgeIncident->displayType = 3;
$objLodgeIncident->addToForm($objtabbedbox->show()  .  '<br>' . $objtabexchange->show()  . '<br />' .'<br />' . "<div align=\"center\">"  . $this->objBack->show(). $this->objnext->show().' ' . $this->objexit->show() . "</div".'<br />');
$objLodgeIncident->addRule('txtvendor', 'Please enter vendor name','required');
$objLodgeIncident->addRule('description', 'Please enter a brief description of the accident','required');
$objLodgeIncident->addRule('txtcost', 'Please enter cost amount','required');
$objLodgeIncident->addRule('txtcost', 'Please enter a numerical value for cost amount','numeric');
$objLodgeIncident->addRule('txtexchange', 'Please enter exchange rate','required');
$objLodgeIncident->addRule('txtexchange', 'Please enter a numerical value for exchange rate','numeric');
$objLodgeIncident->addRule('upload', 'You need to insert a file','required');
$objLodgeIncident->extra="enctype='multipart/form-data'";

/***********************************************************************************************************************************************************************/
//display screen content
echo "<div align=\"center\">" . $this->objlodgeHeading->show()  . "</div>";
echo "<div align=\"center\">" .'<br>'  . $myTabLodgeheading->show() . "</div>";
echo  "<div align=\"left\">"  . $objLodgeIncident->show() . "</div";
             
?>
