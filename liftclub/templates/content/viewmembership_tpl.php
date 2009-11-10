<script type="text/javascript">
//<![CDATA[
function init () {
    $('input_redraw').onclick = function () {
        redraw();
    }
}
function redraw () {
    var url = 'index.php';
    var pars = 'module=security&action=generatenewcaptcha';
    var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
}
function showLoad () {
    $('load').style.display = 'block';
}
function showResponse (originalRequest) {
    var newData = originalRequest.responseText;
    $('captchaDiv').innerHTML = newData;
}
//]]>
</script>
<?php
// check if the site signup user string is set, if so, use it to populate the fields

if(isset($userstring))
{
    $userstring = base64_decode($userstring);
    $userstring = explode(',', $userstring);
}
else {
    $userstring = NULL;
}
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->appendArrayVar('headerParams', '
	<script type="text/javascript">
		var uri = "'.str_replace('&amp;','&',$this->uri(array('module' => 'liftclub', 'action' => 'jsongetcities'))).'"; 
 </script>');

//Ext stuff
$ext = '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css', 'htmlelements').'" type="text/css" />';
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/adapter/ext/ext-base.js', 'htmlelements');
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/ext-all.js', 'htmlelements');
$ext .=$this->getJavaScriptFile('extjsgetcity.js', 'liftclub');
$ext .=$this->getJavaScriptFile('extjsgetcityb.js', 'liftclub');
//$ext .=$this->getJavaScriptFile('forum-search.js', 'rimfhe');
$ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('combos.css', 'liftclub').'"type="text/css" />';
$ext .=$this->getJavaScriptFile('ext-3.0-rc2/examples/shared/examples.js', 'htmlelements');
$this->appendArrayVar('headerParams', $ext);

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText("mod_liftclub_viewdetails", 'liftclub', "View Member Details");

echo '<div style="padding:10px;">'.$header->show();

$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';

$form = new form ('viewdetails', $this->uri(array('action'=>'liftclubhome', 'id'=>$id, 'originid'=>$originid, 'destinyid'=>$destinyid, 'detailsid'=>$detailsid)));

