function toggleRetiredDate() {
    $('retireddate_Day_ID').disabled   = !$('retireddate_Day_ID').disabled;
    $('retireddate_Month_ID').disabled = !$('retireddate_Month_ID').disabled;
    $('retireddate_Year_ID').disabled  = !$('retireddate_Year_ID').disabled;
    $('retireddate_ID_Link').style.display = ($('retireddate_ID_Link').style.display == 'none')? 'inline' : 'none';
}