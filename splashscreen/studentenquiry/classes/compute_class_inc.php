<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }



class compute extends object
{

	
    /**
    * Constructor method 
    */
    	function init() {
       	 	$this->objUser = & $this->getObject("user", "security");
		$this->remotedb =& $this->getObject('remotedb','remotedatasource');
       	}

	function display($filter = null){
		$objIcon =&  $this->getObject('geticon','htmlelements');
		$objRemote =& $this->remotedb->connectRemotely('tbl_student');
		$entries = $objRemote->getAll($filter);
		$objRemote = null;
		$addIcon = $objIcon->getAddIcon($this->uri(array('action'=>'add')));

		$objH =& $this->getObject('htmlheading','htmlelements');
		$objH->str = "Remotely connected to database  $addIcon";

		$table =& $this->getObject('sorttable','htmlelements');
		$table->width = '100%';
		$table->cellpadding = 5;
		$table->cellspacing = 2;
	
		$table->startHeaderRow();
		$table->addHeaderCell('name');		
		$table->addHeaderCell('surname');
		$table->addHeaderCell('Appl No');
		$table->addHeaderCell('Std No');
		$table->addHeaderCell('Status');
		$table->endHeaderRow();
		
		$oddEven = 'odd';
	
		foreach($entries as $data){
			$table->row_attributes = " class = \"$oddEven\"";
			$name = new href("index.php?module=residence&action=info&idnumber=".$data['idnumber'],$data['name']);
			$igama = $name->show();
			
			$link = new link();
			$link->href= "index.php?module=residence&action=info&id=".$data['idnumber'];
			$link->link = $data['name'];
			$table->startRow();
			$table->addCell($link->show());
			$table->addCell($data['surname']);
			$table->addCell($data['applicationNumber']);
			$table->addCell($data['studentNumber']);
		
			$icon = $objIcon->getEditIcon($this->uri(array('action'=>'info','id'=>$data['idnumber'])));
			$table->addCell($data['allocated']);
			$table->endRow();
			
			$oddEven = $oddEven == 'odd'?'even':'odd';
	 		
		}

		$this->setVar('left',$this->leftColumn());
		$this->setVar('right','');
		$this->setVar('content',$table->show());
		$this->setVar('bottom','');	
	}

}
