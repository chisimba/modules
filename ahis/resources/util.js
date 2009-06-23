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

function clearPassiveSurveillance() {
    //$('input_oStatusId').selectedIndex = 0;
    //$('input_qualityId').selectedIndex = 0;
    $('input_remarks').value = '';
    //resetDate('datePrepared');
    //resetDate('dateIBAR');
    //resetDate('dateReceived');
    //resetDate('dateIsReported');
}

function clearPassiveOutbreak() {
    resetDate('dateVet');
    resetDate('dateOccurence');
    resetDate('dateDiagnosis');
    resetDate('dateInvestigation');
    $('input_latmin').value = '';
	$('input_longmin').value = '';
	$('input_latdeg').value = '';
	$('input_longdeg').value = '';
	$('input_locationId').selectedIndex = 0;
	$('input_latdirection').selectedIndex = 0;
    $('input_longdirection').selectedIndex = 0;
	$('input_diseaseId').selectedIndex = 0;
    $('input_causativeId').selectedIndex = 0;
	
    
}

function clearPassiveSpecies() {
    //$('input_speciesId').selectedIndex = 0;
    //$('input_ageId').selectedIndex = 0;
    //$('input_sexId').selectedIndex = 0;
    //$('input_productionId').selectedIndex = 0;
    //$('input_controlId').selectedIndex = 0;
    //$('input_basisId').selectedIndex = 0;
    $('input_susceptible').value = '';
    $('input_cases').value = '';
    $('input_deaths').value = '';
    $('input_vaccinated').value = '';
    $('input_slaughtered').value = '';
    $('input_destroyed').value = '';
    $('input_production').value = '';
    $('input_newcases').value = '';
    $('input_recovered').value = '';
    $('input_prophylactic').value = '';  
}

function clearPassiveVaccine() {
    $('input_source').value = '';
    $('input_batch').value = '';
    //resetDate('dateManufactured');
    //resetDate('dateExpire');
    $('input_panvac').checked = false;
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

function numberVal()
{
	alert('Insert numerics only.');	
}

function confirmation()
{
    alert("Please add at least one Farm");

}