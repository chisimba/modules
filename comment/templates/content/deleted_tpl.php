
<?php

$objIcon = $this->getObject('geticon', 'htmlelements');
$objIcon->setIcon('close');
$urlword=$this->uri(array('action'=>'viewstory'),'stories');

$closeLink = "<a href=\"$urlword\">"
  . $objIcon->show() . "</a>";

   echo "<table align=\"center\"><tr>"
     . "<td width=\"100%\" valign=\"top\" style=\"border-top: 1px #DFDFDF solid; border-bottom: 2px #CCCCCC solid;\">"
     . "<b>" . $this->objLanguage->languageText("mod_comment_deletedit",comment)
     . "</b></td></tr><tr><td valign=\"top\" height=\"155px\" >" 
      . $closeLink . "</td></tr></table>";


?>
