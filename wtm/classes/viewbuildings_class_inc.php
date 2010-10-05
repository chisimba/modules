<?php
/**
*
* WTM view buildings class
*
* This file provides a data viewing class for the WTM module's
* building database. Its purpose is allow administrators to view
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

class viewbuildings extends object 
{
	public $objLanguage;
 
	public $objDBBuildings;
 
	public function init()
	{
		 //Instantiate the language object
		 $this->objLanguage = $this->getObject('language','language');
		 //Instantiate the language object
		 $this->objDBBuildings = $this->getObject('dbwtm_buildings','wtm');
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
		$objForm = new form('buildings', $this->getFormAction());
		//Fetch the buildings from DB
		$allBuildings = $this->objDBBuildings->listAll();
		// Create a table object
		$buildingsTable = &$this->newObject("htmltable", "htmlelements");
		//Define the table border
		$buildingsTable->border = 0;
		//Set the table spacing
		$buildingsTable->cellspacing = '12';
		//Set the table width
		$buildingsTable->width = "60%";

		//Create the array for the table header
		$tableHeader = array();
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_building", 'wtm');
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_longcoordinate", 'wtm');
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_latcoordinate", 'wtm');
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_xexpand", 'wtm');
		$tableHeader[] = $this->objLanguage->languageText("mod_wtm_yexpand", 'wtm'); 

		//Create the table header for display
		$buildingsTable->addHeader($tableHeader, "heading");
		//Render each building in a table.
		foreach($allBuildings as $thisBuilding)
		{
			//Store the values of the array in variables
			$id = $thisBuilding["id"];
			$building = $thisBuilding["building"];
			$longcoordinate = $thisBuilding["longcoordinate"];
			$latcoordinate = $thisBuilding["latcoordinate"];  
			$xexpand = $thisBuilding["xexpand"];  
			$yexpand = $thisBuilding["yexpand"];
			$modified = $thisBuilding["modified"];
			
			//ViewIcon
			$iconViewEvent = $this->getObject('geticon','htmlelements');
			$iconViewEvent->setIcon('view');
			$iconViewEvent->alt = "View Events";
			$mngViewLink = new link($this->uri(array(
										'module'=>'wtm',
										'action'=>'viewEvents',
										'refID'=>$id,
										'refbuilding'=>$building
										)));
			$mngViewLink->link = $iconViewEvent->show();
			$linkViewManage = $mngViewLink->show();
			
			//EditIcon 
			$iconEdSelect = $this->getObject('geticon','htmlelements');
			$iconEdSelect->setIcon('edit');	
			$iconEdSelect->alt = "Edit building";
			$mngedlink = new link($this->uri(array(
										'module'=>'wtm',
										'action'=>'editBuilding', 
										'id' => $id
										)));
			$mngedlink->link = $iconEdSelect->show();
			$linkEdManage = $mngedlink->show(); 
			
			//Get the icon object
			$iconDelete = $this->getObject('geticon', 'htmlelements');
			//Set the icon name
			$iconDelete->setIcon('delete');
			//Set the alternative text of the icon
			$iconDelete->alt = $this->objLanguage->languageText("mod_wtm_deletebuilding", 'wtm');
			//Set align to default
			$iconDelete->align = false;
			//Create a new link Object
			$objConfirm = &$this->getObject("link", "htmlelements");
			//Create a new confirm object. 
			$objConfirm = &$this->newObject('confirm', 'utilities');
			//Set object to confirm and the path for the confirm implementation and confirm text
			$objConfirm->setConfirm($iconDelete->show() , $this->uri(array(
										'module' => 'wtm',
										'action' => 'deleteBuilding',
										'id' => $id
			)) , $this->objLanguage->languageText('mod_wtm_suredelete', 'wtm'));

			// Add the table rows.
			$buildingsTable->startRow();
			$buildingsTable->addCell($building);
			$buildingsTable->addCell($longcoordinate);
			$buildingsTable->addCell($latcoordinate);
			$buildingsTable->addCell($xexpand);
			$buildingsTable->addCell($yexpand);   
			$buildingsTable->addCell($linkViewManage);
			$buildingsTable->addCell($linkEdManage);
			$buildingsTable->addCell($objConfirm->show());
			$buildingsTable->endRow();
		}
	
		//Get the icon object
		$iconSelect = $this->getObject('geticon','htmlelements');
		//Set the name of the icon
		$iconSelect->setIcon('add');	
		//Set the alternative text of the icon
		$iconSelect->alt = "Add New Building";
		//Create a new link for the add link
		$mnglink = new link($this->uri(array(
									'module'=>'wtm',
									'action'=>'addBuilding'
									)));
		//Set the link text/image
		$mnglink->link = $iconSelect->show();
		//Build the link
		$linkManage = $mnglink->show();
		//Add the add button to the table
		// Add the table rows.
		$buildingsTable->startRow();
		//Note we are using column span. The other four parameters are set to default
		$buildingsTable->addCell($linkManage,'','','','','colspan="2"');
		$buildingsTable->endRow();
	  
		$objForm->addToForm($buildingsTable->show());
		return $objForm->show();
	}

	private function getFormAction()
	{
		$action = $this->getParam("action", "addBuilding");
		if ($action == "editBuilding") 
		{
			$formAction = $this->uri(array("action" => "updateBuilding"), "wtm" );
		}
		else
		{
			$formAction = $this->uri(array("action" => "addBuilding"), "wtm");
		}
		return $formAction;
		}
		
	public function show()
	{
		return $this->buildForm();
	}
}
?>
