<?php

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once 'content_class_inc.php';

/**
 * Description of module_class_inc
 *
 * @author manie
 */
class module extends content {

    private $objDbModules;
    private $_metaDataArray;
    private $objLanguage;

    public function init() {
        $this->setType('module');
        $this->objDbModules = $this->newObject('dbmodules', 'unesco_oer');
        $this->_content_types = NULL;
        $this->loadClass('dropdown', 'htmlelements');
        $this->icon = 'module.png';
        $this->expandedIcon = $this->icon;
        $this->objLanguage = $this->getObject('language','language');
    }

    public function showInput($productID, $prevAction = NULL) {
        $productUtil = $this->getObject('productutil', 'unesco_oer');
        $objHelpLink = $this->getObject('helplink','unesco_oer');
        $pair = $option = '';
        if ($this->getID()) {
            $pair = $this->getPairString();
            $option = 'saveedit';
        } else {
            $pair = $this->getParentID() . '__' . $this->getType();
            $option = 'save';
        }

        $uri = $this->uri(array(
            'action' => "saveContent",
            'productID' => $productID,
            'pair' => $pair,
            'option' => $option,
            'nextAction' => $prevAction));
        $form_data = new form('add_products_ui', $uri);

        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "greytexttable";

        $fieldName = 'title';
        $textinput = new textinput($fieldName);
        $textinput->name = "title";
        $textinput->cssClass = "required";
        $textinput->setValue($this->_title);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_title','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_title',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

        $fieldName = 'audience';
        $textinput = new textinput($fieldName);
        $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['audience']);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_module_level','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_level', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_module_level',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

        $editor = $this->newObject('htmlarea', 'htmlelements');

        $fieldName = "entry_requirements";
        $editor->name = $fieldName;
        $editor->height = '450px';
        $editor->cssClass = "required";
        // $editor->width = '70%';

        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_entry_requirements','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_requirements', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_requirements',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        //$editor = $this->newObject('htmlarea', 'htmlelements');

        $fieldName = 'outcomes';
        $editor->name = $fieldName;
        $editor->height = '450px';
        // $editor->width = '70%';

        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_outcomes_objectives','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_outcomes', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_outcomes_objectives',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        $fieldName = 'mode';
//        $textinput = new textinput($fieldName);
//        $textinput->cssClass = "required";
//        $textinput->setValue($this->_metaDataArray[$fieldName]);
        $editor->name = $fieldName;
        $editor->height = '450px';
        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_delivery_mode','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_mode', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_delivery_mode',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        $fieldName = 'no_of_hours';
//        $textinput = new textinput($fieldName);
//        $textinput->cssClass = "required";
//        $textinput->setValue($this->_metaDataArray[$fieldName]);
        $editor->name = $fieldName;
        $editor->height = '450px';
        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_number_of_hours','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_no_of_hours', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_number_of_hours',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        $fieldName = "content";
        $editor->name = $fieldName;
        $editor->height = '450px';

        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_description_long','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_description_long',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        $fieldName = 'assesment';
        $editor->name = $fieldName;
        $editor->height = '450px';
        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_assessment','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_assessment', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_assessment',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        //$editor = $this->newObject('htmlarea', 'htmlelements');

        $fieldName = 'schedule_of_classes';
        $editor->name = $fieldName;
        $editor->height = '450px';

        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_schedule_of_activities','unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_schedule_of_classes', 'unesco_oer');
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_schedule_of_activities',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

//        $fieldName = 'Associated Material';
        $fieldName = 'associated_material';
//        $textinput = new textinput('associated_material');
//        $textinput->cssClass = "required";
//        $textinput->setValue($this->_metaDataArray['associated_material']);
        $editor->name = $fieldName;
        $editor->height = '450px';
        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray[$fieldName]);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $title = $this->objLanguage->languageText('mod_unesco_oer_module_associated_material', 'unesco_oer');
        $table->addCell($title);
        $table->endRow();

        $table->startRow();
//        $table->addCell($textinput->show());
        $table->addCell($editor->show());
        $table->endRow();

