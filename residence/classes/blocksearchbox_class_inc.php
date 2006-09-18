<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table  
*/
class blocksearchbox extends object
{

	function show(){
		$objForm = new form('testform');
		$objForm->setAction($this->uri(array('action'=>'search'),$this->getParam('module')));
		$objForm->setDisplayType(2);

		$search= new textinput('search',null,null,8);
		$search->label='Search';		

		$stdnum= new textinput('studentNumber',null,null,8);
		$stdnum->label='Student Number';
		
		$applicationum= new textinput('applicationNumber',null,null,8);
		$applicationum->label='App Num';

		$idnum= new textinput('idNumber',null,null,8);
		$idnum->label='ID Num';
		
		$save= new button('save');
		$save->setToSubmit();
		$save->setValue('Search');
		
		//Radio Buttons------
		 $this->loadClass('radio', 'htmlelements');
		
		$objElement = new radio('sex_radio');
		
		$objElement->addOption('surname','<p>'.'Surname'.'</p>');
		$objElement->addOption('studentid','<p>'.'Student Id'.'</p>');
		$objElement->addOption('idNumber','<p>'.'Id Number'.'</p>');
		$objElement->setSelected('surname');
		$radio= $objElement->show();
		//Radion button Group



		$objForm->addToForm($search);
		
		$objForm->addToForm($radio);
		//$objForm->addToForm($stdnum);
		//$objForm->addToForm($applicationum);
		//$objForm->addToForm($idnum);
		$objForm->addToForm($save);
		
		$objElement = new tabbedbox();
		$objElement->addTabLabel('Search');	
		
		$objElement->addBoxContent($objForm->show());	
		return $objElement->show();
	}

}
