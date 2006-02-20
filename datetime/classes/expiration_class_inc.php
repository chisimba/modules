<?php
/**
 * 
 * A class to provide features for working with date-sensitive information
 * that may expire on a certain date. It can be used, for example to see if
 * a story or news item has expired, to tag it with a timeout icon, to alter
 * the font used to display it, or to mark it old.
 * 
 * @author Derek Keats
 * @package datetime
 * 
 *  Example:
 *          //Check if expired, if so change font, add icon, & email owner
 *          if ( $objExp->hasExpired($expirationDate) ) {
 *              //put it in an error span
 *              $mainText = "<span class=\"error\">" 
 *                . $mainText . "</span>&nbsp;" 
 *                //add the expired clock icon
 *                . $objExp->getExpiredIcon(); 
 *              //Send an email to the owner of the content
 *              $objExp->sendExpiredMsg('dbstories', 'stories', 
 *                $userId, $title, $abstract, $id);
 *          }
 * 
 */

class expiration extends object {


    var $objConfig;
    var $objLanguage;
    
    /**
    * 
    * Standard KEWL.NextGen constructor function 
    * 
    */
    function init()
    {
        $this->objConfig=&$this->getObject('config','config');    
        $this->objLanguage = & $this->getObject("language", "language");
    } 
    
    /**
    * 
    * Method to check if a given date has past
    * 
    * @return TRUE | FALSE 
    * 
    */
    function hasExpired($strDate)
    {
        //Convert the date to be checked to unix timestamp
        $exCh = strtotime($strDate);
        //Get the date now as a unix timestamp
        $tdCh = time();
        if ( $tdCh > $exCh ) {
            return TRUE;                
        } else {
            return FALSE;
        }
    }
    
    /**
    * 
    * Method to return an expiration icon. It makes use of the icon object.
    * 
    */
    function getExpiredIcon()
    {
        $objClock = $this->newObject("geticon", "htmlelements");
        $objClock->setIcon("clock");
        $objClock->alt = $this->objLanguage->code2Txt("mod_stories_expired");
        return $objClock->show();
    }
    
    
    /**
    * 
    * Method to send a message to the content owner on expiration.
    * It checks if a message has been sent today, and if not it sends one.
    * If a message has already been sent, then it does not send another
    * in the same day. Note that the table needs to have a field called
    * expNotifDate for it to work.
    * 
    * @param string $dbClass The table class to 
    * @param string $pModule The module in which the data class resides
    * @param string $toId The userId of the user it is being sent to
    * @param string $subject The subject of the message
    * @param string $body The body of the message
    * @param string $itemId The id field of the notification item
    * 
    */
    function sendExpiredMsg($dbClass, $pModule, $toId, $title, $body, $itemId)
    {
        //Instantiate the connection to the appropriate table/module
        $objDb = $this->getObject($dbClass, $pModule);
        //Get the last expNotifDate from the database
        $ar = $objDb->getRow('id', $itemId);
        //Initialize $chk
        $chk = 0;
        //Get the notification date and convert to unix timestamp
        if ( $ar['notificationDate'] !== NULL ) {
            $notificationDate  = strtotime($ar['notificationDate']);
            //Get the unix timestamp now
            $nowDate=time();
            //Calculate the time elapsed since last notification
            $chk = $nowDate - $notificationDate;
        } else {
            $notificationDate = "";
        }
        //If a notification date has not been set or its more than 24 hrs old
        if ( $notificationDate==NULL || $notificationDate =="0000-00-00 00:00:00" || $chk > 86400) {
            //Establish the mail subject
            $rep=array('kng' => $this->objConfig->siteName());
            $subject = $this->objLanguage->code2Txt('mod_datetime_contentexpired', $rep);
            $mailBody = $title . "\n\n\n". $body;
            $objMail = & $this->getObject('kngmail', 'email');
            $objMail->sendMail('1', $toId, $subject, $mailBody);
            //Add the current date to the expNotifDate field
            $save=array('notificationDate' => date('Y-m-d H:m:s'));
            $objDb->update("id", $itemId, $save);
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /*
    * 
    * Method to tag expired content
    * 
    * @param String $str The string to be tagged.
    * 
    */
    function tagExpiredContent($str)
    {
        return $str . $this->getExpiredIcon();
    }
    
  
} // end class
?>
