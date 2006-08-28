<?
$appnum = $this->getParam('appnum');

$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module','studentenquiry'));
$this->objDBApplication =& $this->getObject('dbapplication');

$stdinfo = $this->objDBApplication->getApplication($appnum);
$stname = $stdinfo[0]['firstnames'];
$stsname = $stdinfo[0]['surname'];

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<p><b>".$objLanguage->code2Txt('mod_financialaid_studentfamilytitle','financialaid',$rep)."</b></p>";
$idnumber = $stdinfo[0]['idnumber'];
$stdnum = $stdinfo[0]['studentnumber'];
$table =& $this->newObject('htmltable','htmlelements');

$left =& $this->getObject('financialaidleftblock');


$left = $left->show();
$parttimejobs = $this->objDBStudentFamily->getStudentFamily($appnum);

if(count($parttimejobs) > 0){
    $table->startHeaderRow();
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_institution','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('word_course'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_yearofstudy','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
    $table->endHeaderRow();


    foreach($parttimejobs as $data)
    {
        $table->startRow();
        $table->addCell($data['firstname']);
        $table->addCell($data['institution']);
        $table->addCell($data['course']);
        $table->addCell($data['yearofstudy']);
        $table->addCell($data['studentnumber']);
        $table->endRow();
    }
}

$table->startRow();
$link = new link();
$link->href=$this->uri(array('action'=>'applicationinfo','appnum'=>$appnum));
$link->link= $objLanguage->languagetext('mod_financialaid_showappdetails','financialaid');
$table->addCell($link->show());
$table->endRow();

$table->startRow();
$link = new link();
$link->href=$this->uri(array('action'=>'shownextofkin','appnum'=>$appnum));
$link->link= $objLanguage->languagetext('mod_financialaid_showappnextofkindetails','financialaid');
$table->addCell($link->show());
$table->endRow();

$table->startRow();
$link = new link();
$link->href=$this->uri(array('action'=>'showdependants','appnum'=>$appnum));
$link->link= $objLanguage->languagetext('mod_financialaid_showappdependantsdetails','financialaid');
$table->addCell($link->show());
$table->endRow();

$table->startRow();
$link = new link();
$link->href=$this->uri(array('action'=>'showparttimejob','appnum'=>$appnum));
$link->link= $objLanguage->languagetext('mod_financialaid_showappparttimejobdetails','financialaid');
$table->addCell($link->show());
$table->endRow();

$content = "<center>".$details." ".$table->show(). "</center>";

// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();


?>
