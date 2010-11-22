<?php

//load class
$this->loadclass('link', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');

$this->setVarByRef("selected", $departmentname);
$addGift = $this->uri(array('action' => 'submitadd'));
$userExists = $this->uri(array('action' => 'userexists'));
$saveUserUrl = $this->uri(array('action' => 'saveuser'));

$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$divisionLabel = $objSysConfig->getValue('DIVISION_LABEL', 'gift');


$objIcon->setIcon('edit');

$homeWelcome = $this->objHome->homePage();

// get the links on the left
$form = $this->objHome->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('archived'));


$heading = new htmlheading($this->objLanguage->languageText('mod_homeWelcome_heading', 'gift'), 1);
$body = $this->objLanguage->languageText('mod_homeWelcome_body', 'gift');
$notice = $this->objLanguage->languageText('mod_homeWelcome_warning', 'gift');
$policy = $this->objLanguage->languageText('mod_home_policylink', 'gift');

$objpolicyLink = new link($this->uri(array('action' => 'viewpolicy')));
$objpolicyLink->link = 'Click here';
//$objLink->extra = 'onClick="showGiftPolicy()"';
$top = "";
$top.=$heading->show() . $objpolicyLink->show() . $policy . '<br/>';

if ($this->objUser->isAdmin()) {
    $top.='<br/>' . $this->objGift->showCreateDepartmentForm();
}

$top.='<h2 class="departmenthome">' . $departmentname . '</h2>';
$button = new button('approve', "Add gift");
$uri = $this->uri(array('action' => 'add', 'departmentname' => $departmentname));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$top.=$button->show();

if ($this->objUser->isAdmin()) {
    $button = new button('audittrail', "Audit Trail");
    $uri = $this->uri(array('action' => 'showuseractivity'));
    $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
    $top.='&nbsp;&nbsp;' . $button->show();
}

echo $top;

$table = $this->getObject('htmltable', 'htmlelements');
$table->startHeaderRow();
$table->addHeaderCell("Name");
$table->addHeaderCell("Recipient");
$table->addHeaderCell("Donor");
$table->addHeaderCell("Value");
$table->addHeaderCell("Date");
$table->endHeaderRow();
if (count($gifts) > 0) {
    foreach ($gifts as $gift) {

        $editGift = new link($this->uri(array('action' => 'edit', 'id' => $gift['id'])));
        $editGift->link = $objIcon->show();

        $viewDetailsLink = new link($this->uri(array('action' => 'view', 'id' => $gift['id'])));
        $viewDetailsLink->link = $gift['giftname'];
        $table->startRow();
        $table->addCell($viewDetailsLink->show() . $editGift->show());
        $table->addCell($this->objUser->fullname($gift["recipient"]));
        $table->addCell($gift["donor"]);
        $table->addCell($gift["value"]);
        $table->addCell($gift["tran_date"]);
        $table->endRow();
    }
}
echo '<br/>';
echo $table->show();
?>