<?php
//template that shows details or no details , determined by id no

  $this->objschool  = & $this->getObject('searchschools','marketingrecruitmentforum');
  $result = $this->objschool->schoolexisting($schoolbyname);
  
  echo $result;
?>
