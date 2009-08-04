<?php
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
    $mainjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/review.js').'" type="text/javascript"></script>';
    $styleSheet="
    <style type=\"text/css\">
        .x-check-group-alt {
            background: #D1DDEF;
            border-top:1px dotted #B5B8C8;
            border-bottom:1px dotted #B5B8C8;
        }
        div#myForm {
            padding: 0 3em;
        }
    </style>
    ";

    //append to the top of the page
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extall);
    $this->appendArrayVar('headerParams', $mainjs);
    $this->appendArrayVar('headerParams', $styleSheet);

    // display the extj radio form
    echo '<div id="myForm">';
    echo '<div id="form-ct"></div></div>';
    echo '<input type="hidden" name="id" id="id" value="'.$this->getParam("id").'">';
/*
 *
 */
/*
$this->loadClass('htmltable','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('textarea','htmlelements');



//constructs the table
$objTable = new htmltable();

$objTable->startRow();

$objTable->addCell($objLanguage->languageText('mod_ads_review','ads'), 150, NULL, 'left');
$objTable->endRow();
$objTable->startRow();
$coursenamefield =  new textarea('title', '');
$objTable->addCell($coursenamefield->show(),400,NULL,'left');

$objTable->endRow();



$objTable->row_attributes=' height="10"';
$buttons='';
/************** Build form **********************/
/*
$saveButton = new button('save',$objLanguage->languageText('mod_ads_savereview','ads'));
$saveButton->setToSubmit();


$buttons.=$saveButton->show();
$cancelButton = new button('cancel','Cancel');
$actionUrl = $this->uri(array('action' => NULL));
$cancelButton->setOnClick("window.location='$actionUrl'");
$buttons.='&nbsp'.$cancelButton->show();
//$objForm = new form('FormName',$this->uri(array('action'=>'savestudent')));
$objForm = new form('FormName',$this->uri(array('action'=>'savecoursereview')));
$objForm->addToForm($objTable->show());
$objForm->addToForm($buttons);


// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
$rightSideColumn='<h1>'. $objLanguage->languageText('mod_ads_reviewcourseproposal','ads').'</h1>';
//Add the table to the centered layer
$rightSideColumn .= $objForm->show();
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
 * 
 */
?>