//        $fieldName = 'Comments History';
//        $fieldName = 'comments_history';
////        $textinput = new textinput('comments_history');
////        $textinput->cssClass = "required";
////        $textinput->setValue($this->_metaDataArray['comments_history']);
//        $editor->name = $fieldName;
//        $editor->height = '450px';
//        $editor->setBasicToolBar();
//        $editor->setContent($this->_metaDataArray[$fieldName]);
//
//        $table->startRow();
//        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_module_comments_history', 'unesco_oer'));
////        $table->addCell($fieldName);
//        $table->endRow();
//
//        $table->startRow();
//        $table->addCell($editor->show());
//        $table->endRow();
        
         $parents = $this->getParentObjectList();
        $product = $this->getObject('product');
        $product->loadProduct($parents[0]->getParentID());
//        
        if ($product->isAdaptation()){

                         $fieldName = 'remark';
                $editor->name = $fieldName;
                $editor->height = '450px';
                     $editor->cssClass = "required";
                // $editor->width = '70%';

                $editor->setBasicToolBar();
                $editor->setContent($this->_metaDataArray[$fieldName]);

                $table->startRow();
                $table->addCell($this->objLanguage->languageText('mod_unesco_oer_module_remark', 'unesco_oer'));
                $table->endRow();

                $table->startRow();
                $table->addCell($editor->show());
                $table->endRow();

            
            
        }  else $this->_metaDataArray[$fieldName] = 'UNESCO OER ORIGIONAL';
        
   
       
        
        
        
