<?php
/* ------------------- modulesAdmin class extends modules ------------- */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Descendant of modules which adds administrative functionality.
* Dividing the class in two like this avoids loading this
* file when only the basic user functionality is needed.
* @author Derek Keats
* @author Sean Legassick
* @author James Scoble
* @author Megan Watson
* @author Jeremy O'Connor
* $Id$
*/

// PEAR
require_once "DB.php";

// 5ive
require_once "modules_class_inc.php";

class modulesAdmin extends modules
{

    // replaces use of defined constant.
    private $MODULE_ID; 
    private $MODULE_NAME;
    private $MODULE_DESCRIPTION;

    private $objTableInfo; //handle for tableinfo class object
    private $objKeyMaker; //handle for primary key generation
    private $update=FALSE;
    public $output=''; // for any feedback messages from internal functions.
    private $errorText=''; // for any feedback messages from internal functions.

    /**
    * This is a method to check if the module is registered already.
    * Returns TRUE if the module is registered and FALSE if
    * it is not registered.
    * @param string $moduleName The name of the module, in
    * a form that is meaningful to users, for example
    * 'Show users who are logged in'
    * @param string $moduleId The identifier of the module,
    * @returns boolen TRUE|FALSE
    * @deprecated Already in modules_class_inc.php
    */
    /*
     public function checkIfRegistered($moduleName, $moduleId=NULL)
     {
        if (is_null($moduleId)){
            $moduleId=$moduleName;
        }
        return !empty($this->getRow('module_id',$moduleId));
     }  #end of checkIfRegistered() function
    */

    /**
    * This is a method to check if a dependent module is installed.
    * @param string $moduleId The moduleId of the module on which the
    * current module depends
    * @returns boolean TRUE|FALSE
    */
    public function checkDependency($moduleId) {
        return $this->checkIfRegistered(NULL,$moduleId);
    } #end of checkDependency() function

    /**
    * @deprecated See checkDependantModules
    */
    public function checkForDependentModules($moduleId)
    {
        return $this->checkDependentModules($moduleId);
    }

    /**
    * This is a method to check for modules that depend on this one
    * 14/06/2004 - I've changed things to fit the framework- James.
    * @author Derek Keats ?
    * @author James Scoble
    * @param string $moduleId The module ID
    * @return array
    */
    public function checkDependentModules($moduleId)
    {
        $sql="SELECT module_id FROM tbl_modules_dependencies WHERE dependency='$moduleId'";
        $rs = $this->getArray( $sql );
        $dep=array();
        foreach ($rs as $rec)
         {
          $dep[]=$rec['module_id'];
         }
        return $dep;
    } #end of checkForDependentModules() function

    /**
    * This is a method to check if a module is registered and turn the result as an array
    * @param string $moduleId
    * @returns array $result
    */
    public function getModuleInfo($moduleId)
    {
        if ($this->checkIfRegistered($moduleId)){
            $result = array(
                'isreg'=>TRUE,
                'name'=>$this->objLanguage->code2Txt('mod_'.$moduleId.'_name')
            );
        }
        else {
            $result=array(
                'isreg'=>FALSE,
                'name'=>''
            );
        }
        return $result;
    }

