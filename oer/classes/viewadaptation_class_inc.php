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
        $this->loadClass("radio", "htmlelements");
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getObject("dbproducts", "oer");
        $this->objDbProductComments = $this->getObject("dbproductcomments", "oer");
        $this->objDbInstitution = $this->getObject("dbinstitution", "oer");
        $this->objDbInstitutionType = $this->getObject("dbinstitutiontypes", "oer");
        $this->objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        $this->objUser = $this->getObject("user", "security");
        $this->objWashout = $this->getObject('washout', 'utilities');
        $this->loadJS();
        //Flag to check if user has perms to manage adaptations
        $this->hasPerms = $this->objAdaptationManager->userHasPermissions();
        $this->setupLanguageItems();
    }

    /**
     * sets up necessary lang items for use in js
     */
    function setupLanguageItems() {
        // Serialize language items to Javascript
        $arrayVars['totalvotestext'] = "mod_oer_productrating";
        $objSerialize = $this->getObject('serializevars', 'utilities');
        $objSerialize->languagetojs($arrayVars, 'oer');
    }

    /**
     * JS an CSS for product rating
     */
    function loadJS() {
        $ratingUIJs = '<script language="JavaScript" src="' . $this->getResourceUri('jquery.ui.stars.js') . '" type="text/javascript"></script>';
        $ratingEffectJs = '<script language="JavaScript" src="' . $this->getResourceUri('ratingeffect.js') . '" type="text/javascript"></script>';

        $ratingUICSS = '<link rel="stylesheet" type="text/css" href="skins/oer/jquery.ui.stars.min.css">';
        $crystalCSS = '<link rel="stylesheet" type="text/css" href="skins/oer/crystal-stars.css">';

        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/ui/development-bundle/ui/jquery.ui.widget.js', 'jquery'));
        $this->appendArrayVar('headerParams', $ratingEffectJs);
        $this->appendArrayVar('headerParams', $ratingUIJs);
        $this->appendArrayVar('headerParams', $ratingUICSS);
        $this->appendArrayVar('headerParams', $crystalCSS);
    }

    /**
     * Builds a div for rating
     * @return string
     */
    function createRatingDiv($productId) {
        $options = array(
            1 => array('title' => $this->objLanguage->languageText('mod_oer_notsogreat', 'oer')),
            2 => array('title' => $this->objLanguage->languageText('mod_oer_quitegood', 'oer')),
            3 => array('title' => $this->objLanguage->languageText('mod_oer_good', 'oer')),
            4 => array('title' => $this->objLanguage->languageText('mod_oer_great', 'oer')),
            5 => array('title' => $this->objLanguage->languageText('mod_oer_excellent', 'oer')));
        $dbProductRating = $this->getObject("dbproductrating", "oer");
        $totalRating = $dbProductRating->getTotalRating($productId);
        $avg = $totalRating;
        foreach ($options as $id => $val) {
            $options[$id]['disabled'] = 'disabled="disabled"';
            $options[$id]['checked'] = $id == $avg ? 'checked="checked"' : '';
        }

        $div = '<form id="rat" action="" method="post">';

        //$radio = new radio('rate');
        foreach ($options as $id => $rb) {
            $div.='<input type="radio" name="rate" value="' . $id . '|' . $productId . '|' . $this->objUser->userId() . '" title="' . $rb['title'] . ' ' . $rb['checked'] . ' ' . $rb['disabled'] . '/>';
        }
        $div.='</form><div id="loader"><div style="padding-top: 5px;">' . $this->objLanguage->languageText('mod_oer_pleasewait', 'oer') . '...</div></div>';
        $div.='<div id="votes">' . $this->objLanguage->languageText('mod_oer_productrating', 'oer') . ': ' . $totalRating . '</div>';

        return $div;
    }

    function buildAdaptationView($productId) {
        $product = $this->objDbProducts->getProduct($productId);
        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;

        $leftContent = "";

        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $leftContent.='<div id="viewadaptation_coverpage">' . $thumbnail . '</div>';
        $ratingDiv = $this->createRatingDiv($productId);

        $newAdapt = "";
        if ($this->hasPerms) {
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
        $fullProdViewLink->link = $this->objLanguage->languageText('mod_oer_productfullinfo', 'oer');
        $fullProdView = $fullProdViewLink->show();

        $sections = "";
        $sectionTitle = '<h3>' . $this->objLanguage->languageText('mod_oer_sections', 'oer') . '</h3>';
        if ($this->hasPerms) {
            $addSectionIcon = '<img src="skins/oer/images/add-node.png"/>';
            $addNodeLink = new link($this->uri(array("action" => "addsectionnode", "productid" => $productId)));
            $addNodeLink->link = $addSectionIcon . "&nbsp;&nbsp;" . $this->objLanguage->languageText('mod_oer_addnode', 'oer');
            $sections.=$addNodeLink->show();
        }

        //Get comments
        $prodcomments = "";
        $objWallOps = $this->getObject('wallops', 'wall');

        $numOfPostsToDisplay = 10;
        $wallType = '4';
        $comments = '';
        if ($this->hasPerms) {
            $comments = $objWallOps->showObjectWall('identifier', $productId, 0, $numOfPostsToDisplay);
        } else {
            $keyValue = $productId;
            $keyName = 'identifier';
            $dbWall = $this->getObject('dbwall', 'wall');
            $posts = $dbWall->getMorePosts($wallType, 0, $keyName, $keyValue, $numOfPostsToDisplay);
            $numPosts = $dbWall->countPosts($wallType, FALSE, $keyName, $keyValue);
            $str = '';
            if ($numPosts <= 10) {
                $str = $objWallOps->showPosts($posts, $numPosts, $wallType, $keyValue, $numOfPostsToDisplay, TRUE, FALSE, FALSE);
            } else {
                $str = $objWallOps->showPosts($posts, $numPosts, $wallType, $keyValue, $numOfPostsToDisplay, FALSE, FALSE, FALSE);
            }

            $comments = "\n\n<div class='wall_wrapper' id='wall_wrapper_{$keyValue}'>\n" . $str . "\n</div>\n\n";
        }
        $prodcomments.='<div id="viewproduct_usercomments_label">' . $this->objLanguage->languageText('mod_oer_usercomments', 'oer') . ':' .
                $comments .
                '</div>';

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
                $instType = $this->objDbInstitutionType->getType($instData['type']);
                
                $instName = $this->objLanguage->languageText('mod_oer_fullinfo', 'oer');;
                $instNameLink = new link($this->uri(array("action" => "viewinstitution", "id" => $product["institutionid"])));
                $instNameLink->link = $instName;
                $instNameLink->cssClass = "viewinstitutionlink";
                $instNameLk = "" . $instNameLink->show();
                /* $rightContent.='<div id="viewadaptation_author_label"></div>
                  <div id="viewadaptation_author_text"></div><br/><br/>'; */
                $rightContent.='<div id="viewadaptation_label">' . $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . ': </div>
            <div id="viewadaptation_text"></div><div class="pinkText">' . $instData['name'] . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_label">' . $this->objLanguage->languageText('mod_oer_typeofinstitution_label', 'oer') . ':</div>
            <div id="viewadaptation_unesco_contacts_text"> ' . $instType . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_label">' . $this->objLanguage->languageText('mod_oer_group_country', 'oer') . ':</div>
            <div id="viewadaptation_text">' . $instData['country'] . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_category_label">' . $this->objLanguage->languageText('mod_oer_adaptedin', 'oer') . ':</div>
            <div id="viewadaptation_category_text"> ' . $adaptlang . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_text"> ' . $instNameLk . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_label">' . $this->objLanguage->languageText('mod_oer_managedby', 'oer') . ':</div>
            <div id="viewadaptation_keywords_text"> ' . $managedby . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_text"> ' . $this->objLanguage->languageText('mod_oer_viewgroup', 'oer') . '</div><br/><br/>';
            }
        }
        $featuredAdaptation = "";
        if ($this->hasPerms) {
            //Add mark as featured adaptation
            $featuredImg = '<img src="skins/oer/images/featured.png">';
            $featuredLink = new link($this->uri(array("action" => "featureoriginalproduct", "productid" => $productId)));
            $featuredLink->link = $featuredImg;
            $featuredLink->cssClass = "featuredoriginalproduct";
            $featuredAdaptation = "" . $featuredLink->show();
        }


        $table->startRow();
        $table->addCell('<div id="viewadaptation_leftcontent">' . $leftContent . '</div>', "", "top", "left", "", 'colspan="1", style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $product['abstract'] . '</div>', "", "top", "left", "", 'colspan="1", style="width:55%"');
        $table->addCell('<div id="viewadaptation_rightcontent">' . $rightContent . $prodcomments . '</div>', "", "top", "left", "", 'rowspan="8", style="width:30%"');
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "", "top", "left", "", 'style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $fullProdView . '</div>', "", "top", "left", "", 'style="width:55%"');
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
        $table->addCell('<div id="viewadaptation_leftcontent">' . $featuredAdaptation . '</div>', "", "top", "left", "", 'style="width:55%"');
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "", "top", "left", "", 'style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $ratingDiv . '</div>', "", "top", "left", "", 'style="width:55%"');
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

        //Add bookmark
        $objBookMarks = $this->getObject('socialbookmarking', 'utilities');
        $objBookMarks->options = array('stumbleUpon', 'delicious', 'newsvine', 'reddit', 'muti', 'facebook', 'addThis');
        $objBookMarks->includeTextLink = FALSE;
        $bookmarks = $objBookMarks->show();

        //Add mark as featured adaptation
        $printImg = '<img src="skins/oer/images/icons/icon-download.png">';
        //$printLink = new link($this->uri(array("action" => "printproduct", "id" => $productId, "ext" => ".odt", 'type' => 'adaptation')));
        $printLink = new link($this->uri(array("action" => "downloaderedit", "productid" => $productId, "mode" => "add", 'producttype' => 'adaptation')));
        $printLink->link = $printImg;
        $printLink->cssClass = "downloaderedit";
        //$printLink->target = "_blank";
        $printLk = "" . $printLink->show();

        $prodTitle = '<div class="displaybookmarks">' . $bookmarks . " " . $printLk . '</div><br />';
        $prodTitle .= '<h1 class="adaptationListingLink">' . $product['title'] . '</h1>';

        return '<br/><div id="adaptationsBackgroundColor">' . $prodTitle . $table->show() . '</div>';
    }

    /**
     * Function that builds adaptation view for print
     * @param string $productId
     * @return string
     */
    function buildAdaptationForPrint($productId) {
        $product = $this->objDbProducts->getProduct($productId);
        //String holders for content
        $rightContent = "";
        $leftContent = "";
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $leftContent.= $thumbnail;
        
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
            //Get keywords
            $kwords = $product['thumbnail'];
            //get group
            $group = "";
            
            //Get inst data
            $instData = $this->objDbInstitution->getInstitutionById($product["institutionid"]);
            if (!empty($instData)) {
                //Get institution type
                $instType = $this->objDbInstitutionType->getType($instData['type']);
                
                $instName = $this->objLanguage->languageText('mod_oer_fullinfo', 'oer');
                $instNameLink = new link($this->uri(array("action" => "viewinstitution", "id" => $product["institutionid"])));
                $instNameLink->link = $instName;
                $instNameLink->cssClass = "viewinstitutionlink";
                $instNameLk = "" . $instNameLink->show();
                
                /* $rightContent.='<div id="viewadaptation_author_label"></div>
                  <div id="viewadaptation_author_text"></div><br/><br/>'; */
                if (!empty($instData["name"])) {
                    $rightContent.='<b>' . $this->objLanguage->languageText('mod_oer_adaptedby', 'oer') . ': </b>' . $instData['name'] . '<br /><br />';
                }
                if (!empty($instType)) {
                    $rightContent.='<b>' . $this->objLanguage->languageText('mod_oer_typeofinstitution_label', 'oer') . ': </b>' . $instType . '<br /><br />';
                }
                if (!empty($instData['country'])) {
                    $rightContent.='<b>' . $this->objLanguage->languageText('mod_oer_group_country', 'oer') . ': </b>' . $instData['country'] . '<br /><br />';
                }
                if (!empty($adaptlang)) {
                    $rightContent.='<b>' . $this->objLanguage->languageText('mod_oer_adaptedin', 'oer') . ': </b>' . $adaptlang . '<br /><br />';
                }
                if (!empty($instNameLk)) {
                    $rightContent.=' ' . $instNameLk . '<br></br>';
                }
                if (!empty($managedby)) {
                    $rightContent.='<b>' . $this->objLanguage->languageText('mod_oer_managedby', 'oer') . ':</b> ' . $managedby . '<br /><br />';
                }
                if (!empty($group)) {
                    $rightContent.='<b> ' . $this->objLanguage->languageText('mod_oer_viewgroup', 'oer') . '</b><br /><br />';
                }
            }
        }
        
        $strAd = "<p>".$leftContent.'<b><br /> ' . $this->objLanguage->languageText('mod_oer_abstract', 'oer') . '</b><br />'.
                $this->objWashout->parseText($product['abstract'])."<br />".'<b> ' . $this->objLanguage->languageText('mod_oer_description', 'oer') . '</b><br />'.
                $this->objWashout->parseText($product['description'])."<br />".$rightContent."</p>";

        $prodTitle = '<h1>' . $product['title'] . '</h1>';

        return $prodTitle . $strAd;
    }

}

?>