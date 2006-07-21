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
* @author Sean Legassick
*
* $Id$
*/
class modulelist extends controller
{
	//not using constants anymore
    //const GET_VISIBLE = 2;
    //const GET_USERVISIBLE = 3;

	var $objUser;

    function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
    }

    function dispatch($action)
    {
        // ignore action at moment as we only do one thing - list modules

        // load the data object (calls the magical getObject which finds the
        // appropriate file, includes it, and either instantiates the object,
        // or returns the existing instance if there is one
        $objModules =& $this->getObject('modules','modulecatalogue');

        // fetch the module list and make available to the template
        $getType = $this->objUser->isAdmin()
                        ? 2
                        : 3;
        $moduleList = $objModules->getModules($getType);
        $this->setVar('moduleList', $moduleList);

        // return the name of the template to use  because it is a page content template
        // the file must live in the templates/content subdir of the module directory
        return "list_tpl.php";
    }
}

?>