//        $fieldName = 'Remark';
//        $textinput = new textinput('remark');
//        $textinput->cssClass = "required";
//        $textinput->setValue($this->_metaDataArray['remark']);
//
//        $table->startRow();
//        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_module_remark', 'unesco_oer'));
//        $table->addCell($fieldName);
//        $table->endRow();
//
//        $table->startRow();
//        $table->addCell($textinput->show());
//        $table->endRow();

        $dropdown = new dropdown('status');
        $dropdown->cssClass = "required";
        $dropdown->addOption(null);
        $dropdown->addOption('Disabled');
        $dropdown->addOption('Draft');
        $dropdown->addOption('Published');


        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell("Status");
        $table->endRow();

        $table->startRow();
        $table->addCell($dropdown->show());
        $table->endRow();




        $buttonSubmit = new button('upload', 'Save');
        $buttonSubmit->cssId = "upload";
        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
        $buttonSubmit->setToSubmit();


        $form_data->addToForm($table->show() . $buttonSubmit->show());

        if (strcmp($option, 'saveedit') == 0) {
            $buttonDelete = new button('btn_delete', 'Delete');
            $uri2 = $this->uri(array(
                'action' => "saveContent",
                'productID' => $productID,
                'pair' => $pair,
                'option' => 'delete',
                'nextAction' => $prevAction));
            $buttonDelete->setOnClick('javascript: window.location=\'' . $uri2 . '\'');
            $form_data->addToForm($buttonDelete->show());
        }

        $buttonCancel = new button('cancel', 'Cancel');
        $action = "$('.root').html('');";
        $buttonCancel->setOnClick('javascript: ' . $action);
        $form_data->addToForm($buttonCancel->show());


        $content = '<body>';
        $content .= $form_data->show();
        $content .= '</body>';

        return $content;
    }

    public function showReadOnlyInput() {


        $content = "";
        $content .= '  <div class="contentDivThreeWider">';



        $content.= '<h3 class="greyText">  Level  </h3><br> ' . $this->_metaDataArray['audience'] . '  <br><br>';

        $content.= '<h3 class="greyText"> Entry Requirments </h3><br>' . $this->_metaDataArray['entry_requirements'] . '<br><br> ';

        $content.= '<h3 class="greyText">Outcomes/Objectives </h3><br> ' . $this->_metaDataArray['outcomes'] . '<br><br>';
        $content.= '<h3 class="greyText">Delivery Mode </h3><br> ' . $this->_metaDataArray['mode'] . '<br><br>';
        $content.= '<h3 class="greyText">Number of Hours </h3><br> ' . $this->_metaDataArray['no_of_hours'] . '<br><br>';
        $content.= '<h3 class="greyText">Description</h3><br> ' . $this->_metaDataArray['description'] . '<br><br>';
        $content.= '<h3 class="greyText">Assesment</h3><br> ' . $this->_metaDataArray['assesment'] . '<br><br>';
        $content.= '<h3 class="greyText">Scheduele of Activities</h3><br> ' . $this->_metaDataArray['schedule_of_classes'] . '<br><br>';
        $content.= '<h3 class="greyText">Associated Material</h3><br> ' . $this->_metaDataArray['associated_material'] . '<br><br>';
 
        
          $parents = $this->getParentObjectList();
        $product = $this->getObject('product');
        $product->loadProduct($parents[0]->getParentID());
//        

          
          $content.= '<h3 class="greyText">Remarks</h3><br> ' . $this->_metaDataArray['remark'] . '<br><br>';
       

        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);



        $content .= ' </div>';


        return $content;
    }
    
    public function showRemark() {
        
        return  $this->_metaDataArray['remark'];
        
        
        
    }

    public function handleUpload() {
        $this->_title = $this->getParam('title');

        $data = array(
            'title' => $this->_title,
            'audience' => $this->getParam('audience'),
            'year_id' => $this->getParentID(),
            'entry_requirements' => $this->getParam('entry_requirements'),
            'outcomes' => $this->getParam('outcomes'),
            'no_of_hours' => $this->getParam('no_of_hours'),
            'mode' => $this->getParam('mode'),
            'assesment' => $this->getParam('assesment'),
            'schedule_of_classes' => $this->getParam('schedule_of_classes'),
            'associated_material' => $this->getParam('associated_material'),
            'comments_history' => $this->getParam('comments_history'),
            'remark' => $this->getParam('remark'),
            'content' => $this->getParam('content'),
            'object' => $this
        );

        if (empty($this->_id)) {
            $this->_id = $this->objDbModules->addModule($data);
        } else {
            $this->objDbModules->updateModule($this->_id, $data);
        }

        return TRUE;
    }

    protected function saveNew() {
        $this->_id = NULL;
//        $this->_metaDataArray['parentid'] = $this->_metaDataArray['id'];
        unset($this->_metaDataArray['id']);
        unset($this->_metaDataArray['puid']);
        $this->_metaDataArray['year_id'] = $this->getParentID();
            $this->_metaDataArray['object'] = $this;
//        $this->_id = $this->objDbModules->addModule($this->_metaDataArray);
    }

    public function getContentsByParentID($parentID) {
        $modulesData = $this->objDbModules->getModulesByYearID($parentID);
        $modulesArray = array();
        foreach ($modulesData as $moduleData) {
            $tempModule = $this->newObject('module');
            $tempModule->load($moduleData);
            array_push($modulesArray, $tempModule);
        }

        return $modulesArray;
    }

    public function load($id) {
        $dataArray = NULL;
        if (is_array($id)) {
            $dataArray = $id;
        } else {
            $dataArray = $this->objDbModules->getModuleByID($id);
        }


        $this->_id = $dataArray['id'];
        $this->_title = $dataArray['title'];
        $this->_parentID = $dataArray['year_id'];
        $this->_originalID = $dataArray['parentid'];
        $this->_deleted = $dataArray['deleted'];

        $this->_metaDataArray = $dataArray;

        //TODO add code for this modules's contents
        //$this->getContents();
    }

    public function getViewLink($productID) {
        return $this->uri(array('action' => 'ViewProductSection', 'productID' => $productID, 'path' => $this->getID()));
    }
    
    

    public function getCompareLink($test, $id) {
        return $this->uri(array('action' => 'CompareSelected', 'productid' => $test, 'id' => $id));
    }

    public function delete() {
        $success = $this->objDbModules->updateModule($this->_id, array('deleted' => '1'));
        $this->_deleted = $success ? '1' : $this->_deleted;
        return $success;
    }

    public function printHTML($level) {
        $html = parent::printHTML($level);

        $html = "
            <li>
                $this->_title
            </li>
            ";

        return $html;
    }

}

?>
