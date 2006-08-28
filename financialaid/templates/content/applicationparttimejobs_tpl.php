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
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_parttimejobtitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]['idnumber'];
$stdnum = $stdinfo[0]['studentnumber'];
$table =& $this->newObject('htmltable','htmlelements');

$left =& $this->getObject('financialaidleftblock');


$left = $left->show();
$parttimejobs = $this->objDBParttimejobs->getParttimeJobs($appnum);

if(count($parttimejobs) > 0){
    $table->startHeaderRow();
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_jobtitle','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_empdetails','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_emptelno','financialaid'));
    $table->endHeaderRow();


    foreach($parttimejobs as $data)
    {
        $table->startRow();
        $table->addCell($data['jobtitle']);
        $table->addCell($data['employersdetails']);
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
