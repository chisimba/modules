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
      
$details = "<p><b>".$objLanguage->code2Txt('mod_financialaid_infotitle','financialaid',$rep)."</b></p>";
$idnumber = $stdinfo[0]['idnumber'];
$stdnum = $stdinfo[0]['studentnumber'];
$table =& $this->newObject('htmltable','htmlelements');

$left =& $this->getObject('financialaidleftblock');


$left = $left->show();

if(count($stdinfo) > 0){
    $gender = $stdinfo[0]['gender'];
    $saCitizen = $stdinfo[0]['sacitizen'];
    $supportingSelf = $stdinfo[0]['supportingSelf'];

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
    $table->addCell($stdnum);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_gender','financialaid'));
    $table->addCell($gender);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_sacitizen','financialaid'));
    if ($saCitizen == 1){
       $table->addCell($objLanguage->languagetext('word_yes'));
    }else{
       $table->addCell($objLanguage->languagetext('word_no'));
    }
	$table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_supportingself','financialaid'));
    if ($supportingSelf == 1){
	   $table->addCell($objLanguage->languagetext('word_yes'));
    }else{
       $table->addCell($objLanguage->languagetext('word_no'));
    }
   	$table->endRow();
}

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

$table->startRow();
$link = new link();
$link->href=$this->uri(array('action'=>'showstudentfamily','appnum'=>$appnum));
$link->link= $objLanguage->languagetext('mod_financialaid_showappstudentfamilydetails','financialaid');
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
