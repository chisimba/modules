<?php

/**
*
* WTM Controller class for WTM module. 
* 
* @category Chisimba
* @package wtm
* @author Yen-Hsiang Huang <wtm.jason@gmail.com>
* @copyright 2007 AVOIR
* @license http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
* @version CVS: $Id:$
* @link: http://avoir.uwc.ac.za 
*/

// security check - must be included in all scripts
/**
* The $GLOBALS is an array used to control access to certain constants.
* Here it is used to check if the file is opening in engine, if not it
* stops the file from running.
*
* @global entry point $GLOBALS['kewl_entry_point_run']
* @name $kewl_entry_point_run
*/
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}


class WTM extends controller
{
	public $objLanguage;	

	public $objDBBuildings;
	
	public $objDBEvents;
	
	public $objCoordinate;

	public function init()
	{
		//Instantiate the language object.
  		$this->objLanguage = $this->getObject('language','language');
  		//Instantiate the Building DB Object.
  		$this->objDBBuildings = $this->getObject('dbwtm_buildings','wtm');
		//Instantiate the Event DB Object.
  		$this->objDBEvents = $this->getObject('dbwtm_events','wtm');
		//Instantiate the coordinate object.
		$this->objCoordinate = $this->getObject('coordinate','wtm');
		
	}

	//Standard controller dispatch method, set to receive an action and execute it.
  	public function dispatch($action)
 	{
   		//Get action from query string. Default is set to viewBuildings for initial page.
   		$action=$this->getParam('action', 'viewBuildings');
		//Convert the action into a method.
   		$method = $this->__getMethod($action);
   		//Execute the action.
   		return $this->$method();
 	}

	//Action validating method. Checks if the action corresponds to an existing method.
 	private function __validAction(& $action)
 	{
   		if (method_exists($this, "__".$action)) 
		{
			return TRUE;
   		} 
		else 
		{
     		return FALSE;
   		}
 	}

	//Get method function. Converts action into the corresponding method.
 	private function __getMethod(& $action)
 	{
     	if ($this->__validAction($action)) 
		{
			return "__" . $action;
     	}
		else 
		{
		    return "__actionError";
     	}
 	}

	//Action error method. Displays error message for invalid action.
 	private function __actionError()
 	{
    	 //Get action from query string.
     	$action=$this->getParam('action');
		 //Pass error message to the action error template.
     	$this->setVar('str', "<h3>" . $this->objLanguage->languageText("phrase_unrecognisedaction") .": " . $action . "</h3>");
     	return 'actionError_tpl.php';
 	}

	//Add building method. Returns edit building template.
 	private function __addBuilding()
 	{
		return 'editBuilding_tpl.php';
 	}

	//View buildings method. Returns list all buildings template.
 	private function __viewBuildings()
 	{
		return 'listallBuildings_tpl.php';
 	}

	//Add new building method, inserts the form data into the database and returns the list-all buildings template.
	private function __addNewBuilding()
 	{
    	//Data is fetched from the form. Coordinates are multiplied by 10^5 to eliminate decimals.
    	$building = $this->getParam('building');
    	$longcoordinate = 100000 * $this->getParam('longcoordinate'); 
		$latcoordinate = 100000 * $this->getParam('latcoordinate'); 
		$xexpand = 100000 * $this->getParam('xexpand');
		$yexpand = 100000 * $this->getParam('yexpand');
    	//Insert the data to building DB.
    	$id = $this->objDBBuildings->insertSingle($building,$longcoordinate,$latcoordinate,$xexpand,$yexpand);
    	return 'listallBuildings_tpl.php';
 	}

	//Edit building method, retrieves and passes the building id to the edit building template.
 	private function __editBuilding()
 	{
		//Building's id is retrieved.
    	$id = $this->getParam('id');
		//Building id is passed to the edit building template.
    	$this->setVar('id', $id);
    	return "editBuilding_tpl.php";
 	}

	//Update building method, gets updated form data and modifies the entry in the database. Returns the list-all buildingstemplate.
 	private function __updateBuilding()
 	{
    		//Data is fetched from the form. Coordinates are NOT multiplied by 10^5 as they should have been retrieved in integer form.
    		$id = $this->getParam('id');
    		$building = $this->getParam('building');
    		$longcoordinate = $this->getParam('longcoordinate'); 
			$latcoordinate = $this->getParam('latcoordinate'); 
			$xexpand = $this->getParam('xexpand');
			$yexpand = $this->getParam('yexpand');
    		//Update the building data in the DB.
    		$id = $this->objDBBuildings->updateSingle($id,$building,$longcoordinate,$latcoordinate,$xexpand,$yexpand);
    		return "listallBuildings_tpl.php";
 	} 

