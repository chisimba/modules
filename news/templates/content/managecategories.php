<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = 'Manage Categories';
echo $header->show();

if (count($categories) == 0) {
    echo '<div class="noRecordsMessage">'.'TEXT: No Categories found'.'</div>';
} else {
    $table = $this->newObject('htmltable', 'htmlelements');
    $table->startHeaderRow();
        $table->addHeaderCell('Category', '50%');
        $table->addHeaderCell('Number of Stories');
        $table->addHeaderCell('Options');
    $table->endHeaderRow();
    foreach ($categories as $category)
    {
        $table->startRow();
            $table->addCell($category['categoryname']);
            $table->addCell('##');
            $table->addCell('##');
        $table->endRow();
    }
    
    echo $table->show();
}

$form = new form ('addcategory', $this->uri(array('action'=>'addcategory')));
$category = new textinput ('category');
$button = new button ('submitform', 'Add Category');
$button->setToSubmit();
$form->addToForm($category->show().' '.$button->show());
echo $form->show();

$homeLink = new link ($this->uri(NULL));
$homeLink->link = 'Return to News Home';
echo $homeLink->show();
?>