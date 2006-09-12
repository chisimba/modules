<?
$stdnum = $this->getParam('studentNumber');
$surname = $this->getParam('surname');
$idnumber = $this->getParam('idNumber');

$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_infotitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;
$table =& $this->newObject('htmltable','htmlelements');

$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');

//var_dump($student);
if(is_array($stdinfo)){
    $race = $stdinfo[0]->RCE;
    $gender = $stdinfo[0]->SEX;
    $marsts = $stdinfo[0]->MARSTS;
    $title = $stdinfo[0]->TTL;
    $sttype = $stdinfo[0]->STDTYP;
    $stnum = $stdinfo[0]->STDNUM;

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'), '15%');
    $table->addCell($stnum);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_race','financialaid'), '15%');
    $table->addCell($this->objDbStudentInfo->getRace($race));
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_gender','financialaid'), '15%');
    $table->addCell($this->objDbStudentInfo->getGender($gender));
    $table->endRow();

    $table->startRow();
	$table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'), '15%');
	$table->addCell($this->objDbStudentInfo->getMarStatus($marsts));
	$table->endRow();

    $table->startRow();
	$table->addCell($objLanguage->languagetext('mod_financialaid_stdtitle','financialaid'), '15%');
	$table->addCell($title);
	$table->endRow();

    $table->startRow();
	$table->addCell($objLanguage->languagetext('mod_financialaid_stdtype','financialaid'), '15%');
	$table->addCell($sttype);
	$table->endRow();
}

$types = array('B','N','P','R');
$typesnames = array($objLanguage->languagetext('mod_financialaid_boarding','financialaid'),$objLanguage->languagetext('mod_financialaid_nextofkin','financialaid'),$objLanguage->languagetext('mod_financialaid_padd','financialaid'),$objLanguage->languagetext('mod_financialaid_stdres','financialaid'));
$addtype = 0;
$addresstype = $this->getParam('address');
if(!is_null($stdaddress)){
    if(is_array($stdaddress) and count($stdaddress) > 0){
        if($addresstype == 'B') { $addtype=0; }
        else if($addresstype == 'N') { $addtype=1; }
        else if($addresstype == 'P') { $addtype=2; }
        else if($addresstype == 'R') { $addtype=3; }

    	$table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_stradd','financialaid'), '15%');
		$table->addCell($stdaddress[$addtype]->AD1);
		$table->endRow();

		$table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_suburb','financialaid'), '15%');
        $table->addCell($stdaddress[$addtype]->AD2);
		$table->endRow();

		$table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_city','financialaid'), '15%');
        $table->addCell($stdaddress[$addtype]->AD3);
		$table->endRow();

        $table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_pcode','financialaid'), '15%');
        $table->addCell($stdaddress[$addtype]->PSTCDE);
		$table->endRow();

		$table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_tel','financialaid'), '15%');
        $table->addCell($stdaddress[$addtype]->PHNNUM);
		$table->endRow();

		$table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_alttel','financialaid'), '15%');
        $table->addCell($stdaddress[$addtype]->PHNNUM1);
		$table->endRow();

		$table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_email','financialaid'), '15%');
        $table->addCell($stdaddress[$addtype]->EMLADD);
		$table->endRow();

		$table->startRow();
		$table->addCell($objLanguage->languagetext('mod_financialaid_addtype','financialaid'), '15%');
        $table->addCell($typesnames[$addtype]);
		$table->endRow();
    }
}

$addresstype = $this->getParam('address');
$datype = "";
for($i = 0; $i < 4; $i++){
	if($types[$i] != $addresstype){
		$link = new link();
		$link->href=$this->uri(array('action'=>'info','id'=>$stdnum,'address'=>$types[$i]));

	    $link->link= $typesnames[$i];
		$datype .=$link->show()."&nbsp;";
	}
    else if($types[$i] == $addresstype){
       $details .= "<center>" . $this->objLanguage->languageText('mod_studentenquiry_addtype','studentenquiry');
       $details .= ": " . $typesnames[$addtype] . "</center><br />";
    }
}



$details .= "<center>Address Types: ";
$details .= $datype . "</center>";

$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;
?>