$messages = array();

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$usernameLabel = new label($this->objLanguage->languageText('word_username', 'system'));
$table->addCell("<b>".$usernameLabel->show().": </b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($register_username);
//, NULL, NULL, NULL, NULL, 'colspan="2"');
$table->endRow();

$table->startRow();
$needLabel = new label($this->objLanguage->languageText('phrase_iwanto', 'liftclub', 'I want to'));
$table->addCell("<b>".$needLabel->show().": </b>", 150, 'top', 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($userneed." ".$needtype);
$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_accountdetails', 'liftclub', 'Account Details');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');


//Add from (home or trip origin) details
$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$streetnameLabel = new label($this->objLanguage->languageText('mod_liftclub_streetname', 'liftclub', "Street Name"));


$table->addCell("<b>".$streetnameLabel->show().": </b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($street_name);
$table->endRow();

$table->startRow();
$suburbLabel = new label($this->objLanguage->languageText('mod_liftclub_suburb', 'liftclub', "Suburb"));

$table->addCell("<b>".$suburbLabel->show().": </b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($suburborigin);
$table->endRow();

$table->startRow();
$citytownLabel = new label($this->objLanguage->languageText('mod_liftclub_citytown', 'liftclub', "City/Town"));
if($citytownorigin!==null){
$townname = $this->objDBCities->listSingle($citytownorigin);
}

$table->addCell("<b>".$citytownLabel->show().": </b>", 150, 'top', 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($townname[0]["city"]);
$table->endRow();

$provinceLabel = new label($this->objLanguage->languageText('mod_liftclub_province', 'liftclub', "Province"));
$table->startRow();
$table->addCell("<b>".$provinceLabel->show().": </b>", 150, 'bottom', 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($province, NULL, 'bottom', 'left');
$table->endRow();

$table->startRow();
$neighbourLabel = new label($this->objLanguage->languageText('mod_liftclub_neighbour', 'liftclub', "Neighbour"));
$table->addCell("<b>".$neighbourLabel->show()." :</b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($neighbourorigin);
$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_from', 'liftclub', 'From (Home or Trip Origin)');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');
//Add to (home or trip destination) details
$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$institutionLabel = new label($this->objLanguage->languageText("mod_liftclub_institution", "liftclub", "Institution"));
$table->addCell("<b>".$institutionLabel->show()." :</b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($destinstitution);
$table->endRow();

$table->startRow();
$streetnameLabel2 = new label($this->objLanguage->languageText('mod_liftclub_streetname', 'liftclub', "Street Name"));
$table->addCell("<b>".$streetnameLabel2->show()."</b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($deststreetname);
$table->endRow();

$table->startRow();
$suburbLabel2 = new label($this->objLanguage->languageText('mod_liftclub_suburb', 'liftclub', "Suburb"));
$table->addCell("<b>".$suburbLabel2->show()."</b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($destsuburb);
$table->endRow();

$table->startRow();
$citytownLabel2 = new label($this->objLanguage->languageText('mod_liftclub_citytown', 'liftclub', "City/Town"));
if($destcity !== null){
    $townname2 = $this->objDBCities->listSingle($destcity);
}

$table->addCell("<b>".$citytownLabel2->show()." :</b>", 150, 'top', 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($townname2[0]["city"]);
$table->endRow();

$provinceLabel2 = new label($this->objLanguage->languageText('mod_liftclub_province', 'liftclub', "Province"));
$table->startRow();
$table->addCell("<b>".$provinceLabel2->show()." :</b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($destprovince);
$table->endRow();

$table->startRow();
$neighbourLabel2 = new label($this->objLanguage->languageText('mod_liftclub_neighbour', 'liftclub',"Neighbour"));
$table->addCell("<b>".$neighbourLabel2->show()."</b>", 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
$table->addCell($destneighbour);
$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_to', 'liftclub', 'To (Home or Trip Destination)');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');

//Add Trip details
	$table = $this->newObject('htmltable', 'htmlelements');
if($this->getSession('needtype')=='Trip'){
	$table->startRow();
	$table->addCell($this->objLanguage->languageText('mod_liftclub_daterequired', 'liftclub', "Date Required"), 150, 'top', 'right');
	$table->addCell('&nbsp;', 5);
	$table->addCell($tripdaterequired,null,'bottom','left');
	$table->endRow();
	$table->addCell('&nbsp;', 150, 'top', 'right');
	$table->addCell('&nbsp;', 5);
	$table->addCell('&nbsp;');
	$table->endRow();

}else{
	$table->startRow();
	$traveltimes = new textinput('traveltimes');
	$traveltimesLabel = new label($this->objLanguage->languageText('mod_liftclub_traveltimes', 'liftclub', "Travel Times").'&nbsp;', 'input_traveltimes');
 $traveltimes->value = $triptimes;
	if ($mode == 'addfixup') {
		   $traveltimes->value = $this->getParam('traveltimes');

		   if ($this->getParam('traveltimes') == '') {
		       $messages[] = $this->objLanguage->languageText('entertraveltimes', 'liftclub', 'Please Specify the Travel Times');
		   }
	}

	$table->addCell("<b>".$traveltimesLabel->show()." :</b>", 150, NULL, 'right');
	$table->addCell('&nbsp;', 5);
	$table->addCell($triptimes);
	$table->endRow();

	$table->startRow();
		$monday = new checkbox('monday',null,false);
		if($daymon == 'Y')
		 $monday = new checkbox('monday',null,true);
	$mondayLabel = new label($this->objLanguage->languageText('mod_liftclub_monday', 'liftclub', "Monday").'&nbsp;', 'input_monday');
		$tuesday = new checkbox('tuesday',null,false);
		if($daytues == 'Y')
		 $tuesday = new checkbox('tuesday',null,true);
	$tuesdayLabel = new label($this->objLanguage->languageText('mod_liftclub_tuesday', 'liftclub', "Tuesday").'&nbsp;', 'input_tuesday');
		$wednesday = new checkbox('wednesday',null,false);
		if($daywednes == 'Y')
		 $wednesday = new checkbox('wednesday',null,true);
	$wednesdayLabel = new label($this->objLanguage->languageText('mod_liftclub_wednesday', 'liftclub', "Wednesday").'&nbsp;', 'input_wednesday');
		$thursday = new checkbox('thursday',null,false);
		if($daythurs == 'Y')
   $thursday = new checkbox('thursday',null,true);
	$thursdayLabel = new label($this->objLanguage->languageText('mod_liftclub_thursday', 'liftclub', "Thursday").'&nbsp;', 'input_thursday');
		$friday = new checkbox('friday',null,false);
		if($dayfri == 'Y')
		 $friday = new checkbox('friday',null,true);		
	$fridayLabel = new label($this->objLanguage->languageText('mod_liftclub_friday', 'liftclub', "Friday").'&nbsp;', 'input_friday');
		$saturday = new checkbox('saturday',null,false);
		if($daysatur == 'Y')
		 $saturday = new checkbox('saturday',null,true);
	$saturdayLabel = new label($this->objLanguage->languageText('mod_liftclub_saturday', 'liftclub', "Saturday").'&nbsp;', 'input_saturday');
		$sunday = new checkbox('sunday',null,false);
		if($daysun == 'Y')
		 $sunday = new checkbox('sunday',null,true);
	$sundayLabel = new label($this->objLanguage->languageText('mod_liftclub_sunday', 'liftclub', "Sunday").'&nbsp;', 'input_sunday');

	$table->addCell("<b>".$this->objLanguage->languageText('mod_liftclub_days', 'liftclub', "Days")." :</b>", 150, NULL, 'right');
	$table->addCell('&nbsp;', 5);
	$table->addCell("<i>".$monday->show().$mondayLabel->show()." ".$tuesday->show().$tuesdayLabel->show()." ".$wednesday->show().$wednesdayLabel->show()." ".$thursday->show().$thursdayLabel->show()." ".$friday->show().$fridayLabel->show()." ".$saturday->show().$saturdayLabel->show()." ".$sunday->show().$sundayLabel->show()." "."</i>");
	$table->endRow();

	$table->startRow();
	$table->addCell("<b>".$this->objLanguage->languageText('mod_liftclub_daysvary', 'liftclub', 'Days may vary').'&nbsp;:</b>', 150, NULL, 'right');
	$table->addCell('&nbsp;', 5);
 if($varydays == 'Y'){
 	$table->addCell($this->objLanguage->languageText('word_yes', 'system')); 
 }else{
	 $table->addCell($this->objLanguage->languageText('word_no', 'system')); 
 }  	
	$table->endRow();
}
$table->startRow();
$table->addCell("<b>".$this->objLanguage->languageText('mod_liftclub_smoke', 'liftclub', 'Allow smoking?').'&nbsp;:</b>', 150, NULL, 'right');
$table->addCell('&nbsp;', 5);
 if($tripsmoke == 'Y'){
 	$table->addCell($this->objLanguage->languageText('word_yes', 'system')); 
 }else{
	 $table->addCell($this->objLanguage->languageText('word_no', 'system')); 
 }  	
$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('phrase_tripdetails', 'liftclub', 'Trip Details');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');
//Add additional Information
$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$additionalinfoLabel = new label($this->objLanguage->languageText('mod_liftclub_additionalinfo', 'liftclub', "Additional Information").'&nbsp;');
$table->addCell("<b>".$additionalinfoLabel->show()." :</b>", 150, "top", 'right');
$table->addCell('&nbsp;', 5);
$table->addCell("<p>".$tripadditionalinfo."</p>");
$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('mod_liftclub_additionalinfo', 'liftclub', 'Additional Information');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');

echo $form->show();

echo '</div>';
?>
