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
}