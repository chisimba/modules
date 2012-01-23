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

        $leftContent = "";
        $prodTitle = '<h1 class="viewadaptation_title">' . $product['title'] . '</h1>';
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $leftContent.='<div id="viewadaptation_coverpage">' . $thumbnail . '</div>';

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
        $sectionTitle = '<h3>' . $objLanguage->languageText('mod_oer_sections', 'oer') . '</h3>';
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
        
        $table->startRow();
        $table->addCell('<div id="viewadaptation_leftcontent">' . $leftContent . '</div>', "", "top", "left", "", 'colspan="1", style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $prodTitle . '</div>'.
                '<div id="viewadaptation_leftcontent">' . $product['description']. '</div>', "", "top", "left", "", 'colspan="1", style="width:55%"');
        $table->addCell('<div id="viewadaptation_rightcontent>' . $rightContent . '</div>', "", "top", "left", "", 'rowspan="6", style="width:30%"');
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
        $table->addCell('<div id="viewadaptation_leftcontent">' . $fullProdView . '</div>', "", "top", "left", "", 'style="width:55%"');
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewadaptation_leftcontent">' . $sectionTitle . '</div>', "", "top", "left", "", 'style="width:15%"');
        $table->addCell('<div id="viewadaptation_leftcontent">' . $sections . '</div>', "", "top", "right", "", 'style="width:55%"');
        $table->endRow();

        $table->startRow();
        $table->addCell('<div id="viewadaptation_navigator">' . $navigator . '</div>', "", "top", "left", "", 'colspan="2",style="width:70%"');
        $table->endRow();

        $homeLink = new link($this->uri(array("action" => "home")));
        $homeLink->link = $objLanguage->languageText('mod_oer_home', 'system');


        $objTools = $this->newObject('tools', 'toolbar');
        $crumbs = array($homeLink->show());
        $objTools->addToBreadCrumbs($crumbs);

        return '<br/><div id="viewadaptation">' . $table->show() . '</div>';
    }

}

?>