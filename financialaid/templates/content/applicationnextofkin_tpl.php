<?
$appnum = $this->getParam('appnum');

$right =& $this->getObject('applicationblocksearchbox');
$right = $right->show($this->getParam('module','studentenquiry'));
$this->objDBApplication =& $this->getObject('dbapplication');

$stdinfo = $this->objDBApplication->getApplication($appnum);
$stname = $stdinfo[0]['firstnames'];
$stsname = $stdinfo[0]['surname'];

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_nextofkintitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]['idnumber'];
$stdnum = $stdinfo[0]['studentnumber'];
$table =& $this->newObject('htmltable','htmlelements');

$left =& $this->getObject('financialaidleftblock');


$left = $left->show();
$nextofkin = $this->objDBNextofkin->getNextofkin($appnum);

if(count($nextofkin) > 0){

    foreach($nextofkin as $data)
    {
        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_idnumber','financialaid'));
        $table->addCell($data['idnumber']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
        $table->addCell($data['surname']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_firstnames','financialaid'));
        $table->addCell($data['firstnames']);
        $table->endRow();
        
        $table->startRow();
        $table->addCell($objLanguage->languagetext('word_relationship'));
        $table->addCell($data['relationship']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_homeaddress','financialaid'));
        $table->addCell($data['straddress']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
        $table->addCell($data['suburb']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_city','financialaid'));
        $table->addCell($data['city']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
        $table->addCell($data['postcode']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
        $table->addCell($data['maritalstatus']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_spouse','financialaid'));
        $table->addCell($data['spouse']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_occupation','financialaid'));
        $table->addCell($data['occupation']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_empdetails','financialaid'));
        $table->addCell($data['employersdetails']);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_emptelno','financialaid'));
        $table->addCell($data['employerstelno']);
        $table->endRow();

    }
}


$content = "<center>".$details." ".$table->show(). "</center>";

// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();


?>