	//Delete building method. Gets the building id and deletes the building from the database. Returns the list-all template.
 	private function __deleteBuilding()
 	{
    		//Get the form data
    		$id = $this->getParam('id');
    		//Delete the building
    		$id = $this->objDBBuildings->deleteSingle($id);
    		return "listallBuildings_tpl.php";
 	}
	
	//Add event method. Returns the edit event template
 	private function __addEvent()
 	{
     		return 'editEvent_tpl.php';
 	}

	//View events method. Passes the building id returns the list-all events template
 	private function __viewEvents()
 	{
			$this->setVar('refID', $this->getParam('refID'));
     		return 'listallEvents_tpl.php';
 	}

	//Add new event method. Inserts the form data into the database and returns the list-all events template
	private function __addNewEvent()
 	{
    		//Data is fetched from the form.
    		$buildingid = $this->getParam('buildingid');
    		$event = $this->getParam('event');
			$date = $this->getParam('date');
			$description = $this->getParam('description');
			$imagename = $this->getParam('imagename');
			$videoname = $this->getParam('videoname');
    		//Insert the data to events DB
    		$id = $this->objDBEvents->insertSingle($buildingid,$event,$date,$description,$imagename,$videoname);
			//Building id is passed to the list all events template to identify relevant events
			$this->setVar('refID', $buildingid);
    		return 'listallEvents_tpl.php';
 	}

	//Edit events method. Passes the event and building id and returns the edit event template
 	private function __editEvent()
 	{
    		//Retrieve event id
			$id = $this->getParam('id');
			//Retrieve building id and name
			$refID = $this->getParam('refID');
			$refbuilding = $this->getParam('refbuilding');
    		//Passes all the information to the edit event template
			$this->setVar('id', $id);
			$this->setVar('refID', $refID);
			$this->setVar('refbuilding', $refbuilding);
    		return "editEvent_tpl.php";
 	}

	//Update event method. Gets updated form data and modifies the entry in the database. Returns the list-all events template
 	private function __updateEvent()
 	{
    		//Retrieve updated form data
    		$id = $this->getParam('id');
    		$buildingid = $this->getParam('buildingid');
    		$event = $this->getParam('event'); 
			$date = $this->getParam('date');
			$description = $this->getParam('description');
			$imagename = $this->getParam('imagename');
			$videoname = $this->getParam('videoname');
    		//Update the event in the events DB
    		$id = $this->objDBEvents->updateSingle($id,$buildingid,$event,$date,$description,$imagename,$videoname);
			//Pass the building id to the template to identify relevant events
			$this->setVar('refID', $buildingid);
    		return "listallEvents_tpl.php";
 	} 

	//Delete event method. Gets the event id and deletes from the database. Returns the list-all template
 	private function __deleteEvent()
 	{
    		//Retrieve the event id
    		$id = $this->getParam('id');
    		//Delete the event
    		$id = $this->objDBEvents->deleteSingle($id); 
			//Pass the building id to the template to identify relevant events
			$this->setVar('refID', $buildingid);
    		return "listallEvents_tpl.php";
 	}
	
	//Search for building method. Receives a longitude and latitude coordinates as well as a compass heading and determines 
	//if any building is in that direction relative to the coordinates
	private function __search()
	{
		//Retrieve given data from URL
		$longcoordinate = $this->getParam('longcoordinate');
		$latcoordinate = $this->getParam('latcoordinate');
		$angle = $this->getParam('angle');
		//Execute search with the given data		
		$this->objCoordinate->search($longcoordinate,$latcoordinate,$angle);
	}
	
	//Retrieve events method for a specific building. Receives a building id which is used as a search filter parameter to search through the events database
	private function __retrieve()
	{
		//Retrieve given data from URL
		$buildingid = $this->getParam('buildingid');
		//Execute retrieve with given data
		$this->objCoordinate->retrieve($buildingid);
	}
	
	//Retrieve specific method for specific media types. Receives an event id and the requested media type 
	//number which it uses to access the specific media type for the specific event
	private function __retrievemedia()
	{
		//Retrieve given data from URL
		$eventid = $this->getParam('eventid');
		$num = $this->getParam('num');
		//Executre media retrieve with given data
		$this->objCoordinate->retrievemedia($eventid, $num);
	}
	

	//overrides login when accessing from outside
	function requiresLogin()
	{
			return false;
	}
}
?>

