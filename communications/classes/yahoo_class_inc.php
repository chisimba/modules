<?php
/**
* 
* Class for working with Yahoo for a particular user.
* 
* @author Derek Keats
* 
* This class was written by Derek, but it drew extensively
* on code by other people who are listed in the appropriate
* function. Most importantly, I studied the code of Chip Cuccio 
* and Setec Astronomy.
* 
* 
* 
*/

class yahoo extends object 
{
   
    /**
    * 
    * @var object $objIcon Used to hold the icon object
    * 
    */
    var $objIcon;
    
    /**
    * 
    * @var object $objDbUserparams Used to hold the data object for getting user parameters
    * 
    */
    var $objDbUserparams;
    
    /**
    * The initialize method to set the default properties
    */
    function init()
    {
        $this->objLanguage=&$this->getObject('language', 'language');
        $this->objIcon = &$this->getObject('geticon', 'htmlelements');
        $this->objDbUserparams = & $this->getObject('dbuserparams', "userparams");
    }
    
    /**
    * This method allows to check the online status of an Yahoo! account.	
    * It connects directly to the Yahoo! status server.
    * 
    * @param string $yahoo : The user account on yahoo.
    */
    function getStatusIcon($yahooId, $mode='byyahooid')
    {
        if ( $mode == 'byuserid' ) {
            $yahooId = $this->objDbUserparams->getValue('YAHOO', $yahooId);
            if (!$yahooId) {
                $this->objIcon->alt=$this->objLanguage->languageText("mod_communications_yahoonoidreg");
                $this->objIcon->setIcon("yahoo_noid");
                return $this->objIcon->show();
            }
        }
        //Return a different icon depending on status
        switch ( $this->getYahooStatus($yahooId) ) {
            case "yahoo_offline":
                $this->objIcon->setIcon("yahoo_off");
                $this->objIcon->alt=$this->objLanguage->languageText("mod_communications_yahoooff");
                return $this->objIcon->show();
                break;
            case "yahoo_online":
                $this->objIcon->setIcon("yahoo_on");
                $this->objIcon->alt=$this->objLanguage->languageText("mod_communications_yahooon");
                return $this->objIcon->show();
                break;
            case "yahoo_noconnection":
                $this->objIcon->setIcon("yahoo_nocon");
                $this->objIcon->alt=$this->objLanguage->languageText("mod_communications_yahoonocon");
                return $this->objIcon->show();
                break;
            case "yahoo_unknown":
            default:
                $this->objIcon->setIcon("yahoo_on");
                $this->objIcon->alt=$this->objLanguage->languageText("mod_communications_yahoounk");
                return $this->objIcon->show();
                break;
        } #switch

    } 

    /**
    * 
    * Method to get the online status for the yahooid
    * and return it to the calling function
    *
    * @var string $yahooId The Yahoo user to look up
    * @return the status of the user as string
    * 
    */
    function getYahooStatus($yahooId)
    {
        //The yahoo server
        $yServer = "http://opi.yahoo.com/online?u=";
        //open the connection
        $lines = @file ($yServer . $yahooId . "&m=t");
        if ($lines) {
            $response = implode ("", $lines);
            if (strpos ($response, "NOT ONLINE") !== false) {
                return "yahoo_offline";
            } elseif (strpos ($response, "ONLINE") !== false) {
                return "yahoo_online";
            } else {
                return "yahoo_unknown";
            } 
        } else {
            return "yahoo_noconnection";
        } 
    
    }
    

}  // Class

?>
