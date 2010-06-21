<?php header("Content-type: text/html; charset=utf-8"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $objConfig->siteName(); ?></title>
<?php
if (isset($jsLoad)) {
    foreach ($jsLoad as $script) { 
?>
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
if (isSet($bodyParams)) {
    echo "<body " . $bodyParams . ">";
} else {
    echo "<body>";
}
?>

<?php if (!isset($pageSuppressContainer)) { ?>
	<div id="container">      
<?php } ?>

<?php
    // get content
    echo $this->getLayoutContent(); 
?>

<?php if (!isset($pageSuppressContainer)) { ?>
	</div>
<?php } ?>
<?php
$this->putMessages();

?>
</body>
</html>
