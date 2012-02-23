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
        $this->objUser = $this->getObject("user", "security");
        //Flag to check if user is logged in
        $this->isLoggedIn = $this->objUser->isLoggedIn();
        $this->loadJScript();
    }
/**
     * JS an CSS for product rating
     */
    function loadJScript() {
        $dialogCSS = '<link rel="stylesheet" type="text/css" href="skins/oer/download-dialog.css">';

        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/themes/base/jquery.ui.all.css', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/ui/jquery.ui.widget.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/ui/jquery.ui.mouse.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/ui/jquery.ui.draggable.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/ui/jquery.ui.position.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/ui/jquery.ui.resizable.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/ui/jquery.ui.dialog.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('downloader.js'));
        $this->appendArrayVar('headerParams', $dialogCSS);
    }
    
    function buildAdaptationFullView($productId) {
        $product = $this->objDbProducts->getProduct($productId);
        $parentProduct = $this->objDbProducts->getProduct($product["parent_id"]);
        $instData = $this->objDbInstitution->getInstitutionById($product["institutionid"]);
        $parentInstData = $this->objDbInstitution->getInstitutionById($product["institutionid"]);
        //Flag to check if user has perms to manage adaptations
        $hasPerms = $this->objAdaptationManager->userHasPermissions();
        
        //Add bookmark
        $objBookMarks = $this->getObject('socialbookmarking', 'utilities');
        $objBookMarks->options = array('stumbleUpon', 'delicious', 'newsvine', 'reddit', 'muti', 'facebook', 'addThis');
        $objBookMarks->includeTextLink = FALSE;
        $bookmarks = $objBookMarks->show();

        //Add download link
        $printImg = '<img src="skins/oer/images/icons/icon-download.png">';

        // Download link
        $prodTitle = "";
        $downloadString = "";
        if (!$this->isLoggedIn) {
            $printLink = new link("#dialog");
            $printLink->link = $printImg;
            $printLink->cssClass = "downloaderedit";
            $printLink->extra = 'name="modal" onclick="showDownload(); "alt="'.$this->objLanguage->languageText('mod_oer_download', 'oer').'"';
            $printLink->title = $this->objLanguage->languageText('mod_oer_download', 'oer');
            $printLk = "" . $printLink->show();

            // Login link
            $objLoginLk = new link($this->uri(array("action" => "login"), "security"));
            $objLoginLk->cssId = "loginlink";
            $objLoginLk->link = $this->objLanguage->languageText('mod_oer_clicktologin', 'oer');

            // Register link
            $objRegisterLk = new link($this->uri(array("action" => "showregister"), "userregistration"));
            $objRegisterLk->cssId = "registerlink";
            $objRegisterLk->link = $this->objLanguage->languageText('mod_oer_clickhere', 'oer');
            $buttonTitle = $this->objLanguage->languageText('word_next');

            //Next button
            $objNextLk = new link($this->uri(array("action" => "downloaderedit", "productid" => $productId, "mode" => "add", 'producttype' => 'adaptation')));
            $objNextLk->cssId = "nextbtnspan";
            $objNextLk->link = $this->objLanguage->languageText('word_next');
            //Dialogue content
            $toolTipStr = $this->objLanguage->languageText('mod_oer_downloadlnone', 'oer') . ".<br /><br />"
                           . $this->objLanguage->languageText('mod_oer_downloadlntwo', 'oer') . ".<br /><br />"
                           . $objRegisterLk->show() . " " . $this->objLanguage->languageText('mod_oer_downloadlnthree', 'oer')
                           . ". " . $this->objLanguage->languageText('mod_oer_readmore', 'oer') . " "
                           . $this->objLanguage->languageText('mod_oer_downloadlnfour', 'oer') . ".<br /><br />"
                           . $this->objLanguage->languageText('mod_oer_downloadlnfive', 'oer') . " " . $objLoginLk->show() . ". "
                           . $this->objLanguage->languageText('mod_oer_downloadlnsix', 'oer') . ".<br /><br />"
                           . $this->objLanguage->languageText('mod_oer_downloadlnseven', 'oer')
                           . " " . $objNextLk->show();

            $dialogTitle = $this->objLanguage->languageText('mod_oer_downloadproduct','oer')." (".$this->objLanguage->languageText('mod_oer_adaptation','oer').")";
            $downloadString = '<div id="downloader"  title="'.$dialogTitle.'">' . $toolTipStr.'</div>';
            $prodTitle .= '<div class="displaybookmarks">'.$printLk." ". $bookmarks . " " . '</div>';
        } else {
            $printLink = new link($this->uri(array("action" => "downloaderedit", "productid" => $productId, "mode" => "edit", 'producttype' => 'adaptation')));
            $printLink->link = $printImg;
            $printLink->cssClass = "downloaderedit";
            $printLink->extra = 'alt="'.$this->objLanguage->languageText('mod_oer_download', 'oer').'"';
            $printLink->title = $this->objLanguage->languageText('mod_oer_download', 'oer');
            //$printLink->target = "_blank";
            $printLk = "" . $printLink->show();
            $prodTitle .= '<div class="displaybookmarks">' . $printLk . " " . $bookmarks   . '</div>';
        }
        
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

        $leftCol = $downloadString;

        $leftCol .= '<div class="headingHolder"><div class="heading2"><h1 class="greyText">' . $product['title'] . '</h1></div>
            <div class="icons2">'.$prodTitle.'</div></div>';

        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;

        $leftContent = "";
        $rightContent = "";
        $leftContent = '<div class="viewadaptation_leftcontent">' . $leftCol . '<div class="contentDivThreeWider">' . $product['description'] . '</div>';
        $leftContent .= '<div class="adaptationNotesContent"><h3>'.$this->objLanguage->languageText('mod_oer_adaptationotes', 'oer').'</h3>'
                . $product['adaptation_notes'] . '</div></div>';
        $rightContent = '<div class="rightColumnDivWide rightColumnPadding"><div class="frame">' . $navigator . '</div></div>';
        $table->startRow();
        $table->addCell($leftContent, "", "top", "left", "", 'style="width:75%"');
        $table->addCell("<br /><br />".$rightContent, "", "top", "left", "", 'style="width:25%"');
        $table->endRow();

        $topStuff = '<div class="adaptationListViewTop">
            <div class="tenPixelLeftPadding tenPixelTopPadding">
                        <div class="productAdaptationViewLeftColumnTop">
                            <div class="leftTopImage">
                            	<img src="skins/oer/images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49" />
                            </div>
                            <div class="leftFloatDiv">
                                <h3>'.$parentProduct['title'].'</h3>
                                <img src="skins/oer/images/icon-product.png" alt="'.$this->objLanguage->languageText('mod_oer_bookmark', 'oer').
                '" width="18" height="18" class="smallLisitngIcons" />
                                <div class="leftTextNextToTheListingIconDiv">'.$viewParentProd.'</a></div>
                            </div>
                    	</div>
                        
                        <div class="middleAdaptedByIcon">
                        	<img src="skins/oer/images/icon-adapted-by.png" alt="'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'" width="24" height="24"/><br />
                        	<span class="pinkText">'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'</span>
                        </div>


                        <div class="productAdaptationViewMiddleColumnTop">
                            <div class="leftTopImage">
                            	<img src="skins/oer/images/adapted-product-grid-institution-logo-placeholder.jpg" width="45" height="49"/>
                            </div>
                            <div class="middleFloatDiv">
                                <h3 class="darkGreyColour">'.$instData['name'].'</h3>
                                <img src="skins/oer/images/icon-product.png" alt="'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'" width="18" height="18" class="smallLisitngIcons" />
                                <div class="middleTextNextToTheListingIconDiv">'.$viewParentInst.'</div>
                            </div>
                    	</div>

<div class="productAdaptationViewRightColumnTop">
                        <div class="rightAdaptedByIcon">
                        	<img src="skins/oer/images/icon-managed-by.png" alt="'.$this->objLanguage->languageText('mod_oer_managedby', 'oer').'" width="24" height="24"/><br />
                        	<span class="greenText">'.$this->objLanguage->languageText('mod_oer_adaptedby', 'oer').'</span>
                        </div>
                            <div class="rightFloatDiv">
                                <h3 class="greenText">'.$instData['name'].'</h3>
                                <div class="textNextToTheListingIconDiv"><a href="#" class="greenTextLink">View group</a></div>
                            </div>
                    	</div>
                    </div></div>';

        return '<div class="mainContentHolder"><div class="adaptationsBackgroundColor">'.$topStuff. $table->show().'
            <div class="hunderedPercentGreyHorizontalLine">'  . '</div></div></div>';
    }

}

?>