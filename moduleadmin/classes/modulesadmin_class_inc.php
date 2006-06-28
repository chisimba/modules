<?php
/* ------------------- modulesAdmin class extends modules ------------- */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Subclass of modules to add administrative functionality.
* Dividing the class in two like this avoids loading this
* file when only the basic user functionality is needed.
*
*
* @author Derek Keats
* @author Sean Legassick
* @author James Scoble
* @author Megan Watson
* @author Jeremy O'Connor
*
* $Id$
*/

require_once "modules_class_inc.php";
require_once "DB.php";

class modulesAdmin extends modules
{

    private $objTableInfo; //handle for tableinfo class object
    private $objKeyMaker; //handle for primary key generation
    private $MODULE_ID; // replaces use of defined constant.
    private $MODULE_NAME;
    private $MODULE_DESCRIPTION;
    private $update=FALSE;
    private $output=''; // for any feedback messages from internal functions.
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
    */
     public function checkIfRegistered($moduleName, $moduleId=NULL)
     {
        if (is_null($moduleId)){
            $moduleId=$moduleName;
        }
	return !empty($this->getRow('module_id',$moduleId));
     }  #end of checkIfRegistered() function

    /**
    * This is a method to check if a dependent module is installed.
    * @param string $moduleId The moduleId of the module on which the
    * current module depends
    * @returns boolean TRUE|FALSE
    */
    public function checkDependency($moduleId) {
        return $this->checkIfRegistered(NULL,$moduleId));
    } #end of checkDependency() function

    /**
    * This is a method to insert without use of replication
    * @author James Scoble
    * @param $data array
    * @param $table string name of table
    */
    public function localInsert($data, $table='tbl_modules')
    {
        $sql="INSERT INTO $table SET ".$this->makeSQLParams($data);
        $this->executeModSQL($sql);
    }

    /**
    * This is a method to update without use of replication
    * @author James Scoble
    * @param $data array
    * @param $table string name of table
    * @deprecated Used to be called localEdit
    */
    public function localUpdate($data, $where, $table='tbl_modules')
    {
        $sql="UPDATE $table SET ".$this->makeSQLParams($data).' '.$where;
        $this->executeModSQL($sql);
    }

    /**
    * This is a method to make SQL params from an assoc array
    * @author James Scoble
    * @param $arraydata assoc array
    * @returns string $sql
    */
    function makeSQLParams($arraydata)
    {
        $sql='';
        $comma='';
        foreach ($arraydata as $key=>$value)
        {
            $sql.=$comma.' '.$key."='".$value."'";
            $comma=',';
        }
        return $sql;
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
    * @returns boolean TRUE|FALSE
    */
    function registerModule($registerdata)
    {
        if (defined('MODULE_ID'))
        {
            $moduleId=MODULE_ID;
        }
        elseif (isset($registerdata['MODULE_ID']))
        {
            $moduleId=$registerdata['MODULE_ID'];
        }
        else
        {
            return FALSE; // If we can't find the name of the module we're supposed to be registering, what are we doing here?
        }
        $this->MODULE_ID=$moduleId;
        $this->MODULE_NAME=$registerdata['MODULE_NAME'];
        $this->MODULE_DESCRIPTION=$registerdata['MODULE_DESCRIPTION'];

        $this->executeModSQL('BEGIN WORK'); //Start a transaction;

        //If the module already exists, do not register it, else register it
        if ($this->checkIfRegistered($this->MODULE_NAME, $moduleId) && !($this->update))
        {
            if ($this->update){ print "BOOOOM!"; die; }
            return FALSE;
        }
        else
        {
            // check for modules this one is dependant on
            if (isset($registerdata['DEPENDS'][0]))
            {
                foreach ($registerdata['DEPENDS'] as $dline)
                {
                    $test=$this->checkDependency($dline);
                    if ($test==FALSE)
                    {
                        $text=$this->objLanguage->languageText('mod_moduleadmin_needmodule');
                        $text=str_replace('{MODULE}',$dline,$text);
                        $this->output.='<b>'.$text.'</b><br />';
                            //"<b>Cannot register module - needs module $dline to be registered first!</b><br>\n";
                        return FALSE;
                    }
                }
            }
            // Now we add the tables
            if (isset($registerdata['TABLE'][0]))
            {
                $this->objTableInfo=$this->newObject('tableinfo','moduleadmin'); // create object to look at SQL tables
                $this->objKeyMaker=$this->newObject('primarykey','modulelist');
                foreach ($registerdata['TABLE'] as $dline)
                {
                    $test=$this->makeNewTable($dline);
                    if ($test==FALSE)
                    {
                        //"<b>Cannot register module - needs info to create table $dline first!</b><br>\n";
                        $text=$this->objLanguage->languageText('mod_moduleadmin_needinfo');
                        $text=str_replace('{MODULE}',$dline,$text);
                        $this->output.='<b>'.$text.'</b><br />';
                        return FALSE;
                    }
                    else
                    {
                        $sql="DELETE FROM tbl_modules_owned_tables WHERE kng_module='".$moduleId."' and tablename='".$dline."'";
                        $this->executeModSQL( $sql );
                        $sql="INSERT INTO tbl_modules_owned_tables (kng_module,tablename) VALUES ('".$moduleId."','".$dline."')";
                        $this->executeModSQL($sql); // Add the table to the records.
                    }
                }
            }

            // Here we load data into tables from files of SQL statements
            if (isset($registerdata['BIGDATA'][0]))
            {
                $this->objKeyMaker=$this->newObject('primarykey','modulelist');
                foreach ($registerdata['BIGDATA'] as $dline)
                {
                    $test=$this->loadData($dline);
                }
            }

            // Here we create a SOAP file
            //if (isset($registerdata['SOAP_CONTROLLER'][0])&&($registerdata['SOAP_CONTROLLER'][0]==1))
            //{
            //    $this->soapFileMaker($moduleId);
            //}

            // Create directory and subdirectory
            if(isset($registerdata['DIRECTORY'][0])){
                $path = $this->objConfig->contentBasePath().'/'.$registerdata['DIRECTORY'][0].'/';
                if(!is_dir($path)){
                    mkdir($path, 0777);
                }

                if(isset($registerdata['SUBDIRECTORY'][0])){
                    foreach($registerdata['SUBDIRECTORY'] as $line){
                        $subPath = $path.$line.'/';
                        if(!is_dir($subPath)){
                            mkdir($subPath, 0777);
                        }
                    }
                }
            }

            // Set up data for the site navigation: toolbar, sidemenus and pages
            $isAdmin = 0; $isContext = 0; $aclList = ''; $permList = array(); $groupArray = array();
            $groupArray2 = array();
            if(isset($registerdata['MODULE_ISADMIN'])){
                $isAdmin = $registerdata['MODULE_ISADMIN'];
            }
            if(isset($registerdata['DEPENDS_CONTEXT'])){
                $isContext = $registerdata['DEPENDS_CONTEXT'];
            }

            /* Set up permissions for the module.
               Set up a module specific ACL, set up module specific groups and add them
               to the acl.
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
            if (isset($registerdata['MENU_CATEGORY'][0]))
            {
                foreach ($registerdata['MENU_CATEGORY'] as $line)
                {
                    $line=strtolower($line);
                    $sql="INSERT INTO tbl_menu_category
                    (id,category,module,adminOnly,permissions,dependsContext)
                    values ('";
                    $sql.='init@'.time().rand(1000,9999)."','$line',";
                    $sql.="'$moduleId','$isAdmin',";
                    $sql.="'$aclList','$isContext')";
                    $this->executeModSQL($sql);
                }
            }// end menu category

            // Side menus
            if (isset($registerdata['SIDEMENU'][0]))
            {
                $objGroups = $this->getObject('groupAdminModel', 'groupadmin');
                foreach ($registerdata['SIDEMENU'] as $line)
                {
                    $admin = $isAdmin;
                    $groupList = '';
                    $line=strtolower($line);

                    $actions = array();
                    $actions = explode('|', $line);

                    if(isset($actions[1]) && !empty($actions[1])){
                        $line = str_replace($actions[1],'',$line);

                        $conGroups = ''; $siteGroups = ''; $acls = '';
                        $access = explode(',',$actions[1]);
                        $admin = 0;

                        foreach($access as $val){
                            // check for context groups
                            if(!(strpos($val, 'con_') === FALSE)){
                                if(!empty($conGroups)){
                                    $conGroups .= ',';
                                }
                                $conGroups .= ucwords(str_replace('con_','',$val));

                            // check for module permissions, create if don't exist
                            }else if(!(strpos($val, 'acl_') === FALSE)){
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

                            // check for module groups, create if don't exist
                            }else{
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
                    }else{
                        $groupList = $aclList;
                    }
                    $sql="INSERT INTO tbl_menu_category (id, category, module,";
                    $sql.="adminOnly,permissions,dependsContext) values ('";
                    $sql.='init@'.time().rand(1000,9999)."','menu_$line',";
                    $sql.="'$moduleId','$admin',";
                    $sql.="'$groupList','$isContext')";
                    $this->executeModSQL($sql);
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
                    $sql = 'INSERT INTO tbl_menu_category (id, category, module,';
                    $sql .= "adminOnly, permissions, dependsContext) values ('";
                    $sql .= 'init@'.time().rand(1000,9999)."','page_$line',";
                    $sql .= "'$moduleId','$admin','$aclList','$isContext')";
                    $this->executeModSQL($sql);
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

            $fields=array('module_id','module_authors','module_releasedate','module_version','module_path','isAdmin','isVisible','hasAdminPage');
            $values=array($moduleId,addslashes($registerdata['MODULE_AUTHORS']),$registerdata['MODULE_RELEASEDATE'],$registerdata['MODULE_VERSION'],$registerdata['MODULE_PATH'],$registerdata['MODULE_ISADMIN'],$registerdata['MODULE_ISVISIBLE'],$registerdata['MODULE_HASADMINPAGE']);
            foreach($fields as $key)
            {
                $sql_str[$key]=array_shift($values);
            }

            if (isset($registerdata['CONTEXT_AWARE'])){
                $sql_str['isContextAware']=$registerdata['CONTEXT_AWARE'];
            }
            if (isset($registerdata['DEPENDS_CONTEXT'])){
                $sql_str['dependsContext']=$registerdata['DEPENDS_CONTEXT'];
            }

            $this->localinsert($sql_str);
            if ($this->update){
                $this->localEdit($sql_str,"where module_id='$moduleId'");
            }
            //indicate success
            // put the language information for name and description
            $this->pokeLanguage();
            // insert the list of language codes used by the module if any
            if (defined('MODULE_LANGTERMS'))
            {
                $this->pokeTerms(MODULE_LANGTERMS);
            }
            $texts=$this->listTexts($registerdata); // get list of all specified texts
            if ($texts!=FALSE)
            {
                foreach ($texts as $key=>$line)
                {
                    $test=$this->checkText($key);
                    if ($test['flag']!=11)
                    {
                        $this->addText($key,$line['desc'],$line['content']);
                    }
                }
            }
            $texts=$this->listTexts($registerdata,'USES');
            if ($texts!=FALSE)
            {
                foreach ($texts as $key=>$line)
                {
                    $test=$this->checkText($key);
                    if ($test['flag']!=11)
                    {
                        $this->addText($key,$line['desc'],$line['content']);
                    }
                }
            }
            if (isset($registerdata['DEPENDS'][0]))
            {
                $this->recordDependentModules($moduleId,$registerdata['DEPENDS']);
            }
           // $sql="DELETE FROM tbl_languagetext WHERE whereUsed='".$moduleId."'";
        }


        $this->executeModSQL('COMMIT'); //End the transaction;
        return "OK";
    } #end of registerModule() function

    /**
    * This method looks at the registration data and tries to create any tables specified
    * @param array $tables
    * @returns TRUE or FALSE
    */
    function makeTablesForModule($tables)
    {
        $this->objTableInfo=$this->newObject('tableinfo','moduleadmin'); // create object to look at SQL tables
        $this->objKeyMaker=$this->newObject('primarykey','modulelist');
        foreach ($tables as $dline)
        {
            $test=$this->makeNewTable($dline);
            if ($test==FALSE){
                //"<b>Cannot register module - needs info to create table $dline first!</b><br>\n";
                $text=$this->objLanguage->languageText('mod_moduleadmin_needinfo');
                $text=str_replace('{MODULE}',$dline,$text);
                $this->output.='<b>'.$text.'</b><br />';
                return FALSE;
          } else {
                $sql="DELETE FROM tbl_modules_owned_tables WHERE kng_module='".$moduleId."' and tablename='".$dline."'";
                $this->executeModSQL( $sql );
                $sql="INSERT INTO tbl_modules_owned_tables (kng_module,tablename) VALUES ('".$moduleId."','".$dline."')";
                $this->executeModSQL($sql); // Add the table to the records.
          }
        }
        return TRUE;
    }


    /**
    * This is a method to read data from a file and use it to create a table.
    *
    * @author James Scoble
    * @param string $tablefile the name of the file
    * @param string $moduleId the id of the module
    * @returns boolean TRUE|FALSE
    */
    function makeNewTable($tablefile,$moduleId='NONE')
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
        if (file_exists($sqlfile)){
            include($sqlfile);
            foreach ($sqldata as $line)
            {
                $line=str_replace('PKVALUE',($this->objKeyMaker->newkey($tablefile)),$line);
                $this->executeModSQL($line);
            }
            return TRUE;
        } else {
            $this->output.= "<b>$sqlfile</b> ".$this->objLanguage->languageText('phrase_notfound')."<br />\n";
            return FALSE;
        }
    }

   /********
    * function loadData()
    * This is a method to read data from a file and use it to populate (not create) a table.
    * @author James Scoble
    * @param $tablefile the name of the file
    * @param string $moduleId the id of the module to be used
    * @returns boolean TRUE or FALSE
    */
    function loadData($tablefile,$moduleId='NONE')
    {
        if ($moduleId=='NONE'){
            $moduleId=$this->MODULE_ID;
        }
        $sqlfile=$this->objConfig->siteRootPath().'/modules/'.$moduleId.'/'.$tablefile.'.sql';
        if (file_exists($sqlfile))
        {
            ini_set('max_execution_time','120');
            $handle=fopen($sqlfile,'r');
            $line=fgets($handle,2048); // 2k ought to be enough.
            while ($line)
            {
                $line=str_replace('PKVALUE',($this->objKeyMaker->newkey($tablefile)),$line);
                $this->executeModSQL($line);
                $line=fgets($handle,2048);
            }
            fclose($handle);
            return TRUE;
        }
        else
        {
            $this->output.= "<b>$sqlfile</b> ".$this->objLanguage->languageText('phrase_notfound')."<br />\n";
            return FALSE;
        }
    }

    /**
    * This is a method to move icons when registering
    * @param string $moduleId the module
    * @param array $icons the list of icons
    */
    function moveIcons($moduleId,$icons)
    {
        $sourcedir=$this->objConfig->siteRootPath().'/modules/'.$moduleId.'/icons/';
        $destdir=$this->objConfig->siteRootPath().'skins/'.$this->objConfig->defaultSkin().'/icons/';
        foreach ($icons as $line)
        {
            copy($sourcedir.$line,$destdir.$line);
        }
    }

    /**
    * This is a method to put the module information into the language table
    * It first inserts the name of the module and then inserts the
    * description of the module into the English column
    */
    function pokeLanguage() {
        $modTitle="mod_".$this->MODULE_ID."_name";
        $modDescription="mod_".$this->MODULE_ID."_desc";
        //$whereUsed="/modules/";
        $isInNextGen=TRUE;
        // Store the name
        $sql="delete from tbl_english where code='".$modTitle."'";
        $this->executeModSQL($sql);
        $sql="delete from tbl_languagetext where code='".$modTitle."'";
        $this->executeModSQL($sql);
        /*$sql="insert into tbl_english
            (code, description,  English, isInNextGen)
            values ('".$modTitle."', '".addslashes(MODULE_NAME)."', '".addslashes(MODULE_NAME)."', ".
            $isInNextGen.")"; */
        $sql="insert into tbl_languagetext (code,description) values
        ('".$modTitle."', '".addslashes($this->MODULE_NAME)."')";
        $this->executeModSQL($sql);
        $sql="insert into tbl_english
            (code, Content,isInNextGen)
            values ('".$modTitle."', '".addslashes($this->MODULE_NAME)."', '".$isInNextGen."')";
        $this->executeModSQL($sql);
        // JAMES says: The bridge_lang_to_mod table is depreciated.
        //Make the language bridge for the name
        //$sql="delete from bridge_lang_to_mod where module_id='".$this->MODULE_ID."'";
        //$this->executeModSQL($sql);
        //$sql="insert into bridge_lang_to_mod (module_id, code) values ('".MODULE_ID."', '".$modTitle."')";
        //$this->executeModSQL($sql);

        // Store the description
        $sql="insert into tbl_languagetext (code,description) values
        ('".$modDescription."', '".addslashes($this->MODULE_DESCRIPTION)."')";
        $this->executeModSQL($sql);
        $sql="delete from tbl_english where code='".$modDescription."'";
        $this->executeModSQL($sql);
        $sql="insert into tbl_english
            (code, Content, isInNextGen)
            values ('".$modDescription."', '".addslashes($this->MODULE_DESCRIPTION)."', '".$isInNextGen."')";
        $this->executeModSQL($sql);
        //Make the language bridge for the description
        //$sql="insert into bridge_lang_to_mod (module_id, code)  values ('".MODULE_ID."', '".$modDescription."')";
        //$this->executeModSQL($sql);
    } #end of pokeLanguage() function



    /**
    * This is a method to add language terms to the database
    * @param string $terms A comma delimited string of
    * terms that are used in the language database
    */
    function pokeTerms($terms) {
        $terms_array=explode(',', $terms);
        for ($i=0; $i < count($terms_array); $i++) {
            $terms=$terms_array[$i];
            $sql="INSERT INTO tbl_language_modules (module_id, code)
              VALUES ('".$this->MODULE_ID."', '".$terms."')";
          //echo $sql;
            $this->executeModSQL($sql);
        }
    } #end of pokeTerms() function



    /**
    * This is a method to execute additional SQL statements used to
    * register a module. Execute as many times as necessary
    * to execute all SQL needed to effect a new module.
    * This doesn't go through the dbtable class, as these changes
    * are not intended to propagate across a server cluster.
    *
    * @param string $sql Any valid SQL query passed to the method.
    */
    function executeModSQL($sql)
    {
        //$this->output.="<p>".$sql."</p>\n";
        $globalObjDb=&$this->objEngine->getDbObj();
        $globalObjDb->query($sql);
    } //end of executeModSQL() function



    /**
    * This is a method to check for modules that depend on this one
    * 14/06/2004 - I've changed things to fit the framework- James.
    * @author Derek Keats ?
    * @author James Scoble
    * @param string $moduleId
    * returns array $dep
    */
    function checkForDependentModules($moduleId)
    {
        $dep=array();
        $sql="SELECT module_id FROM tbl_modules_dependencies WHERE dependency='".$moduleId."'";
        $rs = $this->getArray( $sql );
        foreach ($rs as $line)
         {
          $dep[]=$line['module_id'];
         }
        return $dep;
    } #end of checkForDependentModules() function


    /**
    * This is a method to record modules this one depends on
    * @author James Scoble
    * @param string $moduleId
    * @param $modulesNeeded array
    *
    */
    function recordDependentModules($moduleId,$modulesNeeded)
    {
        foreach ($modulesNeeded as $line)
        {
            $sql="insert into tbl_modules_dependencies (module_id,dependency) values ('".$moduleId."','".$line."')";
            $rs = $this->executeModSQL($sql);
        }
    } // end of recordDependentModules() function

    /**
    * This is a method to drop tables for the current module. This method
    * gets the list of owned tables from tbl_modules_owned_tables
    * and removes them one at a time
    * 14/06/2004 - I've changed things to fit the framework- James.
    *
    * @author Derek Keats ?
    * @author James Scoble
    * @param string $moduleId
    * @returns array $droppedTables list of the dropped tables
    */
    function dropTables($moduleId)
    {
        $sql="SELECT tablename FROM tbl_modules_owned_tables WHERE kng_module='".$moduleId."'";
        $rs = $this->getArray( $sql );
        $droppedTables=array();
        $rs_reversed=array_reverse($rs,TRUE);
        foreach ($rs_reversed as $line)
            {
                $table=$line['tablename'];
                $droppedTables[]=$table;
                $sql="DROP TABLE IF EXISTS ".$table;
                $this->executeModSQL($sql);
                $sql="DROP TABLE IF EXISTS ".$table."_seq";
                $this->executeModSQL($sql);
            }
        return $droppedTables;
    } #end of dropTables() function



    /**
    * This is a method to uninstall (unregister) a module.
    * This method should check for modules that depend on the current module
    * and refuse to uninstall where there are dependencies. Instead of uninstalling
    * a module that has dependencies, it should give the option to remove the user
    * interface files and set the module isVisible flag to 0
    *
    * 14/06/2004 - I've changed things to fit the framework- James.
    * @author Derek Keats ?
    * @author James Scoble
    * @param string $moduleId the id of the module
    * @param string $registerdata - array of info from the registration file
    * @returns boolean TRUE or FALSE
    */
    function unInstall($moduleId='NONE',$registerdata)
    {
        if ($moduleId=='NONE'){
            $moduleId=$registerdata['MODULE_ID'];
        }

        $modTitle="mod_".$moduleId."_name";
        $modDescription="mod_".$moduleId."_desc";
        //Check if there are modules that depend on this one
        $mList=$this->checkForDependentModules($moduleId);
        if (count($mList)>0)
        {
            $this->objLanguage->languageText('mod_hasdependants');
            $outstr="<b>".$this->objLanguage->languageText('mod_hasdependants')."</b><br\>\n";
            foreach ($mList as $line)
            {
                $outstr.=$line."<br />\n";
            }
            $this->output.= $outstr;
            //define('OUTPUT',$outstr);
            return FALSE;
        }
        else
        {
            $this->executeModSQL('BEGIN WORK'); //Start a transaction;

            //$outstr= "<br>Removing title from language text.<br/>\n";
            $sql="DELETE FROM tbl_english WHERE code='".$modTitle."'";
            $this->executeModSQL($sql);
            $sql="DELETE FROM tbl_languagetext WHERE code='".$modTitle."'";
            $this->executeModSQL($sql);

            //$outstr.="Removing description from language text.<br/>\n";
            $sql="DELETE FROM tbl_english WHERE code='".$modDescription."'";
            $this->executeModSQL($sql);
            $sql="DELETE FROM tbl_languagetext WHERE code='".$modDescription."'";
            $this->executeModSQL($sql);


            $texts=$this->listTexts($registerdata); // remove all specified texts
            if ($texts!=FALSE)
            {
                foreach ($texts as $key=>$line)
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
            $sql="delete from tbl_menu_category where module='$moduleId'";
            $this->executeModSQL($sql);

            // Here we remove CONFIG data from the sysconfig module
            $this->objSysConfig=&$this->getObject('dbsysconfig','sysconfig');
            $this->objSysConfig->deleteModuleValues($moduleId);

            // Here we remove any SOAP files
            if (isset($registerdata['SOAP_CONTROLLER'][0])&&($registerdata['SOAP_CONTROLLER'][0]==1))
            {
                $this->soapFileRemover($moduleId);
            }

            // Drop tables
            $droppedtables=$this->dropTables($moduleId);

            //$outstr.="Removing names for owned tables.<br/>\n";
            $sql="DELETE FROM tbl_modules_owned_tables WHERE kng_module='".$moduleId."'";
            $this->executeModSQL($sql);

            //$outstr.="Deleting module from list of available modules.<br/>\n";
            $sql="DELETE FROM tbl_modules WHERE module_id='".$moduleId."'";
            $this->executeModSQL($sql);

            //$outstr.="Removing language entries for the module.<br/>\n";
            $sql="DELETE FROM tbl_language_modules WHERE module_id='".$moduleId."'";
            $this->executeModSQL($sql);

            //$outstr.="Removing module dependancy entries for the module.<br/>\n";
            $sql="DELETE FROM tbl_modules_dependencies WHERE module_id='".$moduleId."'";
            $this->executeModSQL($sql);

            $this->executeModSQL('COMMIT'); //End the transaction;

            return TRUE;
        }
    }  #end of unInstall() function


    /********
    * This is a method to add specified text entries from both tbl_languagetext and tbl_english
    * @author James Scoble
    * @param $code,$description,$content
    */
    function addText($code,$description,$content)
    {
        $this->removeText($code); // clean-up here
        $code=addslashes($code);
        $description=addslashes($description);
        $content=addslashes($content);
        $sql="insert into tbl_languagetext (code,description) values ('".$code."', '".$description."')";
        //print $sql."<br>\n";
        $this->executeModSQL($sql);
        $sql="insert into tbl_english (code, Content,isInNextGen) values ('".$code."', '".$content."', '1')";
        $this->executeModSQL($sql);
    }

    /********
    * This is a method to remove specified text entries from both tbl_languagetext and tbl_english
    * @author James Scoble
    * @param $code
    */
    function removeText($code)
    {
        $code=addslashes($code);
        $sql="DELETE FROM tbl_english WHERE code='".$code."'";
        $this->executeModSQL($sql);
        $sql="DELETE FROM tbl_languagetext WHERE code='".$code."'";
        $this->executeModSQL($sql);
    }

    /********
    * This is a method to check for specified text entries from both tbl_languagetext and tbl_english
    * @author James Scoble
    * @param $code
    * @returns array with elements flag = 0, 1, 10, or 11, content and desc
    */
    function checkText($code)
    {
        $flag['flag']=0;
        $sql="SELECT * FROM tbl_english WHERE code='".$code."'";
        $data=$this->getArray($sql);
        $flag1=0;
        $content='';
        foreach($data as $line)
        {
            $flag1=1;
            $content=$line['content'];
        }
        $sql="SELECT * FROM tbl_languagetext WHERE code='".$code."'";
        $data=$this->getArray($sql);
        $flag2=0;
        $desc='';
        foreach($data as $line)
        {
            $flag2=10;
            $desc=$line['description'];
        }
        $flag['flag']=$flag1+$flag2;
        $flag['desc']=$desc;
        $flag['content']=$content;
        return $flag;
    }

    /**
    * This is a method to build an array based on another one.
    * @param array $rdata
    * @param string $index type of text to be added
    * @returns FALSE or array $texts
    */
    function listTexts($rdata,$index='TEXT')
    {
        $texts=array();
        if (is_array($rdata) && array_key_exists($index,$rdata) && is_array($rdata[$index]))
        {
            foreach ($rdata[$index] as $line)
            {
                (@list($code,$description,$content)=explode('|',$line));
                if ($content){
                    $texts[$code]['desc']=$description;
                    $texts[$code]['content']=$content;
                } else {
                    $module=$rdata['MODULE_ID'];
                    $errorText=$this->objLanguage->languageText('mod_moduleadmin_textproblem',"Module <b>[MODULE]</b> has invalid text defintion for '[CODE]' ");
                    $this->errorText.=str_replace("[MODULE]",$module,str_replace("[CODE]",$code,$errorText))."<br />\n";
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
    * This is a method to check if a module is registered and turn the result as an array
    * @param string $moduleId
    * @returns array $result
    */
    function getModuleInfo($moduleId)
    {
        $result=array('isreg'=>FALSE,'name'=>'');
        if ($this->checkIfRegistered(' ',$moduleId)){
            $result['isreg']=TRUE;
            $result['name']=$this->objLanguage->code2Txt('mod_'.$moduleId.'_name');
        }
        return $result;
    }

    /**
    * This is a method to create a SOAP file
    * @param string $moduleId
    */
    function soapFileMaker($moduleId)
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
    function soapFileRemover($moduleId)
    {
        $path=$this->objConfig->siteRootPath()."/soap/";
        if (file_exists($path.$moduleId.".php")){
            @unlink($path.$moduleId.".php");
        }
    }

} # end of class


?>
