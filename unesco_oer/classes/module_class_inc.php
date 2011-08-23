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

    public function init() {
        $this->setType('module');
        $this->objDbModules = $this->newObject('dbmodules','unesco_oer');
        $this->_content_types = NULL;
        $this->loadClass('dropdown', 'htmlelements');
    }

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

        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "greytexttable";

        $fieldName = 'title';
        $textinput = new textinput($fieldName);
        $textinput->name = "title";
        $textinput->cssClass = "required";
        $textinput->setValue($this->_title);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell('Section Title');
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

        $fieldName = 'audience';
        $textinput = new textinput($fieldName);
           $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['audience']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell('Level');
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();
        
          $editor = $this->newObject('htmlarea', 'htmlelements');
       
        $fieldName = "Entry Requirements";
        $editor->name = 'entry_requirements';
        $editor->height = '150px';
         $editor->cssClass = "required";
       // $editor->width = '70%';
        
        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray['entry_requirements']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();
        
        
         $fieldName = "Outcomes/Objectives";
        $editor->name = 'outcomes';
        $editor->height = '150px';
       // $editor->width = '70%';
        
        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray['outcomes']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();
        
          $fieldName = 'Delivery Mode';
         
        $textinput = new textinput('mode');
         $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['mode']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();
        
        $fieldName = 'Number of Hours';
        $textinput = new textinput('no_of_hours');
         $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['no_of_hours']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();
        
         $fieldName = "Description";
        $editor->name = $fieldName;
        $editor->height = '150px';
       // $editor->width = '70%';
        
        $editor->setBasicToolBar();
        $editor->setContent($this->_metaDataArray['description']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();
        
         $fieldName = 'Assesment';
        $textinput = new textinput('assesment');
         $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['assesment']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();
        
         $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();
        
         $fieldName = 'Schedule of Activities';
        $textinput = new textinput('scheduele_of_classes');
         $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['schedule_of_classes']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();
        
         $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();
        
         $fieldName = 'Associated Material';
        $textinput = new textinput('associated_material');
         $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['associated_material']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();
        
         $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();
        
         $fieldName = 'Comments History';
        $textinput = new textinput('comments_history');
         $textinput->cssClass = "required";
        $textinput->setValue($this->_metaDataArray['comments_history']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();
        
        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();
        
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

        if (strcmp($option, 'saveedit') == 0){
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
       
        
// $content = ' <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
//  <script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
//<script  >
//   
//
//
// $(document).ready(
//        function()
//        {
//            
//            $("#form_add").validate();
//            alert("test);
//        
//       
//        });
//
//
//
//
//</script>';
 
 
      
     $content = '<body>';
     $content .= $form_data->show();   
     $content .= '</body>';

        return  $content;
    }
    
    public function showReadOnlyInput() {
    
        
        $content = "";
        $content .= '  <div class="contentDivThreeWider">';
    
    
      
        $content.= '<h3 class="greyText">  Level  </h3><br> '.  $this->_metaDataArray['audience'] . '  <br><br>';
        
         $content.= '<h3 class="greyText"> Entry Requirments </h3><br>'. $this->_metaDataArray['entry_requirements']. '<br><br> ';
         
          $content.= '<h3 class="greyText">Outcomes/Objectives </h3><br> '.  $this->_metaDataArray['outcomes']. '<br><br>';
          $content.= '<h3 class="greyText">Delivery Mode </h3><br> '. $this->_metaDataArray['mode']. '<br><br>';
          $content.= '<h3 class="greyText">Number of Hours </h3><br> '.  $this->_metaDataArray['no_of_hours']. '<br><br>';
          $content.= '<h3 class="greyText">Description</h3><br> '. $this->_metaDataArray['description']. '<br><br>';
          $content.= '<h3 class="greyText">Assesment</h3><br> '.  $this->_metaDataArray['assesment']. '<br><br>';
           $content.= '<h3 class="greyText">Scheduele of Activities</h3><br> '.  $this->_metaDataArray['schedule_of_classes']. '<br><br>';
            $content.= '<h3 class="greyText">Associated Material</h3><br> '.  $this->_metaDataArray['associated_material']. '<br><br>';
             $content.= '<h3 class="greyText">Comments history</h3><br> '.  $this->_metaDataArray['comments_history']. '<br><br>';

        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
      

   
        $content .= ' </div>';
        
        
        return $content;
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
                    'schedule_of_classes' => $this->getParam('scheduele_of_classes'),
                    'associated_material' => $this->getParam('associated_material'),
                    'comments_history' => $this->getParam('comments_history'),
        
                
            );

        if (empty($this->_id)) {
            $this->_id =  $this->objDbModules->addModule($data);
        }else{
            $this->objDbModules->updateModule($this->_id, $data);
        }

        return TRUE;
    }

    protected function saveNew() {
        $this->_id = NULL;
        $this->_metaDataArray['parentid'] = $this->_metaDataArray['id'];
        unset ($this->_metaDataArray['id']);
        unset ($this->_metaDataArray['puid']);
        $this->_metaDataArray['year_id'] = $this->getParentID();
        $this->_id =  $this->objDbModules->addModule($this->_metaDataArray);
    }

    public function getContentsByParentID($parentID) {
        $modulesData = $this->objDbModules->getModulesByYearID($parentID);
        $modulesArray = array();
        foreach ($modulesData as $moduleData){
            $tempModule = $this->newObject('module');
            $tempModule->load($moduleData);
            array_push($modulesArray, $tempModule);
        }

        return $modulesArray;
    }

    public function load($id) {
        $dataArray = NULL;
        if (is_array($id)){
            $dataArray = $id;
        }else{
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
    
     public function getCompareLink($test,$id) {
        return $this->uri(array('action' => 'CompareSelected', 'productid' => $test, 'id' => $id));
    }


    public function delete() {
        $success = $this->objDbModules->update('id',  $this->_id, array('deleted'=>'1'));
        $this->_deleted = $success ? '1' : $this->_deleted;
        return $success;
    }
}
?>
