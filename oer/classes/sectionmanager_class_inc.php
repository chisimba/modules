<?php

/**
 * This method contains util methods for managing product sections
 *
 * @author davidwaf
 */
class sectionmanager extends object {

    function init() {
        $this->dbproducts = $this->getObject('dbproducts', 'oer');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject("user", "security");
        $this->rootTitle = $this->objLanguage->languageText('mod_oer_sections', 'oer');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('hiddeninput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->loadClass('treenode', 'tree');
        $this->loadClass('htmllist', 'tree');
        $this->loadClass('treemenu', 'tree');
        $this->loadClass('htmldropdown', 'tree');
        $this->loadClass('dhtml', 'tree');

        $this->addJS();
        $this->setupLanguageItems();
    }

    /**
     * adds essential js
     */
    function addJS() {
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('section.js', 'oer'));
    }

    /**
     * sets up necessary lang items for use in js
     */
    function setupLanguageItems() {
        // Serialize language items to Javascript
        $arrayVars['status_success'] = "mod_oer_status_success";
        $arrayVars['status_fail'] = "mod_oer_status_fail";
        $arrayVars['confirm_delete_section'] = "mod_oer_confirm_delete_section";
        $arrayVars['loading'] = "mod_oer_loading";
        $objSerialize = $this->getObject('serializevars', 'utilities');
        $objSerialize->languagetojs($arrayVars, 'oer');
    }

    /**
     *
     * @return type saves section of a product
     */
    function saveSection() {

        $data = array(
            "product_id" => $this->getParam("productid"),
            "parent_id" => $this->getParam("parentid"),
            "title" => $this->getParam("title"),
            "forward" => $this->getParam("forward"),
            "background" => $this->getParam("background"),
            "status" => $this->getParam("status"),
            "introduction" => $this->getParam("introduction")
        );

        $dbSections = $this->getObject("dbsections", "oer");
        $id = $dbSections->addSection($data);
        return $id;
    }

    function saveSectionNode() {
        $parentid = $this->getParam('selectednode');

        $dbSections = $this->getObject("dbsectionnodes", "oer");
        $sectionNode = $dbSections->getSectionNode($parentid);
        $parent = $sectionNode['path'];
        $name = $this->getParam("title");
        $path = "";
        if ($parent) {
            $path = $parent . '/' . $name;
        } else {
            $path = $name;
        }

        $data = array(
            "product_id" => $this->getParam("productid"),
            "title" => $this->getParam("title"),
            "path" => $path,
            "level" => count(explode("/", $path))
        );


        $id = $dbSections->addSectionNode($data);
        return $id;
    }

