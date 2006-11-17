<?php
//template that shows details or no details , determined by id no
  $this->objstudcard = & $this->getObject('searchstudcard','marketingrecruitmentforum');
  $results  = $this->objstudcard->searchID($idsearch);
  echo $results;
?>
