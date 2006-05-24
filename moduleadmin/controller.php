<?php
/* -------------------- moduleadmin class extends controller ----------------*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Module class to handle registration and admin of modules
* @copyright (c) 2004 KEWL.NextGen
* @package moduleadmin
* @author James Scoble
*
* $Id: controller.php
*/


class moduleadmin extends controller
{
    /**
    * Here we declare all the class variables that get instantiated in the init() function
    */
    // The site-wide Config object
    var $objConfig;
    var $objModule; // primary class object moduleadmin from modulelist module
    var $objUser;
    var $template; // name of template file to display output
    var $objRegFile;
    var $modname; //name of the module to operate on.
    // The class variable for template output
    var $output;
    // Standard language object
    var $objLanguage;

    function init()
    {
        // instantiate the basic config object
        $this->objConfig=&$this->getObject('altconfig','config');
        // Language Object
        $this->objLanguage=&$this->getObject('language','language');

        // instantiate the main class for module registration:
        $this->objModule=&$this->getObject('modulesadmin','modulelist');
        // the class for reading register.conf files
        $this->objRegFile=$this->newObject('filereader','moduleadmin');
        // the security/user class
        $this->objUser=&$this->getObject('user','security');
        // the name of the module the user has selected for admin functions.
        $this->modname=$this->getParam('modname');

        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Log this module call
        $this->objLog->log();

        $this->output='';
    }

 /**
 * @author James Scoble
 *
 * @params $action
 *
 * Basically a switch() structure dependant on $action.
 * Returns $this->template for the template file name
 */
 function dispatch($action)
 {
     // Non-admins are not supposed to use this module AT ALL
     // The one exception being First Time Registration
     // When no user accounts are set up yet anyway
     if ($action!='firsttimeregistration'){
        $isAdmin=$this->objUser->isAdmin();
        $isAdmin = TRUE;
        //var_dump($isAdmin);
        if (($isAdmin==FALSE)){
            return 'notadmin_tpl.php';
        }
     }
  switch ($action)
   {
    case 'show classes':
        $this->listClassFiles($this->modname);
        $this->template='classfiles_tpl.php';
        break;
    case 'textelements':
        $texts=$this->moduleText($this->modname);
        $this->setVar('moduledata',$texts);
        $this->setVar('modname',$this->modname);
        $this->template='textelements_tpl.php';
        break;
    case 'addtext':
        $texts=$this->moduleText($this->modname,'fix');
        $texts=$this->moduleText($this->modname);
        $this->output=$this->objModule->output;
        $this->setVar('moduledata',$texts);
        $this->setVar('modname',$this->modname);
        $this->template='textelements_tpl.php';
        break;
    case 'replacetext':
        $texts=$this->moduleText($this->modname,'replace');
        $texts=$this->moduleText($this->modname);
        $this->output=$this->objModule->output;
        $this->setVar('moduledata',$texts);
        $this->setVar('modname',$this->modname);
        $this->template='textelements_tpl.php';
        break;
    case 'batchreplacetext':
        $this->batchReplaceText();
        return $this->nextAction(null, array('module'=>'moduleadmin'));
        break;
    case 'register':
        $regResult=$this->registerModule($this->modname);
        $this->output.=$this->objModule->output;
        if ($regResult=='OK'){
            $this->output.=$this->confirmRegister();
            $this->template='register_tpl.php';
        } else {
            $this->template='result_tpl.php';
        }
        break;
    case 'batchregister':
        $selectedModules=$this->getArrayParam('arrayList');
        if (count($selectedModules)>0){
            $this->batchRegister($selectedModules);
        } else {
            $this->output.='<b>'.$this->objLanguage->languageText('mod_moduleadmin_noselect','No modules were selected').'</b>';
        }
        $this->template='result_tpl.php';
        break;
    case 'batchderegister':
        $selectedModules=$this->getArrayParam('arrayList');
        if (count($selectedModules)>0){
            $this->batchDeregister($selectedModules);
        } else {
            $this->output.='<b>'.$this->objLanguage->languageText('mod_moduleadmin_noselect','No modules were selected').'</b>';
        }
        $this->template='result_tpl.php';
        break;
    case 'smartregister':
        $regResult=$this->smartRegister($this->modname);
        $this->output.=$this->objModule->output;
        if ($regResult=='OK'){
            $this->template='result_tpl.php';
        } else {
            $this->template='result_tpl.php';
        }
        break;
    case 'deregister':
        $flag=$this->unInstallModule($this->modname);
        $this->output.=$this->objModule->output;
        $this->template='result_tpl.php';
        if ($flag==TRUE){
            $this->output.=$this->confirmRegister('mod_moduleadmin_deregconfirm');
        }
        break;
    case 'info':
        $this->registerdata=$this->getModuleInfo($this->modname);
        if ($this->registerdata==FALSE){
            return $this->nextAction(null,array('action'=>'list'));
            break;
        }
        $this->template='info_tpl.php';
        break;
    case 'firsttimeregistration':
        $this->objSysConfig=&$this->getObject('dbsysconfig','sysconfig');
        $check=$this->objSysConfig->getValue('firstreg_run','moduleadmin');
        if ($check!=TRUE){
            $this->firstRegister();
        }
        // Show login page
        return $this->nextAction(NULL, NULL, 'security');
    case 'list':
    default:
        $filter=$this->getParam('filter',$this->getSession('filter','a'));
        $this->setSession('filter',$filter);
        $modulelist=$this->listModuleFiles($filter);
        $this->output=$this->objModule->output;
        $this->setVar('modulelist',$modulelist);
        $this->template='moduledata_tpl.php';
        break;
   }
    // Batch template uses the same info as the 'list' option above
    // but displays it differently
    if ($action=='batch'){
       $this->template='batch_tpl.php';
    }
    // Deregister option for batch template
    if ($action=='deregisterbatch'){
        $this->setVar('registerFlag',1);
        $this->setVar('batchAction','batchderegister');
        $this->template='batch_tpl.php';
    }
   return $this->template;
 }

