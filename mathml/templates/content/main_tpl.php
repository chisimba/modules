<?php
	$this->loadClass('windowpop','htmlelements');
  $this->objPop=&new windowpop;
  $this->objPop->set('location','http://www.mozilla.org/projects/mathml/fonts');
  $this->objPop->set('linktext','Click to download fonts');
  $this->objPop->set('width','800');
  $this->objPop->set('height','600');
  $this->objPop->set('left','300');
  $this->objPop->set('top','400');
  $this->objPop->putJs(); 
  print $ar."<br />".$this->objPop->show();
?>