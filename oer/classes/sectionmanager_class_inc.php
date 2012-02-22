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
        //  $this->appendArrayVar('headerParams', '<script type="text/javascript">var loggedIn=' . $this->objUser->isLoggedIn() . ';</script>');
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
        $id = $this->getParam("id");
        $data = array(
            "product_id" => $productId,
            "title" => $this->getParam("title"),
            "forward" => $this->getParam("forward"),
            "background" => $this->getParam("background"),
            "status" => $this->getParam("status")
        );

        $dbCurriculum = $this->getObject("dbcurriculums", "oer");
        if($id == Null) {
            $id = $dbCurriculum->addCurriculum($data);
        } else {
            $dbCurriculum->updateCurriculum($data, $id);
        }
        //here we must return the product id to be used for creating section tree
        return $productId;
    }

    /**
     * updates existing curriculum
     * @return type 
     */
    function updateCurriculum() {
        $productId = $this->getParam("productid");
        $id = $this->getParam("id");
        $data = array(
            "title" => $this->getParam("title"),
            "forward" => $this->getParam("forward"),
            "background" => $this->getParam("background"),
            "introduction" => $this->getParam("introduction"),
            "status" => $this->getParam("status")
        );

        $dbCurriculum = $this->getObject("dbcurriculums", "oer");
        $dbCurriculum->updateCurriculum($data, $id);
        //here we must return the product id to be used for creating section tree
        return $productId;
    }

    /**
     * generates a random string
     * @return string 
     */
    public function genRandomString() {
        $length = 5;
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * Saves a given section, depending on the type of node selected
     * @return type 
     */
    function saveSectionNode() {

        $parentid = $this->getParam('selectednode');

        $dbSections = $this->getObject("dbsectionnodes", "oer");
        $sectionNode = $dbSections->getSectionNode($parentid);
        $parent = $sectionNode['path'];
        $name = $this->getParam("title");
        $nodeType = $this->getParam("nodetype");
        $status = $this->getParam("status");
        $sectionId = null;

        $path = "";
        $child = $this->genRandomString();
        if ($parent) {
            $path = $parent . '/' . $child;
        } else {
            $path = $child;
        }

        $data = array(
            "product_id" => $this->getParam("productid"),
            "section_id" => $sectionId,
            "title" => $name,
            "path" => $path,
            "status" => $status,
            "nodetype" => $nodeType,
            "level" => count(explode("/", $path))
        );


        $id = $dbSections->addSectionNode($data);

        return $id;
    }

    /**
     * Saves section content to the db
     * @return type 
     */
    function saveSectionContent() {
        $data = array(
            "node_id" => $this->getParam("sectionid"),
            "title" => $this->getParam("title"),
            "deleted"=>'N',
            "content" => $this->getParam("content"),
            "status" => $this->getParam("status"),
            "contributedby" => $this->getParam("contributedby")
        );


        $dbSectionContent = $this->getObject("dbsectioncontent", "oer");
        $id = $dbSectionContent->addSectionContent($data);
        return $id;
    }

    function updateSectionNode() {

        $parentid = $this->getParam('selectednode');
        $sectionId = $this->getParam("id");
        $dbSections = $this->getObject("dbsectionnodes", "oer");
        $sectionNode = $dbSections->getSectionNode($parentid);
        $parent = $sectionNode['path'];
        $name = $this->getParam("title");
        $nodeType = $this->getParam("nodetype");
        $status = $this->getParam("status");


        $path = "";
        $child = $this->genRandomString();
        if ($parent) {
            $path = $parent . '/' . $child;
        } else {
            $path = $name;
        }

        $data = array(
            "product_id" => $this->getParam("productid"),
            "section_id" => $sectionId,
            "title" => $name,
            "path" => $path,
            "status" => $status,
            "nodetype" => $nodeType,
            "level" => count(explode("/", $path))
        );

        $id = $dbSections->updateSectionNode($data, $sectionId);
        return $id;
    }

    /**
     * Updates section info by setting deleted to false
     * @return type 
     */
    function deleteSectionNode() {
        $sectionId = $this->getParam("id");

        $data = array(
            "deleted" => "Y"
        );
        $dbSections = $this->getObject("dbsectionnodes", "oer");
        $id = $dbSections->updateSectionNode($data, $sectionId);
        return $id;
    }

    /**
     * updates section content
     * @return type 
     */
    function updateSectionContent() {
        $data = array(
            "node_id" => $this->getParam("sectionid"),
            "title" => $this->getParam("title"),
            "content" => $this->getParam("content"),
            "status" => $this->getParam("status"),
            "contributedby" => $this->getParam("contributedby")
        );

        $id = $this->getParam("id");
        $dbSectionContent = $this->getObject("dbsectioncontent", "oer");
        $dbSectionContent->updateSectionContent($data, $id);
        return $id;
    }

    /**
     * Builds a form for managing section
     * @return type 
     */
    function buildAddEditCuriculumForm($productId, $id=null, $parentid=null, $isOriginalProduct = null) {

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
        $objTable->addCell($this->objLanguage->languageText('mod_oer_introduction', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $description = $this->newObject('htmlarea', 'htmlelements');
        $description->name = 'introduction';
        $description->height = '150px';
        $description->value = $curriculum['introduction'];
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

        $action = 'createcurriculum';
        if ($id != null) {
            $action = "editcurriculum";
        }

        $form = new form('curriculumform', $this->uri(array('action' => $action, "productid" => $productId)));
        $form->addToForm($fieldset->show());
        $button = new button('create', $this->objLanguage->languageText('word_save', 'system'));
        $button->setToSubmit();
        $form->addToForm('<br/>' . $button->show());
        return $header->show() . $form->show();
    }

    /**
     * Returns a top level curriculum form or dynamic forms depending on the 
     * sections being created
     * @param type $productId
     * @param type $sectionsExist
     * @param type $name
     * @return type 
     */
    function buildCreateEditNodeForm($productId, $sectionId, $isOriginalProduct) {

        $dbCurriculum = $this->getObject("dbcurriculums", "oer");
        $curriculum = $dbCurriculum->getCurriculum($productId);        
        if ($curriculum == Null) {
            return $this->buildAddEditCuriculumForm($productId, $sectionId, $isOriginalProduct);
        } else {
            if ($sectionId == $curriculum['id']) {
                return $this->buildAddEditCuriculumForm($productId, $sectionId, $isOriginalProduct);
            } else {
                return $this->getAddEditNodeForm($productId, $sectionId, $isOriginalProduct);
            }
        }
    }

    /**
     * builds a forms for creating a new node
     * @param type $name
     * @return type 
     */
    function getAddEditNodeForm($productId, $sectionId, $isOriginalProduct) {
        $dbSections = $this->getObject("dbsectionnodes", "oer");
        $section = $dbSections->getSectionNode($sectionId);

        $action = "createsectionnode";
        if ($section != null) {
            $action = 'updatesectionnode';
        }        

        $form = new form('createsectionnode', $this->uri(array('action' => $action, "productid" => $productId)));

        if ($section != null) {
            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $sectionId;
            $form->addToForm($hidId->show());
        }
        $hidOP = new hiddeninput('isoriginalproduct');
        $hidOP->cssId = "id";
        $hidOP->value = $isOriginalProduct;
        $form->addToForm($hidOP->show());

        $label = new label($this->objLanguage->languageText('mod_oer_nodetype', 'oer'), 'input_sectionname');
        $nodeType = new dropdown('nodetype');
        $nodeType->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $nodeType->cssClass = "required";
        $nodeType->addOption('folder', $this->objLanguage->languageText('mod_oer_folder', 'oer'));
        $nodeType->addOption('section', $this->objLanguage->languageText('mod_oer_section', 'oer'));
        if ($section != null) {
            $nodeType->setSelected($section['nodetype']);
        }

        $form->addToForm('<br/>' . $label->show());
        $form->addToForm('<br/>' . $nodeType->show());

        //title
        $form->addToForm('<br/>' . $this->objLanguage->languageText('mod_oer_title', 'oer'));
        $textinput = new textinput('title');
        $textinput->size = 60;
        $textinput->cssClass = "required";

        if ($section != null) {
            $textinput->value = $section['title'];
        }
        $form->addToForm('<br/>' . $textinput->show());


        $statusField = new dropdown('status');
        $statusField->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $statusField->cssClass = "required";
        $statusField->addOption('disabled', $this->objLanguage->languageText('mod_oer_disabled', 'oer'));
        $statusField->addOption('draft', $this->objLanguage->languageText('mod_oer_draft', 'oer'));
        $statusField->addOption('published', $this->objLanguage->languageText('mod_oer_published', 'oer'));
        if ($section != null) {
            $statusField->setSelected($section['status']);
        }

        $createIn = '<div id="createin">' . $this->objLanguage->languageText('mod_oer_createin', 'oer') . '<br/>' .
                $selected = '';
        if ($section != null) {
            $selected = $section['path'];
        }
        $createIn.= $this->buildSectionsTree($productId, '', "false", 'htmldropdown', $selected) . '</div>';

        $form->addToForm("<br/>" . $createIn);
        $form->addToForm('<br/>' . $this->objLanguage->languageText('mod_oer_status', 'oer') . '<br/>' . $statusField->show());

        $button = new button('create', $this->objLanguage->languageText('word_save', 'system'));
        $button->setToSubmit();
        $form->addToForm('<br/>' . $button->show());

        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        //Check if original product or adaptation
        if ($isOriginalProduct == 1) {
            $uri = $this->uri(array("action" => "vieworiginalproduct", "id" => $productId));
        } else {
            $uri = $this->uri(array("action" => "viewadaptation", "id" => $productId));
        }
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $form->addToForm('&nbsp;&nbsp;' . $button->show());

        $fs = new fieldset();
        $fs->setLegend($this->objLanguage->languageText('mod_oer_nodename', 'oer'));
        $fs->addContent($form->show());

        $title = $this->objLanguage->languageText('mod_oer_addnode', 'oer');
        if ($section != null) {
            $title = $section['title'];
        }

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "createnode_title";
        $header->str = $title;


        return $header->show() . $fs->show();
    }

    /**
     * this formats and presents content of a selected node on sections tree of
     * a product
     * @param type $productId
     * @param type $sectionId
     * @return string 
     */
    function getSectionContent($productId, $sectionId) {
        $dbSections = $this->getObject("dbsectioncontent", "oer");
        $dbSectionNode = $this->getObject("dbsectionnodes", "oer");
        $dbProducts = $this->getObject("dbproducts", "oer");
        $node = $dbSectionNode->getSectionNode($sectionId);
        $section = $dbSections->getSectionContent($sectionId);
        $product = $dbProducts->getProduct($productId);
        $content = "";
        $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
        if ($product['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
        }
        $content.='<div id="sectionheader">';

        $content.='<a href="?module=oer&action=vieworiginalproduct&id=' . $productId . '">' . $thumbnail . '</a>';
        $content.='<a href="?module=oer&action=vieworiginalproduct&id=' . $productId . '">' . '<h1>' . $product['title'] . '</h1></a>';
        $content.='</div>';
        $content.='<div id="sectionbody">';

        //Add bookmark
        $objBookMarks = $this->getObject('socialbookmarking', 'utilities');
        $objBookMarks->options = array('stumbleUpon', 'delicious', 'newsvine', 'reddit', 'muti', 'facebook', 'addThis');
        $objBookMarks->includeTextLink = FALSE;
        $bookmarks = $objBookMarks->show();

        $leftContent = "";
        $leftContent .= '<p>' . $bookmarks . '</p>';
        $leftContent .= '<h2>' . $node['title'] . '</h2>';
        $leftContent .= $this->objLanguage->languageText('mod_oer_contributedby', 'oer') . '&nbsp;' . $section['contributedby'];
        $leftContent .= $section['content'];

        $rightContent = "";
        $rightContent.=$this->buildSectionsTree($productId, $sectionId);

        $table = $this->getObject("htmltable", "htmlelements");
        $table->startRow();
        $table->addCell($leftContent, "60%", "top", "left");
        $table->addCell('<div id="viewproduct_rightcontent>' . $rightContent . '</div>', "40%", "top", "left");
        $table->endRow();

        $content.=$table->show();
        $content.='</div>';
        return $content;
    }

    /**
     * this creates a form for filling in section details
     * @param type $productId
     * @param type $sectionId
     * @return type 
     */
    function getAddEditSectionForm($productId, $sectionId) {
        $dbSections = $this->getObject("dbsectioncontent", "oer");
        $dbSectionNode = $this->getObject("dbsectionnodes", "oer");
        $node = $dbSectionNode->getSectionNode($sectionId);
        $section = $dbSections->getSectionContent($sectionId);
        $contentId = null;
        $action = "createsectioncontent";
        if ($section != null) {
            $action = 'updatesectioncontent';
            $contentId = $section['id'];
        }

        $form = new form('createsectioncontent', $this->uri(array('action' => $action, "sectionid" => $sectionId, "productid" => $productId)));

        if ($section != null) {
            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $section['id'];
            $form->addToForm($hidId->show());
        }

        //title
        $form->addToForm('<br/><h2>' . $node['title'] . '</h2>');

        //content
        $form->addToForm('<br/>' . $this->objLanguage->languageText('mod_oer_content', 'oer'));

        $contentField = $this->newObject('htmlarea', 'htmlelements');
        $contentField->name = 'content';
        if ($section != null) {
            $contentField->value = $section['content'];
        }
        $contentField->height = '350px';
        $form->addToForm('<br/>' . $contentField->show());



        $statusField = new dropdown('status');
        $statusField->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $statusField->cssClass = "required";
        $statusField->addOption('disabled', $this->objLanguage->languageText('mod_oer_disabled', 'oer'));
        $statusField->addOption('draft', $this->objLanguage->languageText('mod_oer_draft', 'oer'));
        $statusField->addOption('published', $this->objLanguage->languageText('mod_oer_published', 'oer'));
        if ($section != null) {
            $statusField->setSelected($section['status']);
        }

        $form->addToForm('<br/>' . $this->objLanguage->languageText('mod_oer_status', 'oer') . '<br/>' . $statusField->show());
        $form->addToForm('<br/>' . $this->objLanguage->languageText('mod_oer_contributedby', 'oer'));

        $textarea = new textarea('contributedby', '', 5, 55);
        // $textarea->cssClass = 'required';
        if ($section != null) {
            $textarea->value = $section['contributedby'];
        }
        $form->addToForm('<br/>' . $textarea->show());


        $button = new button('create', $this->objLanguage->languageText('word_save', 'system'));
        $button->setToSubmit();
        $form->addToForm('<br/>' . $button->show());

        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "vieworiginalproduct", "id" => $productId));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $form->addToForm('&nbsp;&nbsp;' . $button->show());

        $fs = new fieldset();
        $fs->setLegend($this->objLanguage->languageText('mod_oer_content', 'oer'));
        $fs->addContent($form->show());

        $title = $this->objLanguage->languageText('mod_oer_addnewcontent', 'oer');
        if ($section != null) {
            $title = $section['title'];
        }

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "createsection_title";
        $header->str = $title;


        return $header->show() . $fs->show();
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
    function buildSectionsTree($productId, $sectionId, $showThumbNail=null, $treeType='dhtml', $selected='', $treeMode='side', $action='') {
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId("ProductCreators");
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $userId = $this->objUser->userId();

        $dbsections = $this->getObject("dbsectionnodes", "oer");
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
        $rootId = "-1";

        if ($curriculum != null) {
            $this->rootTitle = $curriculum['title'];
            $rootId = $curriculum['id'];
        }


        if ($treeType == 'htmldropdown') {
            $allFilesNode = new treenode(array('text' => $this->rootTitle, 'link' => '-1'));
        } else {
            $allFilesNode = new treenode(array('text' => $this->rootTitle, 'link' => $this->uri(array('action' => 'viewsection', "productid" => $productId, 'sectionid' => $rootId, 'nodetype' => 'curriculum', 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'cssClass' => $cssClass))));
        }

        $refArray = array();
        $refArray[$this->rootTitle] = & $allFilesNode;

        //Create a new tree
        $menu = new treemenu();

        $sectionEditor = FALSE;

        if ($objGroupOps->isGroupMember($groupId, $userId)) {
            $sectionEditor = TRUE;
        }

        if (count($sectionNodes) > 0) {
            foreach ($sectionNodes as $sectionNode) {
                $maxLen = 50;
                $nodeType = $sectionNode['nodetype'];
                if ($nodeType == 'folder') {
                    $icon = 'folder.gif';
                }
                if ($nodeType == 'section') {
                    $icon = 'document.png';
                    $expandedIcon = $icon;
                }


                $text = $sectionNode['title'];
                if (strlen($text) > $maxLen) {
                    $text = substr($sectionNode['title'], 0, $maxLen) . '...';
                }
                $folderText = $text;
                $folderShortText = $text;

                if ($sectionNode['path'] == $selected) {
                    $folderText = '<strong>' . $folderText . '</strong>';
                    $cssClass = 'confirm';
                } else {
                    $cssClass = '';
                }
                
                if($sectionNode['deleted'] == 'Y'){
                    $cssClass='deleted';
                }
                if ($treeType == 'htmldropdown') {
                    // echo "css class == $cssClass<br/>";
                    $node = & new treenode(array('title' => $folderText, 'text' => $folderShortText, 'link' => $sectionNode['id'], 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'cssClass' => $cssClass));
                } else {
                    $link = new link($this->uri(array('action' => 'viewsection', "productid" => $sectionNode['product_id'], 'sectionid' => $sectionNode['id'], 'nodetype' => $sectionNode['nodetype'])));
                    $link->cssClass = 'sectionlink';
                    $node = & new treenode(array('title' => $folderText, 'text' => $folderShortText, 'link' => $link->href, 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'cssClass' => $cssClass, 'expanded' => true));
                }

                $parent = $this->getParent($sectionNode['path'], $productId);

                if (array_key_exists($parent, $refArray)) {
                    $refArray[$parent]->addItem($node);
                }

                $refArray[$sectionNode['path']] = & $node;
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

        $thumbnail = "";
        $space = "";
        if ($showThumbNail == 'true') {
            $dbProduct = $this->getObject("dbproducts", "oer");
            $product = $dbProduct->getProduct($productId);
            $thumbnail = '<img src="usrfiles/' . $product['thumbnail'] . '"  width="79" height="101" align="left"/>';
            if ($product['thumbnail'] == '') {
                $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="left"/>';
            }
            $space = '<br/>';
        }
        return '<div id="navthumbnail">' . $thumbnail . '</div>' . '<div id="sectionstree">' . $space . $treeMenu->getMenu() . '</div>';
    }

}

?>
