function toggleRetiredDate() {
    $('retireddate_Day_ID').disabled       = !$('retireddate_Day_ID').disabled;
    $('retireddate_Month_ID').disabled     = !$('retireddate_Month_ID').disabled;
    $('retireddate_Year_ID').disabled      = !$('retireddate_Year_ID').disabled;
    $('retireddate_ID_Link').style.display = ($('retireddate_ID_Link').style.display == 'none')? 'inline' : 'none';
}

function toggleAhisUser() {
    $('input_username').disabled = !$('input_username').disabled;
    $('input_password').disabled = !$('input_password').disabled;
    $('input_confirm').disabled =  !$('input_confirm').disabled;
    
    if (!$('input_ahisuser').checked) {
        if ($('input_adminuser').checked) {
            $('input_adminuser').checked = false;
        }
        if ($('input_superuser').checked) {
            $('input_superuser').checked = false;
        }
    }
}

function toggleAdminUser() {
    if ($('input_adminuser').checked) {
        if (!$('input_ahisuser').checked) {
            $('input_ahisuser').checked = true;
            toggleAhisUser();
        }
    } else {
        if ($('input_superuser').checked) {
            $('input_superuser').checked = false;
        }
    }
}

function toggleSuperUser() {
    if ($('input_superuser').checked) {
        if (!$('input_ahisuser').checked) {
            $('input_ahisuser').checked = true;
            toggleAhisUser();
        }
        if (!$('input_adminuser').checked) {
            $('input_adminuser').checked = true;
        }
    }
}

function resetDate(fieldName) {
    var today = new Date();
    
    var day = today.getDate();
    var monthIndex = today.getMonth();
    var month = monthIndex + 1;
    var year = today.getFullYear();

    $(fieldName + '_Day_ID').selectedIndex = day - 1;
    $(fieldName + '_Month_ID').selectedIndex = monthIndex;
    $(fieldName + '_Year_ID').value = year;
    day += '';
    month += '';
    if (day.length < 2) {
        day = '0' + day;
    }
    if (month.length < 2) {
        month = '0' + month;
    }
    
    $('input_' + fieldName).value = year + '-' + month + '-' + day;
}

function clearDiseaseLocality() {
    $('input_localityTypeId').selectedIndex = 0;
	$('input_latDirec').selectedIndex = 0;
	$('input_longDirec').selectedIndex = 0;
	$('input_farmingSystemId').selectedIndex = 0;
	jQuery('#input_localityName').val('');
	jQuery('#input_latitude').val('');
	jQuery('#input_longitude').val('');
}

function clearNatureOfDiagnosis() {
    $('input_diagnosisId').selectedIndex = 0; 
}

function clearControlMeasures() {
    $('input_controlId').selectedIndex = 0; 
	$('input_otherControlId').selectedIndex = 0; 
}

function clearDiseaseNumbers() {
    $('input_speciesId').selectedIndex = 0;
	$('input_ageId').selectedIndex = 0;
	$('input_sexId').selectedIndex = 0;
	jQuery('#input_risk').val('');
	jQuery('#input_cases').val('');
	jQuery('#input_deaths').val('');
	jQuery('#input_destroyed').val('');
	jQuery('#input_slaughtered').val('');
	jQuery('#input_cumulativeCases').val('');
	jQuery('#input_cumulativeDeaths').val('');
	jQuery('#input_cumulativeDestroyed').val('');
	jQuery('#input_cumulativeSlaughtered').val('');
	
}

function boxLimiter(box) {
    if (box.value.length > 30) {
		box.value = box.value.substr(0,30);
    }
}

function clear_viewReports() {
	var today = new Date();
    var monthIndex = today.getMonth();
    var month = monthIndex + 1;
    var year = today.getFullYear();
	
	$('input_year').value = year;
	$('input_month').selectedIndex = monthIndex;
}

function changePartitionType() {
	jQuery('#input_partitionLevelId >option').remove();
	jQuery('#input_partitionId >option').remove();
	jQuery('#input_partitionLevelId').attr('disabled', true);
	jQuery('#input_partitionId').attr('disabled', true);
	var categoryId = jQuery('#input_partitionTypeId').val();
	//load levels
	jQuery.getJSON("index.php?module=openaris&action=ajax_getpartitionlevels&categoryId="+categoryId,
				   function(data) {
						jQuery.each(data, function(key, value) {
							jQuery('#input_partitionLevelId').append(jQuery("<option></option>").attr("value",key).text(value));
						});
						if (data.length != 0) {
							jQuery('#input_partitionLevelId').removeAttr('disabled');
						}
						changeNames();
				   });
	
}

