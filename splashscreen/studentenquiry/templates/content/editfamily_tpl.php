<?
$right =& $this->getObject('blocksearchbox');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show();

$header = & $this->newObject('htmlheading','htmlelements');
$header->str="Edit Family Member for ".ucfirst($title[0]['value'])."  ".ucfirst($stdinfo[0]['name'])." ".ucfirst($stdinfo[0]['surname']);

$id=$stdinfo[0]['personID'];
$details = $header->show();

$table =& $this->newObject('htmltable','htmlelements');


if(is_array($annualincome)){
	$drpannual = new dropdown('annual');
	$drpannual->addOption(' ', ' ');
	foreach($annualincome as $annual){
		$drpannual->addOption($annual['lookupID'],$annual['value']);
	}

$annualgross = $this->studentinfo->getLookupInfo($familyperson[0]['annualgross']);
//$drpannual->setSelected($annualgross[0]['value']);
$drpannual->setSelected($familyperson[0]['annualgross']);
}

if(is_array($monthlyincome)){
	$drpmonthly = new dropdown('monthly');
	$drpmonthly->addOption(' ', ' ');
	foreach($monthlyincome as $monthly){
		$drpmonthly->addOption($monthly['lookupID'],$monthly['value']);
	}

$monthlygross = $this->studentinfo->getLookupInfo($familyperson[0]['monthlygross']);
//$drpmonthly->setSelected($monthlygross[0]['value']);
$drpmonthly->setSelected($familyperson[0]['monthlygross']);
}


if(is_array($race)){
	$drprace = new dropdown('race');
	$drprace->addOption(' ',' ');
	foreach($race as $ras){
		$drprace->addOption($ras['lookupID'],$ras['value']);
	}
$drprace->setSelected($familyperson[0]['raceID']);

}


if(is_array($gender)){
	$drpgender = new dropdown('gender');
	$drpgender->addOption(' ',' ');
	foreach($gender as $gen){
		$drpgender->addOption($gen['lookupID'],$gen['value']);
	}
$drpgender->setSelected($familyperson[0]['genderID']);
}

if(is_array($title)){
	$drptitle = new dropdown('title');
	$drptitle->addOption(' ',' ');
	foreach($title as $titl){
		$drptitle->addOption($titl['lookupID'],$titl['value']);
	}
$drptitle->setSelected($familyperson[0]['titleID']);
}

if(is_array($maritalstatus)){
	$drpmaritalstatus = new dropdown('maritalstatus');
	$drpmaritalstatus->addOption(' ',' ');
	foreach($maritalstatus as $mstatus){
		$drpmaritalstatus->addOption($mstatus['lookupID'],$mstatus['value']);
	}
$drpmaritalstatus->setSelected($familyperson[0]['maritalStatusID']);
}

if(is_array($relationship)){
	$drprelationship = new dropdown('relationship');
	$drprelationship->addOption(' ',' ');
	foreach($relationship as $relate){
		if($relate['value'] != 'sponsor' && $relate['value'] != 'student' && $relate['value'] != 'employer'){
			$drprelationship->addOption($relate['lookupID'],$relate['value']);
		}
	}
$drprelationship->setSelected($familyperson[0]['personTypeID']);
}

if(is_array($addresstype)){
	$drpaddresstype = new dropdown('addresstype');
	$drpaddresstype->addOption(' ',' ');
	foreach($addresstype as $address){
		if($address['value'] != "Address During Term"){
			$drpaddresstype->addOption($address['lookupID'],$address['value']);
		}
	}
$drpaddresstype->setSelected($fpersonaddr[0]['contactTypeID']);
}

$address = $fpersonaddr[0];
$person = $familyperson[0];

$contactID = new textinput('txtContactID',$address['contactID'],'hidden');
$contactTypeID = new textinput('txtContactTypeID',$address['contactTypeID'],'hidden');
$family = new textinput('txtFamilymemberID',$person['familymemberID'],'hidden');


$idnumber = new textinput('txtIdnumber',$person['idnumber']);
$name = new textinput('txtName',$person['name']);
$surname = new textinput('txtSurname',$person['surname']);
$streetAddress = new textinput('txtStreetAddress',$address['streetAddress']);
$suburb = new textinput('txtSuburb',$address['suburb']);
$town = new textinput('txtTown',$address['town']);
$code = new textinput('txtCode',$address['code']);
$telephone = new textinput('txtTelephone',$address['telephone']);
$cellphone = new textinput('txtCellphone',$address['cellphone']);
$email = new textinput('txtEmail',$address['email']);
$spousename = new textinput('txtSpousename',$person['spousename']);

