<?php
/**
*
* WTM edit building class
*
* This file provides an edit class for the WTM module's building database.
* It creates the editing form for which new buildings can be enter or existing
* buildings modified. Its purpose is to allow administrators to edit/add
* building to the database.
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

class editbuilding extends object 
{
	public $objLanguage;
 
	public $objDBBuildings;
 
	public function init()
	{
		//Instantiate the language object
		$this->objLanguage = $this->getObject('language','language');
		//Load the DB object
		$this->objDBBuildings = $this->getObject('dbwtm_buildings','wtm');  
	}
	
	
	private function loadElements()
	{
		//Load the form class
		$this->loadClass('form','htmlelements');
		//Load the textinput class
		$this->loadClass('textinput','htmlelements');
		//Load the label class
		$this->loadClass('label','htmlelements');
		//Load the button object
		$this->loadClass('button','htmlelements');
		//Load the link object
		$this->loadClass('link','htmlelements');
	}
	
	private function buildForm()
	{
		$this->loadElements();
		//Create the form
		$objForm = new form('buildings', $this->getFormAction());
		$id = $this->getParam('id');
		//If id is not empty, get the building details
		if (!empty($id))
		{
			//Fetch the data
			$buildingData = $this->objDBBuildings->listSingle($id);
			$building = $buildingData[0]["building"];
			$longcoordinate = $buildingData[0]["longcoordinate"];
			$latcoordinate = $buildingData[0]["latcoordinate"];
			$xexpand = $buildingData[0]["xexpand"];
			$yexpand = $buildingData[0]["yexpand"];
		}
		else
		{
			$building = "";
			$longcoordinate = "";
			$latcoordinate = "";
			$xexpand = "";
			$yexpand = "";
		}

        //...........BUILDING TEXT INPUT.......................
        //Create a new textinput for the name of the building
        $objBuilding = new textinput('building', $building);
        //Create a new label for the text labels
        $buildingLabel = new label ($this->objLanguage->languagetext("mod_wtm_building","wtm"),"building");
        $objForm->addToForm($buildingLabel->show() , "<br />");
        $objForm->addToForm($objBuilding->show() . "<br />");
		
		//...........LONGCOORDINATE TEXT INPUT.......................
        //Create a new textinput for coordinate1
        $objLongCoordinate = new textinput('longcoordinate', $longcoordinate);
        //Create a new label for the text labels
        $longCoordinateLabel = new label ($this->objLanguage->languagetext("mod_wtm_longcoordinate","wtm"),"longcoordinate");
        $objForm->addToForm($longCoordinateLabel->show() , "<br />");
        $objForm->addToForm($objLongCoordinate->show() . "<br />");
		//...........LATCOORDINATE TEXT INPUT.......................
        //Create a new textinput for coordinate1
        $objLatCoordinate = new textinput('latcoordinate', $latcoordinate);
        //Create a new label for the text labels
        $latCoordinateLabel = new label ($this->objLanguage->languagetext("mod_wtm_latcoordinate","wtm"),"latcoordinate");
        $objForm->addToForm($latCoordinateLabel->show() , "<br />");
        $objForm->addToForm($objLatCoordinate->show() . "<br />");
		//...........XEXPAND TEXT INPUT.......................
        //Create a new textinput for coordinate2
        $objXexpand = new textinput('xexpand', $xexpand);
        //Create a new label for the text labels
        $xexpandLabel = new label ($this->objLanguage->languagetext("mod_wtm_xexpand","wtm"),"xexpand");
        $objForm->addToForm($xexpandLabel->show() , "<br />");
        $objForm->addToForm($objXexpand->show() . "<br />");
		//...........YEXPAND TEXT INPUT.......................
        //Create a new textinput for coordinate3
        $objYexpand = new textinput('yexpand', $yexpand);
        //Create a new label for the text labels
        $yexpandLabel = new label ($this->objLanguage->languagetext("mod_wtm_yexpand","wtm"),"yexpand");
        $objForm->addToForm($yexpandLabel->show() , "<br />");
        $objForm->addToForm($objYexpand->show() . "<br />");

		
        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objSubmitButton = new button('save');
        // Set the button type to submit
        $objSubmitButton->setToSubmit();
        // Use the language object to label button
		// with the word save
        $objSubmitButton->setValue(' '.$this->objLanguage->languageText("mod_wtm_savebuilding", "wtm").' ');
        $objForm->addToForm($objSubmitButton->show());
		
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
        
		
		$objForm->addToForm($linkBackManage);
		
		
      	return $objForm->show();	
	}

	private function getFormAction()
	{
		//Get the action to determine if its add or edit
		$action = $this->getParam("action", "addBuilding");
		if ($action == "editBuilding") 
		{
			//Get the building id and pass to uri
			$id = $this->getParam("id");
			$formAction = $this->uri(array("action" => "updateBuilding", "id"=>$id), "wtm" );
		} 
		else
		{
			$formAction = $this->uri(array("action" => "addNewBuilding"), "wtm");
		}
		return $formAction;
	}	
	
	public function show()
	{
		return $this->buildForm();
	}
}
?>

