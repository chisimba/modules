jQuery(document).ready(function() {
    jQuery('#downloader').hide();
});
function showDownload(){
    alert("I am");
   jQuery('#downloader').dialog({width:650});
};