function changeNames() {
	jQuery('#input_partitionId >option').remove();
	jQuery('#input_partitionId').attr('disabled', true);
	var countryId = jQuery('#input_countryId').val();
	if (countryId != -1) {
		var levelId = jQuery('#input_partitionLevelId').val();
		jQuery.getJSON("index.php?module=openaris&action=ajax_getpartitionnames&levelId="+levelId+"&countryId="+countryId,
				   function(data) {
						jQuery.each(data, function(key, value) {
							jQuery('#input_partitionId').append(jQuery("<option></option>").attr("value",key).text(value));
						});
						if (data.length != 0) {
							jQuery('#input_partitionId').removeAttr('disabled');
						}
				   });
	}
}

function changeCountry() {
	changeNames();
	changeOutbreakCode();
}

function changeOutbreakCode() {
	var countryId 	= jQuery('#input_countryId').val();
	var diseaseId 	= jQuery('#input_diseaseId').val();
	var year 		= jQuery('#input_year').val();
	if (countryId != -1 && year != '') {
		jQuery.getJSON("index.php?module=openaris&action=ajax_getoutbreakcode&diseaseId="+diseaseId+"&countryId="+countryId+"&year="+year,
				   function(data) {
						jQuery('#input_outbreakCode').val(data.code);
					});
	} else {
		jQuery('#outbreakCode').val('');
	}
}

function getOfficerInfo(role) {
	var userId = jQuery('#input_'+role+'OfficerId').val();
	var fax;
	var phone;
	var email;
	if (userId == -1) {
		jQuery('#input_'+role+'OfficerFax').val('');
		jQuery('#input_'+role+'OfficerTel').val('');
		jQuery('#input_'+role+'OfficerEmail').val('');
	} else {
		jQuery.getJSON("index.php?module=openaris&action=ajax_getofficerinfo&userid="+userId,
					   function(data) {
							jQuery('#input_'+role+'OfficerFax').val(data.fax);
							jQuery('#input_'+role+'OfficerTel').val(data.phone);
							jQuery('#input_'+role+'OfficerEmail').val(data.email);
					   });
	}
}

function toggleOutbreak(value) {
	if (value == 0) {
		jQuery('#input_reportTypeId').attr('disabled', true);
		jQuery('#input_outbreakId').attr('disabled', true);
		jQuery('#input_diseaseId').attr('disabled', true);
		jQuery('#input_occurenceId').attr('disabled', true);
		jQuery('#input_infectionId').attr('disabled', true);
		disableDatePicker('observationDate');
		disableDatePicker('vetDate');
		disableDatePicker('investigationDate');
		disableDatePicker('sampleDate');
		disableDatePicker('diagnosisDate');
		disableDatePicker('interventionDate');
		jQuery('[name=enter]').removeClass('nextButton');
		jQuery('[name=enter]').addClass('saveButton');
		
	} else {
		jQuery('#input_reportTypeId').removeAttr('disabled');
		if (jQuery('#input_reportTypeId').val() == 1) {
			jQuery('#input_outbreakId').removeAttr('disabled');
		}
		jQuery('#input_diseaseId').removeAttr('disabled');
		jQuery('#input_occurenceId').removeAttr('disabled');
		jQuery('#input_infectionId').removeAttr('disabled');
		enableDatePicker('observationDate');
		enableDatePicker('vetDate');
		enableDatePicker('investigationDate');
		enableDatePicker('sampleDate');
		enableDatePicker('diagnosisDate');
		enableDatePicker('interventionDate');
		jQuery('[name=enter]').removeClass('saveButton');
		jQuery('[name=enter]').addClass('nextButton');
		
	}
}

function disableDatePicker(name) {
	jQuery('#'+name+'_Day_ID').attr('disabled', true);
    jQuery('#'+name+'_Month_ID').attr('disabled', true);
    jQuery('#'+name+'_Year_ID').attr('disabled', true);
    jQuery('#'+name+'_ID_Link').hide();
}

function enableDatePicker(name) {
	jQuery('#'+name+'_Day_ID').removeAttr('disabled');
    jQuery('#'+name+'_Month_ID').removeAttr('disabled');
    jQuery('#'+name+'_Year_ID').removeAttr('disabled');
    jQuery('#'+name+'_ID_Link').show();
}

function toggleReportType() {
	if (jQuery('#input_reportTypeId').val() == 1) {
		jQuery('#input_outbreakCode').hide();
		jQuery('#input_outbreakId').show();
		jQuery('#input_diseaseId').attr('disabled', true);
		jQuery('#input_occurenceId').attr('disabled', true);
		jQuery('#input_infectionId').attr('disabled', true);
		
	} else {
		jQuery('#input_outbreakCode').show();
		jQuery('#input_outbreakId').hide();
		jQuery('#input_diseaseId').removeAttr('disabled');
		jQuery('#input_occurenceId').removeAttr('disabled');
		jQuery('#input_infectionId').removeAttr('disabled');
	}
}

function numberVal()
{
	alert('Insert numerics only.');	
}

function confirmation()
{
    alert("Please add at least one Farm");

}