$employertel = new textinput('txtEmployertel',$person['employertelephone']);
$employerdetails = new textarea('txtEmployerdetails',$person['employerdetails'],4,20);
$year = new textinput('txtYear');
$occupation = new textinput('txtOccupation',$person['occupation']);

$table->startRow();
$table->addCell($contactID->show());
$table->addCell($contactTypeID->show());
$table->addCell($family->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>Personal");
$table->addCell("<b>Information");
$table->endRow();

$table->startRow();
$table->addCell('Id Number');
$table->addCell($idnumber->show());
$table->addCell('&nbsp;&nbsp;&nbsp;Title');
$table->addCell($drptitle->show());
$table->endRow();

$table->startRow();
$table->addCell('Name');
$table->addCell($name->show());
$table->addCell('&nbsp;&nbsp;&nbsp;Surname');
$table->addCell($surname->show());
$table->endRow();

$table->startRow();
$table->addCell('Gender');
$table->addCell($drpgender->show());
$table->addCell('&nbsp;&nbsp;&nbsp;Marital_Status &nbsp;&nbsp;');
$table->addCell($drpmaritalstatus->show());
$table->endRow();

$table->startRow();
$table->addCell('Race');
$table->addCell($drprace->show());
$table->addCell('&nbsp;&nbsp;&nbsp;Relationship &nbsp;&nbsp;');
$table->addCell($drprelationship->show());
$table->endRow();

$table->startRow();
$table->addCell('Occupation &nbsp;&nbsp;');
$table->addCell($occupation->show());
$table->addCell('&nbsp;&nbsp;&nbsp;Employer_Telephone &nbsp;&nbsp;');
$table->addCell($employertel->show());

$table->startRow();
$table->addCell('Monthly Gross Income');
$table->addCell($drpmonthly->show());
$table->addCell('&nbsp;&nbsp;&nbsp;Annual Gross &nbsp;&nbsp;&nbsp;Income');
$table->addCell($drpannual->show());

$table->startRow();
$table->addCell("Employer_Details ");
$table->addCell($employerdetails->show());
$table->addCell('&nbsp;&nbsp;&nbsp;Spouse Name');
$table->addCell($spousename->show());
$table->endRow();

$table->startRow();
$table->addCell("&nbsp;&nbsp;&nbsp ");
$table->addCell("&nbsp;&nbsp;&nbsp ");
$table->endRow();

$table->startRow();
$table->addCell("<b>Contact");
$table->addCell("<b>Details");
$table->endRow();

$table->startRow();
$table->addCell('Street_Address &nbsp;&nbsp;');
$table->addCell($streetAddress->show());
$table->addCell('&nbsp;&nbsp;&nbsp Suburb');
$table->addCell($suburb->show());

$table->endRow();


$table->startRow();
$table->addCell('Town');
$table->addCell($town->show());
$table->addCell('&nbsp;&nbsp;&nbsp Code');
$table->addCell($code->show());
$table->endRow();


$table->startRow();
$table->addCell('Telephone');
$table->addCell($telephone->show());
$table->addCell('&nbsp;&nbsp;&nbsp Cellphone');
$table->addCell($cellphone->show());
$table->endRow();

$table->startRow();
$table->addCell('Email');
$table->addCell($email->show());
$table->addCell('&nbsp;&nbsp;&nbsp Contact Type');
$table->addCell($drpaddresstype->show());
$table->endRow();

$ok= new button('ok');
$ok->setToSubmit();
$ok->setValue('OK');

$cancel= new button('cancel');
$cancel->setToSubmit();
$cancel->setValue('cancel');

$table->startRow();
$table->addCell($ok->show());
$table->addCell($cancel->show());
$table->endRow();

$this->leftNav = $this->getObject('layer','htmlelements');
$this->leftNav->id = "leftnav";
$this->leftNav->str=$left;
echo $this->leftNav->addToLayer();

$this->rightNav = $this->getObject('layer','htmlelements');
$this->rightNav->id = "rightnav";
$this->rightNav->str = $right;
echo $this->rightNav->addToLayer();

$content = "<center>".$details."  ".$table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'editfamily','id'=>$id,'fid'=>$this->getParam('familyid'))));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $objForm->show();
//$this->contentNav->height="300px";
echo $this->contentNav->addToLayer();


?>
