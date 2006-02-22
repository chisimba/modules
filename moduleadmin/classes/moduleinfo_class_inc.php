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

    var $objConfig;

    function init ()
    {
        //Instantiate the configuration object
        $this->objConfig=&$this->getObject('config','config');
        // instantiate the main class for module registration:
        $this->objModule=&$this->getObject('modulesadmin','modulelist');
        // the class for reading register.conf files
        $this->objRegFile=$this->newObject('filereader','moduleadmin');
    }


    /**
    * This method gets a list of the directories in the 'modules' dir,
    * and checks for module elements such as a controller.php file, a register.php
    * file, and a classes directory. it calls the functions checkForFile() and
    * checkdir(), both within this class and returns an assoc array.
    * @author James Scoble
    */
    function listModuleFiles()
    {
        $lookdir=$this->objConfig->siteRootPath()."/modules";
        $modlist=$this->checkdir($lookdir);
        natsort($modlist);
        $k=0;
        foreach ($modlist as $line) {
            switch ($line) {
            case '.':
            case '..':
            case 'CVS':
                break; // don't bother with system-related dirs
            default:
                if (is_dir($lookdir.'/'.$line)) {
                    $ret[$k]['Counter'] = $k+1;
                    $ret[$k]['Module'] = $line;
                    $filepath=$this->findRegisterFile($line);
                    if ($filepath) {
                        $registerdata=$this->objRegFile->readRegisterFile($filepath);
                        if (isset($registerdata['MODULE_NAME'])) {
                            $ret[$k]['Name'] = $registerdata['MODULE_NAME'];
                        } else {
                            $ret[$k]['Name'] = "ERROR!No module name";
                        }
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

    /** This is a method to check for existance of registration file
    * @author James Scoble
    * @param string modname
    * @returns FALSE on error, string filepatch on success
    */
    function findregisterfile($modname)
    {
        $endings=array('php','conf');
        $path=$this->objConfig->siteRootPath()."/modules/".$modname."/register.";
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
    

} //end of class definition

?>
