<?
 		$this->loadClass('radio', 'htmlelements');
		$this->loadClass('dropdown', 'htmlelements');
		$this->loadClass('checkbox', 'htmlelements');
		$this->loadClass('button', 'htmlelements');
		$this->loadClass('tabbedbox', 'htmlelements');
		$this->loadClass('textinput','htmlelements');
		$this->loadClass('textarea','htmlelements');
		$this->loadClass('calendar','htmlelements');
		$this->loadClass('layer','htmlelements');
		$this->loadClass('windowpop','htmlelements');
		$this->loadClass('form','htmlelements');
		$this->loadClass('multitabbedbox','htmlelements');
		$this->loadClass('mouseoverpopup','htmlelements');
		$this->loadclass('csslayout','htmlelements');


		$cssLayout = new csslayouut();


		//Button-----And----- Popup
		$objElement = new button('mybutton');
		$objElement->setValue('Normal Button');
		$objElement->setOnClick('alert(\'Iggy Dumbass!\')');
		$button=$objElement->show();
		echo $button;
		//----------

//Example:

 	$switchmenu = $this->newObject('switchmenu', 'htmlelements');
 	$switchmenu->addBlock('People From Days Gone By', 'Block Text 1 <br /> Block Text 1 <br /> Block Text 1');
 	$switchmenu->addBlock('Most Recently Spotted', 'Block Text 2 <br /> Block Text 2 <br /> Block Text 2', 'confirm'); // Adds a CSS Style
 	$switchmenu->addBlock('Why i Hate them', 'Block Text 3 <br /> Block Text 3 <br /> Block Text 3');

echo $switchmenu->show();





?>
