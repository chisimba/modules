<?php

/* ----------- klorclient search  class extends object ------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
*
*@author Jameel adam
* @version $Id: coursesearch_class_inc.php,v 1.1 
* @copyright 2006, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @package klorclient
* 
* @var object $objUser String to hold instance of the user object
* @var object $objLog String to hold instance of the log object
* @var object $objLanguage String to hold the instance of the language object
* @var string $width String to hold the width of the fieldset
* @var string $align String to hold the alignment of items within the fieldset
*/

class coursesearch extends object
{	
	var $objLanguage;
	var $objLog;
	var $width;
	var $align;

	/**
	* initialization function
	*/
	
	function init()
	{		
		$this->width="80%";
		$this->align="left";		
		$this->objLanguage = &$this->getObject('language', 'language');		
		$this->objLog=$this->newObject('logactivity', 'logger');
		$this->objLog->log();
	}
		
	/**
	* SET METHODS
	*/
	function setWidth($width)
	{
		$this->width=$width;
	}

	function setAlign($align)
	{
		$this->align=$align;
	}
	
	/**
	* GET METHODS
	*/
	function getWidth()
	{
		return $this->width;
	}

	function getAlign()
	{
		return $this->align;
	}

	/**
	* function to create the search fieldset
	*/

	function buildSearch()
	{
		//load required form elements
		$this->loadClass('form','htmlelements');
		$this->loadClass('dropdown','htmlelements');
		$this->loadClass('radio','htmlelements');
		$this->loadclass('textinput', 'htmlelements');
		$this->loadclass('button', 'htmlelements');
		$this->loadclass('link', 'htmlelements');
		//whenever a search is executed, get the last searched query
		$searchField=$this->getParam("searchField", NULL);
		
		//search form
		$searchform = new form ('coursesearch', 'index.php');
		$searchform->method= 'GET';
		//center items in the form
		$searchform->addToForm('<div align="'.$this->align.'">'); 
		
		//hidden fields
		$hiddentextinput = new textinput('module', 'klorclient');
		$hiddentextinput->fldType = 'hidden';
		$searchform->addToForm($hiddentextinput->show());
        
        $hiddentextinput = new textinput('action', 'listcourses');
        $hiddentextinput->fldType = 'hidden';
        $searchform->addToForm($hiddentextinput->show());
        
		//add search text boxes
		$textinput = new textinput ('searchLetter');
		$textinput->size = '40';
		$searchField?$textinput->value=$searchField:'';
		$searchform->addToForm($textinput->show().'<br />');
		//add text to fieldset that reads: Search by
		$searchform->addToForm('<strong>'.$this->objLanguage->languageText('mod_klorclient_searchBy'). ': '.'</strong>&nbsp;'.'<br>');
		//add radio options: firstName (selected), surname, and folderNo
		$radiotype = new radio ('searchHow');
		$radiotype->addOption('Coursename', $this->objLanguage->languageText('mod_klorserver_courseName'));
		$radiotype->addOption('code', $this->objLanguage->languageText('mod_klorclient_code'));		
		$radiotype->addOption('codeNo', $this->objLanguage->languageText('mod_klorclient_codeNumber'));
		$radiotype->setSelected('Coursename');
		$searchform->addToForm($radiotype->show().'<br />');
		//add text to fieldset that reads: Gender
		$searchform->addToForm('<strong>'.$this->objLanguage->languageText('mod_klorclient_field'). ': '.'</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		//add radio buttons: gender both (selected), male, female
		$radiotype = new radio ('searchfield');
		$radiotype->addOption('both', $this->objLanguage->languageText('mod_klorclient_fieldBoth'));
		$radiotype->addOption('local', $this->objLanguage->languageText('mod_klorclient_local'));
		$radiotype->addOption('remote', $this->objLanguage->languageText('mod_klorclient_remote'));
		$radiotype->setSelected('both');
		$searchform->addToForm($radiotype->show() . '&nbsp;');		
        
        //add the button
		$submitbutton = new button ('search', $this->objLanguage->languageText('mod_klorclient_button_search'));
		$submitbutton->setToSubmit();
		$searchform->addToForm($submitbutton->show());
		
		//close the div tag
		$searchform->addToForm('</div>');
		//create the search field set
		$searchFieldset =& $this->getObject('fieldset', 'htmlelements');
		$searchFieldset->setLegend($this->objLanguage->languageText('mod_klorclient_searchforcoursename'));
		$searchFieldset->width=$this->width;
		
		$searchFieldset->addContent($searchform->show());
		return $searchFieldset->show();
	}
	
	/* 
	* function to display the search patients field set
	*/
	function show()
	{
		//first build the search
		return $this->buildSearch();
	}
}
?>
