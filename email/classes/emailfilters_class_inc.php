<?
/**
* Email filters class for NextGen Internal Email
* @author James Scoble
* @date 22 March 2005
*/
class emailfilters extends object
{
    // Basic class objects
    var $objUser;
    var $objConfig;
    var $objContextGroups;
    var $objLanguage;
    var $objModuleAdmin;
    var $objMail;

    function init()
    {
        // User Object
        $this->objUser=&$this->getObject('user','security');
        // Config Object
        $this->objConfig=&$this->getObject('config','config');
        // Language Object
        $this->objLanguage=&$this->getObject('language','language');
        // Context Groups object
        $this->objContextGroups = &$this->getObject( 'manageGroups', 'contextgroups');
    }

    /**
    * This is a method to return a list of all users with a 
    * specified role in a given context
    * It calls the contextUsers method in the manageGroups class of module contextgroups
    * And does not need to supply the context
    * @param string $type
    * @returns array $result;
    */
    function getContextUsers($type=NULL)
    {
        $result=array();
        $arrUsers = $this->objContextGroups->contextUsers($type);
        if (count($arrUsers)>0){
            foreach ($arrUsers as $line)
            {
                $result[]=array('userId'=>$line['userId'],'fullname'=>$line['fullName']);
            }
        }
        return $result;
    }
    
    /**
    * Method to get an array of "buddies" for email
    * It checks if the buddies module is registered first
    * @param string $userId
    * @returns array $result;
    */
    function getBuddies($userId)
    {   
        // Init the modulesadmin class and check if buddies is registered
        $this->objModuleAdmin=&$this->getObject('modulesadmin','modulelist');
        if ($this->objModuleAdmin->checkIfRegistered('buddies','buddies')){
            $result=array();
            // Now get a list of buddies
            $objDbBuddies=&$this->getObject('dbbuddies','buddies');
            $arrBuddies=$objDbBuddies->getBuddies($userId);
            if (count($arrBuddies)>0){
                foreach ($arrBuddies as $line)
                {
                    $result[]=array('userId'=>$line['buddyId'],'fullname'=>$line['Fullname']);
                }
            }
            return $result;
        } else {
            return FALSE;
        }
    }
    
    /**
    * method to return a simple array of userId's
    * Using an array with multiple fields per line
    * @param array $array
    * @returns array $result
    */
    function getOnlyUserId($array)
    {
        $result=array();
        if ((is_array($array))&&(count($array)>0)&&(array_key_exists('userId',$array))){
            foreach ($array as $line)
            {
                $result[]=$array['userId'];
            }
        }
        return $result;
    }

    /**
    * This is a method to display a list of alphabetic letters
    * which are links to show the users with surnames
    * beginning with that letter
    */
    function emailUserMenu()
    {
        $objAlpha=$this->newObject('alphabet','display');
        $link=$this->uri(array('action'=>'userlist','userfield'=>'LETTER'));
        $row=$objAlpha->putAlpha($link,TRUE,NULL,'userlist');
        return($row."\n");
    }
    
    /**
    * This method is a wrapper to get a list of the recent users
    * that the given userId has sentto or recieved from.
    * @param string $userId
    * @returns array $result
    */
     function getRecentContacts($userId)
     {
        $this->objMail=&$this->getObject('kngmail','email');
        $data=$this->objMail->getAll(" where sender_id='$userId' or user_id='$userId'");
        $result=array();
        $working=array();
        // Build an array of all the people sent to or recieved from
        foreach ($data as $line)
        {
            // Check for and discount error-message emails
            if ($line['sender_id']!='KNG'){
                $working[$line['sender_id']]=$this->objUser->fullname($line['sender_id']);
                $working[$line['user_id']]=$this->objUser->fullname($line['user_id']);
            }
        }
        // Now an array with userId and fullname in each row
        foreach ($working as $key=>$value)
        {
            if ($key!=NULL){
                $result[]=array('userId'=>$key,'fullname'=>$value);
            }
        }

        return $result;
        
     }
     
     /**
     * Method to build a javascript-based table of internal email links
     * @param array $data
     * @returns array $links
     */
     function showUserLinks($data)
     {
        $str='';
        if ((is_array($data))&&(count($data)>0)){
            array_multisort($data);
            foreach ($data as $line)
            {
                $username=$this->objUser->userName($line['userId']);
                $fullname=str_replace('\'','',$line['fullname']);
                $fullname=str_replace('\\','',$fullname);
                // Detect blank username
                if ($username!=''){
                    // Multi-line statement to build javascript link
                    $str.= "<a name='".$username."' href='javascript:sendEmailTo(\"".$fullname." <"
                    .$username.">\");' onMouseOver=\"window.status='"
                    .addslashes($fullname)."'; return true;\"; onMouseOut=\"window.status=''; \";>"
                    .$fullname."</a><br>\n";
                }
            }
        } else {
            $str.= "<b>".$this->objLanguage->languageText('mod_email_found_no_users').":</b><br />\n";
        }
        return $str;
     }
     
     /**
     * Method to show links to the various filters
     * This function checks if the user is in a "context", and if the buddies module is registered,
     * before displaying links to them
     * @returns string $str
     */
     function showFilterLinks()
     {
        $str='';
        $objDBContext=&$this->getObject('dbcontext','context');
        if ($objDBContext->isInContext()){
            $contextword=$this->objLanguage->languageText('mod_context_context','Context');
            $cUsers=$this->objLanguage->code2Txt('mod_email_filter1',array('CONTEXT'=>$contextword));
            $str.="<a target='userlist' href='".$this->uri(array('action'=>'userlist','userfield'=>'contextusers'))."'>&nbsp;$cUsers</a>&nbsp;|";
        }
        $this->objModuleAdmin=&$this->getObject('modulesadmin','modulelist');
        if ($this->objModuleAdmin->checkIfRegistered('buddies','buddies')){
            $buddies=$this->objLanguage->languageText('mod_email_filter2');
            $str.="<a target='userlist' href='".$this->uri(array('action'=>'userlist','userfield'=>'buddies'))."'>&nbsp;$buddies</a>&nbsp;|";
        }
            $recent=$this->objLanguage->languageText('mod_email_filter3');
        $str.="<a target='userlist' href='".$this->uri(array('action'=>'userlist','userfield'=>'recent'))."'>&nbsp;$recent</a>&nbsp;|";
        return $str;
     }        
}
?>
