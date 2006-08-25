<?
    // Get an Instance of the language object
    $objLanguage = &$this->getObject('language', 'language');
    
$stdnum = $this->getParam('studentNumber');
$applnum = $this->getParam('applicationNumber');
$surname = $this->getParam('surname');
$idnumber = $this->getParam('idNumber');
  
$right =& $this->getObject('blocksearchbox');
$right = $right->show($this->getParam('module'));

$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;

$details = "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

// Set an array of replacements for code2txt
$rep = array('FIRSTNAME' => $stname, 'LASTNAME' => $stsname);
// Set up the variable $hi with the value of the string
$pgtitle = $objLanguage->code2Txt("mod_studentenquiry_infotitle",'studentenquiry', $rep);

$details .= "<b>". $pgtitle . "</p>";
//$details = "<p><b>Details of ".$stname."  ".$stsname."</p>";
$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;
$table =& $this->newObject('htmltable','htmlelements');

$left =& $this->getObject('leftblock');


$left = $left->show();
$this->studentinfo =& $this->getObject('dbstudentinfo');

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
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_stdnum2','studentenquiry'));
		$table->addCell($stnum);
		$table->endRow();
  
        $table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_race','studentenquiry'));
		$table->addCell($this->studentinfo->getRace($race));
		$table->endRow();
  
        $table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_gender','studentenquiry'));
		$table->addCell($this->studentinfo->getGender($gender));
		$table->endRow();
     
        $table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_mrtsts','studentenquiry'));
		$table->addCell($this->studentinfo->getMarStatus($marsts));
		$table->endRow();

        $table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_stdtitle','studentenquiry'));
		$table->addCell($title);
		$table->endRow();
  
        $table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_stdtype','studentenquiry'));
		$table->addCell($sttype);
		$table->endRow();
	//}
//echo "<br><pre>stdinfo: " ;    print_r($stdinfo);  echo "</pre><br>" ;
}

$types = array('B','N','P','R');
$typesnames = array($this->objLanguage->languageText('mod_studentenquiry_boarding','studentenquiry'),
$this->objLanguage->languageText('mod_studentenquiry_nextofkin','studentenquiry'),
$this->objLanguage->languageText('mod_studentenquiry_padd','studentenquiry'),
$this->objLanguage->languageText('mod_studentenquiry_stdres','studentenquiry'));
//echo "<br>(test)studentaddress: " ;   print_r($stdaddress);   echo "<br>" ;
$addtype = 0;
$addresstype = $this->getParam('address');
//echo "<br>(info1)addresstype: " . $addresstype . "<br>";
if(!is_null($stdaddress))
if(is_array($stdaddress) and count($stdaddress) > 0){
	//var_dump($stdaddress);
        if($addresstype == 'B') { $addtype=0; }
        else if($addresstype == 'N') { $addtype=1; }
        else if($addresstype == 'P') { $addtype=2; }
        else if($addresstype == 'R') { $addtype=3; }
        
    	$table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_stradd','studentenquiry'));
		$table->addCell($stdaddress[$addtype]->AD1);
		$table->endRow();

		$table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_suburb','studentenquiry'));
        $table->addCell($stdaddress[$addtype]->AD2);
		$table->endRow();

		$table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_city','studentenquiry'));
        $table->addCell($stdaddress[$addtype]->AD3);
		$table->endRow();
  
        $table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_pcode','studentenquiry'));
        $table->addCell($stdaddress[$addtype]->PSTCDE);
		$table->endRow();
  
		$table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_tel','studentenquiry'));
        $table->addCell($stdaddress[$addtype]->PHNNUM);
		$table->endRow();

		$table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_alttel','studentenquiry'));
        $table->addCell($stdaddress[$addtype]->PHNNUM1);
		$table->endRow();
		
		$table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_email','studentenquiry'));
        $table->addCell($stdaddress[$addtype]->EMLADD);
		$table->endRow();
		
		$table->startRow();
		$table->addCell($this->objLanguage->languageText('mod_studentenquiry_addtype','studentenquiry'));
        $table->addCell($typesnames[$addtype]);
		$table->endRow();
}

else{
$table->startRow();
$table->addCell('Address Type');
}

//$contactType = $this->studentinfo->getLookupInfo($values->contactTypeID);

$addresstype = $this->getParam('address');
//echo "<br>(info)addresstype: " . print_r($stdaddress) . "<br>";
$datype = "";
for($i = 0; $i < 4; $i++){
	if($types[$i] != $addresstype){
		$link = new link();
		$link->href=$this->uri(array('action'=>'info','id'=>$stdnum,'address'=>$types[$i]));
		
		$contactType = $this->studentinfo->studentAddress($stdnum);
        $link->link= $typesnames[$i];
		$datype .=$link->show()."<br>"; 
	}
}

$table->addCell($datype);
$table->endRow();

/*
$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
$this->rightNav->width="200px";
echo $this->rightNav->addToLayer();
 */
$link = new link();
if($this->getParam('module') === "studentinfo"){
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

$content = " ".$details." ".$table->show()." ".$link->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'enquiry','id'=>$idnumber)));
$objForm->setDisplayType(2);

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$objForm->addToForm($content);
//$objForm->addToForm($ok);

  /*
$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();
*/

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();


?>
