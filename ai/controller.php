<?php

/**
 * Short description for file
 * 
 * Long description (if any) ...
 * 
 * PHP version 5
 * 
 * The license text...
 * 
 * @category  Chisimba
 * @package   adm
 * @author    Administrative User <pscott@uwc.ac.za>
 * @copyright 2007 Administrative User
 * @license   gpl
 * @version   CVS: $Id: controller.php 7800 2008-01-17 12:53:42Z paulscott $
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
ini_set("max_execution_time", -1);
// end security check


/**
 * Short description for class
 * 
 * Long description (if any) ...
 * 
 * @category  Chisimba
 * @package   adm
 * @author    Administrative User <pscott@uwc.ac.za>
 * @copyright 2007 Administrative User
 * @license   gpl
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class ai extends controller
{

	/**
     * Description for public
     * @var    unknown
     * @access public 
     */
	public $objLanguage;

	/**
     * Description for public
     * @var    unknown
     * @access public 
     */
	public $objConfig;

	public $objHuman;
	public $objGA;

	/**
     * Constructor method to instantiate objects and get variables
     */
	public function init()
	{
		try {
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objConfig = $this->getObject('altconfig', 'config');
			$this->objGA = $this->getObject('ga');
			$this->objHuman = $this->newObject('human');
		}
		catch(customException $e)
		{
			customException::cleanUp();
			exit;
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
				echo "Human demo...<br /><br />";
				
				$adam = $this->newObject('human');
				$adam->makeHuman(4,2,3,1);
				
				$eve = $this->newObject('human');
				$eve->makeHuman(1,4,2,3);
				
				$this->objGA->population = array($adam, $eve);
				
				$this->objGA->fitness_function = 'total'; 
				$this->objGA->num_couples = 1;				//4 couples per generation (when possible)
				$this->objGA->death_rate = 0;				//No kills per generation
				$this->objGA->generations = 100;				//Executes 100 generations
				$this->objGA->crossover_functions = 'avg';   //Uses the 'avg' function as crossover function
				$this->objGA->mutation_function = 'inc';		//Uses the 'inc' function as mutation function
				$this->objGA->mutation_rate = 10;			//10% mutation rate
				//var_dump($this->objGA); die();
				$this->objGA->evolve();						//Run
				//$this->debug($this->objGA->population);
				$this->debug($this->objGA->select($this->objGA->population,'total',1)); //The best
				break;
		}
	}
	
	public function debug($x)
	{
		echo "<pre style='border: 1px solid black'>";
		print_r($x);
		echo '</pre>';
	}

}
?>