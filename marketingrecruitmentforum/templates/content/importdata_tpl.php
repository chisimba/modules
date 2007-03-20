<?php
//template to import data into excel sheet
      /**
        *place in a spreadsheet
        */
        $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
        $valsex = $this->objstudcard->getstudqualify();
        $this->objxlswriter = & $this->getObject('xlswriter','marketingrecruitmentforum');
        $this->objxlswriter->setup(false,"Marketing Reports","Qualify Students","null");
        $this->objxlswriter->rows2sheet($valsex,1,1);
        $this->objxlswriter->closesheet();
        
?>
