<?php
/**
*
* WTM edit event class
*
* This file provides an edit class for the WTM module's event database.
* It creates the editing form for which new events can be enter or existing
* events modified. Its purpose is to allow administrators to edit/add
* event to the database.
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

class editevent extends object 
{
	public $objLanguage;
 
	public $objDBEvents;
 
	public function init()
	{
		//Instantiate the language object
		$this->objLanguage = $this->getObject('language','language');
		//Load the DB object
		$this->objDBEvents = $this->getObject('dbwtm_events','wtm');  
	}
	
	
	private function loadElements()
	{
		//Load the form class
		$this->loadClass('form','htmlelements');
		//Load the textinput class
		$this->loadClass('textinput','htmlelements');
		//Load the textarea class
		$this->loadClass('textarea','htmlelements'); 
		//Load the label class
		$this->loadClass('label','htmlelements');
		//Load the button object
		$this->loadClass('button','htmlelements');
		$this->loadClass('link','htmlelements');
	}
	
	private function buildForm()
	{
		$this->loadElements();
		//Create the form
		$objForm = new form('events', $this->getFormAction());
		$id = $this->getParam('id');
		$refID = $this->getParam('refID');
		$refbuilding = $this->getParam('refbuilding');
		//If id is not empty, get the event details
		if (!empty($id))
		{
			//Fetch the data
			$eventData = $this->objDBEvents->listSingle($id);
			$buildingid = $eventData[0]["buildingid"];
			$event = $eventData[0]["event"];
			$date = $eventData[0]["date"];
			$description = $eventData[0]["description"];
			$imagename = $eventData[0]["imagename"];
			$videoname = $eventData[0]["videoname"];
		}
		else
		{
			$buildingid = $refID;
			$event = "";
			$date = "";
			$description = "";
			$imagename = "";
			$videoname = "";
		}

        //...........BUILDINGID TEXT INPUT.......................
        //Create a new textinput for the name of the event
        $objBuildingID = new textinput('buildingid', $buildingid, 'hidden');
        //Create a new label for the text labels
        $BuildingIDLabel = new label ($refbuilding);
        $objForm->addToForm($BuildingIDLabel ->show() , "<br />");
        $objForm->addToForm($objBuildingID->show() . "<br />");

        //...........EVENT TEXT INPUT.......................
        //Create a new textinput for the name of the event
        $objEvent = new textinput('event', $event);
        //Create a new label for the text labels
        $eventLabel = new label ($this->objLanguage->languagetext("mod_wtm_event","wtm"),"event");
        $objForm->addToForm($eventLabel ->show() , "<br />");
        $objForm->addToForm($objEvent->show() . "<br />");
		
		//...........DATE TEXT INPUT.......................
        //Create a new textinput for the date
        $objDate = new textinput('date', $date);
        //Create a new label for the text labels
        $dateLabel = new label ($this->objLanguage->languagetext("mod_wtm_date","wtm"),"date");
        $objForm->addToForm($dateLabel->show() , "<br />");
        $objForm->addToForm($objDate->show() . "<br />");
		
		//...........DESCRIPTION TEXTAREA--------------
        //Create a new textarea for the description
        $objDescription = new textarea('description', $description);
        $descriptionLabel = new label($this->objLanguage->languageText("mod_wtm_description","wtm"),"description");
        $objForm->addToForm($descriptionLabel->show() . "<br />");
        $objForm->addToForm($objDescription->show() . "<br />");
		
		//...........IMAGENAME TEXT INPUT.......................
        //Create a new textinput for medialink
        $objImageName = new textinput('imagename', $imagename);
        //Create a new label for the text labels
        $imageNameLabel = new label ($this->objLanguage->languagetext("mod_wtm_imagename","wtm"),"imagename");
        $objForm->addToForm($imageNameLabel->show() , "<br />");
        $objForm->addToForm($objImageName->show() . "<br />");
		
		//...........VIDEONAME TEXT INPUT.......................
        //Create a new textinput for medialink
        $objVideoName = new textinput('videoname', $videoname);
        //Create a new label for the text labels
        $videoNameLabel = new label ($this->objLanguage->languagetext("mod_wtm_videoname","wtm"),"videoname");
        $objForm->addToForm($videoNameLabel->show() , "<br />");
        $objForm->addToForm($objVideoName->show() . "<br />");
		
        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
        // Use the language object to label button
		// with the word save
        $objButton->setValue(' '.$this->objLanguage->languageText("mod_wtm_saveEvent", "wtm").' ');
        $objForm->addToForm($objButton->show());
      			
		//----------BACK BUTTON--------------
        //Create a button for submitting the form
        $objBackButton = new button();
				
		$mngBackLink = new link($this->uri(array(
									'module'=>'wtm',
									'action'=>'viewEvents',
									'refID'=>$refID,
									'refbuilding'=>$refbuilding
									)));
		$objBackButton->setValue(' '.$this->objLanguage->languageText("mod_wtm_backbutton", "wtm").' ');
		$mngBackLink->link = $objBackButton->show();
		$linkBackManage = $mngBackLink->show();
        // Use the language object to label button
		// with the word save
		$objForm->addToForm($linkBackManage);
		
		return $objForm->show();
	}

	private function getFormAction()
	{
		//Get the action to determine if its add or edit
		$action = $this->getParam("action", "addEvent");
		if ($action == "editEvent") 
		{
			//Get the event id and pass to uri
			$id = $this->getParam("id");
			$formAction = $this->uri(array("action" => "updateEvent", "id"=>$id), "wtm" );
		} 
		else
		{
			$formAction = $this->uri(array("action" => "addNewEvent"), "wtm");
		}
		return $formAction;
	}	
	
	public function show()
	{
		return $this->buildForm();
	}
}
?>

