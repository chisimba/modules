<?php
/* -------------------- sis class extends controller ----------------*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check


class sis extends controller
{
    public $objConfig;
    public $objLanguage;
    public $objSISforms;

    /**
    * ye olde standard init()
    */
    public function init()
    {
        $this->objConfig = $this->getObject('altconfig','config');
        $this->objLanguage = $this->getObject('language','language');
        $this->objSISforms = $this->getObject('studentforms','sis');
        $this->objFMPro = $this->getObject('fmpro', 'filemakerpro');
    }

    /**
    *
    *
    */
    public function dispatch($action)
    {
        switch ($action)
        {
            case "myprofile":
                return $this->myProfile();
            case "saveprofile":
                return $this->saveProfile();
            case 'newstudent':
                return $this->showStudent();
            case 'savestudent':
                return $this->saveStudent();
            default:
                return $this->showDefault();
        }
    }


    /**
    * Default action
    * @returns string $template
    */
    public function showDefault()
    {
       return "default_tpl.php";
    }

    /**

    /**
    * Calls classes to get form info
    * @returns string $template
    */
    public function showStudent()
    {
       $this->string=$this->objSISforms->studentInput();
       return "default_tpl.php";
    }

    /**
    * Calls classes to save form info
    * @returns string $template
    */
    public function saveStudent()
    {
       $paramNames=array();
       $paramvals=array();
       foreach ($paramNames as $line)
       {
          $paramvals[$line]=$this->getParam($line);
       }

       return "default_tpl.php";
    }

    private function myProfile() {
        $profile = $this->objFMPro->getUserProfile();
        //var_dump($profile[0]->_impl->_relatedSets);
        $this->setVarByRef('profile', $profile);
        var_dump($profile);
    }



}

?>
