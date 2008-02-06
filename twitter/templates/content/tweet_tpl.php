<?php
//Create an instance of the css layout class
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$objWidjet = $this->getObject("tweetbox","twitter");



//Right panel
$objUserParams = $this->getObject("dbuserparamsadmin","userparamsadmin");
$objUserParams->readConfig();
$userName = $objUserParams->getValue("twittername");
$password = $objUserParams->getValue("twitterpassword");
$this->objTwitterRemote->initializeConnection($userName, $password);
$statusUpdate = "<img src =\"" .
  $this->getResourceUri("images/twitter.png", "twitter")
  . "\" alt=\"Twitter\" /><br />"
  . $this->objTwitterRemote->showStatus(TRUE, FALSE);
$cssLayout->setLeftColumnContent($statusUpdate);

//Add public timeline
$publicTimeline = $this->objTwitterRemote->showPublicTimeline();
$middleBit = "<table><tr><td valign=\"top\">" 
  . $objWidjet->show() . "</td><td>" 
  . "<h3>" . $this->objLanguage->languageText("mod_twitter_pubtimeline", "twitter")
  . "</h3>" . $publicTimeline
  . "</td></tr></table>";
$cssLayout->setMiddleColumnContent($middleBit);




  
$cssLayout->setRightColumnContent("Wait a bit");

//Render it out
echo $cssLayout->show();
?>