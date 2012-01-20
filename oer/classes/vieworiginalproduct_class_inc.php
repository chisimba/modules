<?php

/**
 * This class contains util methods for displaying product details
 *
 * @author davidwaf
 */
class vieworiginalproduct extends object {

    function init() {
        
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

        $objDbThemes = $this->getObject("dbthemes", "oer");
        $themeIds = explode(",", $product['themes']);
        $themes = "<ul>";
        foreach ($themeIds as $themeId) {
            $themes.='<li>' . $objDbThemes->getThemeFormatted($themeId) . '</li>';
        }
        $themes.='</ul>';

        $rightContent.='<div id="viewproduct_category_label">' . $objLanguage->languageText('mod_oer_category', 'oer') . ': ' . $themes . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_keywords_label">' . $objLanguage->languageText('mod_oer_keywords', 'oer') . ': ' . $product['keywords'] . '</div><br/><br/>';

        $language = new dropdown('language');
        $language->addOption('', $objLanguage->languageText('mod_oer_select', 'oer'));
        $language->addOption('en', $objLanguage->languageText('mod_oer_english', 'oer'));

        $rightContent.='<div id="viewproduct_selectlanguages_label">' . $objLanguage->languageText('mod_oer_selectlangversions', 'oer') . ':<br/>' . $language->show() . '</div><br/><br/>';
        $rightContent.='<div id="viewproduct_relatednews_label">' . $objLanguage->languageText('mod_oer_relatednews', 'oer') . ': </div><br/><br/>';
        $rightContent.='<div id="viewproduct_relatedevents_label">' . $objLanguage->languageText('mod_oer_relatedevents', 'oer') . ':</div><br/><br/>';
        $rightContent.='<div id="viewproduct_usercomments_label">' . $objLanguage->languageText('mod_oer_usercomments', 'oer') . ': </div>';


        $sections = "";
        $sections.='<h3 class="original_product_section_title">' . $objLanguage->languageText('mod_oer_sections', 'oer') . '</h3>';
        $addSectionIcon = '<img src="skins/oer/images/add-node.png" align="left"/>';
        $addNodeLink = new link($this->uri(array("action" => "addsectionnode", "productid" => $productId)));
        $addNodeLink->link = $addSectionIcon . $objLanguage->languageText('mod_oer_addnode', 'oer');
        $sections.=$addNodeLink->show();
        $sectionManager = $this->getObject("sectionmanager", "oer");
   
        $navigator= $sectionManager->buildSectionsTree($productId,'');
        $leftContent.='<br/>' .$sections.'<br/>'. $navigator;

        $table->startRow();

        $table->addCell('<div id="viewproduct_leftcontent">' . $leftContent . '</div>', "60%", "top", "left");

        $table->addCell('<div id="viewproduct_rightcontent>' . $rightContent . '</div>', "40%", "top", "left");

        $table->endRow();

        $homeLink = new link($this->uri(array("action" => "home")));
        $homeLink->link = $objLanguage->languageText('mod_oer_home', 'system');

        return '<br/><div id="viewproduct">' . $table->show() . '</div>';
    }

}

?>
