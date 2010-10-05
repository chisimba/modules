<?php
/**
*
* WTM view events class
*
* This file provides a data viewing class for the WTM module's
* events database. Its purpose is allow administrators to view
* the contents of the database.
* 
* @category Chisimba
* @package wtm
* @author Yen-Hsiang Huang <wtm.jason@gmail.com>
* @copyright 2007 AVOIR
* @license http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
* @version CVS: $Id:$
* @link: http://avoir.uwc.ac.za 
*/

// security check
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
// end security check

class viewevents extends object 
{
	public $objLanguage;
 
	public $objDBEventss;
 
	public function init()
	{
		 //Instantiate the language object
		 $this->objLanguage = $this->getObject('language','language');
		 //Instantiate the language object
		 $this->objDBEvents = $this->getObject('dbwtm_events','wtm');
	}

	private function loadElements()
	{
		 //Load the form class
		 $this->loadClass('form','htmlelements');
		 //Load the form class
		 $this->loadClass('link','htmlelements');
		 //Load the textinput class
		 $this->loadClass('textinput','htmlelements');
		 //Load the label class
		 $this->loadClass('label','htmlelements');
		 //Load the textarea class
		 $this->loadClass('textarea','htmlelements');
		 //Load the button object
		 $this->loadClass('button','htmlelements');
	}

	private function buildForm()
	{
		$this->loadElements();
		//Create the form
		$objForm = new form('events', $this->getFormAction());
		//Fetch the buildings from DB
		$allEvents = $this->objDBEvents->listAll();
		// Create a table object
		$eventsTable = &$this->newObject("htmltable", "htmlelements");
		//Define the table border
		$eventsTable->border = 0;
		//Set the table spacing
		$eventsTable->cellspacing = '12';
		//Set the table width
		$eventsTable->width = "60%";

		//Create the array for the table header
		$tableHeader = array();
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_event", 'wtm');
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_date", 'wtm');
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_description", 'wtm');
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_imagename", 'wtm'); 
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_videoname", 'wtm'); 

		//Create the table header for display
		$eventsTable->addHeader($tableHeader, "heading");
		
		$refID = $this->getParam('refID');
		$refbuilding = $this->getParam('refbuilding');
		//Render each building in a table.
		foreach($allEvents as $thisEvent)
		{
			if ($thisEvent["buildingid"] == $refID)
			{
				//Store the values of the array in variables
				$id = $thisEvent["id"];
				$buildingid = $thisEvent["buildingid"];
				$event = $thisEvent["event"];
				$date = $thisEvent["date"];
				$description = $thisEvent["description"];  
				$imagename = $thisEvent["imagename"];  
				$videoname = $thisEvent["videoname"];  
				$modified = $thisEvent["modified"];
				//Edit Row 
				$iconEdSelect = $this->getObject('geticon','htmlelements');
				$iconEdSelect->setIcon('edit');	
				$iconEdSelect->alt = "Edit event";
				$mngedlink = new link($this->uri(array(
											'module'=>'wtm',
											'action'=>'editEvent', 
											'id' => $id,
											'refID'=>$refID,
											'refbuilding'=>$refbuilding
											)));
				$mngedlink->link = $iconEdSelect->show();
				$linkEdManage = $mngedlink->show(); 
				//Get the icon object
				$iconDelete = $this->getObject('geticon', 'htmlelements');
				//Set the icon name
				$iconDelete->setIcon('delete');
				//Set the alternative text of the icon
				$iconDelete->alt = $this->objLanguage->languageText("mod_wtm_deleteEvent", 'wtm');
				//Set align to default
				$iconDelete->align = false;
				//Create a new link Object
				$objConfirm = &$this->getObject("link", "htmlelements");
				//Create a new confirm object. 
				$objConfirm = &$this->newObject('confirm', 'utilities');
				//Set object to confirm and the path for the confirm implementation and confirm text
				$objConfirm->setConfirm($iconDelete->show() , $this->uri(array(
											'module' => 'wtm',
											'action' => 'deleteEvent',
											'id' => $id,
											'refID' => $refID
				)) , $this->objLanguage->languageText('mod_wtm_suredelete', 'wtm'));

				// Add the table rows.
				$eventsTable->startRow();
				$eventsTable->addCell($event);
				$eventsTable->addCell($date);
				$eventsTable->addCell($description);
				$eventsTable->addCell($imagename);
				$eventsTable->addCell($videoname);
				$eventsTable->addCell($linkEdManage);
				$eventsTable->addCell($objConfirm->show());
				$eventsTable->endRow();
			}
		}
	
		//Get the icon object
		$iconSelect = $this->getObject('geticon','htmlelements');
		//Set the name of the icon
		$iconSelect->setIcon('add');	
		//Set the alternative text of the icon
		$iconSelect->alt = "Add New Event";
		
		//Create a new link for the add link
		$mnglink = new link($this->uri(array(
									'module'=>'wtm',
									'action'=>'addEvent',
									'refID'=>$refID,
									'refbuilding'=>$refbuilding
									)));
		//Set the link text/image
		$mnglink->link = $iconSelect->show();
		//Build the link
		$linkManage = $mnglink->show();
		//Add the add button to the table
		// Add the table rows.
		$eventsTable->startRow();
		//Note we are using column span. The other four parameters are set to default
		$eventsTable->addCell($linkManage,'','','','','colspan="2"');
		$eventsTable->endRow();
		
		//----------BACK BUTTON--------------
        //Create a button for submitting the form
        $objBackButton = new button();
				
		$mngBackLink = new link($this->uri(array(
									'module'=>'wtm',
									'action'=>'viewBuildings' 
									)));
		$objBackButton->setValue(' '.$this->objLanguage->languageText("mod_wtm_backbutton", "wtm").' ');
		$mngBackLink->link = $objBackButton->show();
		$linkBackManage = $mngBackLink->show();
        // Use the language object to label button
		// with the word save
			  
		$objForm->addToForm($eventsTable->show());
		$objForm->addToForm($linkBackManage);
		return $objForm->show();
	}

	private function getFormAction()
	{
		$action = $this->getParam("action", "addEvent");
		if ($action == "editEvent") 
		{
			$formAction = $this->uri(array("action" => "updateEvent"), "wtm" );
		}
		else
		{
			$formAction = $this->uri(array("action" => "addEvent"), "wtm");
		}
		return $formAction;
		}
		
	public function show()
	{
		return $this->buildForm();
	}
}
?>