    /**
    * This is a method to register the module. It stores the module information in
    * the database table tbl_modules, creates any needed SQL tables,
    * adds languagetext elements, moves icons, etc. All based on info from
    * the module's 'register.conf' file.
    * @author Derek Keats ?
    * @author James Scoble
    * @author Megan Watson
    * @param array $registerdata - all the info from the register.conf file.
    * @returns mixed OK | FALSE
    */
    public function registerModule(&$registerdata)
    {
        try {
            if (isset($registerdata['MODULE_ID']))
            {
                $moduleId=$registerdata['MODULE_ID'];
            }
            else
            {
                return FALSE; // If we can't find the name of the module we're supposed to be registering, what are we doing here?
            }
    
            $this->MODULE_ID=$registerdata['MODULE_ID'];
            $this->MODULE_NAME=$registerdata['MODULE_NAME'];
            $this->MODULE_DESCRIPTION=$registerdata['MODULE_DESCRIPTION'];
    
            $this->localQuery('BEGIN WORK'); //Start a transaction;
    
            //If the module already exists, do not register it, else register it
            if ($this->checkIfRegistered($this->MODULE_NAME, $moduleId) && !$this->update)
            {
                return FALSE;
            }
            else
            {
                // check for modules this one is dependant on
                if (isset($registerdata['DEPENDS']))
                {
                    foreach ($registerdata['DEPENDS'] as $depends)
                    {
                        if (!$this->checkDependency($depends))
                        {
                            $text=$this->objLanguage->languageText('mod_moduleadmin_needmodule');
                            $text=str_replace('{MODULE}',$depends,$text);
                            $this->output.='<b>'.$text.'</b><br />';
                            return FALSE;
                        }
                    }
                }
                // Now we add the tables
                if (isset($registerdata['TABLE']))
                {
                    //$this->objKeyMaker=$this->newObject('primarykey','moduleadmin');
                    //$this->objTableInfo=$this->newObject('tableinfo','moduleadmin'); 
                    foreach ($registerdata['TABLE'] as $table)
                    {
                        if (!$this->makeTable($table))
                        {
                            $text=$this->objLanguage->languageText('mod_moduleadmin_needinfo');
                            $text=str_replace('{MODULE}',$dline,$text);
                            $this->output.='<b>'.$text.'</b><br />';
                            return FALSE;
                        }
                        else
                        {
                            // Delete the table from the records.
                            $sql="DELETE FROM tbl_modules_owned_tables WHERE kng_module='".$moduleId."' and tablename='".$dline."'";
                            $this->localQuery( $sql );
                            // Add the table to the records.
                            $sql="INSERT INTO tbl_modules_owned_tables (kng_module,tablename) VALUES ('".$moduleId."','".$dline."')";
                            $this->localQuery($sql); 
                        }
                    }
                }
    
                // Here we load data into tables from files of SQL statements
                if (isset($registerdata['BIGDATA']))
                {
                    //$this->objKeyMaker=$this->newObject('primarykey','moduleadmin');
                    foreach ($registerdata['BIGDATA'] as $bigdata)
                    {
                        $this->loadData($bigdata);
                    }
                }
    
                // Here we create a SOAP file
                //if (isset($registerdata['SOAP_CONTROLLER'][0])&&($registerdata['SOAP_CONTROLLER'][0]==1))
                //{
                //    $this->soapFileMaker($moduleId);
                //}
    
                // Create directory and subdirectory
                if(isset($registerdata['DIRECTORY'])){
                    foreach ($registerdata['DIRECTORY'] as $directory) {
                        $path = 
                            $this->objConfig->contentBasePath()
                            .'/'.$directory
                            .'/';
                        if (!is_dir($path)) {
                            mkdir($path, 0777);
                        }
                    }
                }
    
                /* @deprecated SUBDIRECTORY */
    
                // Set up data for the site navigation: toolbar, sidemenus and pages
    
                $isAdmin = 0; 
                $isContext = 0; 
                $aclList = ''; 
                $permList = array(); 
                $groupArray = array();
                $groupArray2 = array();
    
                if(isset($registerdata['MODULE_ISADMIN'])){
                    $isAdmin = $registerdata['MODULE_ISADMIN'];
                }
    
                if(isset($registerdata['DEPENDS_CONTEXT'])){
                    $isContext = $registerdata['DEPENDS_CONTEXT'];
                }
    
                /*
                Set up permissions for the module.
                Set up a module specific ACL, set up module specific groups and add 
                them to the acl.
                If there is no ACL, set up groups.
                */
    
                if(isset($registerdata['ACL'][0])){
                    $objPerm = $this->getObject('permissions_model', 'permissions');
                    $objGroups = $this->getObject('groupAdminModel', 'groupadmin');
    
                    foreach($registerdata['ACL'] as $regAcl){
                        $perms = explode('|', $regAcl);
    
                        if(isset($perms[0]) && !empty($perms[0])){
                            $aclId = $objPerm->newAcl($moduleId.'_'.$perms[0], $moduleId.' '.$perms[0]);
                            if(empty($aclList)){
                                $aclList = $aclId;
                            }else{
                                $aclList .= ','.$aclId;
                            }
                            $permList[] = $perms[0];
    
                            if(isset($perms[1]) && !empty($perms[1])){
                                $groups = explode(',', $perms[1]);
                                foreach($groups as $group){
                                    $groupId = $objGroups->addGroup($moduleId.'_'.$group, $moduleId
                                    .' '.$group);
                                    $objPerm->addAclGroup($aclId, $groupId);
                                    $groupArray[] = $group;
                                }
                            }
                        }else{
                            if(isset($perms[1]) && !empty($perms[1])){
                                $groups = explode(',', $perms[1]);
                                foreach($groups as $group){
                                    $groupId = $objGroups->addGroup($moduleId.'_'.$group, $moduleId
                                    .' '.$group);
                                    $groupArray[] = $group;
                                }
                            }
                        }
                    }
                }
    
                // Link existing groups with access to the module.
                // First check if the group exists and create it if it doesn't.
                if(isset($registerdata['USE_GROUPS'][0])){
                    $objGroups = $this->getObject('groupAdminModel', 'groupadmin');
                    $groupList = '';
    
                    foreach($registerdata['USE_GROUPS'] as $group){
                        $grId = $objGroups->getId($group);
                        if(empty($grId)){
                            $objGroups->addGroup($group, $moduleId.' '.$group);
                        }
                        $groupArray2[] = $group;
                        if(empty($groupList)){
                            $groupList = $group;
                        }else{
                            $groupList .= ','.$group;
                        }
                    }
                    $aclList .= '|'.$groupList;
                }
    
                // Link existing groups with access to a context dependent module
                if(isset($registerdata['USE_CONTEXT_GROUPS'][0])){
                    $objGroups = $this->getObject('groupAdminModel', 'groupadmin');
                    $contextGroupList = '';
    
                    foreach($registerdata['USE_CONTEXT_GROUPS'] as $conGroup){
                        if(empty($contextGroupList)){
                            $contextGroupList = $conGroup;
                        }else{
                            $contextGroupList .= ','.$conGroup;
                        }
                    }
                    $aclList .= '|_con_'.$contextGroupList;
                }
    
                // Create a condition type
                if(isset($registerdata['CONDITION_TYPE'][0])){
                    $objType =& $this->getObject('conditiontype','decisiontable');
                    foreach($registerdata['CONDITION_TYPE'] as $val){
                        $array = explode('|', $val);
                        $class = $array[0];
                        if(isset($array[1])){
                            $types = explode(',', $array[1]);
                            foreach($types as $type){
                                $objType->create($type, $class, $moduleId);
                                $objType->insert();
                            }
                        }
                    }
                }
    
                /* Create conditions.
                    Create a condition in the decisiontable, returns the condition object.
                    Populate an array with condition objects for use in creating rules.
                */
                $conditions = array();
                if(isset($registerdata['CONDITION'][0])){
                    $objCond =& $this->getObject('condition','decisiontable');
                    foreach($registerdata['CONDITION'] as $condition){
                        $array = explode('|', $condition);
                        if(isset($array[2]) && !empty($array[2])){
                                    $list = explode(',', $array[2]);
                        }else{
                            $list = '';
                        }
                        $paramList = array();
    
                        if($array[1] == 'hasPermission'){
                            foreach($permList as $perm){
                                foreach($list as $val){
                                    if($perm == $val){
                                        $val = $moduleId.'_'.$perm;
                                        $paramList[] = $val;
                                    }
                                }
                            }
                        }else if($array[1] == 'isMember'){
                            foreach($list as $val){
                                foreach($groupArray as $perm){
                                    if($perm == $val){
                                        $val = $moduleId.'_'.$perm;
                                        $paramList[] = $val;
                                    }
                                }
                                foreach($groupArray2 as $perm2){
                                    if($perm2 == $val){
                                        $val = $perm2;
                                        $paramList[] = $val;
                                    }
                                }
                            }
                        }else{
                            $paramList = $list;
                        }
    
                        $name = $array[0];
                        if(!empty($paramList)){
                            $paramList = implode(',', $paramList);
                                    $params = $array[1].$objCond->_delimiterFunc.$paramList;
                            }else{
                            $params = $array[1];
                        }
                        $conditions[$name] = $objCond->create($name, $params);
                    }
                }
    
                // Use existing conditions
                if(isset($registerdata['USE_CONDITION'][0])){
                    $objCond =& $this->getObject('condition','decisiontable');
                    foreach($registerdata['USE_CONDITION'] as $condition){
                        $array = explode('|', $condition);
                        $name = $array[0];
                        $conditions[$name] = $objCond->create($name);
                    }
                }
    
                /* Create rules.
                    Create the decisiontable for the module.
                    Create the action in the decisiontable, returns the action object.
                    Create the rule in the decisiontable, returns the rule object.
                    Add the action object to the rule object.
                    Add the condition object to the rule object.
                */
                if(isset($registerdata['RULE'][0])){
                    $objDecisionTable =& $this->getObject('decisiontable','decisiontable');
                    $objAction =& $this->getObject('action','decisiontable');
                    $objAction->connect($objDecisionTable);
                    $objRule =& $this->getObject('rule','decisiontable');
                    $objRule->connect($objDecisionTable);
                    $i = 1;
    
                    // Create the decision table
                    $modTable = $objDecisionTable->create($moduleId);
    
                    foreach($registerdata['RULE'] as $rule){
                        $ruleName = $moduleId.' rule '.$i++;
                        $array = explode('|', $rule);
                        $actionList = explode( ',', $array[0] );
                        $conditionList = explode( ',', $array[1] );
    
                        // Create rule object and add to the decision table
                        $rule = $objRule->create($ruleName);
                        // Add the rule to the decision table.
                        $objDecisionTable->addRule( $rule );
    
                        // Create action object and add to decision table.
                        foreach( $actionList as $anAction ) {
                            $arrActions[$anAction] = $objAction->create($anAction);
                            // Add the action to the decision table.
                            $objDecisionTable->add( $arrActions[$anAction] );
                            // Add the rule to the action
                            $arrActions[$anAction]->add($rule);
                        }
    
                        // Add the condition to the rule
                        foreach( $conditionList as $aCondition ) {
    
                            $rule->add($conditions[$aCondition]);
                        }
                    }
                }
    
                // end Permissions and Security
    
                // Site Navigation
    
                // Menu category
    
                if (isset($registerdata['MENU_CATEGORY']))
                {
                    foreach ($registerdata['MENU_CATEGORY'] as $menu_category)
                    {
                        $menu_category=strtolower($menu_category);
                        $sql="INSERT INTO tbl_menu_category (
                            id,
                            category,
                            module,
                            adminOnly,
                            permissions,
                            dependsContext
                        )
                        VALUES (
                            'init@".time().rand(1000,9999)."'
                            ,'$menu_category'
                            ,'$moduleId'
                            ,'$isAdmin'
                            ,'$aclList'
                            ,'$isContext'
                        )";
                        $this->localQuery($sql);
                    }
                }// end menu category
    
                // Side menus
                if (isset($registerdata['SIDEMENU']))
                {
                    $objGroups = $this->getObject('groupAdminModel', 'groupadmin');
                    foreach ($registerdata['SIDEMENU'] as $sidemenu)
                    {
                        $admin = $isAdmin;
                        $groupList = '';
                        $sidemenu=strtolower($sidemenu);
                        $actions = explode('|', $sidemenu);
                        if(isset($actions[1]) && !empty($actions[1])){
                            $sidemenu = str_replace($actions[1],'',$sidemenu);
                            $conGroups = ''; 
                            $siteGroups = ''; 
                            $acls = '';
                            $access = explode(',',$actions[1]);
                            $admin = 0;
                            foreach($access as $val){
                                // check for context groups
                                if(!(strpos($val, 'con_') === FALSE)){
                                    if(!empty($conGroups)){
                                        $conGroups .= ',';
                                    }
                                    $conGroups .= ucwords(str_replace('con_','',$val));
                                }
                                // check for module permissions, create if don't exist
                                else if(!(strpos($val, 'acl_') === FALSE)){
                                    $perm = str_replace('acl_','',$val);
                                    $permId = $objPerm->getId($moduleId.'_'.$perm);
                                    if(empty($permId)){
                                        $permId = $objPerm->newAcl($moduleId.'_'.$perm, $moduleId
                                        .' '.$perm);
                                    }
                                    if(!empty($acls)){
                                        $acls .= ',';
                                    }
                                    $acls .= $permId;
    
                                }
                                // check for module groups, create if don't exist
                                else{
                                    // check for sitewide access
                                    if(strtolower($val) == 'site'){
                                        $siteGroups .= 'site';
                                    }else{
                                        $grId = $objGroups->getId($val);
                                        $group = ucwords($val);
                                        if(empty($grId)){
                                            $group = $moduleId.'_'.ucwords($val);
                                            $grId = $objGroups->getId($group);
                                            if(empty($grId)){
                                                $objGroups->addGroup($group, $moduleId.' '.$val);
                                            }
                                        }
                                        if(!empty($siteGroups)){
                                            $siteGroups .= ',';
                                        }
                                        $siteGroups .= $group;
                                    }
                                }
                            }
                            // build permissions string
                            $groupList = $acls.'|'.$siteGroups.'|_con_'.$conGroups;
                        }
                        else {
                            $groupList = $aclList;
                        }
                        $sql="INSERT INTO tbl_menu_category (
                            id, 
                            category, 
                            module,
                            adminOnly,
                            permissions,
                            dependsContext
                        )
                        VALUES (
                            'init@".time().rand(1000,9999)."'
                            ,'menu_{$line}'
                            ,'$moduleId'
                            ,'$admin'
                            ,'$groupList'
                            ,'$isContext'
                        )";
                        $this->localQuery($sql);
                    }
                }// end side menu
    
                // admin and lecturer pages
                if(isset($registerdata['PAGE'][0])){
                    foreach($registerdata['PAGE'] as $line){
                        $actions = explode('|',$line);
                        $pages = explode(',',$actions[0]);
                        $admin = 0;
                        foreach($pages as $page){
                            if(!(strpos($page, 'admin')===FALSE)){
                                $admin = 1;
                            }
                            if(!(strpos($page, 'lecturer')===FALSE)){
                                $admin = 0;
                            }
                        }
                        $sql = "INSERT INTO tbl_menu_category (
                            id, 
                            category, 
                            module,
                            adminOnly, 
                            permissions, 
                            dependsContext
                        ) 
                        VALUES ('
                            'init@".time().rand(1000,9999)."'
                            ,'page_{$line}'
                            ,'$moduleId'
                            ,'$admin'
                            ,'$aclList'
                            ,'$isContext'
                        )";
                        $this->localQuery($sql);
                    }
                }// end pages
    
