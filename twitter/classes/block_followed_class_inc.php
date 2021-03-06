<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
*
* The class provides a hello world block to demonstrate
* how to use blockalicious
*
* @author Derek Keats
*
*/
class block_followed extends object
{
    var $title;

    /**
    * Constructor for the class
    */
    function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->title=$this->objLanguage->languageText("mod_twitter_followed", "twitter");
    }

    /**
    * Method to output a Tweet block
    */
    function show()
    {
        $objUserParams = $this->getObject("dbuserparamsadmin","userparamsadmin");
        //This enables the thing to work as a blog plugin
        $objGuess = $this->getObject('bestguess', 'utilities');
        $un = $objGuess->guessUserName();
        $objUserParams->setUid($un);
        $objUserParams->readConfig();
        $userName = $objUserParams->getValue("twittername");
        $password = $objUserParams->getValue("twitterpassword");
        if ($userName!==NULL && $password !==NULL) {
            $objTwitterRemote = $this->getObject('twitterremote', 'twitter');
            $objTwitterRemote->initializeConnection($userName, $password);
            return $objTwitterRemote->showFollowed();
        } else {
            return $this->objLanguage->languageText("mod_twitter_nologonshort", "twitter");
        }
    }
}
?>