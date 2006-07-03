<?
/*
* Class for reading all of the register.conf files.
* @author James Scoble methods moved from controller by Derek Keats
* @version $Id$
* @copyright 2004
* @license GNU GPL
*/

class moduleinfo extends object
{

    private $objConfig;
    private $objModulesAdmin;
    private $objFileReader;

    public function init ()
    {
        //Instantiate the configuration object
        $this->objConfig=&$this->getObject('config','config');
        // instantiate the main class for module registration:
        $this->objModulesAdmin=&$this->getObject('modulesadmin','moduleadmin');
        // the class for reading register.conf files
        $this->objFileReader=&$this->newObject('filereader','moduleadmin');
    }


    /**
    * This method gets a list of the directories in the 'modules' dir,
    * and checks for module elements such as a controller.php file, a register.php
    * file, and a classes directory. it calls the functions checkForFile() and
    * checkDir(), both within this class and returns an array.
    * @author James Scoble
    */
    public function listModuleFiles()
    {
        try {
            $dir=$this->objConfig->siteRootPath()."/modules";
            $modules=$this->listDir($dir);
            natsort($modulelist);
            $k=0;
            foreach ($modules as $module) {
                switch ($module) {
                case '.':
                case '..':
                case 'CVS':
                    break; // don't bother with system-related dirs
                default:
                    if (is_dir($dir.'/'.$module)) {
                        $ret[$k]['Counter'] = $k+1;
                        $ret[$k]['Module'] = $module;
                        $filepath=$this->findRegisterFile($module);
                        if ($filepath !== FALSE) {
                            $registerdata=$this->objFileReader->readRegisterFile($filepath);
                            $ret[$k]['Name'] = $registerdata['MODULE_NAME'];
                            $ret[$k]['Description'] = $registerdata['MODULE_DESCRIPTION'];
                            $ret[$k]['Authors'] = $registerdata['MODULE_AUTHORS'];
                            $ret[$k]['KINKY purpose'] = " ";
                        }
                        $k++;
                    }
                }
            }
            return $ret;
        }
        catch (Exception $e) {
            echo $e->getMessage();
            exit(0);
        }
    }

    /**
    * Lists the files in the specified dir.
    * @author James Scoble
    * @param string $path Directory to search
    * @returns array $list
    */
    private function listDir($file)
    {
        $list = array();
        $dirObj = dir($file);
        while (false !== ($entry = $dirObj->read()))
        {
            $list[]=$entry;
        }
        $dirObj->close();
        return $list;
    }


    /**
    * Boolean test for the existance of file $fname, in directory $where
    * @author James Scoble
    * @param string $path Directory
    * @param string $filename File Name
    * @returns boolean TRUE or FALSE
    */
    private function checkForFile($path,$filename)
    {
        return file_exists($path."/".$filename);
    }

    /** This is a method to check for existance of registration file
    * @author James Scoble
    * @param string modname
    * @returns FALSE on error, string filepatch on success
    */
    private function findRegisterFile($modname)
    {
        $extensions=array('.conf', '.php'); // Search for .conf first
        $filepath=$this->objConfig->siteRootPath()."/modules/".$modname."/register";
        foreach ($extensions as $extension)
        {
            if (file_exists($filepath.$extension))
            {
                return $filepath.$extension;
            }
        }
        return FALSE;
    }
    
    /**
    * Method to get information about a specified module
    * by reading from the register.conf files
    * @param string $modName the module
    * @returns array $registerdata
    */
    public function getModuleInfo($modName)
    {
        $filepath=$this->findRegisterFile($modName);
        if ($filepath !== FALSE)
        {
            $registerdata=$this->objFileReader->readRegisterFile($filepath);
            if ($registerdata !== FALSE){
               return $registerdata;
            } else {
                return FALSE;
            }
        }
    } // end of function
} //end of class definition
?>