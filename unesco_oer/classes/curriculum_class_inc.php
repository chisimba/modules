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



/**
 * Description of curriculum_class_inc
 *
 * @author manie
 */

require_once 'content_class_inc.php';

class curriculum extends content {

    private $objDbCurricula;

    private $_forward;
    private $_background;
    private $_introductory_description;
     private $_remark;
    


    public function showInput($productID, $prevAction = NULL) {
        $objLanguage = $this->getObject('language','language');
        $productUtil = $this->getObject('productutil', 'unesco_oer');
        $objHelpLink = $this->getObject('helplink','unesco_oer');
        $pair = $option = '';
        if ($this->getID()) {
            $pair = $this->getPairString();
            $option = 'saveedit';
        }else{
            $pair = $this->getParentID().'__'.$this->getType();
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
         $textinput->cssClass = "required";
        $textinput->setValue($this->_title);

        $tooltip = $objLanguage->languageText('mod_unesco_oer_tooltip_title','unesco_oer');
        $title = $objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer');
        $table->startRow();
        $table->addCell($title.$productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_title',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();               


        $fieldName = 'forward';
        $editor = $this->newObject('htmlarea', 'htmlelements');
      
        $editor->name = $fieldName;
        $editor->height = '450px';
       // $editor->width = '70%';
        
        $editor->setBasicToolBar();
        $editor->cssclass = 'required';
        $editor->setContent($this->_forward);

        $table->startRow();
        $table->addCell($objLanguage->languageText('mod_unesco_oer_curriculum_forward', 'unesco_oer'));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        $fieldName = 'background';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '450px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_background);

        $table->startRow();
        $table->addCell($objLanguage->languageText('mod_unesco_oer_curriculum_background', 'unesco_oer'));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        $fieldName = 'introductory_description';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '450px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_introductory_description);

        $tooltip = $objLanguage->languageText('mod_unesco_oer_tooltip_description_long','unesco_oer');
        $title = $objLanguage->languageText('mod_unesco_oer_curriculum_description', 'unesco_oer');
        $table->startRow();
        $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_description_long',$title)));
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();
        
        
       
        $product = $this->getObject('product');
        $product->loadProduct($productID);
//        
        if ($product->isAdaptation()){
          

                $fieldName = 'remark';
                $editor->name = $fieldName;
                $editor->height = '450px';
                $editor->setBasicToolBar();
                $editor->setContent($this->_metaDataArray[$fieldName]);


                $tooltip = $objLanguage->languageText('mod_unesco_oer_tooltip_remark', 'unesco_oer');
                $title = $objLanguage->languageText('mod_unesco_oer_module_remark', 'unesco_oer');
                $table->startRow();
                $table->addCell($title . $productUtil->getToolTip($tooltip, $objHelpLink->show('mod_unesco_oer_tooltip_remark',$title)));
                $table->endRow();

                $table->startRow();
                $table->addCell($editor->show());
                $table->endRow();
        
        } else $this->_metaDataArray[$fieldName] = 'UNESCO OER ORIGIONAL';
        
        
        $dropdown = new dropdown('status');
        $dropdown->addOption($objLanguage->languageText('mod_unesco_oer_status_disabled', 'unesco_oer'));
        $dropdown->addOption($objLanguage->languageText('mod_unesco_oer_status_draft', 'unesco_oer'));
        $dropdown->addOption($objLanguage->languageText('mod_unesco_oer_status_published', 'unesco_oer'));
         
         
        $table->startRow();
        $table->addCell($objLanguage->languageText('mod_unesco_oer_status', 'unesco_oer'));
        $table->endRow();
         
        $table->startRow();
        $table->addCell($dropdown->show());
        $table->endRow();
        
        
        

        $buttonSubmit = new button('upload', $objLanguage->languageText('mod_unesco_oer_product_upload_button', 'unesco_oer'));
        $buttonSubmit->cssId = "upload";
        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
        $buttonSubmit->setToSubmit();

        $form_data->addToForm($table->show() . $buttonSubmit->show());

        if (strcmp($option, 'saveedit') == 0){
            $buttonDelete = new button('btn_delete', $objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer'));
             $uri2 = $this->uri(array(
                'action' => "saveContent",
                'productID' => $productID,
                'pair' => $pair,
                'option' => 'delete',
                'nextAction' => $prevAction));
            $buttonDelete->setOnClick('javascript: window.location=\'' . $uri2 . '\'');
            $form_data->addToForm($buttonDelete->show());
        }

        $buttonCancel = new button('cancel','Cancel');
        $action = "$('.root').html('');";
        $buttonCancel->setOnClick('javascript: ' . $action);
        $form_data->addToForm($buttonCancel->show());

        return $form_data->show();
    }
    
     public function showReadOnlyInput() {
        $objLanguage = $this->getObject('language','language');
        $forwardHeading =$objLanguage->languageText('mod_unesco_oer_curriculum_forward', 'unesco_oer');
        $backgroundHeading =$objLanguage->languageText('mod_unesco_oer_curriculum_background', 'unesco_oer');
        $descriptionHeading =$objLanguage->languageText('mod_unesco_oer_curriculum_description', 'unesco_oer');

        $content = " <h3 class='greyText'> $forwardHeading : </h3>$this->_forward<br>" ;
        $content .= " <h3 class='greyText'> $backgroundHeading : </h3>$this->_background<br>" ;
        $content .= " <h3 class='greyText'> $descriptionHeading : </h3>$this->_introductory_description<br>" ;
    
            $content .= " <h3 class='greyText'> Remark : </h3>$this->_remark<br>" ;
           

        return $content;
    }

    public function init() {
        $this->setType('curriculum');
        $this->objDbCurricula = $this->getObject('dbcurricula');
        $this->_content_types = array('calendar' => 'calendar');
    }

    public function handleUpload() {
        $this->_background = $this->getParam('background');
        $this->_forward = $this->getParam('forward');
        $this->_introductory_description = $this->getParam('introductory_description');
        $this->_title = $this->getParam('title');
          $this->_remark = $this->getParam('remark');
    

        if (empty($this->_id)) {
            $this->saveNew();
        }else{
            $this->updateExisting();
        }

        return TRUE;
    }

    protected function saveNew() {
        $this->_id =  $this->objDbCurricula->addCurriculum(
                    $this->getParentID(), // This is the ID of the product this curruculum is contained in.
                    $this->_title,
                    $this->_forward,
                    $this->_background,
                    $this->_introductory_description,
                    $this->tempID,
                    $this->_remark
                    );
    }

    protected function updateExisting() {
        $data = array(
            'product_id' => $this->getParentID(), // This is the ID of the product this curruculum is contained in.
            'title' => $this->_title,
            'forward'=> $this->_forward,
            'background'=> $this->_background,
            'introductory_description'=> $this->_introductory_description,
             'remark' => $this->_remark
        );

        $this->objDbCurricula->updateCurriculum(
                    $this->_id,
                    $data
                    );
    }

    public function getContentsByParentID($parentID) {
        $curriculaData = $this->objDbCurricula->getCurriculaByProductID(
                                                $parentID
                                                );
        $curriculaArray = array();

        foreach ($curriculaData as $curriculumData){
            $tempCurriculum = $this->newObject('curriculum');
            $tempCurriculum->load($curriculumData);
            array_push($curriculaArray, $tempCurriculum);
        }

        return $curriculaArray;
    }

    public function load($id) {
         $dataArray = NULL;
        if (is_array($id)){
            $dataArray = $id;
        }else{
            $dataArray = $this->objDbCurricula->getCurriculumByID($id);
        }

        $this->_background = $dataArray['background'];
        $this->_forward = $dataArray['forward'];
          $this->_remark = $dataArray['remark'];
        $this->_introductory_description = $dataArray['introductory_description'];
        $this->_id = $dataArray['id'];
        $this->_title = $dataArray['title'];
        $this->_parentID = $dataArray['product_id'];
        $this->_originalID = $dataArray['parentid'];
        $this->_deleted = $dataArray['deleted'];

        $this->getContents();
    }
    
    public function getViewLink($productID) {
        return $this->uri(array('action' => 'ViewProductSection', 'productID' => $productID, 'path' => $this->getID()));
    }
    
   
    
    public function getCompareLink($test,$id) {
       return $this->uri(array('action' => 'Comparechosen', 'id' => $this->getID(), 'productid' => $test, 'chosenid' => $id));
    }

    public function delete() {
        $success = $this->objDbCurricula->updateCurriculum($this->_id, array('deleted'=>'1'));
        $this->_deleted = $success ? '1' : $this->_deleted;
        if ($success) {
            $this->deleteAllContents();
        }

        return $success;
    }
    
     public function showRemark() {
        
        return  $this->_remark;
     }

    public function printHTML($level) {
        if($level >= 3){
            $level++;
        } else {
            $level = 3;
        }
        $html = parent::printHTML($level);;
        $html .= "<h$level>1. Forward</h$level>" . $this->_forward;
        $html .= "<h$level>2. Background</h$level>" . $this->_background;
        $html .= "<h$level>3. Introductory Description</h$level>" . $this->_introductory_description;
           $html .= "<h$level>4. Comments History</h$level>" . $this->_remark;
        return $html;
    }
}

?>