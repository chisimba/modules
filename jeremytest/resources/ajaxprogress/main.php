<?php
    // AJAX progress
    // (C) 2011 Jeremy O'Connor
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>ajax_progress</title>
<script type="text/javascript" src="../../../../core_modules/prototype/resources/scriptaculous/1.7.1_beta3/lib/prototype.js"></script>
</head>
<body>
<span id="status"></span>
<script type="text/javascript">
var timerId = null;
///ajax_progress/
new Ajax.Request('install.php', {
    asynchronous: true,
    method:'get',
    onSuccess: function(transport){
        if (timerId != null) {
            window.clearInterval(timerId);
            timerId = null;
        }
        var response = transport.responseText || "no response text";
        $('status').innerHTML = "Success! " + response+' Please wait while you are redirected...';
        ///ajax_progress/
        window.location.replace('complete.php'); //?message='+encodeURI(response)
    },
    onFailure: function(){
        if (timerId != null) {
            window.clearInterval(timerId);
            timerId = null;
        }
        $('status').innerHTML = 'Something went wrong...';
    }
});
function UpdateProgress()
{
    ///ajax_progress/
    new Ajax.Request('progress.php', {
        asynchronous: false,
        method:'get',
        onSuccess: function(transport){
            var response = transport.responseText || "no response text";
            $('status').innerHTML = response;
        },
        onFailure: function(){
            $('status').innerHTML = 'Something went wrong...';
        }
    });
}
timerId = window.setInterval('UpdateProgress()', 1000);
</script>
</body>
</html>