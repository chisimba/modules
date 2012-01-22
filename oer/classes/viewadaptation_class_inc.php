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
        $objDbInstitution = $this->getObject("dbinstitution", "oer");
        $objDbInstitutionType = $this->getObject("dbinstitutiontypes", "oer");
        $product = $objDbProducts->getProduct($productId);
        $table = $this->getObject("htmltable", "htmlelements");
        $table->attributes = "style='table-layout:fixed;'";
        $table->border = 0;
        $tablecellspacing = 20;

        $leftContent = "";
        $prodTitle ='<h1 class="viewproduct_title">' . $product['title'] . '</h1>';
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $leftContent.='<div id="viewproduct_coverpage">' . $thumbnail . '</div>';

        //Link for - adapting product from existing adapatation
        $newAdaptLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $productId, 'mode="new"')));
        $newAdaptLink->link = $objLanguage->languageText('mod_oer_makenewfromadaptation', 'oer');
        $newAdapt = $newAdaptLink->show();

        //Link for - See existing adaptations of this UNESCO Product
        $existingAdaptationsLink = new link($this->uri(array("action" => "viewadaptation", "id" => $productId)));
        $existingAdaptationsLink->link = $objLanguage->languageText('mod_oer_existingadaptations', 'oer');
        $existingAdaptations = $existingAdaptationsLink->show();

        //Link for - Full view of product
        $fullProdViewLink = new link($this->uri(array("action" => "viewadaptation", "id" => $productId)));
        $fullProdViewLink->link = $objLanguage->languageText('mod_oer_fullprodview', 'oer');
        $fullProdView = $fullProdViewLink->show();

        $sections = "";
        $sectionTitle = '<h3 class="original_product_section_title">' . $objLanguage->languageText('mod_oer_sections', 'oer') . '</h3>';
        $addSectionIcon = '<img src="skins/oer/images/add-node.png"/>';
        $addNodeLink = new link($this->uri(array("action" => "addsectionnode", "productid" => $productId)));
        $addNodeLink->link = $addSectionIcon . "&nbsp;&nbsp;" . $objLanguage->languageText('mod_oer_addnode', 'oer');
        $sections.=$addNodeLink->show();
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
            $instData = $objDbInstitution->getInstitutionById($product["institutionid"]);
            if (!empty($instData)) {
                //Get institution type
                $instType = $objDbInstitutionType->getType($instData["type"]);
                /* $rightContent.='<div id="viewadaptation_author_label"></div>
                  <div id="viewadaptation_author_text"></div><br/><br/>'; */
                $rightContent.='<div id="viewadaptation_label">' . $objLanguage->languageText('mod_oer_adaptedby', 'oer') . ': </div>
            <div id="viewadaptation_text"><h2>' . $instData['name'] . '</h2></div><br/><br/>';
                $rightContent.='<div id="viewadaptation_label">' . $objLanguage->languageText('mod_oer_typeofinstitution_label', 'oer') . ':</div>
            <div id="viewadaptation_unesco_contacts_text"> ' . $instType . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_label">' . $objLanguage->languageText('mod_oer_group_country', 'oer') . ':</div>
            <div id="viewadaptation_text">' . $instData['country'] . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_category_label">' . $objLanguage->languageText('mod_oer_adaptedin', 'oer') . ':</div>
            <div id="viewadaptation_category_text"> ' . $adaptlang . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_label">' . $objLanguage->languageText('mod_oer_language', 'oer') . ':</div>
            <div id="viewadaptation_keywords_text"> ' . $adaptlang . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_text"> ' . $objLanguage->languageText('mod_oer_fullinfo', 'oer') . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_label">' . $objLanguage->languageText('mod_oer_managedby', 'oer') . ':</div>
            <div id="viewadaptation_keywords_text"> ' . $managedby . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_text"> ' . $objLanguage->languageText('mod_oer_viewgroup', 'oer') . '</div><br/><br/>';
                $rightContent.='<div id="viewadaptation_keywords_label">' . $objLanguage->languageText('mod_oer_comments', 'oer') . ':</div>
            <div id="viewadaptation_keywords_text"> ' . $managedby . '</div><br/><br/>';
            }
        }
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
        $table->addCell('<div id="viewproduct_leftcontent">' . $prodTitle . '</div>', "100%", "top", "left", "", 'colspan="3"');
        $table->endRow();
        $table->startRow();
        $table->addCell('<div id="viewproduct_leftcontent">' . $leftContent . '</div>', "10%", "top", "left", "", 'colspan="1"');
        $table->addCell('<div id="viewproduct_leftcontent">' . $product['description'] . '</div>', "50%", "top", "left", "", 'colspan="1"');
        $table->addCell('<div id="viewproduct_rightcontent>' . $rightContent . '</div>', "40%", "top", "left", "", 'rowspan="6"');
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "20%", "top", "left", "");
        $table->addCell('<div id="viewproduct_leftcontent">' . $newAdapt . '</div>', "40%", "top", "left", "");
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "20%", "top", "left", "");
        $table->addCell('<div id="viewproduct_leftcontent">' . $existingAdaptations . '</div>', "40%", "top", "left", "");
        $table->endRow();
        $table->startRow();
        $table->addCell('&nbsp;', "20%", "top", "left", "");
        $table->addCell('<div id="viewproduct_leftcontent">' . $fullProdView . '</div>', "40%", "top", "left", "");
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewproduct_leftcontent">' . $sectionTitle . '</div>', "20%", "top", "left");
        $table->addCell('<div id="viewproduct_leftcontent">' . $sections . '</div>', "40%", "top", "right");
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewproduct_leftcontent">' . $navigator . '</div>', "60%", "top", "left", "", 'colspan="2"');
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