                // end Site Navigation
    
                // Here we pass CONFIG data to the sysconfig module
                if (isset($registerdata['CONFIG']))
                {
                    $this->objSysConfig=&$this->getObject('dbsysconfig','sysconfig');
                    $this->objSysConfig->registerModuleParams($moduleId,$registerdata['CONFIG']);
                }
    
                // Icons
                if (isset($registerdata['ICON'][0]))
                {
                    $this->moveIcons($moduleId,$registerdata['ICON']);
                }
    
                // Now the main data entry - building up arrays of the essential params
                $sql_arr = array(
                    'module_id' => $moduleId
                    ,'module_authors' => addslashes($registerdata['MODULE_AUTHORS'])
                    ,'module_releasedate' => $registerdata['MODULE_RELEASEDATE']
                    ,'module_version' => $registerdata['MODULE_VERSION']
                    ,'module_path' => $registerdata['MODULE_PATH']
                    ,'isAdmin' => $registerdata['MODULE_ISADMIN']
                    ,'isVisible' => $registerdata['MODULE_ISVISIBLE']
                    ,'hasAdminPage' => $registerdata['MODULE_HASADMINPAGE']
                );
                if (isset($registerdata['CONTEXT_AWARE'])){
                    $sql_arr['isContextAware']=$registerdata['CONTEXT_AWARE'];
                }
                if (isset($registerdata['DEPENDS_CONTEXT'])){
                    $sql_arr['dependsContext']=$registerdata['DEPENDS_CONTEXT'];
                }
                $this->localInsert($sql_arr);
                if ($this->update) {
                    $this->localUpdate($sql_arr,"WHERE module_id='$moduleId'");
                }
                //indicate success
                // put the language information for name and description
                $this->registerModuleLanguageElements();
                // insert the list of language codes used by the module if any
                /*
                if (defined('MODULE_LANGTERMS'))
                {
                    $this->registerModuleLanguageTerms(MODULE_LANGTERMS);
                }
                */
                $texts=$this->listTexts($registerdata); // get list of all specified texts
                if ($texts !== false) {
                    foreach ($texts as $key=>$value)
                    {
                        $test=$this->checkText($key);
                        if ($test['flag']!=11)
                        {
                            $this->addText($key,$value['desc'],$value['content']);
                        }
                    }
                }
                $texts=$this->listTexts($registerdata,'USES');
                if ($texts !== false)
                {
                    foreach ($texts as $key=>$value)
                    {
                        $test=$this->checkText($key);
                        if ($test['flag']!=11)
                        {
                            $this->addText($key,$value['desc'],$value['content']);
                        }
                    }
                }
                if (isset($registerdata['DEPENDS'][0]))
                {
                    $this->registerDependentModules($moduleId,$registerdata['DEPENDS']);
                }
                // $sql="DELETE FROM tbl_languagetext WHERE whereUsed='".$moduleId."'";
            }
            $this->localQuery('COMMIT'); //End the transaction;
            return "OK";
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit(0);
        }
    } #end of registerModule() function

    /**
    * This is a method to uninstall (deregister) a module.
    * This method should check for modules that depend on the current module
    * and refuse to uninstall where there are dependencies. Instead of uninstalling
    * a module that has dependencies, it should give the option to remove the user
    * interface files and set the module isVisible flag to 0
    * 14/06/2004 - I've changed things to fit the framework- James
    * @author Derek Keats ?
    * @author James Scoble
    * @param string $moduleId the id of the module
    * @param string $registerdata - array of info from the registration file
    * @returns boolean TRUE or FALSE
    */
    public function deregisterModule($moduleId,&$registerdata)
    {
        if (is_null($moduleId)) {
            $moduleId=$registerdata['MODULE_ID'];
        }
        $modTitle="mod_{$moduleId}_name";
        $modDescription="mod_{$moduleId}_desc";
        //Check if there are modules that depend on this one
        $dependantModules=$this->checkForDependentModules($moduleId);
        if (!empty($dependantModules))
        {
            $str="<b>".$this->objLanguage->languageText('mod_hasdependants')."</b><br/>";
            foreach ($dependantModules as $dependantModule)
            {
                $str.=$dependantModule."<br />";
            }
            $this->output.= $str;
            return FALSE;
        }
        else
        {
            $this->localQuery('BEGIN WORK'); //Start a transaction;

            $sql="DELETE FROM tbl_english WHERE code='{$modTitle}'";
            $this->localQuery($sql);
            $sql="DELETE FROM tbl_languagetext WHERE code='{$modTitle}'";
            $this->localQuery($sql);

            $sql="DELETE FROM tbl_english WHERE code='{$modDescription}'";
            $this->localQuery($sql);
            $sql="DELETE FROM tbl_languagetext WHERE code='{$modDescription}'";
            $this->localQuery($sql);

            $texts=$this->listTexts($registerdata); // remove all specified texts
            if ($texts!==FALSE)
            {
                foreach ($texts as $key=>$value)
                {
                    $this->removeText($key);
                }
            }

            // Remove groups and acls for the module
            if(isset($registerdata['ACL'][0])){
                $objPerms = $this->getObject('permissions_model','permissions');
                $objGroups = $this->getObject('groupadminmodel','groupadmin');
                foreach($registerdata['ACL'] as $perm){
                    $perms = explode('|', $perm);
                    $aclId = $objPerms->getId($moduleId.'_'.$perms[0]);
                    $objPerms->deleteAcl($aclId);
                    if(isset($perms[1]) && !empty($perms[1])){
                        $groups = explode(',', $perms[1]);
                        foreach($groups as $group){
                            $groupId = $objGroups->getId($moduleId.'_'.$group);
                            $objGroups->deleteGroup($groupId);
                        }
                    }
                }
            }

            // Remove decisiontable rules and actions
            $objDecisionTable =& $this->getObject('decisiontable','decisiontable');
            $objDecisionTable->create($moduleId);
            $objDecisionTable->retrieve();
            $objDecisionTable->delete();

            // Remove module specific conditions
            if(isset($registerdata['CONDITION'])){
                $objCond =& $this->getObject('condition','decisiontable');
                foreach($registerdata['CONDITION'] as $condition){
                    $array = explode('|', $condition);
                    $name = $array[0];
            if(isset($array[2]) && !empty($array[2])){
                        $params = $array[1].'|'.$array[2];
            }else{
                $params = $array[1];
            }
            $conditions[$name] = $objCond->create($name, $params);
                    $conditions[$name]->retrieveId();
                    $conditions[$name]->delete();
                }
            }

            // Remove navigation links
            $sql="DELETE FROM tbl_menu_category WHERE module='$moduleId'";
            $this->localQuery($sql);

            // Here we remove CONFIG data from the sysconfig module
            $this->objSysConfig=&$this->getObject('dbsysconfig','sysconfig');
            $this->objSysConfig->deleteModuleValues($moduleId);

            // Here we remove any SOAP files
            /*
            if (isset($registerdata['SOAP_CONTROLLER'][0])&&($registerdata['SOAP_CONTROLLER'][0]==1))
            {
                $this->soapFileRemover($moduleId);
            }
            */

            // Drop tables
            $droppedTables=$this->dropTables($moduleId);

            $sql="DELETE FROM tbl_modules_owned_tables WHERE kng_module='$moduleId'";
            $this->localQuery($sql);

            $sql="DELETE FROM tbl_modules WHERE module_id='$moduleId'";
            $this->localQuery($sql);

            $sql="DELETE FROM tbl_language_modules WHERE module_id='$moduleId'";
            $this->localQuery($sql);

            $sql="DELETE FROM tbl_modules_dependencies WHERE module_id='$moduleId'";
            $this->localQuery($sql);

            $this->localQuery('COMMIT'); //End the transaction;

            return TRUE;
        }
    }  #end of deregisterModule() function

    /**
    * This method looks at the registration data and tries to create any tables specified
    * @param array $tables
    * @returns boolean TRUE|FALSE
    * @deprecated No longer used
    */
    private function makeTables($tables)
    {
        try {
            $this->objKeyMaker=&$this->newObject('primarykey','moduleadmin');
            $this->objTableInfo=&$this->newObject('tableinfo','moduleadmin'); 
            foreach ($tables as $table)
            {
                $this->makeTable($table);
                /*
                $text=$this->objLanguage->languageText('mod_moduleadmin_needinfo');
                $text=str_replace('{MODULE}',$dline,$text);
                $this->output.='<b>'.$text.'</b><br />';
                return FALSE;
                */
                $sql="DELETE FROM tbl_modules_owned_tables WHERE kng_module='$moduleId' AND tablename='$table'";
                $this->localQuery( $sql );
                $sql="INSERT INTO tbl_modules_owned_tables (kng_module,tablename) VALUES ('$moduleId','$table')";
                $this->localQuery($sql); // Add the table to the records.
            }
            return TRUE;
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit(0);
        }
    }

    /**
    * This is a method to read data from a file and use it to create a table.
    * @author James Scoble
    * @param string $tablefile The file that contains the sql
    * @param string $moduleId The id of the module
    * @returns boolean TRUE|FALSE
    */
    private function makeTable($tablefile,$moduleId='NONE')
    {
        if ($moduleId=='NONE'){
            $moduleId=$this->MODULE_ID;
        }
        $this->objTableInfo->tablelist();
        if ($this->objTableInfo->checktable($tablefile))
        {
            return TRUE; // table already exists, don't try to create it over again!
        }
        $sqlfile=$this->objConfig->siteRootPath().'/modules/'.$moduleId.'/'.$tablefile.'.sql';
        if (!file_exists($sqlfile)){
            $sqlfile=$this->objConfig->siteRootPath().'/modules/'.$moduleId.'/sql/'.$tablefile.'.sql';
        }
        if (!file_exists($sqlfile)){
            throw new Exception("<b>$sqlfile</b>".$this->objLanguage->languageText('phrase_notfound')."<br />");
        }
        include($sqlfile);
        foreach ($sqldata as $sql)
        {
            $sql=str_replace('PKVALUE',($this->objKeyMaker->newkey($tablefile)),$sql);
            $this->localQuery($sql);
        }
        return TRUE;
    }

   /**
    * function loadData()
    * This is a method to read data from a file and use it to populate (not create) a table.
    * @author James Scoble
    * @param $tablefile the name of the file
    * @param string $moduleId the id of the module to be used
    * @returns boolean TRUE or FALSE
    */
    private function loadData($tablefile,$moduleId='NONE')
    {
        if ($moduleId=='NONE'){
            $moduleId=$this->MODULE_ID;
        }
        $sqlfile=$this->objConfig->siteRootPath().'/modules/'.$moduleId.'/'.$tablefile.'.sql';
        if (!file_exists($sqlfile)){
            $sqlfile=$this->objConfig->siteRootPath().'/modules/'.$moduleId.'/sql/'.$tablefile.'.sql';
        }
        if (!file_exists($sqlfile))
        {
            throw new Exception("<b>$sqlfile</b>".$this->objLanguage->languageText('phrase_notfound')."<br />");
        }
        ini_set('max_execution_time','120');
        $handle=fopen($sqlfile,'r');
        while (!feof($handle))
        {
            $line=fgets($handle,16384); // 16KB
            $line=str_replace('PKVALUE',($this->objKeyMaker->newkey($tablefile)),$line);
            $this->localQuery($line);
        }
        fclose($handle);
        return TRUE;
    }

    /**
    * This is a method to move icons when registering
    * @param string $moduleId the module
    * @param array $icons the list of icons
    */
    private function moveIcons($moduleId,$icons)
    {
        $srcdir=$this->objConfig->siteRootPath().'/modules/'.$moduleId.'/icons/';
        $destdir=$this->objConfig->siteRootPath().'skins/'.$this->objConfig->defaultSkin().'/icons/';
        foreach ($icons as $icon)
        {
            copy($srcdir.$icon,$destdir.$icon);
        }
    }

    /**
    * This is a method to put the module information into the language table
    * It first inserts the name of the module and then inserts the
    * description of the module into the English column
    */
    private function registerModuleLanguageElements() {
        $modTitle="mod_".$this->MODULE_ID."_name";
        $modDescription="mod_".$this->MODULE_ID."_desc";
        $isInNextGen=TRUE;
        $sql="DELETE FROM tbl_languagetext WHERE code='".$modTitle."'";
        $this->localQuery($sql);
        $sql="DELETE FROM tbl_languagetext WHERE code='".$modDescription."'";
        $this->localQuery($sql);
        $sql="DELETE FROM tbl_english WHERE code='".$modTitle."'";
        $this->localQuery($sql);
        $sql="DELETE FROM tbl_english WHERE code='".$modDescription."'";
        $this->localQuery($sql);
        $sql="INSERT INTO tbl_languagetext (code,description) VALUES ('".$modTitle."', '".addslashes($this->MODULE_NAME)."')";
        $this->localQuery($sql);
        $sql="INSERT INTO tbl_languagetext (code,description) VALUES ('".$modDescription."', '".addslashes($this->MODULE_DESCRIPTION)."')";
        $this->localQuery($sql);
        $sql="INSERT INTO tbl_english (code, Content,isInNextGen) VALUES ('".$modTitle."', '".addslashes($this->MODULE_NAME)."', '".$isInNextGen."')";
        $this->localQuery($sql);
        // Store the description
        $sql="INSERT INTO tbl_english (code, Content, isInNextGen) VALUES ('".$modDescription."', '".addslashes($this->MODULE_DESCRIPTION)."', '".$isInNextGen."')";
        $this->localQuery($sql);
    } #end of registerModuleLanguageElements() function

    /**
    * This is a method to add language terms to the database
    * @param string $terms A comma delimited string of
    * terms that are used in the language database
    */
    private function registerModuleLanguageTerms($terms) {
        $terms_arr=explode(',', $terms);
        foreach ($terms_arr as $term) {
            $sql="INSERT INTO tbl_language_modules (module_id, code) VALUES ('{$this->MODULE_ID}', '$term')";
            $this->localQuery($sql);
        }
    } #end of registerModuleLanguageTerms() function

    /**
    * Registers modules that this module depends on
    * @author James Scoble
    * @param string $moduleId The module ID
    * @param $modulesNeeded array The modules this module depends on
    *
    */
    private function registerDependentModules($moduleId,$modulesNeeded)
    {
        foreach ($modulesNeeded as $moduleNeeded)
        {
            $sql="INSERT INTO tbl_modules_dependencies (module_id,dependency) VALUES ('$moduleId','$moduleNeeded')";
            $rs = $this->localQuery($sql);
        }
    } // end of recordDependentModules() function

    /**
    * This is a method to drop tables for the current module. This method
    * gets the list of owned tables from tbl_modules_owned_tables
    * and removes them one at a time
    * 14/06/2004 - I've changed things to fit the framework- James
    * @author Derek Keats ?
    * @author James Scoble
    * @param string $moduleId
    * @returns array $droppedTables list of the dropped tables
    */
    private function dropTables($moduleId)
    {
        $sql = "SELECT tablename FROM tbl_modules_owned_tables WHERE kng_module='$moduleId'";
        $rs = $this->getArray( $sql );
        $rs_reversed=array_reverse($rs, TRUE);
        $droppedTables=array();
        foreach ($rs_reversed as $rec)
            {
                $table=$rec['tablename'];
                $droppedTables[]=$table;
                $sql="DROP TABLE IF EXISTS {$table}";
                $this->localQuery($sql);
                $sql="DROP TABLE IF EXISTS {$table}_seq";
                $this->localQuery($sql);
            }
        return $droppedTables;
    } #end of dropTables() function


    /**
    * This is a method to check for specified text entries from both tbl_languagetext and tbl_english
    * @author James Scoble
    * @param $code
    * @returns array with elements flag = 0, 1, 10, or 11, content and desc
    */
    private function checkText($code)
    {
        $flag['flag']=0;
        
        $sql="SELECT * FROM tbl_english WHERE code='".$code."'";
        $arr=$this->getArray($sql);
        $flag1=0;
        $content='';
        foreach($arr as $el)
        {
            $flag1=1;
            $content=$el['content'];
        }
        
        $sql="SELECT * FROM tbl_languagetext WHERE code='".$code."'";
        $arr=$this->getArray($sql);
        $flag2=0;
        $description='';
        foreach($arr as $el)
        {
            $flag2=10;
            $description=$el['description'];
        }
        $flag['flag']=$flag1+$flag2;
        $flag['content']=$content;
        $flag['desc']=$description;
        return $flag;
    }

    /**
    * This is a method to build an array based on another one.
    * @param array $rdata
    * @param string $index type of text to be added
    * @returns FALSE or array $texts
    */
    private function listTexts($rdata,$index='TEXT')
    {
        $texts=array();
        if (is_array($rdata) && array_key_exists($index,$rdata) && is_array($rdata[$index]))
        {
            foreach ($rdata[$index] as $line)
            {
                list($code,$description,$content)=explode('|',$line);
                if ($content){
                    $texts[$code]['content']=$content;
                    $texts[$code]['desc']=$description;
                } else {
                    $module=$rdata['MODULE_ID'];
                    $errorText=$this->objLanguage->languageText('mod_moduleadmin_textproblem',"Module <b>{MODULE}</b> has invalid text defintion for '{CODE}'<br/>");
                    $errorText = str_replace("{MODULE}",$module,$errorText);
                    $errorText = str_replace("{CODE}",$code,$errorText);
                    $this->errorText .= $errorText;
                }
            }
            return $texts;
        }
        else
        {
            return FALSE;
        }
    }

    /**
    * This is a method to add specified text entries from both tbl_languagetext and tbl_english
    * @author James Scoble
    * @param $code,$description,$content
    */
    private function addText($code,$description,$content)
    {
        // clean-up here
        $this->removeText($code);
        
        $code=addslashes($code);
        $description=addslashes($description);
        $content=addslashes($content);
        
        $sql="INSERT INTO tbl_languagetext (code,description) VALUES ('$code', '$description')";
        $this->localQuery($sql);
        
        $sql="INSERT INTO tbl_english (code,Content,isInNextGen) VALUES ('$code', '$content', '1')";
        $this->localQuery($sql);
    }

    /**
    * This is a method to remove specified text entries from both tbl_languagetext and tbl_english
    * @author James Scoble
    * @param $code
    */
    private function removeText($code)
    {
        $code=addslashes($code);
        
        $sql="DELETE FROM tbl_english WHERE code='$code'";
        $this->localQuery($sql);
        
        $sql="DELETE FROM tbl_languagetext WHERE code='$code'";
        $this->localQuery($sql);
    }

    /**
    * This is a method to make SQL params from an array
    * @author James Scoble
    * @param $data array Fields and values
    * @returns string $sql
    */
    private function makeSQLParams($data)
    {
        $sql='';
        $comma='';
        foreach ($data as $key=>$value)
        {
            $sql.="$comma $key = '$value'";
            $comma=',';
        }
        return $sql;
    }

    /**
    * This is a method to execute additional SQL statements used to
    * register a module. Execute as many times as necessary
    * to execute all SQL needed to effect a new module.
    * This doesn't go through the dbtable class, as these changes
    * are not intended to propagate across a server cluster.
    * @param string $sql Any valid SQL query passed to the method.
    */
    private function localQuery($sql)
    {
        $globalObjDb=&$this->objEngine->getDbObj();
        $globalObjDb->query($sql);
    } //end of localQuery() function

    /**
    * This is a method to insert without use of replication
    * @author James Scoble
    * @param $data array
    * @param $table string name of table
    */
    private function localInsert($data, $table='tbl_modules')
    {
        $sql="INSERT INTO $table SET ".$this->makeSQLParams($data);
        $this->localQuery($sql);
    }

    /**
    * This is a method to update without use of replication
    * @author James Scoble
    * @param $data array
    * @param $table string name of table
    * @deprecated Used to be called localEdit
    */
    private function localUpdate($data, $where, $table='tbl_modules')
    {
        $sql="UPDATE $table SET ".$this->makeSQLParams($data).' '.$where;
        $this->localQuery($sql);
    }

    /**
    * This is a method to create a SOAP file
    * @param string $moduleId
    */
    private function soapFileMaker($moduleId)
    {
        $path=$this->objConfig->siteRootPath()."/soap/";
        if ((file_exists($path))&&(!file_exists($path.$moduleId.".php"))){
            $str='<?php'."\n";
            $str.='$soap_module_name = "'.$moduleId.'";'."\n";
            $str.='require "../soapinclude.php"'."\n";
            $str.="?>\n";
            $fp=@fopen($path.$moduleId.".php","w");
            @fwrite($fp,$str);
            @fclose($fp);
        }
    }

    /**
    * This is a method to remove a SOAP file
    * @param string $moduleId
    */
    private function soapFileRemover($moduleId)
    {
        $path=$this->objConfig->siteRootPath()."/soap/";
        if (file_exists($path.$moduleId.".php")){
            @unlink($path.$moduleId.".php");
        }
    }
} # end of class
?>