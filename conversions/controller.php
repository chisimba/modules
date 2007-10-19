<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
class conversions extends controller
{
    public $objLanguage;
    public $objConfig;
    public $objDist;
    public $objTemp;
    public $objVol;
    public $objWeight;
    public $objUser;
    //Constructor method to instantiate objects and get variables
    public function init() 
    {
        try {
            $this->objUser = $this->getObject('user', 'security');
            $this->objDist = $this->getObject('dist');
            $this->objTemp = $this->getObject('temp');
            $this->objVol = $this->getObject('vol');
            $this->objWeight = $this->getObject('weight');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    /**
     * Method to process actions to be taken
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null) 
    {
        switch ($action) {
            default:
            case 'default':
                $goTo = $this->getParam('goTo');
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'dist':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'dist';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'temp':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'temp';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'vol':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'vol';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'weight':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'weight';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;
        }
    }
}
?>
