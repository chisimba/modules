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
* @see modulesadmin class for administrative operations
* @author Derek Keats 
* @author Sean Legassick
* @author Jeremy O'Connor
$Id$ 
*/

// Constants for $getType parameter of getModules

define('GET_ALL', 1);
define('GET_VISIBLE', 2);
define('GET_USERVISIBLE', 3);

class modules extends dbTable 
{
    private $objLanguage;
    //private $objConfig;
    public $objConfig;

    // Handle for config object
    private $config;

    public function init()
    {
        parent::init('tbl_modules');
        // Config and Language Objects
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objConfig =& $this->getObject('config','config');
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
    * @param  $gettype int Type of request
    * @return array List of modules
    */
    public function getModules($getType)
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
            case GET_USERVISIBLE:
                $sql .= "WHERE tbl_modules.isVisible=1 AND tbl_modules.isAdmin!=1 ";
                break;
            case GET_VISIBLE:
                $sql .= "WHERE tbl_modules.isVisible=1 ";
                break;
            case GET_ALL:
                break;
            default:
                die("Invalid getType in modules::getModules");
        } 
        $sql .= "ORDER BY module_id";
        $modules = $this->getArray($sql);
        $_modules = array();
        foreach ($modules as $module) {
            $_module = array();
            $_module['module_id'] = $module['module_id'];
            $_module['module_path'] = $module['module_path'];
            $_module['title'] = $this->objLanguage->languagetext('mod_' . $module['module_id'] . '_name');
            $_module['description'] = $this->objLanguage->languagetext('mod_' . $module['module_id'] . '_desc');
            $_modules[] = $_module;
        }
        return !empty($_modules) ? $_modules : false;
    } 

    /**
    * Method to check if a module is Admin-only or not.
    * Added 10 March 2005
    * @author James Scoble
    * @param string $moduleId
    * @returns Boolean TRUE|FALSE
    */
    public function isAdminModule($moduleId)
    {
        $result=$this->getAll("WHERE module_id='$moduleId'");
        if (!empty($result) && isset($result[0])) {
            return $result[0]['isAdmin'] == '1';
        }
        else {
            return FALSE;
        }
    }

    /**
    * This is a method to check if the module is registered already.
    * Returns TRUE if the module is registered and FALSE if
    * it is not registered.
    * @param string $moduleName The name of the module, in
    * a form that is meaningful to users, for example
    * 'Show users who are logged in'.
    * @param string $moduleId The identifier of the module.
    * @returns boolen TRUE|FALSE
    */
     public function checkIfRegistered($moduleName, $moduleId=NULL) 
     {
        if (is_null($moduleId)) {
            $moduleId=$moduleName;
        }
        $row = $this->getRow('module_id',$moduleId);
        return !empty($row);
     }  #end of checkIfRegistered() function
     
    /** 
    * This method returns the version of a module in the database 
    * ie: The version level of the emodule at the time it was registered.  
    * @param string $module the module to lookup 
    * @returns string $version the version in the database | FALSE
    */ 
    public function getVersion($moduleId)
    {
        $row=$this->getRow('module_id',$moduleId);
        return !empty($row) ? $row['module_version'] : FALSE;

    }
} 
