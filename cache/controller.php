<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check
class cache extends controller
{
	public $objLog;
	public $objLanguage;
	public $objConfig;
	public $objYaml;



	/**
     * Constructor method to instantiate objects and get variables
     */
	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objConfig = $this->getObject('altconfig', 'config');
			$this->objYaml = $this->getObject('yaml', 'utilities');
			//Get the activity logger class
			$this->objLog = $this->newObject('logactivity', 'logger');
			//Log this module call
			$this->objLog->log();
		}
		catch(customException $e) {
			echo customException::cleanUp();
			die();
		}
	}

	/**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
	public function dispatch($action = Null)
	{
		switch ($action) {
			default:
				// get the cache config file
				$servarr = file('cache.config');
				foreach($servarr as $servers)
				{
					$serv = explode(', ', $servers);
					$cache[] = array('ip' => $serv[0], 'port' => $serv[1]);
				}
				$this->setVarByRef('cache', $cache);
				return 'edit_tpl.php';
				break;
				
			case 'addserver':
				$ip = $this->getParam('ip');
				$port = $this->getParam('port');
				$servarr = file('cache.config');
				$new = "$ip, $port \r\n";
				$adder = array($new);
				$all = array_merge($adder, $servarr); 
				// re-write the cache config file
				$filename = 'cache.config';
				
			    $handle = fopen($filename, 'a');
			    if(fwrite($handle, $new) == FALSE)
			    {
			    	echo "fokop";
			    }
    			fclose($handle);

				die();
		}
	}
}
?>