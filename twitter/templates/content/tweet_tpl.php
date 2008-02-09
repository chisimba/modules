<?php
//Create an instance of the css layout class
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$objWidjet = $this->getObject("tweetbox","twitter");
//$objUserParams = $this->getObject("dbuserparamsadmin","userparamsadmin");
//$objUserParams->readConfig();
//$userName = $objUserParams->getValue("twittername");
//$password = $objUserParams->getValue("twitterpassword");



//Right panel
$objBlock = $this->getObject("blocks", "blocks");
$rightBit =  $objBlock->showBlock("followers", "twitter");
$cssLayout->setRightColumnContent($rightBit);


//Left panel
$statusUpdate = "<img src =\"" .
  $this->getResourceUri("images/twitter.png", "twitter")
  . "\" alt=\"Twitter\" style=\"margin-bottom: 3px; \"/><br />"
  . $objBlock->showBlock("tweetbox", "twitter")
  . $objBlock->showBlock("lasttweet", "twitter")
  . $objBlock->showBlock("followed", "twitter");
$cssLayout->setLeftColumnContent($statusUpdate);






//Add public timeline
$publicTimeline = $this->objTwitterRemote->showPublicTimeline();
$middleBit = "<table><tr><td>"
  . "<h3>" . $this->objLanguage->languageText("mod_twitter_pubtimeline", "twitter")
  . "</h3>" . $publicTimeline
  . "</td></tr></table>";
$cssLayout->setMiddleColumnContent($middleBit);







//Render it out
echo $cssLayout->show();
?>