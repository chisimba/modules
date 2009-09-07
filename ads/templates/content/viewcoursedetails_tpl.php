<?php
    $myData = $this->objDocumentStore->getViewData($date, $courseid, $version);
    $this->objForms = $this->getObject("viewforms");
    $this->objForms->setData($myData);

    $allForms = "";
    for($i=0;$i<8;$i++) {
        switch($i) {
            case 0: $allForms .= $this->objForms->getForm("A", 5);
                    break;
            case 1: $allForms .= $this->objForms->getForm("B", 11);
                    break;
            case 2: $allForms .= $this->objForms->getForm("C", 6);
                    break;
            case 3: $allForms .= $this->objForms->getForm("D", 24);
                    break;
            case 4: $allForms .= $this->objForms->getForm("E", 11);
                    break;
            case 5: $allForms .= $this->objForms->getForm("F", 7);
                    break;
            case 6: $allForms .= $this->objForms->getForm("G", 8);
                    break;
            case 7: $allForms .= $this->objForms->getForm("H", 5);
                    break;
        }
    }

    echo "<div id='content'>".$allForms."</div>";
?>
