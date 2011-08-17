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
    


    public function showInput($productID, $prevAction = NULL) {

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

//        $html .= '  <h4 class="greyText fontBold labelSpacing">Foreward</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea3" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>
//                    <h4 class="greyText fontBold labelSpacing">Background</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>
//                    <h4 class="greyText fontBold labelSpacing">Introductory Description</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>';

        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "greytexttable";

        $fieldName = 'title';
        $textinput = new textinput($fieldName);
         $textinput->cssClass = "required";
        $textinput->setValue($this->_title);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

        $fieldName = 'forward';
        $editor = $this->newObject('htmlarea', 'htmlelements');
      
        $editor->name = $fieldName;
        $editor->height = '150px';
       // $editor->width = '70%';
        
        $editor->setBasicToolBar();
        $editor->cssclass = 'required';
        $editor->setContent($this->_forward);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();

        $fieldName = 'background';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_background);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();

        $fieldName = 'introductory_description';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_introductory_description);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();
        
         $dropdown = new dropdown('status');
        $dropdown->addOption('Disabled');
         $dropdown->addOption('Draft');
         $dropdown->addOption('Published');
         
         
        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell("status");
        $table->endRow();
         
        $table->startRow();
        $table->addCell($dropdown->show());
        $table->endRow();
        
        
        

        $buttonSubmit = new button('upload', 'upload');
           $buttonSubmit->cssId = "upload";
        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
        $buttonSubmit->setToSubmit();

        $form_data->addToForm($table->show() . $buttonSubmit->show());

        return $form_data->show();
    }
    
     public function showReadOnlyInput() {
         

     
  

//        $html .= '  <h4 class="greyText fontBold labelSpacing">Foreward</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea3" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>
//                    <h4 class="greyText fontBold labelSpacing">Background</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>
//                    <h4 class="greyText fontBold labelSpacing">Introductory Description</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
//                        </span>


       

        $content = ' <h3 class="greyText"> Forward : </h3>'. $this->_forward . '<br>' ;
        $content .= ' <h3 class="greyText"> Background : </h3>'. $this->_background . '<br>' ;
      $content .= ' <h3 class="greyText"> Introductory Description : </h3>'. $this->_introductory_description. '<br>' ;
     

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
                    $this->_id
       
                    );
    }

    protected function updateExisting() {
        $this->objDbCurricula->updateCurriculum(
                    $this->_id,
                    $this->getParentID(), // This is the ID of the product this curruculum is contained in.
                    $this->_title,
                    $this->_forward,
                    $this->_background,
                    $this->_introductory_description
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
        $this->_introductory_description = $dataArray['introductory_description'];
        $this->_id = $dataArray['id'];
        $this->_title = $dataArray['title'];
        $this->_parentID = $dataArray['id'];
         $this->_originalID = $dataArray['parentid'];

        //TODO add code for this curriculum's contents
        $this->getContents();
    }
    
    public function getViewLink($productID) {
        return $this->uri(array('action' => 'ViewProductSection', 'productID' => $productID, 'path' => $this->getID()));
    }


}
?>

<!--<h4 class="greyText fontBold labelSpacing">Foreward</h4>
<h4 class="greyText fontBold labelSpacing">
    <span class="wideDivider">
        <textarea name="textarea3" class="wideInputTextAreaField"></textarea>
    </span>
</h4>
<h4 class="greyText fontBold labelSpacing">Background</h4>
<h4 class="greyText fontBold labelSpacing">
    <span class="wideDivider">
        <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
    </span>
</h4>
<h4 class="greyText fontBold labelSpacing">Introductory Description</h4>
<h4 class="greyText fontBold labelSpacing">
    <span class="wideDivider">
        <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
    </span>
</h4>-->