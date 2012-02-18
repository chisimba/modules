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
        $parentProduct = $this->objDbProducts->getProduct($product["parent_id"]);
        $instData = $this->objDbInstitution->getInstitutionById($product["institutionid"]);
        $parentInstData = $this->objDbInstitution->getInstitutionById($product["institutionid"]);
        
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

        //Link for - original product for this adaptation
        $viewParentProdLink = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $product["parent_id"], "mode"=>"grid")));
        $viewParentProdLink->link = $this->objLanguage->languageText('mod_oer_fullprodview', 'oer');
        $viewParentProd = $viewParentProdLink->show();

        //Link for - See existing adaptations of this UNESCO Product
        $viewParentInstLink = new link($this->uri(array("action" => "viewinstitution", "id" => $product["institutionid"])));
        $viewParentInstLink->link = $this->objLanguage->languageText('mod_oer_fullviewinst', 'oer');
        $viewParentInst = $viewParentInstLink->show();

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
                    <span class="greyText">' . $product['title'] . '</span>
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

        $content = '<div class="viewadaptation_leftcontent">' . $leftCol . '<div class="contentDivThreeWider">' . $product['description'] . '</div></div>';
        $content .= '<div class="rightColumnDivWide rightColumnPadding"><div class="frame">' . $navigator . '</div></div>';

        $topStuff = '<div class="adaptationListViewTop">
            <div class="tenPixelLeftPadding tenPixelTopPadding">
                        <div class="productAdaptationViewLeftColumnTop">
                            <div class="leftTopImage">
                            	<img src="skins/oer/images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49">
                            </div>
                            <div class="leftFloatDiv">
                                <h3>'.$parentProduct['title'].'</h3>
                                <img src="skins/oer/images/icon-product.png" alt="'.$this->objLanguage->languageText('mod_oer_bookmark', 'oer').'" width="18" height="18" class="smallLisitngIcons">
                                <div class="leftTextNextToTheListingIconDiv">'.$viewParentProd.'</a></div>
                            </div>
                    	</div>
                        
                        <div class="middleAdaptedByIcon">
                        	<img src="skins/oer/images/icon-adapted-by.png" alt="'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'" width="24" height="24"><br>
                        	<span class="pinkText">'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'</span>
                        </div>


                        <div class="productAdaptationViewMiddleColumnTop">
                            <div class="leftTopImage">
                            	<img src="skins/oer/images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49">
                            </div>
                            <div class="middleFloatDiv">
                                <h3 class="darkGreyColour">'.$instData['name'].'</h3>
                                <img src="skins/oer/images/icon-product.png" alt="'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'" width="18" height="18" class="smallLisitngIcons">
                                <div class="middleTextNextToTheListingIconDiv">'.$viewParentInst.'</div>
                            </div>
                    	</div>

<div class="productAdaptationViewRightColumnTop">
                        <div class="rightAdaptedByIcon">
                        	<img src="skins/oer/images/icon-managed-by.png" alt="'.$this->objLanguage->languageText('mod_oer_managedby', 'oer').'" width="24" height="24"><br>
                        	<span class="greenText">'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'</span>
                        </div>
                            <div class="rightFloatDiv">
                                <h3 class="greenText">'.$instData['name'].'</h3>
                                <div class="textNextToTheListingIconDiv"><a href="#" class="greenTextLink">View group</a></div>
                            </div>
                    	</div>
                    </div></div>';

        return '<div class="mainContentHolder"><div class="adaptationsBackgroundColor">'.$topStuff.'
            <div class="hunderedPercentGreyHorizontalLine">' . $content . '</div></div></div>';
    }

}

?>