    /**
    * This method gets a list of the directories in the 'modules' dir,
    * and checks for module elements such as a controller.php file, a register.php
    * file, and a classes directory. it calls the functions checkForFile() and
    * checkdir(), both within this class and returns an assoc array.
    * @author James Scoble
    */
    function listModuleFiles($filter='listall')
    {
        $bigarray=$this->objModule->getAll();
        $regmodules=array();
        foreach ($bigarray as $line)
        {
            $regmodules[]=$line['module_id'];
        }
        $lookdir=$this->objConfig->getSiteRootPath()."/modules";
        $modlist=$this->checkdir($lookdir);
        natsort($modlist);
        $modulelist=array();
        foreach ($modlist as $line)
        {
            switch ($line)
            {
            case '.':
            case '..':
            case 'CVS':
                break; // don't bother with system-related dirs
            default:
            if ( is_dir($lookdir.'/'.$line) && ( ($filter=='listall') || (substr($line,0,1)==$filter) ) ){
                $isReg=in_array($line,$regmodules);
                $hasController=$this->checkForFile($lookdir.'/'.$line,'controller.php');
                $hasRegFile=($this->checkForFile($lookdir.'/'.$line,'register.conf')+$this->checkForFile($lookdir.'/'.$line,'register.php'));
                $modulelist[$line]['hasController']=$hasController;
                $modulelist[$line]['hasRegFile']=$hasRegFile;
                $modulelist[$line]['hasClasses']=$this->checkForFile($lookdir.'/'.$line,'classes');
                $modulelist[$line]['isReg']=$isReg;
                }
            }
        }
        return $modulelist;
    }

    /**
    * This method takes one parameter, which it treats as a directory name.
    * It returns an array - listing the files in the specified dir.
    * @author James Scoble
    * @param string $file - directory/folder
    * @returns array $list
    */
    function checkdir($file)
    {
        $dirObj = dir($file);
        while (false !== ($entry = $dirObj->read()))
        {
            $list[]=$entry;
        }
        $dirObj->close();
        return $list;
    }

    /**
    * Boolean test for the existance of a controller file in a module folder
    * @param string $modname
    * @returns bool TRUE|FALSE
    */
    function hasController($modname)
    {
        $lookdir=$this->objConfig->getSiteRootPath()."/modules/".$modname;
        if (is_dir($lookdir)){
            return $this->checkForFile($lookdir,'controller.php');
        } else {
            return FALSE;
        }
    }

