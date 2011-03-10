<?
    $this->loadClass("checkbox", "htmlelements");
    $this->loadClass('fieldset', 'htmlelements');
    //$this->loadClass('form', 'htmlelements');
    $this->loadClass('button', 'htmlelements');

    $displayMessage = $this->objLanguage->languageText("mod_apo_sectionsMessage", "apo");

    $edit = new link($this->uri(array("action"=>"showeditdocument", "id"=>$id)));
    $objIcon = $this->newObject("geticon", "htmlelements");
    $objIcon->setIcon('edit');
    $edit->link = $objIcon->show();
    
    echo "<h2>".$document['docname']."&nbsp;&nbsp;".$edit->show()."</h2>";
    
    // select which sections the user would like to print in the document.
    //print_r($document);

    
    $table = $this->newObject('htmltable', 'htmlelements');
    $table->border = 0;
    $table->cellspacing = '3';
    //$table->width = "30%";

    //Overview
    $checkbox = new checkbox("all");
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("All Sections");
    $table->endRow();

    //Overview
    $checkbox = new checkbox("overview");
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Overview");
    $table->endRow();


    //Rules and Syllabus - Page One
    $checkbox->name = "rulesandsyllabusone";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Rules And Syllabus - Page One");
    $table->endRow();

    //Rules and Syllabus - Page Two
    $checkbox->name = "rulesandsyllabustwo";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Rules And Syllabus - Page Two");
    $table->endRow();

    // Subsidy Requirements
    $checkbox->name = "subsidy";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Subsidy Requirements");
    $table->endRow();

    // Outcomes and Assessments - Page One
    $checkbox->name = "outcomesandassessmentone";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Outcomes and Assessments - Page One");
    $table->endRow();

    // Outcomes and Assessments - Page Two
    $checkbox->name = "outcomesandassessmenttwo";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Outcomes and Assessments - Page Two");
    $table->endRow();

    // Outcomes and Assessments - Page Three
    $checkbox->name = "outcomesandassessmentthree";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Outcomes and Assessments - Page Three");
    $table->endRow();

    // Resources
    $checkbox->name = "resources";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Resources");
    $table->endRow();

    // Collaborations and Contracts
    $checkbox->name = "collaborations";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Collaborations and Contracts");
    $table->endRow();

    // Review
    $checkbox->name = "review";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Review");
    $table->endRow();

    // Comments
    $checkbox->name = "comments";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Comments");
    $table->endRow();

    // Feedback
    $checkbox->name = "feedback";
    $table->startRow();
    $table->addCell($checkbox->show(), "20");
    $table->addCell("Feedback");
    $table->endRow();

    $button = new button();

    $button = new button('Print Document', $this->objLanguage->languageText('mod_apo_printdf', 'apo', 'Print Document'));
    $button->setToSubmit();

    $table->startRow();
    $table->addCell($button->show());
    $table->endRow();

    $myFieldset = new fieldset();
    $myFieldset->width = "50%";
    $myFieldset->setLegend($displayMessage);
    $myFieldset->addContent($table->show());

    $action = "makepdf";
    $form = new form('makepdf', $this->uri(array('action' => $action, 'id' => $document['id'])));
    $form->addToForm($myFieldset->show());
    echo $form->show();
?>