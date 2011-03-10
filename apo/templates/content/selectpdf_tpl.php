<?
    $this->loadClass("checkbox", "htmlelements");

    $displayMessage = $this->objLanguage->languageText("mod_apo_sectionsMessage", "apo");

    $objIcon = $this->newObject("geticon", "htmlelements");
    $objIcon->setIcon('edit');
    
    echo "<h2>".$document['docname']."&nbsp;&nbsp;".$objIcon->show()."</h2>";
    echo $displayMessage;


    // select which sections the user would like to print in the document.
    print_r($document);


    $table = $this->newObject('htmltable', 'htmlelements');
    $table->border = 0;
    $table->cellspacing = '3';
    //$table->width = "30%";
    $checkbox = new checkbox("overview");

    //Overview
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("overview");
    $table->endRow();

    echo $table->show();
?>