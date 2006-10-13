<?php

//class to contain all school names


class schoolnames{
  
  public function init()
  {
    //initialise all values
  }
  
  public function schoolnames() {
   
   
   $currencyvals  = 'schoolnames';           
   $this->objschoolnamesdropdown  = $this->newObject('dropdown','htmlelements');
   $this->objschoolnamesdropdown->dropdown($currencyvals);
   $this->objschoolnamesdropdown->addOption('AFRIKAANSE ONDERWYSINSTITUUT','AFRIKAANSE ONDERWYSINSTITUUT') ;
   $this->objschoolnamesdropdown->addOption('ALL SAINTS EDUCATIONAL CENTREL','ALL SAINTS EDUCATIONAL CENTRE') ;
   //$this->objschoolnamesdropdown->addOption('ALMEGA COLLEGE','ALMEGA COLLEGE') ;
   //$this->objschoolnamesdropdown->addOption('Andorran Franc . ADF','Andorran Franc . ADF') ;
   $this->objschoolnamesdropdown->size = 50;
  }
}
?>
