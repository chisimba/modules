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
        $this->loadClass("link","htmlelements");
        $objLanguage = $this->getObject("language", "language");
        $objDbProducts = $this->getObject("dbproducts", "oer");
        $product = $objDbProducts->getProduct($productId);
        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";

        $leftContent = "";
        $leftContent.='<h1 class="viewproduct_title">' . $product['title'] . '</h1>';
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $leftContent.='<div id="viewproduct_coverpage">' . $thumbnail . '</div>' . $product['description'];

        $rightContent = "";
        $rightContent.='<div id="viewproduct_authors_label">' . $objLanguage->languageText('mod_oer_authors', 'oer') . ': ' . $product['author'] . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_unesco_contacts_label">' . $objLanguage->languageText('mod_oer_unesco_contacts', 'oer') . ': ' . $product['contacts'] . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_publishedby_label">' . $objLanguage->languageText('mod_oer_publishedby', 'oer') . ': ' . $product['publisher'] . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_category_label">' . $objLanguage->languageText('mod_oer_category', 'oer') . ': ' . $product['themes'] . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_keywords_label">' . $objLanguage->languageText('mod_oer_keywords', 'oer') . ': ' . $product['keywords'] . '</div><br/><br/>';
        $language = "Not specified";
        if($product['language'] == 'en'){
            $language = "English";
        }
        $rightContent.='<div id="viewproduct_selectlanguages_label">' . $objLanguage->languageText('mod_oer_language', 'oer') . ':<br/>' . $language . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_relatednews_label">' . $objLanguage->languageText('mod_oer_relatednews', 'oer') . ': </div><br/><br/>';
        $rightContent.='<div id="viewproduct_relatedevents_label">' . $objLanguage->languageText('mod_oer_relatedevents', 'oer') . ':</div><br/><br/>';
        $rightContent.='<div id="viewproduct_usercomments_label">' . $objLanguage->languageText('mod_oer_usercomments', 'oer') . ': </div>';

        $table->startRow();
        $table->addCell('<div id="viewproduct_leftcontent">' . $leftContent . '</div>', "60%", "top", "left");
        $table->addCell('<div id="viewproduct_rightcontent>' . $rightContent . '</div>', "40%", "top", "left");

        $table->endRow();

        $homeLink=new link($this->uri(array("action"=>"home")));
        $homeLink->link=$objLanguage->languageText('mod_oer_home', 'system');
        
        
        $objTools = $this->newObject('tools', 'toolbar');
        $crumbs = array($homeLink->show());
        $objTools->addToBreadCrumbs($crumbs);

        return  '<br/><div id="viewproduct">' . $table->show() . '</div>';
    }
}
?>