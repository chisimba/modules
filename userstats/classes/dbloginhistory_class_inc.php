<?php
/* -------------------- stories class ----------------*/

/**
* Class for the stories table in the database
*/
class dbloginhistory extends dbTable
{

    var $objUser;
    var $objLanguage;

    /**
    * Constructor method to define the table
    */
    function init() {
        parent::init('tbl_userloginhistory');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language', 'language');
    }
    
    /**
    * 
    * Get the login history count from the database
    * 
    */
    function getLoginHistory()
    {
        $order= $this->getParam('order', 'surname');
        $sql="SELECT count(tbl_userloginhistory.userId) 
          AS logins, max(lastLoginDateTime) 
          AS lastOn, tbl_users.title, tbl_users.firstName,
          tbl_users.surname, tbl_users.country, 
          tbl_users.emailAddress FROM  tbl_userloginhistory
          LEFT JOIN tbl_users  ON tbl_userloginhistory.userId = tbl_users.userId
          GROUP BY tbl_userloginhistory.userId
          ORDER BY " . $order;
        return $this->getArray($sql);
    }
    
    /**
    * 
    * Method to get the total number of logins 
    * on the system
    * 
    */
    function getTotalLogins()
    {
        $sql="SELECT COUNT(userId) AS TotalLogins 
          FROM tbl_userloginhistory";
        $ar = $this->getArray($sql);
        return $ar[0]['TotalLogins'];
    }
    
    function getUniqueLogins()
    {
        $sql="SELECT COUNT(DISTINCT(userId)) 
          AS UniqueLogins FROM tbl_userloginhistory";
        $ar = $this->getArray($sql);
        return $ar[0]['UniqueLogins'];
    }
    
}  #end of class
?>