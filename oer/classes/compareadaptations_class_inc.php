<?php

/**
 * This method contains util methods for comparing a product's adaptations
 *
 * @author pwando
 */
class compareadaptations extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->rootTitle = $this->objLanguage->languageText('mod_oer_none', 'oer');
        //Load htmlelement classes
        $this->loadClass('link', 'htmlelements');
        //Get DB Objects
        $this->dbProducts = $this->getObject('dbproducts', 'oer');
        $this->objUser = $this->getObject("user", "security");
        $this->dbSectionNode = $this->getObject("dbsectionnodes", "oer");
        $this->objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        $this->objDbInstitution = $this->getObject("dbinstitution", "oer");
        $this->sectionManager = $this->getObject('sectionmanager', 'oer');
    }

    /**
     * Build detailed section view
     * @param String $productId
     * @param String $sectionId
     * @return string
     */
    function buildCompareView($productId, $sectionId, $mode) {
        //Flag to check if user has perms to manage adaptations
        $hasPerms = $this->objAdaptationManager->userHasPermissions();

        //Get section data
        //$node = $this->dbSectionNode->getSectionNode($sectionId);
        $isOriginalProduct = $this->dbProducts->isOriginalProduct($productId);

        //get product data
        $product = $this->dbProducts->getProduct($productId);

        //Get product adaptations
        $productAdaptations = $this->dbProducts->getProductAdaptations($productId, '');

        $instData = $this->objDbInstitution->getInstitutionById($product["institutionid"]);

        //Flag to check if user has perms to manage adaptations
        $hasPerms = $this->objAdaptationManager->userHasPermissions();

        //Add bookmark
        $objBookMarks = $this->getObject('socialbookmarking', 'utilities');
        $objBookMarks->options = array('stumbleUpon', 'delicious', 'newsvine', 'reddit', 'muti', 'facebook', 'addThis');
        $objBookMarks->includeTextLink = FALSE;
        $bookmarks = $objBookMarks->show();

        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;

        $newAdapt = "";
        if ($hasPerms) {
            //Link for - adapting product from existing adapatation
            $newAdaptLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $productId, 'mode="new"')));
            $newAdaptLink->link = $this->objLanguage->languageText('mod_oer_makenewfromadaptation', 'oer');
            $newAdapt = $newAdaptLink->show();
        }

        //Link for - original product title
        $viewParentProdLink = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $product["parent_id"], "mode" => "grid")));
        $viewParentProdLink->link = $this->objLanguage->languageText('mod_oer_fullprodview', 'oer');
        $viewParentProd = $viewParentProdLink->show();

        //Link for - See existing adaptations of this UNESCO Product
        $viewParentInstLink = new link($this->uri(array("action" => "viewinstitution", "id" => $product["institutionid"])));
        $viewParentInstLink->link = $this->objLanguage->languageText('mod_oer_fullviewinst', 'oer');
        $viewParentInst = $viewParentInstLink->show();

        //Link for - parent inst title
        $viewInstTitleLink = new link($this->uri(array("action" => "viewinstitution", "id" => $product["institutionid"])));
        $viewInstTitleLink->link = $instData['name'];
        $viewInstTitle = $viewInstTitleLink->show();

        //Build navigation path
        if ($isOriginalProduct) {
            //Link for - product list
            $prodListLink = new link($this->uri(array("action" => "home")));
            $prodListLink->link = $this->objLanguage->languageText('mod_oer_maintitle2', 'oer');
            $prodListPage = $prodListLink->show();
            $navpath = $prodListPage . " > " . $product['title'];
            //Link for - view product for this section
            $viewProdTitleLink = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $product["id"], "mode" => "grid")));
            $viewProdTitleLink->link = $product['title'];
            $viewProdTitle = $viewProdTitleLink->show();
        } else {
            //Get parent prod data
            $parentProduct = $this->dbProducts->getProduct($product["parent_id"]);

            //Link for - adaptation list
            $adaptListLink = new link($this->uri(array("action" => "viewadaptation", "id" => $productId)));
            $adaptListLink->link = $this->objLanguage->languageText('mod_oer_adaptations', 'oer');
            $adaptListPage = $adaptListLink->show();
            $navpath = $adaptListPage . " > " . $viewInstTitle . " > " . $product['title'];
            //Link for - original product for this adaptation
            $viewParentTitleLink = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $product["parent_id"], "mode" => "grid")));
            $viewParentTitleLink->link = $parentProduct['title'];
            $viewParentTitle = $viewParentTitleLink->show();
        }

        $selected = "";

        //Fetch section tree
        $navigator = $this->sectionManager->buildSectionsTree($productId, '', "false", '', $selected);

        $homeLink = new link($this->uri(array("action" => "home")));
        $homeLink->link = $this->objLanguage->languageText('mod_oer_home', 'system');


        $objTools = $this->newObject('tools', 'toolbar');
        $crumbs = array($homeLink->show());
        $objTools->addToBreadCrumbs($crumbs);

        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;
        $table->cellpadding = 5;
        $table->cellspacing = 5;

        $rightContent = "";
        $rightContent = '<div class="compareAdaptationsNav"><div class="frame">' . $navigator . '</div></div>';
        $table->startRow();
        $table->addCell($rightContent, "", "top", "left", "", '');
        //Show navigation for each of the product's adaptations
        if (count($productAdaptations) > 0) {
            foreach ($productAdaptations as $prodAdaptation) {
                $adaptNav = $this->sectionManager->buildSectionsTree($prodAdaptation["id"], '', "false", '', $selected);
                $adaptContent = '<div class="compareAdaptationsNav"><div class="frame">' . $adaptNav . '</div></div>';
                $table->addCell($adaptContent, "", "top", "left", "", '');
            }
        }
        $table->endRow();

        $topStuff = "";


        //Heading varies depending on whether its an original product or adaptation
        if ($isOriginalProduct) {
            //Get icons
            $prodIconOne = '<img src="skins/oer/images/icon-product.png" alt="' . $this->objLanguage->languageText('mod_oer_bookmark', 'oer') .
                    '" class="smallIcons" />';
            $prodIconTwo = '<img src="skins/oer/images/document-new.png" alt="' . $this->objLanguage->languageText('mod_oer_bookmark', 'oer') .
                    '" class="smallIcons" />';
            $prodIconThree = '<img src="skins/oer/images/sort-by-grid.png" alt="' . $this->objLanguage->languageText('mod_oer_bookmark', 'oer') .
                    '" class="smallIcons" />';
            //Get count of adaptations
            $adaptationCount = $this->dbProducts->getProductAdaptationCount($productId);
            //Get prod thumbnail
            $prodthumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="59" height="76" align="left"/>';
            if ($product['thumbnail'] == '') {
                $prodthumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="59" height="76" align="left"/>';
            }
            //Link for - Full view of product
            $fullProdViewLink = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $productId, "identifier" => $productId, "mode" => "grid")));
            $fullProdViewLink->link = $this->objLanguage->languageText('mod_oer_fullviewofproduct', 'oer');
            $fullProdView = $prodIconOne . " " . $fullProdViewLink->show();
            //Link for - make new adaptation
            $makeAdaptationLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $productId, "mode" => "new")));
            $makeAdaptationLink->link = $this->objLanguage->languageText('mod_oer_makenewfromadaptation', 'oer');
            $makeAdaptation = $prodIconTwo . " " . $makeAdaptationLink->show();
            //Link for - view adaptations if count is >0
            $viewAdaptations = "";
            if ($adaptationCount > 0) {
                $viewAdaptationsLink = new link($this->uri(array("action" => "adaptationlist", "productid" => $productId)));
                $viewAdaptationsLink->link = $this->objLanguage->languageText('mod_oer_existingadaptations', 'oer') . " (" . $adaptationCount . ")";
                $viewAdaptations = $prodIconThree . " " . $viewAdaptationsLink->show();
            }
            $toplinks = $viewAdaptations;
            //Form title
            if ($hasPerms) {
                $toplinks = $makeAdaptation . " " . $viewAdaptations;
            }
            $topStuff = '<div class="adaptationListViewTop"><div class="leftTopImage">' . $prodthumbnail .
                    '</div><div><h3>' . $viewProdTitle . '</h3>
                        <p>' . $fullProdView . '</p>
                            <p>' . $toplinks . '</p></div></div>';
        } else {
            //Get prod & inst thumbnails
            $prodthumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="45" height="49" align="left"/>';
            if ($product['thumbnail'] == '') {
                $prodthumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="45" height="49" align="left"/>';
            }
            $instthumbnail = '<img src="usrfiles/' . $instData['thumbnail'] . '"   width="45" height="49"  align="bottom"/>';
            if ($instData['thumbnail'] == '') {
                $instthumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="45" height="49"  align="bottom"/>';
            }
            $topStuff = '<div class="adaptationListViewTop">
            <div class="tenPixelLeftPadding tenPixelTopPadding">
                        <div class="productAdaptationViewLeftColumnTop">
                            <div class="leftTopImage">' . $prodthumbnail . '</div>
                            <div class="leftFloatDiv">
                                <h3>' . $viewParentTitle . '</h3>
                                <img src="skins/oer/images/icon-product.png" alt="' . $this->objLanguage->languageText('mod_oer_bookmark', 'oer') .
                    '" class="smallLisitngIcons" />
                                <div class="leftTextNextToTheListingIconDiv">' . $viewParentProd . '</a></div>
                            </div>
                    	</div>
                        <div class="middleAdaptedByIcon">
                        	<img src="skins/oer/images/icon-adapted-by.png" alt="' .
                    $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . '" width="24" height="24"/><br />
                        	<span class="pinkText">' . $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . '</span>
                        </div>


                        <div class="productAdaptationViewMiddleColumnTop">
                            <div class="leftTopImage">' . $instthumbnail . '</div>
                            <div class="middleFloatDiv">
                                <h3 class="darkGreyColour">' . $viewInstTitle . '</h3>
                                <img src="skins/oer/images/icon-product.png" alt="' .
                    $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . '" class="smallLisitngIcons" />
                                <div class="middleTextNextToTheListingIconDiv">' . $viewParentInst . '</div>
                            </div>
                    	</div>

                    <div class="productAdaptationViewRightColumnTop">
                        <div class="rightAdaptedByIcon">
                        	<img src="skins/oer/images/icon-managed-by.png" alt="' .
                    $this->objLanguage->languageText('mod_oer_managedby', 'oer') . '" width="24" height="24"/><br />
                        	<span class="greenText">' . $this->objLanguage->languageText('mod_oer_managedby', 'oer') . '</span>
                        </div>
                            <div class="rightFloatDiv">
                                <h3 class="greenText">' . $instData['name'] . '</h3>
                                <div class="textNextToTheListingIconDiv"><a href="#" class="greenTextLink">View group</a></div>
                            </div>
                    	</div>
                    </div></div>';
        }

        return '<div class="navPath">' . $navpath .
        '</div><div class="topContentHolder">' . $topStuff . '</div><br/><br/>
            <div class="mainContentHolder"><div class="frame">' . $table->show() . '</div></div>';
    }

}

?>