<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $objConfig->siteName(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--<meta HTTP-EQUIV=Refresh CONTENT="5;URL=index.php">-->
<!--<meta HTTP-EQUIV="Refresh" CONTENT="10">-->
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
<head>
<?php
$objSkin->skinStartPage(); 
?>
<body>
	<!--<div id="container">-->
        <?php echo $this->getContent(); ?>
	<!--</div>-->
<?php
//$this->putMessages();
?>
</body>
</html>