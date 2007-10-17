<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
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
		try
		{
			$this->objUser = $this->getObject('user', 'security');
			$this->objConvertIt = $this->getObject('convertIt');
			$this->objDist = $this->getObject('dist');
			$this->objTemp = $this->getObject('temp');
			$this->objVol = $this->getObject('vol');
			$this->objWeight = $this->getObject('weight');
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objConfig = $this->getObject('altconfig', 'config');
		}
		catch(customException $e)
		{
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
		switch ($action)
		{
			default:
				case 'default':
					return 'convertit_tpl.php';
					break;

				case 'goto':
					return $this->objConvertIt->doChange($this->getParam('goTo'));
					break;

				case 'dist':
					$value = $this->getParam('value');
					$from = $this->getParam('from');
					$to = $this->getParam('to');
					$this->setVarByRef('action', $action);
					$this->setVarByRef('value', $value);
					$this->setVarByRef('from', $from);
					$this->setVarByRef('to', $to);
					return "dist_tpl.php";
            		break;

				case 'temp':
					$value = $this->getParam('value');
					$from = $this->getParam('from');
					$to = $this->getParam('to');
					$this->setVarByRef('action', $action);
					$this->setVarByRef('value', $value);
					$this->setVarByRef('from', $from);
					$this->setVarByRef('to', $to);
					return "temp_tpl.php";
					break;

				case 'vol':
					$value = $this->getParam('value');
					$from = $this->getParam('from');
					$to = $this->getParam('to');
					$this->setVarByRef('action', $action);
					$this->setVarByRef('value', $value);
					$this->setVarByRef('from', $from);
					$this->setVarByRef('to', $to);
					return "vol_tpl.php";
					break;

				case 'weight':
					$value = $this->getParam('value');
					$from = $this->getParam('from');
					$to = $this->getParam('to');
					$this->setVarByRef('action', $action);
					$this->setVarByRef('value', $value);
					$this->setVarByRef('from', $from);
					$this->setVarByRef('to', $to);
					return "weight_tpl.php";
					break;  

		}
	}
}
?>
