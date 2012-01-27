<?php

/**
 * This class contains util methods for displaying adaptations
 *
 * @author pwando
 */
class viewadaptation extends object {

    function init() {
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass("link", "htmlelements");
        $this->loadClass("form", "htmlelements");
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getObject("dbproducts", "oer");
        $this->objDbProductComments = $this->getObject("dbproductcomments", "oer");
        $this->objDbInstitution = $this->getObject("dbinstitution", "oer");
        $this->objDbInstitutionType = $this->getObject("dbinstitutiontypes", "oer");
        $this->objAdaptationManager = $this->getObject("adaptationmanager", "oer");
    }

    function buildAdaptationView($productId) {
        $product = $this->objDbProducts->getProduct($productId);
        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;

        //Flag to check if user has perms to manage adaptations
        $hasPerms = $this->objAdaptationManager->userHasPermissions();

        $leftContent = "";
        
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $leftContent.='<div id="viewadaptation_coverpage">' . $thumbnail . '</div>';

        $newAdapt = "";
        if ($hasPerms) {
            //Link for - adapting product from existing adapatation
            $newAdaptLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $productId, 'mode="new"')));
            $newAdaptLink->link = $this->objLanguage->languageText('mod_oer_makenewfromadaptation', 'oer');
            $newAdapt = $newAdaptLink->show();
        }

        //Link for - See existing adaptations of this UNESCO Product
        $existingAdaptationsLink = new link($this->uri(array("action" => "viewadaptation", "id" => $productId)));
        $existingAdaptationsLink->link = $this->objLanguage->languageText('mod_oer_existingadaptations', 'oer');
        $existingAdaptations = $existingAdaptationsLink->show();

        //Link for - Full view of product
        $fullProdViewLink = new link($this->uri(array("action" => "fullviewadaptation", "id" => $productId)));
        $fullProdViewLink->link = $this->objLanguage->languageText('mod_oer_fullprodview', 'oer');
        $fullProdView = $fullProdViewLink->show();

        $sections = "";
        $sectionTitle = '<h3>' . $this->objLanguage->languageText('mod_oer_sections', 'oer') . '</h3>';
        if ($hasPerms) {
            $addSectionIcon = '<img src="skins/oer/images/add-node.png"/>';
            $addNodeLink = new link($this->uri(array("action" => "addsectionnode", "productid" => $productId)));
            $addNodeLink->link = $addSectionIcon . "&nbsp;&nbsp;" . $this->objLanguage->languageText('mod_oer_addnode', 'oer');
            $sections.=$addNodeLink->show();
        }

        //Get comments
        $prodcomments = "";
        $userComments = $this->objDbProductComments->getProductComments($productId);
        if(!empty($userComments)){
            $prodcomments .= '<div id="viewadaptation_keywords_label">' . $this->objLanguage->languageText('mod_oer_usercomments', 'oer') . ':</div>';
            foreach($userComments as $usercomment) {
                $prodcomments .= '<br /><div class="greyTextLink">'.$usercomment["comment"].'</div>';
            }
        }
        //Comment fetcher
        $commentfetcher = "";
        if ($hasPerms) {
            $fetcheritems = "";
            $textarea = new textarea('usercomment', '', 5, 5);
            $textarea->cssClass = 'commentTextBox';
            $fetcheritems.="<br />" . $textarea->show();
            $addSectionIcon = '<img src="skins/oer/images/button-search.png"/>';
            //$addNodeLink = new link('javascript:document.form_adaptationViewForm.submit();');
            $submitLink = new link('#');
            $submitLink->link = $this->objLanguage->languageText('word_submit', 'system') . "&nbsp;&nbsp;" . $addSectionIcon;
            $submitLink->extra = 'onclick="document.form_adaptationViewForm.submit();return false;"';
            $submitLink->class = "submitCommentImage";

            //$button = new button('save', $this->objLanguage->languageText('word_submit', 'system') . "&nbsp;&nbsp;" . $addSectionIcon);
            $button = new button('save', $this->objLanguage->languageText('word_submit', 'system'));
            $button->cssClass = "submitCommentImage";
            $button->setToSubmit();
            $fetcheritems.="<br />" . $button->show();
            //Form for comment fetcher
            $formData = new form('adaptationViewForm', $this->uri(array("action" => "addcomment", "product_id" => $productId)));
            $formData->addToForm($fetcheritems);
            $commentfetcher = $formData->show();
        }
        $sectionManager = $this->getObject("sectionmanager", "oer");

        $navigator = $sectionManager->buildSectionsTree($product["id"], '');

        $rightContent = "";
        //Get institution details
        if (!empty($product["institutionid"])) {
            //Get adaptation manager
            $managedby = "";
            //Get comments
            $comments = "";
            //Get language
            $adaptlang = "";
            if ($product['language'] == "en") {
                $adaptlang = "English";
            }
            //Get inst data
            $instData = $this->objDbInstitution->getInstitutionById($product["institutionid"]);
            if (!empty($instData)) {
                //Get institution type
                $instType = $this->objDbInstitutionType->getType($instData["type"]);
                /* $rightContent.='<div id="viewadaptation_author_label"></div>
                  <div id="viewadaptation_author_text"></div><br/><br/>'; */
                $rightContent.='<div id="viewadaptation_label">' . $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . ': </div>
            <div id="viewadaptation_text"><h2 class="pinkText">' . $instData['name'] . '</h2></div><br/><br/>';
                $rightContent.='<div id="viewadaptation_label">' . $this->objLanguage->languageText('mod_oer_typeofinstitution_label', 'oer') . ':</div>
            <div id="viewadaptation_unesco_contacts_text"> ' . $instType . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_label">' . $this->objLanguage->languageText('mod_oer_group_country', 'oer') . ':</div>
            <div id="viewadaptation_text">' . $instData['country'] . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_category_label">' . $this->objLanguage->languageText('mod_oer_adaptedin', 'oer') . ':</div>
            <div id="viewadaptation_category_text"> ' . $adaptlang . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_text"> ' . $this->objLanguage->languageText('mod_oer_fullinfo', 'oer') . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_label">' . $this->objLanguage->languageText('mod_oer_managedby', 'oer') . ':</div>
            <div id="viewadaptation_keywords_text"> ' . $managedby . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_text"> ' . $this->objLanguage->languageText('mod_oer_viewgroup', 'oer') . '</div><br/><br/>';
                /*$rightContent.='<div id="viewadaptation_keywords_label">' . $this->objLanguage->languageText('mod_oer_usercomments', 'oer') . ':</div>
            <div id="viewadaptation_keywords_text"> ' . $managedby . '</div><br/><br/>';*/
            }
        }

        $table->startRow();
        $table->addCell('<div id="viewadaptation_leftcontent">' . $leftContent . '</div>', "", "top", "left", "", 'colspan="1", style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $product['abstract'] . '</div>', "", "top", "left", "", 'colspan="1", style="width:55%"');
        $table->addCell('<div id="viewadaptation_rightcontent>' . $rightContent .$prodcomments. $commentfetcher . '</div>', "", "top", "left", "", 'rowspan="6", style="width:30%"');
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "", "top", "left", "", 'style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $newAdapt . '</div>', "", "top", "left", "", 'style="width:55%"');
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "", "top", "left", "", 'style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $existingAdaptations . '</div>', "", "top", "left", "", 'style="width:55%"');
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "", "top", "left", "", 'style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $fullProdView . '</div>', "", "top", "left", "", 'style="width:55%"');
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewadaptation_leftcontent">' . $sectionTitle . '</div>', "", "top", "left", "", 'style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $sections . '</div>', "", "top", "right", "", 'style="width:55%"');
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewadaptation_navigator">' . $navigator . '</div>', "", "top", "left", "", 'colspan="2",style="width:70%"');
        $table->endRow();

        $homeLink = new link($this->uri(array("action" => "home")));
        $homeLink->link = $this->objLanguage->languageText('mod_oer_home', 'system');


        $objTools = $this->newObject('tools', 'toolbar');
        $crumbs = array($homeLink->show());
        $objTools->addToBreadCrumbs($crumbs);

        $prodTitle = '<h1 class="adaptationListingLink">' . $product['title'] . '</h1>';

        return '<br/><div id="adaptationsBackgroundColor">'.$prodTitle . $table->show() . '</div>';
    }

}

?>