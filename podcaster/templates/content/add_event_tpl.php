<?php

if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// Load classes
$this->loadClass("form", "htmlelements");
$this->loadClass("textinput", "htmlelements");
$this->loadClass("button", "htmlelements");
$this->loadClass("htmltable", 'htmlelements');

//Add page heading
$objHeading = &$this->getObject('htmlheading', 'htmlelements');
$objHeading->type = 1;
$objHeading->str = $objLanguage->languageText("mod_podcaster_addevent", 'podcaster', "Add event");

echo $objHeading->show();

$form = new form("add", $this->uri(array(
                    'module' => 'podcaster',
                    'action' => 'addeventconfirm'
                )));

$objTable = new htmltable();
$objTable->width = '30';
$objTable->attributes = " align='center' border='0'";
$objTable->cellspacing = '12';
$row = array(
    "<b>" . $objLanguage->languageText("word_name") . ":</b>"
);
$objTable->addRow($row, NULL);
$row = array(
    $objUser->fullName()
);
$objTable->addRow($row, NULL);
//Add event lable and text input box
$event = new textinput("event", "");
$event->size = 60;
$form->addRule('event', $objLanguage->languageText("mod_podcaster_typeeventname", 'podcaster', "You need to type in the event name"), 'required');
$row = array(
    "<b>" . $label = $objLanguage->languageText("mod_podcaster_eventname", 'podcaster', 'Event name') . ":</b>"
);
$objTable->addRow($row, NULL);
$row = array(
    $event->show()
);
$objTable->addRow($row, NULL);
//Save button
$button = new button("submit", $objLanguage->languageText("word_save")); //word_save
$button->setToSubmit();
// Show the cancel link
$buttonCancel = new button("submit", $objLanguage->languageText("word_cancel"));
$objCancel = &$this->getObject("link", "htmlelements");
$objCancel->link($this->uri(array(
            'module' => 'podcaster',
            'action' => 'configure_events'
        )));
$objCancel->link = $buttonCancel->show();
$linkCancel = $objCancel->show();
$row = array(
    $button->show() . ' / ' . $linkCancel
);
$objTable->addRow($row, NULL);
$form->addToForm($objTable->show());
echo $form->show();
?>