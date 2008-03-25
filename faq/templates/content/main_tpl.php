<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

// Show the heading
$objHeading = new htmlheading();
$objHeading->type=1;

// Show the add link
$objLink =& $this->getObject('link','htmlelements');
$objLink->link($this->uri(array('module'=>'faq', 'action'=>'add')));
$iconAdd = $this->getObject('geticon','htmlelements');
$iconAdd->setIcon('add');
$iconAdd->alt = $objLanguage->languageText("faq_addnewentry", "faq");
$iconAdd->title = $objLanguage->languageText("faq_addnewentry", "faq");
$iconAdd->align=false;
$objLink->link = $iconAdd->show();
        
// Add the Icon to the heading
$objHeading->str = $contextTitle.': '.$objLanguage->languageText("phrase_faq","system", 'Frequently Asked Questions');

// Show Add Item link
if (count($categories) > 0) {
    $objHeading->str .= ' '.$objLink->show();
}

echo $objHeading->show();

if (count($categories) == 0) {
    echo '<div class="noRecordsMessage">No FAQ Categories available</div>';
} else {
    echo '<ol>';
    
    $objIcon = $this->newObject('geticon','htmlelements');
    
    $objIcon->setIcon('edit');
    $objIcon->alt=$objLanguage->languageText("word_edit");
    $objIcon->title=$objLanguage->languageText("word_edit");
    
    $editIcon = $objIcon->show();
    
    $objIcon->setIcon('delete');
    $objIcon->alt=$objLanguage->languageText("word_delete");
    $objIcon->title=$objLanguage->languageText("word_delete");
    
    $deleteIcon = $objIcon->show();
    
    foreach ($categories as $item)
    {
        
        $numItems = $this->objFaqEntries->getNumCategoryItems($item['id']);
        
        // Create link to category
        $categoryLink = new link($this->uri(array('action'=>'view','category'=>$item['id'])));
        $categoryLink->link = $item['categoryname'];
        $categoryLink->title = $this->objLanguage->languageText('mod_faq_viewcategory', 'faq');
        
        echo '<li>'.$categoryLink->show().' ('.$numItems.')';
        
        $permissions = TRUE;
        
        if ($permissions) {
            // Create the edit link.
            $editLink = new link($this->uri(array('action'=>'editcategory', 'id'=>$item['id'])));
            $editLink->link = $editIcon;
            
            // Create the delete link.
            $objConfirm = $this->newObject('confirm','utilities');
            
            $objConfirm->setConfirm(
                $deleteIcon,
                $this->uri(array(
                    'action'=>'deletecategoryconfirm',
                    'id'=>$item["id"]
                )),
                $objLanguage->languageText('phrase_suredelete')
            );
            
            echo ' &nbsp; '.$editLink->show().' '.$objConfirm->show();
        }
        
        echo '</li>';
    }
    echo '</ol>';
}



$addLink = new link("javascript:showHideAddCategory();");
$addLink->link = $objLanguage->languageText('mod_faq_addcategory','faq');

echo '
<script type="text/javascript">
// <![CDATA[
function showHideAddCategory()
{
    jQuery("#addfaqcategory").toggle();
    adjustLayout();
}
// ]]>
</script>
';

echo '<p>'.$addLink->show()./*' / '.$returnToFaqLink->show().*/'</p>';

echo '<div id="addfaqcategory" style="display:none;">';

$objHeading = new htmlheading();
$objHeading->type=3;
$objHeading->str =$objLanguage->languageText("mod_faq_addcategory","faq");
echo $objHeading->show();

// Load the classes.
$this->loadClass("form","htmlelements");
$this->loadClass("textinput","htmlelements");
$this->loadClass("button","htmlelements");

// Create the form.
$form = new form("createcategory", $this->uri(array('action'=>'addcategoryconfirm')));

$textInput = new textinput("category", NULL);
$textInput->size = 40;

$form->setDisplayType(1);
$form->addToForm($textInput->show());
$form->addToForm("&nbsp;");
$button = new button("submit", $objLanguage->languageText("word_save"));
$button->setToSubmit();

$cancelButton = new button("submit", $objLanguage->languageText('word_cancel'));
$cancelButton->setOnClick("showHideAddCategory();");

$form->addToForm($button->show().' / '.$cancelButton->show());
$form->addRule('category', 'Please enter the name of the category', 'required');
// Show the form.
echo $form->show();

echo '</div>';

?>
