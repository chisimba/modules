<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<?php
$this->loadClass('link', 'htmlelements');

$content= $this->objViewerUtils->getArticleContent($storyid,$articleid);
echo $content;
?>
