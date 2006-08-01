<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="eng_GB" lang="eng_GB" dir="ltr" >
<head>
		<title></title>
	
<?php

if (isset($headerParams)) {

    if (is_array($headerParams)) {
        foreach ($headerParams as $headerParam)
        {
            echo $headerParam."\n\n";
        }
    } else {
        echo $headerParams;
    }

}

?>
	<link rel="stylesheet" href="/templates/joomla_admin/css/template.css" type="text/css" />
</head>

<body class="contentpane">

<?php
 echo $this->getLayoutContent();
?>
</body>
</html>