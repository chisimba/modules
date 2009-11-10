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
$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loader');

$this->appendArrayVar('headerParams', '
    <script type="text/javascript">
        
        // Flag Variable - Update message or not
        var doUpdateMessage = false;
        
        // Var Current Entered Code
        var currentCode;
        
        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery("#input_addtofav").bind(\'change\', function() {
                sitepath = jQuery("#input_sitepath").val();
                checkCode(jQuery("#input_favusrid").attr(\'value\'));
            });
        });
        
        // Function to check whether context code is taken
        function checkCode(code)
        {
            // Messages can be updated
            doUpdateMessage = true;
            
            // If code is null
            if (code == null) {
                // Remove existing stuff
                jQuery("#favmessage").html("Unfortunately there is a problem with that user");
                jQuery("#favmessage").removeClass("error");
                jQuery("#favmessage").removeClass("success");
                doUpdateMessage = false;
                                
            // Else Need to do Ajax Call
            } else {                
                // Check that existing code is not in use
                if (currentCode != code) {
                    
                    // Set message to checking
                    jQuery("#favmessage").removeClass("success");
                    jQuery("#favmessage").html("<span id=\"favusercheck\">'.addslashes($objIcon->show()).' Processing ...</span>");                                     
                    // Set current Code
                    currentCode = code;
                    
                    // DO Ajax 
                    jQuery.ajax({
                        type: "GET", 
                        url: sitepath, 
                        data: "module=liftclub&action=addfavourite&favusrid="+code, 
                        success: function(msg){                        
                            // Check if messages can be updated and code remains the same
                            if (doUpdateMessage == true && currentCode == code) {
                                
                                // IF code exists
                                if (msg == "ok") {
                                    jQuery("#favmessage").html("Added to your favourites successfully!");
                                    jQuery("#favmessage2").html(" ");
                                    jQuery("#favmessage").addClass("success");
                                    jQuery("#favmessage").removeClass("error");
                                } else if (msg == "exists") {
                                    jQuery("#favmessage").html("Already part of your favourites!");
                                    jQuery("#favmessage2").html(" ");
                                    jQuery("#favmessage").addClass("success");
                                    jQuery("#favmessage").removeClass("error");
                                } else if (msg == "notlogged") {
                                    jQuery("#favmessage").addClass("error");
                                    jQuery("#favmessage").html("kindly log in to be able to add lift to favourites!");
                                    jQuery("#favmessage2").html(" ");
                                // Else
                                } else {
                                    jQuery("#favmessage").html("Unexpected error occured!");
                                    jQuery("#favmessage").addClass("error");
                                }
                                
                            }
                        }
                    });
                }
            }
        }
    </script>');
$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText("mod_liftclub_viewdetails", 'liftclub', "View Member Details");

echo '<div style="padding:10px;">'.$header->show();

$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';

$form = new form ('viewdetails', $this->uri(array('action'=>'liftclubhome', 'id'=>$id, 'originid'=>$originid, 'destinyid'=>$destinyid, 'detailsid'=>$detailsid)));

$messages = array();
//Get userid
$thisuserid = $this->objUser->userId();
//Add to favourite if logged in
if(!empty($thisuserid)){
	$addfav = new checkbox('addtofav',null,false);
	$favUsrId = new textinput('favusrid',null,'hidden');
	$favUsrId->value = $this->getParam('liftuserid');
	$sysSiteRoot = $this->objConfig->getsiteRoot()."index.php";
	$sitepathtitle = new textinput('sitepath',$sysSiteRoot,"hidden",10);
	$table = $this->newObject('htmltable', 'htmlelements');
	$table->startRow();
	$table->addCell("<br /><div id='favmessage2'><b>".$this->objLanguage->languageText('mod_liftclub_addfavourite', 'liftclub', "Add to favourite")."? ".$addfav->show()." </b></div>".$favUsrId->show().$sitepathtitle->show(), 150, 'top', 'left');
	$table->endRow();
	$table->startRow();
	$table->addCell("<br /><div id='favmessage'> </div>", 150, 'top', 'left');
	$table->endRow();

	$form->addToForm($table->show());
	$form->addToForm('<br />');
}
//Add user info
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
$table->addCell($userneed." - ".$needtype);
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
