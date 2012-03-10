<?php
/* -------------------- modulelist class extends controller ----------------*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Module class to handle displaying the module list
*
* @author Paul Scott <pscott@uwc.ac.za>
*
* $Id$
*/
class modulelist extends controller
{
	public $objUser;

    function init()
    {
        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    function dispatch($action)
    {
        $this->requiresLogin();
        // See if we want packages or core_modules
        $whichDir = $this->getParam('moduletype', 'packages');
        
        
        // ignore action at moment as we only do one thing - list modules
        foreach(glob($this->objConfig->getSiteRootPath() 
          . $whichDir .'/*') as $dirs) {
            $chkdirs = str_replace($this->objConfig->getSiteRootPath()
              . $whichDir . '/', "", $dirs);
            if($chkdirs == 'COPYING' || $chkdirs == 'build.xml'|| $chkdirs == 'build.xml~' || $chkdirs == 'chisimba_modules.txt' || $chkdirs == 'modlist.php') {
                continue;
            } else {
                $dirname = $dirs;
                if(file_exists(("$dirs/register.conf"))) {
                    $moddata = file_get_contents("$dirs/register.conf");
                }
                preg_match_all('/(MODULE_DESCRIPTION:(.*))/', $moddata, $results);
                if(isset($results[2][0])) {
                    $descrip = $results[2][0];
                }
                preg_match_all('/(MODULE_STATUS:(.*))/', $moddata, $results);
                if(isset($results[2][0])) {
                    $status = strtolower($results[2][0]);
                } else {
                    $status = "unset";
                }
                
                $moduleList[] = array(
                  'modname' => $chkdirs, 
                  'description' => $descrip,
                  'status' => $status);
            }
        }
        
        $this->setVar('moduleList', $moduleList);
        return "list_tpl.php";
    }
    
    /**
     * Overide the login object in the parent class
     *
     * @param  void
     * @return bool
     * @access public
     */
    public function requiresLogin() {
        return FALSE;
    }

}

?>
