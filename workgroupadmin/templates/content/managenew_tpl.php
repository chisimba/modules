<?php
// Display heading.

    $pageTitle = $this->newObject('htmlheading','htmlelements');
    $pageTitle->type=1;
    $pageTitle->align='left';
    $pageTitle->str=ucwords($objLanguage->code2Txt("mod_workgroupadmin_workgroup",'workgroupadmin'))." : ".$workgroup['description'];
	echo $pageTitle->show();

$objForm = $this->newObject('form','htmlelements');
$objForm->name = "form1";
$objForm->action = $this->uri ( 
    array( 
        'action' => 'processform',
        'workgroupId'=>$workgroup['id']
    ) 
);

// Create the selectbox object
$objSelectBox = $this->newObject('selectbox','htmlelements');
// Initialise the selectbox.
$objSelectBox->create( 
    $objForm, 
    'leftList[]', 
    ucfirst($objLanguage->code2Txt('mod_workgroupadmin_users_in_course','workgroupadmin')), 
    'rightList[]', 
    ucfirst($objLanguage->code2Txt('mod_workgroupadmin_members','workgroupadmin'))
);

// Populate the selectboxes
$objSelectBox->insertLeftOptions( $users, 'userid', 'fullname' );
$objSelectBox->insertRightOptions( $members, 'userid', 'fullname' );

// Insert the selectbox into the form object.
$objForm->addToForm( $objSelectBox->show() );

// Get and insert the save and cancel form buttons
$arrFormButtons = $objSelectBox->getFormButtons();
$objForm->addToForm( implode( ' / ', $arrFormButtons ) );

// Show the form
echo $objForm->show();
?>