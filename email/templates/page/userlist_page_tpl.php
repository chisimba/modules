<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $objSkin->getSkinUrl(); ?>kewl_css.php">
<script language='Javascript' type='text/javascript'>
function sendEmailTo(username)
{
    if (parent.document.Email1.to.value==''){
        parent.document.Email1.to.value=username
    } else {
        parent.document.Email1.to.value=username+','+parent.document.Email1.to.value
    }
}
</script>
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