    /**
     * Builds a form for managing section
     * @return type 
     */
    function buildAddEditSectionForm($productid, $id=null, $parentid=null) {

        $objTable = $this->getObject('htmltable', 'htmlelements');
        $product = $this->dbproducts->getProduct($productid);

        $hidId = new hiddeninput('productid');
        $hidId->cssId = "productid";
        $hidId->value = $productid;
        $objTable->startRow();
        $objTable->addCell($hidId->show());
        $objTable->endRow();

        if ($id != null) {
            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $id;
            $objTable->startRow();
            $objTable->addCell($hidId->show());
            $objTable->endRow();
        }


        if ($parentid != null) {
            $hidId = new hiddeninput('parentid');
            $hidId->cssId = "parentid";
            $hidId->value = $parentid;
            $objTable->startRow();
            $objTable->addCell($hidId->show());
            $objTable->endRow();
        }
        $dbsections = $this->getObject("dbsections", "oer");
        if ($id != null) {
            $section = $dbsections->getSection($id);
        }

        //titles
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_title', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('title');
        $textinput->size = 60;
        if ($section != null) {
            $textinput->value = $section['title'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //forward
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_forward', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $forward = $this->newObject('htmlarea', 'htmlelements');
        $forward->name = 'forward';
        if ($section != null) {
            $forward->value = $section['forward'];
        }
        $forward->height = '150px';
        $objTable->addCell($forward->show());
        $objTable->endRow();


        //background
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_background', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $background = $this->newObject('htmlarea', 'htmlelements');
        $background->name = 'abstract';
        $background->height = '150px';
        if ($section != null) {
            $background->value = $section['background'];
        }
        $objTable->addCell($background->show());
        $objTable->endRow();


        //description
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_description', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $description = $this->newObject('htmlarea', 'htmlelements');
        $description->name = 'description';
        $description->height = '150px';
        if ($section != null) {
            $description->value = $section['description'];
        }

        $objTable->addCell($description->show());
        $objTable->endRow();


        //published status
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_published', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $published = new dropdown('status');
        $published->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $published->cssClass = "required";
        $published->addOption('disabled', $this->objLanguage->languageText('mod_oer_disabled', 'oer'));
        $published->addOption('draft', $this->objLanguage->languageText('mod_oer_draft', 'oer'));
        $published->addOption('published', $this->objLanguage->languageText('mod_oer_published', 'oer'));
        if ($product != null) {
            $published->setSelected($product['status']);
        }
        $objTable->addCell($published->show());
        $objTable->endRow();


        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_section', 'oer'));
        $fieldset->addContent($objTable->show());

        $formData = new form('sectionForm', $this->uri(array("action" => "savesection")));
        $formData->addToForm($fieldset);

        $formData->addToForm('<br/><div id="save_results"><div/>');
        $button = new button('saveSectionButton', $this->objLanguage->languageText('word_save', 'system', 'Save'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "vieworiginalproduct", "id" => $id));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_section";
        $header->str = $product['title'] . '-' . $this->objLanguage->languageText('mod_oer_section', 'oer');


        return $header->show() . $formData->show();
    }

    /**
     * builds a forms for creating a new node
     * @param type $name
     * @return type 
     */
    function getCreateSectionNodeForm($productId, $name='') {

        $form = new form('createsectionnode', $this->uri(array('action' => 'createsectionnode', "productid" => $productId)));
        $textinput = new textinput('title');
        $textinput->value = $name;
        $textinput->cssClass = 'required';
        $label = new label($this->objLanguage->languageText('mod_oer_nodename', 'oer'), 'input_sectionnodename');
        $form->addToForm("<br/>" . $this->objLanguage->languageText('mod_oer_createin', 'oer'));
        $form->addToForm("<br/>" . $this->buildSectionsTree($productId, '', 'htmldropdown'));
        $form->addToForm('<br/>' . $label->show());
        $form->addToForm('<br/>' . $textinput->show());
        $button = new button('create', $this->objLanguage->languageText('word_save', 'system'));
        $button->setToSubmit();

        $form->addToForm('<br/>' . $button->show());

        $fs = new fieldset();
        $fs->setLegend($this->objLanguage->languageText('mod_oer_nodename', 'oer'));
        $fs->addContent($form->show());
        return $fs->show();
    }

    function getParent($path) {

        $parent = "";
        $parts = explode("/", $path);
        $count = count($parts);
        for ($i = 0; $i < $count - 1; $i++) {
            if ($parent == '') {
                $parent.= $parts[$i];
            } else {
                $parent.="/" . $parts[$i];
            }
        }
        if ($parent == '') {
            $parent = $this->rootTitle;
        }
        return $parent;
    }

    function buildSectionsTree($productId, $sectionId, $treeType='dhtml', $selected='', $treeMode='side', $action='') {
        $dbsections = $this->getObject("dbsections", "oer");
        $sectionNodes = $dbsections->getSectionNodes($productId);

        if ($selected == '') {
            $sectionNode = $dbsections->getSectionNode($sectionId);
            $selected = $sectionNode['title'];
        }

        $icon = 'folder.gif';
        $expandedIcon = 'folder-expanded.gif';
        $cssClass = "";
        if ($treeType == 'htmldropdown') {

            $allFilesNode = new treenode(array('text' => $this->rootTitle, 'link' => '-1'));
        } else {
            $allFilesNode = new treenode(array('text' => $this->rootTitle, 'link' => $this->uri(array('action' => 'viewsection', "sectionid" => $sectionId)), 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'cssClass' => $cssClass));
        }



        $refArray = array();
        $refArray[$this->rootTitle] = & $allFilesNode;

        //Create a new tree
        $menu = new treemenu();


        if (count($sectionNodes) > 0) {
            foreach ($sectionNodes as $sectionNode) {
                $folderText = $sectionNode['title'];

                $folderShortText = substr($sectionNode['title'], 0, 500) . '...';
                if ($this->objUser->isAdmin()) {
                    
                }
                if ($sectionNode['title'] == $selected) {
                    $folderText = '<strong>' . $folderText . '</strong>';
                    $cssClass = 'confirm';
                } else {
                    $cssClass = '';
                }
                if ($treeType == 'htmldropdown') {
                    // echo "css class == $cssClass<br/>";
                    $node = & new treenode(array('title' => $folderText, 'text' => $folderShortText, 'link' => $sectionNode['id'], 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'cssClass' => $cssClass));
                } else {
                    $node = & new treenode(array('title' => $folderText, 'text' => $folderShortText, 'link' => $this->uri(array('action' => 'viewsection', 'sectionid' => $sectionNode['id'])), 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'cssClass' => $cssClass));
                }

                $parent = $this->getParent($sectionNode['path']);
                // if(!($parent == $this->rootTitle)){
                //echo $folderText . " parent== " . $parent . " path ==" . $dept['path'] . " level == " . $dept['level'] . '<br/>';
                // }
                if (array_key_exists($parent, $refArray)) {
                    $refArray[$parent]->addItem($node);
                }

                $refArray[$sectionNode['path']] = & $node;


                //$allFilesNode->addItem($node);
            }
        }

        $menu->addItem($allFilesNode);
        if ($treeType == 'htmldropdown') {
            $treeMenu = new htmldropdown($menu, array('inputName' => 'selectednode', 'id' => 'input_parentfolder', 'selected' => $selected));
        } else {
            $this->appendArrayVar('headerParams', $this->getJavascriptFile('TreeMenu.js', 'tree'));
            $this->setVar('pageSuppressXML', TRUE);
            $objSkin = $this->getObject('skin', 'skin');
            $treeMenu = new dhtml($menu, array('images' => 'skins/_common/icons/tree', 'defaultClass' => 'treeMenuDefault'));
        }

        return $treeMenu->getMenu();
    }

}

?>
