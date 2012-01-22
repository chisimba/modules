<?php

/**
 * This class contains util methods for displaying adaptations
 *
 * @author pwando
 */
class viewadaptation extends object {

    function init() {

    }

    function buildAdaptationView($productId) {
        $this->loadClass("link", "htmlelements");
        $objLanguage = $this->getObject("language", "language");
        $objDbProducts = $this->getObject("dbproducts", "oer");
        $product = $objDbProducts->getProduct($productId);
        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;
        $tablecellspacing = 20;

        $leftContent = "";
        $leftContent.='<h1 class="viewproduct_title">' . $product['title'] . '</h1>';
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $leftContent.='<div id="viewproduct_coverpage">' . $thumbnail . '</div>' . $product['description'];

        $sections = "";
        $sectionTitle = '<h3 class="original_product_section_title">' . $objLanguage->languageText('mod_oer_sections', 'oer') . '</h3>';
        $addSectionIcon = '<img src="skins/oer/images/add-node.png"/>';
        $addNodeLink = new link($this->uri(array("action" => "addsectionnode", "productid" => $productId)));
        $addNodeLink->link = $addSectionIcon ."&nbsp;&nbsp;". $objLanguage->languageText('mod_oer_addnode', 'oer');
        $sections.=$addNodeLink->show();
        $sectionManager = $this->getObject("sectionmanager", "oer");

        $navigator = $sectionManager->buildSectionsTree($product["id"], '');
        //$leftContent.='<br/>' .$sections.'<br/>'. $navigator;

        $rightContent = "";
        $rightContent.='<div id="viewadaptation_author_label"></div>
            <div id="viewadaptation_author_text"></div><br/><br/>';
        $rightContent.='<div id="viewadaptation_author_label">' . $objLanguage->languageText('mod_oer_authors', 'oer') . ': </div>
            <div id="viewadaptation_author_text">' . $product['author'] . '</div><br/><br/>';
        $rightContent.='<div id="viewadaptation_unesco_contacts_label">' . $objLanguage->languageText('mod_oer_unesco_contacts', 'oer') . ':</div>
            <div id="viewadaptation_unesco_contacts_text"> ' . $product['contacts'] . '</div><br/><br/>';
        $rightContent.='<div id="viewadaptation_publishedby_label">' . $objLanguage->languageText('mod_oer_publishedby', 'oer') . ':</div>
            <div id="viewadaptation_publishedby_text">' . $product['publisher'] . '</div><br/><br/>';
        $rightContent.='<div id="viewadaptation_category_label">' . $objLanguage->languageText('mod_oer_category', 'oer') . ':</div>
            <div id="viewadaptation_category_text"> ' . $product['themes'] . '</div><br/><br/>';
        $rightContent.='<div id="viewadaptation_keywords_label">' . $objLanguage->languageText('mod_oer_keywords', 'oer') . ':</div>
            <div id="viewadaptation_keywords_text"> ' . $product['keywords'] . '</div><br/><br/>';
        /*
          $language = "Not specified";
          if($product['language'] == 'en'){
          $language = "English";
          }
          $rightContent.='<div id="viewproduct_selectlanguages_label">' . $objLanguage->languageText('mod_oer_language', 'oer') . ':<br/>' . $language . '</div><br/><br/>';
          $rightContent.='<div id="viewproduct_relatednews_label">' . $objLanguage->languageText('mod_oer_relatednews', 'oer') . ': </div><br/><br/>';
          $rightContent.='<div id="viewproduct_relatedevents_label">' . $objLanguage->languageText('mod_oer_relatedevents', 'oer') . ':</div><br/><br/>';
          $rightContent.='<div id="viewproduct_usercomments_label">' . $objLanguage->languageText('mod_oer_usercomments', 'oer') . ': </div>'; */

        $table->startRow();
        $table->addCell('<div id="viewproduct_leftcontent">' . $leftContent . '</div>', "60%", "top", "left", "", 'colspan="2"');
        $table->addCell('<div id="viewproduct_rightcontent>' . $rightContent . '</div>', "40%", "top", "left", "", 'rowspan="3"');
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewproduct_leftcontent">' . $sectionTitle . '</div>', "30%", "top", "left");
        $table->addCell('<div id="viewproduct_leftcontent">' . $sections . '</div>', "30%", "top", "right");
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewproduct_leftcontent">' . $navigator . '</div>', "30%", "top", "left", "", 'colspan="2"');
        $table->endRow();

        $homeLink = new link($this->uri(array("action" => "home")));
        $homeLink->link = $objLanguage->languageText('mod_oer_home', 'system');


        $objTools = $this->newObject('tools', 'toolbar');
        $crumbs = array($homeLink->show());
        $objTools->addToBreadCrumbs($crumbs);

        return '<br/><div id="viewproduct">' . $table->show() . '</div>';
    }

}

?>