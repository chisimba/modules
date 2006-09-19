<?
$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');

$details = "<center><h2>".$objLanguage->languagetext('mod_financialaid_addstudent','financialaid')."</h2></center>";

$stdNum = $this->getParam('stdnum', '');

$studentinfo = $this->objDbStudentInfo->getPersonInfo($stdNum);

$noStudent = FALSE;

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

if (is_array($studentinfo)){
    if (count($studentinfo) > 0){
        $startyear = date("Y");

        $appid = "init" . "_" . rand(1000,9999) . "_" . time();
        $appidfield = new textinput("appid", $appid,  "hidden", NULL);
        $year = new textinput("year", $startyear,  "hidden", NULL);
        $snum = new textinput("stdnum", $stdNum,  "hidden", NULL);
        $surname = new textinput("surname", $studentinfo[0]->SURNAM,  "hidden", NULL);
        $idnum = new textinput("idnumber", $studentinfo[0]->IDN,  "hidden", NULL);

        $semester = new radio('semester');
        $semester->addOption('1',$objLanguage->languagetext('word_first'));
        $semester->addOption('2',$objLanguage->languagetext('word_second'));
        $semester->setSelected('1');
        $semester->setBreakSpace('&nbsp;&nbsp;');

        $supportingSelf = new radio('supportingself');
        $supportingSelf->addOption('1',$objLanguage->languagetext('word_yes'));
        $supportingSelf->addOption('0',$objLanguage->languagetext('word_no'));
        $supportingSelf->setSelected('1');
        $supportingSelf->setBreakSpace('&nbsp;&nbsp;');

        $addbut= new button('add');
        $addbut->setToSubmit();
        $addbut->setValue($objLanguage->languagetext('word_add'));

        $cancelbut= new button('cancel');
        $cancelbut->setToSubmit();
        $cancelbut->setValue($objLanguage->languagetext('word_cancel'));


        $table->startRow();
        $table->addCell($appidfield->show());
        $table->endRow();
        
        $table->startRow();
        $table->addCell($year->show());
        $table->endRow();

        $table->startRow();
        $table->addCell($snum->show());
        $table->endRow();

        $table->startRow();
        $table->addCell($surname->show());
        $table->endRow();

        $table->startRow();
        $table->addCell($idnum->show());
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('word_year'));
        $table->addCell($startyear);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('word_semester'));
        $table->addCell($semester->show());
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
        $table->addCell($stdNum);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_idnumber','financialaid'));
        $table->addCell($studentinfo[0]->IDN);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
        $table->addCell($studentinfo[0]->SURNAM);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_firstnames','financialaid'));
        $table->addCell($studentinfo[0]->FSTNAM);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_gender','financialaid'));
        $table->addCell($this->objDbStudentInfo->getGender($studentinfo[0]->SEX));
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
        $table->addCell($this->objDbStudentInfo->getMarStatus($studentinfo[0]->MARSTS));
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_supportingself','financialaid'));
        $table->addCell($supportingSelf->show());
        $table->endRow();

        $table->startRow();
        $table->addCell($addbut->show());
        $table->addCell($cancelbut->show());
        $table->endRow();

        $objForm = new form('applicationform');
        $objForm->setAction($this->uri(array('action'=>'saveapplication')));
        $objForm->setDisplayType(2);

        $objForm->addToForm($details.$table->show());
        $content = $objForm->show();
    }else{
        $noStudent = TRUE;
    }
}else{
    $noStudent = TRUE;
}

if ($noStudent == TRUE){
        $link = new link();
        $link->href=$this->uri(array('action'=>'addapplication'));
       	$link->link = $objLanguage->languagetext('mod_financialaid_addanotherstudent','financialaid');
        $content = $details."<center><div class='error'>".$objLanguage->languagetext('mod_financialaid_studentnotexisting','financialaid')."</div><br />";
        $content .= $link->show()."</center>";
}

echo $content;


?>
