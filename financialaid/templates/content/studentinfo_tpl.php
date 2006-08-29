<?
$stdnum = $this->getParam('studentNumber');
$applnum = $this->getParam('applicationNumber');
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
	//for($i = 0;$i < count($stdinfo);$i++){
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
	//}
//echo "<br /><pre>stdinfo: " ;    print_r($stdinfo);  echo "</pre><br />" ;
}

$types = array('B','N','P','R');
$typesnames = array($objLanguage->languagetext('mod_financialaid_boarding','financialaid'),$objLanguage->languagetext('mod_financialaid_nextofkin','financialaid'),$objLanguage->languagetext('mod_financialaid_padd','financialaid'),$objLanguage->languagetext('mod_financialaid_stdres','financialaid'));
//echo "<br />(test)studentaddress: " ;   print_r($stdaddress);   echo "<br />" ;
$addtype = 0;
$addresstype = $this->getParam('address');
//echo "<br />(info1)addresstype: " . $addresstype . "<br />";
if(!is_null($stdaddress))
if(is_array($stdaddress) and count($stdaddress) > 0){
	//var_dump($stdaddress);
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
        $table->startRow();
        $table->addCell('');
}else{
        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_addtype','financialaid'));
}





//$contactType = $this->objDbStudentInfo->getLookupInfo($values->contactTypeID);

$addresstype = $this->getParam('address');
//echo "<br />(info)addresstype: " . print_r($stdaddress) . "<br />";
$datype = "";
for($i = 0; $i < 4; $i++){
	if($types[$i] != $addresstype){
		$link = new link();
		$link->href=$this->uri(array('action'=>'info','id'=>$stdnum,'address'=>$types[$i]));

		$contactType = $this->objDbStudentInfo->studentAddress($stdnum);
        $link->link= $typesnames[$i];
		$datype .=$link->show()."<br />";
	}
}

$table->addCell($datype);
$table->endRow();




$link = new link();
if($this->getParam('module') === "financialaid"){
	$link->href=$this->uri(array('action'=>'nextofkin','id'=>$idnumber));
	$link->link="Show Family Member(s)";
}
if($this->getParam('module') === "residence"){
	$link->href=$this->uri(array('action'=>'resapplication','id'=>$idnumber));
	$link->link="Click here for Application(s) for Residence";
}

if($this->getParam('module') === "studentenquiry"){
	$link->href=$this->uri(array('action'=>'nextofkin','id'=>$idnumber));
	//$link->link="Show Family Member(s)";
}

$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;


?>