    /**
    * Boolean test for the existance of file $fname, in directory $where
    * @author James Scoble
    * @param string $where file path
    * @param string $fname file name
    * @returns boolean TRUE or FALSE
    */
    function checkForFile($where,$fname)
    {
        if (file_exists($where."/".$fname))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
    * Method to get information about a specified module
    * by reading from the register.conf files
    * @param string $modname the module
    * @returns array $registerdata
    */
    function getModuleInfo($modname)
    {
        $filepath=$this->findRegisterFile($modname);
        if ($filepath) // if there were no file it would be FALSE
        {
            $registerdata=$this->objRegFile->readRegisterFile($filepath);
            if ($registerdata){
               return $registerdata;
            } else {
                return FALSE;
            }
        }
    } // end of function


    /**
    * This method is a 'wrapper' function - it takes info from the
    * 'register.conf' file provided by the module to be registered,
    * and passes it to its namesake function in the modulesadmin
    * class - which is where the SQL entries actually happen.
    * @author James Scoble
    * @param string $modname the module_id of the module to be used
    * @returns string $regResult
    */
    function registerModule($modname)
    {
        $filepath=$this->findRegisterFile($modname);
        if ($filepath) // if there were no file it would be FALSE
        {
            $this->registerdata=$this->objRegFile->readRegisterFile($filepath);
            if ($this->registerdata) {
                // Added 2005-08-24 as extra check
                if ( isset($this->registerdata['WARNING']) && ($this->getParam('confirm')!='1') ){
                    $this->output.=$this->warningText($modname,$this->registerdata['WARNING']);
                    return FALSE;
                }
               $regResult= $this->objModule->registerModule($this->registerdata);
               return $regResult;
            }
        } else {
            $this->output.=$this->confirmRegister('mod_moduleadmin_err_nofile');
            return FALSE;
        }
    } // end of function


    /**
    * Method to handle registration of multiple modules at once
    * @param array $modArray
    */
    function batchRegister($modArray)
    {
        foreach ($modArray as $line)
        {
            $this->smartRegister($line);
        }
    }

    /**
    * This method is designed to handle the registeration of multiple modules at once.
    * @param string $modname
    */
    function smartRegister($modname)
    {
        $isReg=$this->objModule->checkIfRegistered($modname,$modname);
        if ($isReg==TRUE){
            return TRUE;
        }
        $filepath=$this->findRegisterFile($modname);
        if ($filepath) // if there were no file it would be FALSE
        {
            $registerdata=$this->objRegFile->readRegisterFile($filepath);
            if ($registerdata){
                if (isset($registerdata['DEPENDS'])){
                    foreach ($registerdata['DEPENDS'] as $line)
                    {
                        $result=$this->smartRegister($line);
                        if ($result==FALSE) {
                            return FALSE;
                        }
                    }
                }
                $regResult= $this->objModule->registerModule($registerdata);
                if ($regResult=='OK'){
                    $this->output.=$this->confirmRegister('mod_moduleadmin_regconfirm',$modname).'<br />';
                }
                return $regResult;
            }
        } else {
            $this->output.=$this->confirmRegister('mod_moduleadmin_err_nofile');
            return FALSE;
        }
    } // end of function

    /**
    * Method to handle deregistration of multiple modules at once
    * @param array $modArray
    */
    function batchDeregister($modArray)
    {
        foreach ($modArray as $line)
        {
            $this->smartDeregister($line);
        }
    }

    /**
    * This method is designed to handle the deregisteration of multiple modules at once.
    * @param string $modname
    */
    function smartDeregister($modname)
    {
        $isReg=$this->objModule->checkIfRegistered($modname,$modname);
        if ($isReg==FALSE){
            return TRUE;
        }
        $filepath=$this->findRegisterFile($modname);
        if ($filepath) // if there were no file it would be FALSE
        {
            $registerdata=$this->objRegFile->readRegisterFile($filepath);
            if ($registerdata){
                // Here we get a list of modules that depend on this one
                $depending=$this->objModule->checkForDependentModules($modname);
                if (count($depending)>0){
                    foreach ($depending as $line)
                    {
                        $result=$this->smartDeregister($line);
                        if ($result==FALSE) {
                            return FALSE;
                        }
                    }
                }
                $regResult= $this->objModule->unInstall($modname,$registerdata);
                if ($regResult=='OK'){
                    $this->output.=$this->confirmregister('mod_moduleadmin_deregconfirm',$modname).'<br />';
                }
                return $regResult;
            }
        } else {
            $this->output.=$this->confirmRegister('mod_moduleadmin_err_nofile');
            return FALSE;
        }
    } // end of function


    /**
    * This method is a 'wrapper' function - it takes info from the 'register.conf'
    * file provided by the module to be registered, and passes it to its namesake
    * function in the modulesadmin class - which is where the SQL entries actually
    * happen. It uses file() to load the register.php file into an array, then
    * chew through it line by line, looking for keywords.
    *
    * @author James Scoble
    * @param string $modname the module_id of the module to be used
    * @returns boolean TRUE or FALSE
    */
    function unInstallModule($modname)
    {
        $filepath=$this->findRegisterFile($modname);
        $this->registerdata=$this->objRegFile->readRegisterFile($filepath);
        if (is_array($this->registerdata))
        {
            return $this->objModule->unInstall($modname,$this->registerdata);
        }
            else
            {
                $this->output.=$this->confirmRegister('mod_moduleadmin_err_nofile');
                return FALSE;
            }
    } // end of function unInstallModule()

    /** This is a method to check for existance of registration file
    * @author James Scoble
    * @param string modname
    * @returns FALSE on error, string filepatch on success
    */
    function findregisterfile($modname)
    {
        $endings=array('php','conf');
        $path=$this->objConfig->getSiteRootPath()."/modules/".$modname."/register.";
        foreach ($endings as $line)
        {
            if (file_exists($path.$line))
            {
                return $path.$line;
            }
        }
        return FALSE;
    }

    /**
    * This is a method to look through list of texts specified for module,
    * and see if they are registered or not.
    * @author James Scoble
    * @param string $modname
    * @param string $action - optional, if its 'fix' then the function tries
    * to add any texts that are missing.
    * returns array $mtexts
    */
    function moduleText($modname,$action='readonly')
    {
        $mtexts=array();
        $filepath=$this->objRegFile->findRegisterFile($modname);
        $rdata=$this->objRegFile->readRegisterFile($filepath,FALSE);
        $texts=$this->objModule->listTexts($rdata,'TEXT');
        if (is_array($texts))
        {
            foreach ($texts as $code=>$data)
            {
                $isreg=$this->objModule->checkText($code); // this gets an array,
                                                           // with 3 elements - flag, content, and desc
                $text_desc=$data['desc'];
                $text_val=$data['content'];
                if (($action=='fix')&&($isreg['flag']==0))
                {
                    $this->objModule->executeModSQL('BEGIN WORK'); //Start a transaction;
                    $this->objModule->addText($code,$text_desc,$text_val);
                    $this->objModule->executeModSQL('COMMIT'); //End the transaction;
                }
                if ($action=='replace')
                {
                    $this->objModule->executeModSQL('BEGIN WORK'); //Start a transaction;
                    $this->objModule->removeText($code);
                    $this->objModule->addText($code,$text_desc,$text_val);
                    $this->objModule->executeModSQL('COMMIT'); //End the transaction;
                }
                $mtexts[]=array('code'=>$code,'desc'=>$text_desc,'content'=>$text_val,'isreg'=>$isreg,'type'=>'TEXT');
            }
        }
        $texts=$this->objModule->listTexts($rdata,'USES');
        if (is_array($texts))
        {
            foreach ($texts as $code=>$data)
            {
                $isreg=$this->objModule->checkText($code); // this gets an array,
                                                           // with 3 elements - flag, content, and desc
                $text_desc=$data['desc'];
                $text_val=$data['content'];
                if (($action=='fix')&&($isreg['flag']==0))
                {
                    $this->objModule->executeModSQL('BEGIN WORK'); //Start a transaction;
                    $this->objModule->addText($code,$text_desc,$text_val);
                    $this->objModule->executeModSQL('COMMIT'); //End the transaction;
                }
                if ($action=='replace')
                {
                    $this->objModule->executeModSQL('BEGIN WORK'); //Start a transaction;
                    $this->objModule->removeText($code);
                    $this->objModule->addText($code,$text_desc,$text_val);
                    $this->objModule->executeModSQL('COMMIT'); //End the transaction;
                }
                $mtexts[]=array('code'=>$code,'desc'=>$text_desc,'content'=>$text_val,'isreg'=>$isreg,'type'=>'USES');
            }
        }
        return $mtexts;
    }

    /**
    * This is a method to update the text elements in all registered modules at once
    */
    function batchReplaceText()
    {
        $bigarray=$this->objModule->getAll();
        foreach ($bigarray as $line)
        {
            $texts=$this->moduleText($line['module_id'],'replace');
        }
    }

    /**
    * This method is a 'file reader' function - it takes info from the 'register.php'
    * file provided by the module to be registered, and uses file() to load the
    * register.php file into an array, then chew through it line by line, looking
    * for keywords. These are then returned as an associative array.
    *
    * @author James Scoble
    * @depreciated this function also exists in a distinct class, and this
    * version in the controller might not be up-to-date.
    *
    * SO..this is crud...why is it stil here? --->Derek 2004
    * Its gone now, but this comment-block is left for reference. --->James 2005
    */
    //end of function readRegisteFile


    /** This is a method to list the class files for a given module
    * @author James Scoble
    * @param string $modname
    * @returns array
    */
    function listClassFiles($modname)
    {
        $modname=$this->getParam('modname');
        $lookdir=$this->objConfig->siteRootPath()."/modules/".$modname."/classes";
        $filelist=$this->checkdir($lookdir);
        $classes=array();
        foreach ($filelist as $line)
        {
            switch ($line)
            {
            case '.':
            case '..':
            case 'CVS':
            break; // don't bother with system-related dirs
            default:
            $classes[]=$line;
            }
        }
        $this->setVar('modname',$modname);
        $this->setVar('classes',$classes);
        return $classes;
    }

    /**
    * This method is used to produce the text for successful registration or deregistration
    * @param string $text the text to use for the message
    */
    function confirmRegister($text='mod_moduleadmin_regconfirm',$modname='',$bold=TRUE)
    {
        if ($modname==''){
            $modname=$this->modname;
        }
        if ($bold){
            $bopen='<strong><em>';
            $bclose='</em></strong>';
        } else {
            $bopen='';
            $bclose='';
        }
        $objLanguage=& $this->getObject('language','language');
        $outstr=$objLanguage->languageText($text);
        //$outstr=str_replace('USER',$bopen.$this->objUser->fullname().$bclose,$outstr);
        $outstr=str_replace('MODULE',$bopen.$modname.$bclose,$outstr);
        return $outstr;
    }

    /**
    * This is a method to handle first-time registration of the basic modules
    */
    function firstRegister()
    {
        $mList=file($this->objConfig->siteRootPath().'/install/default_modules.txt');
        foreach ($mList as $line)
        {
            $this->registerModule(trim($line));
        }
        // Flag the first time registration as having been run
        $this->objSysConfig->insertParam('firstreg_run', 'moduleadmin',TRUE);
        // Make certain the user-defined postlogin module is registered.
        $postlogin=$this->objSysConfig->getValue('KEWL_POSTLOGIN_MODULE','_site_');
        if (($postlogin!='')&&(!($this->objModule->checkIfRegistered($postlogin,$postlogin)))){
            $this->registerModule($postlogin);
        }
    }

    function requiresLogin() // overides that in parent class
    {
        $action=$this->getParam('action');
        switch($action)
        {
        case 'firsttimeregistration':
            return FALSE;
        default:
            return TRUE;
        }
    }


    /**
    * Method to display a warning if there is a warning tag
    * @param string $modname the module
    * @param array $warnings the warnings
    * @returns string $str the warning screen
    */
    function warningText($modname,$warnings)
    {
        $str="\n<fieldset>\n<legend>$modname</legend>\n";
        foreach ($warnings as $line)
        {
            $str.="<span class='warning'><b>$line</b></span><br />\n";
        }
        $str.="</fieldset>\n";
        $str.="<strong>".$this->objLanguage->languageText('mod_moduleadmin_asksure')."</strong><br />\n";
        $objNav=&$this->getObject('navbuttons','navigation');
        $str.=$objNav->pseudoButton($this->uri(array('action'=>'register','modname'=>$modname,'confirm'=>'1')), $this->objLanguage->languageText('mod_word_register'));
        $str.=$objNav->pseudoButton($this->uri(array()), $this->objLanguage->languageText('phrase_goback'));
        return $str;
    }


 } // end of class moduleadmin


?>
