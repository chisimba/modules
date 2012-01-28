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
        $this->rootTitle = $this->objLanguage->languageText('mod_oer_none', 'oer');
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
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('sections.js', 'oer'));
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
     * Saves new curriculum 
     * @return type 
     */
    function saveCurriculum() {
        $productId = $this->getParam("productid");
        $data = array(
            "product_id" => $productId,
            "title" => $this->getParam("title"),
            "forward" => $this->getParam("forward"),
            "background" => $this->getParam("background"),
            "status" => $this->getParam("status")
        );

        $dbCurriculum = $this->getObject("dbcurriculums", "oer");
        $dbCurriculum->addCurriculum($data);
        //here we must return the product id to be used for creating section tree
        return $productId;
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
        $nodeType = $this->getParam("nodetype");
        if ($nodeType == 'curriculum') {
            $forward = $this->getParam("forward");
            echo $forward;
            die();
        }
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
    function buildAddEditCuriculumForm($productId, $id=null, $parentid=null) {

        $objTable = $this->getObject('htmltable', 'htmlelements');
        $product = $this->dbproducts->getProduct($productId);
        $dbCurriculum = $this->getObject("dbcurriculums", "oer");
        $curriculum = $dbCurriculum->getCurriculum($productId);

        $hidId = new hiddeninput('productid');
        $hidId->cssId = "productid";
        $hidId->value = $productId;
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


        //title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_title', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('title');
        $textinput->size = 60;
        $textinput->cssClass = "required";
        $textinput->value = $curriculum['title'];
        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //forward
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_forward', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $forward = $this->newObject('htmlarea', 'htmlelements');
        $forward->name = 'forward';
        $forward->value = $curriculum['forward'];
        $forward->height = '150px';
        $objTable->addCell($forward->show());
        $objTable->endRow();


        //background
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_background', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $background = $this->newObject('htmlarea', 'htmlelements');
        $background->name = 'background';
        $background->height = '150px';
        $background->value = $curriculum['background'];
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
        $description->value = $curriculum['description'];
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

        $published->setSelected($curriculum['status']);
        $objTable->addCell($published->show());
        $objTable->endRow();


        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_curriculum', 'oer'));
        $fieldset->addContent($objTable->show());


        $title = $curriculum['title'];
        if ($title == '') {
            $title = $this->objLanguage->languageText('mod_oer_section', 'oer');
        }
        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_section";
        $header->str = $title . '-' . $this->objLanguage->languageText('mod_oer_curriculum', 'oer');


        $form = new form('curriculumform', $this->uri(array('action' => 'createcurriculum', "productid" => $productId)));
        $form->addToForm($fieldset->show());
        $button = new button('create', $this->objLanguage->languageText('word_save', 'system'));
        $button->setToSubmit();
        $form->addToForm('<br/>' . $button->show());
        return $header->show() . $form->show();
    }

    /**
     * This creates the fields for calendar
     * @return type 
     */
    function createCalendarField() {
        $label = new label($this->objLanguage->languageText('mod_oer_calendar', 'oer'), 'input_sectionnodename');

        $textinput = new textinput('calendar');
        $textinput->value = $name;
        $textinput->cssClass = 'required';

        return $label->show() . '<br/>' . $textinput->show();
    }

    /**
     * Creates year field
     * @return type 
     */
    function createYearField() {
        $label = new label($this->objLanguage->languageText('mod_oer_year', 'oer'), 'input_sectionnodename');

        $textinput = new textinput('calendar');
        $textinput->value = $name;
        $textinput->cssClass = 'required';

        return $label->show() . '<br/>' . $textinput->show();
    }

    /**
     * Returns a top level curriculum form or dynamic forms depending on the 
     * sections being created
     * @param type $productId
     * @param type $sectionsExist
     * @param type $name
     * @return type 
     */
    function buildCreateEditNodeForm($productId, $name='') {
        $dbCurriculum = $this->getObject("dbcurriculums", "oer");
        $curriculum = $dbCurriculum->getCurriculum($productId);
        if ($curriculum != null) {

            return $this->getAddEditNodeForm($productId);
        } else {
            return $this->buildAddEditCuriculumForm($productId);
        }
    }

    /**
     * builds a forms for creating a new node
     * @param type $name
     * @return type 
     */
    function getAddEditNodeForm($productId, $name='') {

        $form = new form('createsectionnode', $this->uri(array('action' => 'createsectionnode', "productid" => $productId)));
        $textinput = new textinput('title');
        $textinput->value = $name;
        $textinput->cssClass = 'required';
        $label = new label($this->objLanguage->languageText('mod_oer_sectionname', 'oer'), 'input_sectionname');

        $form->addToForm('<br/>' . $label->show());
        $form->addToForm('<br/>' . $textinput->show());



        $label = new label($this->objLanguage->languageText('mod_oer_sectiontype', 'oer'), 'input_sectionname');
        $nodeType = new dropdown('nodetype');
        $nodeType->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $nodeType->cssClass = "required";
     
        $nodeType->addOption('calendar', $this->objLanguage->languageText('mod_oer_calendar', 'oer'));
        $nodeType->addOption('year', $this->objLanguage->languageText('mod_oer_year', 'oer'));
        $nodeType->addOption('module', $this->objLanguage->languageText('mod_oer_module', 'oer'));
        $nodeType->addOnchange("javascript:displaySelectedNode();");



        $form->addToForm('<br/>' . $label->show());
        $form->addToForm('<br/>' . $nodeType->show());

        $createIn = '<div id="createin">' . $this->objLanguage->languageText('mod_oer_createin', 'oer') . '<br/>' .
                $this->buildSectionsTree($productId, '', 'htmldropdown') . '</div>';

        $form->addToForm("<br/>" . $createIn);



        $form->addToForm('<br/><div id="calendar">' . $this->createCalendarField() . '</div>');
        $form->addToForm('<div id="year">' . $this->createYearField() . '</div>');


        $button = new button('create', $this->objLanguage->languageText('word_save', 'system'));
        $button->setToSubmit();

        $form->addToForm('<br/>' . $button->show());

        $fs = new fieldset();
        $fs->setLegend($this->objLanguage->languageText('mod_oer_nodename', 'oer'));
        $fs->addContent($form->show());
        return $fs->show();
    }

    /**
     * Returns the parent of a given node. If the node is the top one, the title
     * ofthe curriculum is returned instead
     * @param type $path
     * @param type $productId
     * @return type 
     */
    function getParent($path, $productId) {

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
            $dbSections = $this->getObject("dbsectionnodes", "oer");
            $sectionsExist = $dbSections->sectionsExist($productId);
            if (!$sectionsExist) {
                $parent = $this->rootTitle;
            } else {
                
            }
        }
        return $parent;
    }

    /**
     * Builds a tree structure that represents sections within a product. This method
     * can also build a dropdown-like true, depending on the options passed
     * @param type $productId The product to build sections for
     * @param type $sectionId 
     * @param type $treeType Type of tree to build: could be dropdown or dhtml
     * @param type $selected The preselected node
     * @param type $treeMode
     * @param type $action
     * @return type 
     */
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

        $dbCurriculum = $this->getObject("dbcurriculums", "oer");
        $curriculum = $dbCurriculum->getCurriculum($productId);
        if ($curriculum != null) {
            $this->rootTitle = $curriculum['title'];
        }

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

                $parent = $this->getParent($sectionNode['path'], $productId);

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
