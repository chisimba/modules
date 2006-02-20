<?php
/* ------------------- modules class extends dbTable ------------- */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
* The class representing the modules table, handling all non-administrative
* operations on the table.
* 
* @see modulesAdmin subclass for administrative operations
* @author Derek Keats 
* @author Sean Legassick

$Id$ 
*/
class modules extends dbTable 
{
    // constants for $getType parameter of getModules
    var $GET_ALL = 1;
    var $GET_VISIBLE = 2;
    var $GET_USERVISIBLE = 3;
    var $objLanguage;
    var $config; //handle for config object
    var $objConfig;
    function init()
    {
        parent::init('tbl_modules');
        // Config and Language Objects
        $this->objConfig=&$this->getObject('config','config');
        $this->objLanguage = &$this->getObject('language', 'language');
    } 

    /**
    * This method retrieves  all existing modules by querying the database
    * table tbl_modules. If the user is an administrator, it 
    * returns all visible modules, otherwise it returns all modules
    * where the value of isAdmin is FALSE. It only returns modules
    * where the value of isVisible is TRUE, thus modules can be
    * used to provide functionality to other modules without needing
    * to have a user interface. Modules that do not need to be visible
    * are thus not exposed to the user.
    * 
    * @param  $objUser user The current user object
    * @return Array List of module rows as associative arrays
    */
    function getModules($getType)
    { 
        // DEREK: This is a pretty shaky method. Can anyone find a better way?
        // SEAN: I've changed things so that we do two joins on tbl_languagetexts
        // and thus everything for a module comes back in one row
        // I think this is better...?
        /* $sql="SELECT tbl_modules.module_id, tbl_modules.module_path, 
				tbl_languagetexts_name.id as nameId, tbl_languagetexts_name.English as name, 
                tbl_languagetexts_desc.id as descId, tbl_languagetexts_desc.English as description
				FROM tbl_modules 
                INNER JOIN bridge_lang_to_mod
				ON tbl_modules.module_id = bridge_lang_to_mod.moduleId 
				INNER JOIN tbl_languagetexts AS tbl_languagetexts_name ON 
				bridge_lang_to_mod.codeName = tbl_languagetexts_name.code 
                INNER JOIN tbl_languagetexts AS tbl_languagetexts_desc ON
                bridge_lang_to_mod.codeDesc = tbl_languagetexts_desc.code ";
         */
        // JAMES: The triple join is no longer needed. Only tbl_modules and tbl_languagetexts are needed.
        $sql = "SELECT module_id, module_path from tbl_modules ";

        switch ($getType) {
            case $this->GET_USERVISIBLE:
                $sql .= "WHERE tbl_modules.isVisible=1 AND tbl_modules.isAdmin!=1 ";
                break;

            case $this->GET_VISIBLE:
                $sql .= "WHERE tbl_modules.isVisible=1 ";
                break;

            case $this->GET_ALL:
                break;

            default:
                die("Invalid getType in modules::getModules");
        } 

        $sql .= "ORDER BY module_id";

        $modcodes = $this->getArray($sql);

        foreach ($modcodes as $line) {
            $modline = array();
            $modline['module_id'] = $line['module_id'];
            $modline['module_path'] = $line['module_path'];
            $modline['title'] = $this->objLanguage->languagetext('mod_' . $line['module_id'] . '_name');
            $modline['description'] = $this->objLanguage->languagetext('mod_' . $line['module_id'] . '_desc');
            $modarray[] = $modline;
        } 
        if (isset($modarray)) {
            return $modarray;
        } else {
            return false;
        } 
    } 

    /**
    * Method to check if a module is Admin-only or not.
    * Added 10 March 2005
    * @author James Scoble
    * @param string $moduleId
    * @returns Boolean TRUE|FALSE
    */
    function isAdminModule($moduleId)
    {
        $result=$this->getAll("where module_id='$moduleId'");
        if ((is_array($result))&&(count($result)>0)){
            $value=$result[0]['isAdmin'];
            if ($value==1){
                return TRUE;
            }
        }
        // Default return false if not return true
        return FALSE;
    }

    
    /**
    * This is a method to check if the module is registered already.
    * Returns TRUE if the module is registered and FALSE if
    * it is not registered.
    * @param string $moduleName The name of the module, in
    * a form that is meaningful to users, for example
    * 'Show users who are logged in'
    * @param string $moduleId The identifier of the module, 
    * in the form initial_modulecode where initial represents
    * the initials for the developer to avoid duplication.
    * @returns boolen TRUE|FALSE
    */
     function checkIfRegistered($moduleName, $moduleId=NULL) 
     {
        if ($moduleId==NULL){
            $moduleId=$moduleName;
        }
        $line=$this->getRow('module_id',$moduleId);
        if ($line['module_id']==$moduleId){
            $fn_ret=TRUE;
         } else {
            $fn_ret=FALSE;
         }
        return $fn_ret;
     }  #end of checkIfRegistered() function
     
    /** 
    * This method returns the version of a module in the database 
    * ie: The version level of the emodule at the time it was registered.  
    * @param string $module the module to lookup 
    * @returns string $version the version in the database 
    */ 
    function getVersion($module)
    {
        $row=$this->getRow('module_id',$module);
        if (!is_array($row)){ 
            return FALSE; 
        } 
        $version=$row['module_version']; 
        return $row; 
    }
                                                                                                        
} 
