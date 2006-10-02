<?
$this->objHelp=& $this->getObject('help','help');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('button','htmlelements');
$objHeading = &$this->getObject('htmlheading','htmlelements');
	
$table = & $this->newObject('htmltable','htmlelements');
$table->width = '75%';


 $mode = $this->getParam('mode');
 
 
 
//echo $this->objHelp->show('remote','klorclient');

$form = new form('remote',$this->uri(array('action'=>'remoteconfirm')));

		$textarea = & $this->newObject('textinput','htmlelements');
        $textarea->size = '40';
        $textarea->value = '';
        $textarea->setId(null);
      
        $objButton=new button('save');
        $objButton->setToSubmit();
        $objButton->setValue($this->objLanguage->languageText("mod_contextadmin_save"));



		//back link
		$back_icon;
		$back_icon = $this->getObject('geticon','htmlelements');
		$back_icon->setIcon('');
		$lblView = "Back";	
		$back_icon->alt = $lblView;
		$back_icon->align=false;
		// Delete an entry in the table.
		$uriBack = $this->uri(
			array(
				'action' => ' ', 
				'id' =>$id 
			)
		);
		$BackLink   = "<a href=\"{$uriBack}\">".$back_icon->show()."</a>";



		//
		//'http://'.$_SERVER['HTTP_HOST'].'/nextgen/modules/klorserver/server.php?wsdl'
		//

        //remote address wsdl?
        $address = $textarea;
        $address->name = 'remoteaddress';
        $address->setValue('http://'.'freecourseware.uwc.ac.za'.'/modules/klorserver/server.php?wsdl');
        $address->label = 'remote address';

        //remote address
        $httpaddress = $textarea;
        $httpaddress->name = 'httpaddress';
        $httpaddress->setValue('http://'.'freecourseware.uwc.ac.za');
        $httpaddress->label = 'http address';

        //remote wsdl method
        $method = $textarea;
        $method->name = 'remotemethod';
        $method->setValue('fileList');
        $method->label = 'remote wsdl method';

	$table->addRow(array($address->label,$address->show()),'odd');
	$table->addRow(array($httpaddress->label,$httpaddress->show()),'odd');
	$table->addRow(array($method->label,$method->show()),'odd');

	$button = new button("submit",
	$objLanguage->code2Txt("word_save"));    //word_save
	$button->setToSubmit();
	$row = array($button->show().'&nbsp;'.$BackLink);
	$table->addRow($row);
	$form->addToForm($table->show());

	
	//heading
	$nbsp = '&nbsp;&nbsp;&nbsp;';
	$pgTitle = $objHeading;
	$pgTitle->type = 1;
	$pgTitle->str = 'Connect to Remote Klor Host'.$nbsp.$this->objHelp->show('remote','klorclient');
	
	//echo $this->objHelp->show('add','calendar'); 
	echo $pgTitle->show().''.$form->show();
	
	
	
	
?>
