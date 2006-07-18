<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Chat<?php /*echo $objConfig->siteName();*/ ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<meta HTTP-EQUIV=Refresh CONTENT="5;URL=index.php">-->
<!--<meta http-equiv="Refresh" content="5" />-->
<script type="text/javascript" language="JavaScript">
function StartTimer()
{
	window.setInterval('UpdateTimer()', 10000);
}
function UpdateTimer()
{
	//alert('OK');
	//window.location.href='index.php';
    window.location.reload();
}
</script>
<?php if (isset($jsLoad)) {
    foreach ($jsLoad as $script) { ?>
       <script type="text/javascript" src="<?php echo $objConfig->siteRoot().$script?>"></script>
    <?php }
} ?>
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
<link rel="stylesheet" type="text/css" href="<?php echo $objSkin->getSkinUrl(); ?>kewl_css.php">
</head>
<?php
$objSkin->skinStartPage();
?>
<body onload="StartTimer()">
	<script language="JavaScript">
	//alert('OK');
		//parent.document.getElementById('mainscreen').innerHTML='<?php echo $this->getContent(); ?>';
	</script>
	<!--<div id="container">-->
        <?php echo $this->getLayoutContent(); ?>
	<!--</div>-->
<?php
//$this->putMessages();
?>
</body>
</html>