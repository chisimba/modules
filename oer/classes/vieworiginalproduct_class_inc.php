<?php

/**
 * This class contains util methods for displaying product details
 *
 * @author davidwaf
 */
class vieworiginalproduct extends object {

    function init() {
        $this->objUser = $this->getObject("user", "security");
        $this->loadJS();
        $this->setupLanguageItems();
    }

    /**
     * this creates the detailed view of the prodcut details
     * @param type $productId
     * @return type 
     */
    function buildProductDetails($productId) {
        $this->loadClass("link", "htmlelements");
        $objLanguage = $this->getObject("language", "language");
        $objDbProducts = $this->getObject("dbproducts", "oer");
        $product = $objDbProducts->getProduct($productId);
        $table = $this->getObject("htmltable", "htmlelements");

        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId("ProductCreators");
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $userId = $this->objUser->userId();

        $table->attributes = "style='table-layout:fixed;'";

        $leftContent = "";
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }

        //$editControls = '<div id="viewproducr_editcontrols">';
        $editControls = "";
        if ($objGroupOps->isGroupMember($groupId, $userId)) {
            $editImg = '<img src="skins/oer/images/icons/edit.png">';
            $deleteImg = '<img src="skins/oer/images/icons/delete.png">';
            $adaptImg = '<img src="skins/oer/images/icons/add.png">';

            $adaptLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $productId, "mode" => "new")));
            $adaptLink->link = $adaptImg;
            $editControls.= $adaptLink->show();

            $editLink = new link($this->uri(array("action" => "editoriginalproductstep1", "id" => $productId, "mode" => "edit")));
            $editLink->link = $editImg;
            $editControls.="" . $editLink->show();

            $deleteLink = new link($this->uri(array("action" => "deleteoriginalproduct", "id" => $productId)));
            $deleteLink->link = $deleteImg;
            $deleteLink->cssClass = "deleteoriginalproduct";
            $editControls.="" . $deleteLink->show();
        }
        // $editControls.='</div>';
       
        $leftContent.='<h1 class="viewproduct_title">'  . $product['title'] . '</h1>';
        $leftContent.='<div id="viewproduct_coverpage">' . $thumbnail . '</div>';

  if ($this->objUser->isLoggedIn()) {
            $leftContent.=$this->createRatingDiv($productId);
        }
        $leftContent.=$product['description'];
      
        $rightContent = "";
        $rightContent.='<div id="viewproduct_editcontrols">'.$editControls.'</div>';
        $rightContent.='<div id="viewproduct_authors_label">' . $objLanguage->languageText('mod_oer_authors', 'oer') . ': ' . $product['author'] . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_unesco_contacts_label">' . $objLanguage->languageText('mod_oer_unesco_contacts', 'oer') . ': ' . $product['contacts'] . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_publishedby_label">' . $objLanguage->languageText('mod_oer_publishedby', 'oer') . ': ' . $product['publisher'] . '</div><br/><br/>';

        $objDbThemes = $this->getObject("dbthemes", "oer");
        $themeIds = explode(",", $product['themes']);
        $themes = '';
        foreach ($themeIds as $themeId) {
            $themes.= $objDbThemes->getThemeFormatted($themeId) . '<br/>';
        }

        $rightContent.='<div id="viewproduct_category_label">' . $objLanguage->languageText('mod_oer_category', 'oer') . ': ' . $themes . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_keywords_label">' . $objLanguage->languageText('mod_oer_keywords', 'oer') . ': ' . $product['keywords'] . '</div><br/><br/>';

        $language = new dropdown('language');
        $language->addOption('', $objLanguage->languageText('mod_oer_select', 'oer'));
        $language->addOption('en', $objLanguage->languageText('mod_oer_english', 'oer'));

        $rightContent.='<div id="viewproduct_selectlanguages_label">' . $objLanguage->languageText('mod_oer_selectlangversions', 'oer') . ':<br/>' . $language->show() . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_relatednews_label">' . $objLanguage->languageText('mod_oer_relatednews', 'oer') . ': </div><br/><br/>';
        $rightContent.='<div id="viewproduct_relatedevents_label">' . $objLanguage->languageText('mod_oer_relatedevents', 'oer') . ':</div><br/><br/>';

        $objWallOps = $this->getObject('wallops', 'wall');

        $numOfPostsToDisplay = 10;
        $wallType = '4';
        $comments = '';
        if ($this->objUser->isLoggedIn()) {
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
        $rightContent.='<div id="viewproduct_usercomments_label">' . $objLanguage->languageText('mod_oer_usercomments', 'oer') . ':' .
                $comments .
                '</div>';


        $sections = '<div id="nodeband">';

        $sections.='<h3 class="original_product_section_title">' . $objLanguage->languageText('mod_oer_sections', 'oer') . '</h3>';
        if ($objGroupOps->isGroupMember($groupId, $userId)) {
            $addSectionIcon = '<img src="skins/oer/images/add-node.png" align="left"/>';
            $addNodeLink = new link($this->uri(array("action" => "addsectionnode", "productid" => $productId)));
            $addNodeLink->link = $addSectionIcon . $objLanguage->languageText('mod_oer_addnode', 'oer');
            $sections.=$addNodeLink->show();
        }

        $sections.='</div>';


        $sectionManager = $this->getObject("sectionmanager", "oer");
        $navigator = $sectionManager->buildSectionsTree($productId, '');



        $leftContent.='<br/>' . $sections . '<br/>' . $navigator;

        $table->startRow();

        $table->addCell('<div id="viewproduct_leftcontent">' . $leftContent . '</div>', "60%", "top", "left");

        $table->addCell('<div id="viewproduct_rightcontent>' . $rightContent . '</div>', "40%", "top", "left");

        $table->endRow();

        $homeLink = new link($this->uri(array("action" => "home")));
        $homeLink->link = $objLanguage->languageText('mod_oer_home', 'system');

        return '<br/><div id="viewproduct">' . $table->show() . '</div>';
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
     * sets up necessary lang items for use in js
     */
    function setupLanguageItems() {
        // Serialize language items to Javascript
        $arrayVars['totalvotestext'] = "mod_oer_productrating";
        $objSerialize = $this->getObject('serializevars', 'utilities');
        $objSerialize->languagetojs($arrayVars, 'oer');
    }
    /**
     * Builds a div for rating
     * @return string 
     */
    function createRatingDiv($productId) {
        $objLanguage=  $this->getObject("language", "language");
        $options = array(
            1 => array('title' =>  $objLanguage->languageText('mod_oer_notsogreat', 'oer')),
            2 => array('title' =>  $objLanguage->languageText('mod_oer_quitegood', 'oer')),
            3 => array('title' =>  $objLanguage->languageText('mod_oer_good', 'oer')),
            4 => array('title' =>  $objLanguage->languageText('mod_oer_great', 'oer')),
            5 => array('title' =>  $objLanguage->languageText('mod_oer_excellent', 'oer')));
        $dbProductRating = $this->getObject("dbproductrating", "oer");
        $totalRating = $dbProductRating->getTotalRating($productId);
        $avg = $totalRating;
        foreach ($options as $id => $val) {
            $options[$id]['disabled'] = 'disabled="disabled"';
            $options[$id]['checked'] = $id == $avg ? 'checked="checked"' : '';
        }

        $div = '<form id="rat" action="" method="post">';


        foreach ($options as $id => $rb) {
            $div.='<input type="radio" name="rate" value="' . $id . '|'.$productId.'|'.$this->objUser->userId().'" title="' . $rb['title'] . ' ' . $rb['checked'] . ' ' . $rb['disabled'] . '/>';
        }

       

        $div.='</form>

			<div id="loader"><div style="padding-top: 5px;">'. $objLanguage->languageText('mod_oer_pleasewait', 'oer').'...</div></div>';
       $div.='<div id="votes">'. $objLanguage->languageText('mod_oer_productrating', 'oer').': '.$totalRating.'</div>';

        return $div;
    }

}

?>
