<?php

/**
 * This class contains util methods for displaying full view of an adaptation
 *
 * @author pwando
 */
class fullviewadaptation extends object {

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

    function buildAdaptationFullView($productId) {
        $product = $this->objDbProducts->getProduct($productId);
        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;

        //Flag to check if user has perms to manage adaptations
        $hasPerms = $this->objAdaptationManager->userHasPermissions();

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
        $fullProdViewLink = new link($this->uri(array("action" => "viewadaptation", "id" => $productId)));
        $fullProdViewLink->link = $this->objLanguage->languageText('mod_oer_fullprodview', 'oer');
        $fullProdView = $fullProdViewLink->show();


        $sectionManager = $this->getObject("sectionmanager", "oer");

        $navigator = $sectionManager->buildSectionsTree($productId, '');

        $homeLink = new link($this->uri(array("action" => "home")));
        $homeLink->link = $this->objLanguage->languageText('mod_oer_home', 'system');


        $objTools = $this->newObject('tools', 'toolbar');
        $crumbs = array($homeLink->show());
        $objTools->addToBreadCrumbs($crumbs);

        $leftCol = '<div class="pageBreadCrumb">
                    <a href="#" class="greyText Underline">User Set</a> |
                    <a href="#" class="greyText Underline">Current</a> |
                    <a href="#" class="greyText Underline">Path</a> |
                    <span class="greyText">'.$product['title'].'</span>
                    <br><br>
                </div>
            <div class="headingHolder"><div class="heading2"><h1 class="greyText">' . $product['title'] . '</h1></div>
            <div class="icons2">
            <a href="#"><img src="skins/oer/images/icons/icon-edit-section.png" alt="Edit" width="18" height="18"></a>
            <a href="#"><img src="skins/oer/images/icons/icon-delete.png" alt="Delete" width="18" height="18"></a>
            <a href="#"><img src="skins/oer/images/icons/icon-add-to-adaptation.png" alt="Add to Adaptation" width="18" height="18"></a>
            <a href="#"><img src="skins/oer/images/icons/icon-content-top-print.png" alt="Print" width="18" height="18"></a>
            <a href="#"><img src="skins/oer/images/icons/icon-download.png" alt="Download" width="18" height="18"></a>
            </div></div>';

        $content = '<div class="viewadaptation_leftcontent">' . $leftCol .'<div class="contentDivThreeWider">'. $product['description'] . '</div></div>';
        $content .= '<div class="rightColumnDivWide rightColumnPadding"><div class="frame">' . $navigator . '</div></div>';

        return '<div class="mainContentHolder"><div class="adaptationsBackgroundColor"><div class="adaptationListViewTop">
            <div class="hunderedPercentGreyHorizontalLine">' . $content . '</div></div></div></div>';
    }

}

?>