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
 * Description of dbmodules_class_inc
 *
 * @author manie
 */
class dbmodules extends dbtable {

    private $objUser;


    function init() {
        parent::init('tbl_unesco_oer_modules');
        $this->objUser = $this->getObject('user', 'security');
    }

    function getModules($filter = NULL) {
        return $this->getAll($filter);
    }

    function addModule($data){
        $objModule = $data['object'];
        unset ($data['object']);
        $id = $this->insert($data);
        if ($id != FALSE) {
            $this->addLuceneIndex($id,$data, $objModule);
        }
        return $id;
    }

    function addLuceneIndex($id, $moduleArray, $objModule) {

//        $data = array(
//            'title' => $this->_title,
//            'audience' => $this->getParam('audience'),
//            'year_id' => $this->getParentID(),
//            'entry_requirements' => $this->getParam('entry_requirements'),
//            'outcomes' => $this->getParam('outcomes'),
//            'no_of_hours' => $this->getParam('no_of_hours'),
//            'mode' => $this->getParam('mode'),
//            'assesment' => $this->getParam('assesment'),
//            'schedule_of_classes' => $this->getParam('scheduele_of_classes'),
//            'associated_material' => $this->getParam('associated_material'),
//            'comments_history' => $this->getParam('comments_history'),
//        );

            //$this->objKeywords->addStoryKeywords($id, $keyTags);

            //$objTags = $this->getObject('dbnewstags');
            //$objTags->addStoryTags($id, $tags);

            // Call Object
            $objIndexData = $this->getObject('indexdata', 'search');
            
            // Prep Data
            $docId = 'unesco_oer_module_'.$id;
            $docDate = $this->now();
            $parentObjectList = $objModule->getParentObjectList();
            $url = $this->uri(array('action' => 'ViewProductSection', 'productID' => $parentObjectList[0]->getParentID(), 'path' => $id),'unesco_oer');
            $title = stripslashes($moduleArray['title']);

            // Remember to add all info you want to be indexed to this field
            $contents = stripslashes($moduleArray['title']);

            // A short overview that gets returned with the search results
            $objTrim = $this->getObject('trimstr', 'strings');
            $teaser = $objTrim->strTrim(strip_tags(stripslashes($moduleArray['outcomes'])), 300);
            
            $module = 'unesco_oer';

            $additionalSearchIndex = array(
                'audience' => $moduleArray['audience'],
                'entry_requirements' => $moduleArray['entry_requirements'],
                'outcomes' => $moduleArray['outcomes'],
                'no_of_hours' => $moduleArray['no_of_hours'],
                'mode' => $moduleArray['mode'],
                'assesment' => $moduleArray['assesment'],
                'schedule_of_classes' => $moduleArray['scheduele_of_classes'],
                'associated_material' => $moduleArray['associated_material']
            );
            

            $userId = $this->objUser->userId();

//            if (is_array($tags)) $tags = 'array';
//            else $tags = 'noarray';

            // Add to Index
            $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents,
            $teaser, $module, $userId, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $additionalSearchIndex);
    }

    function updateModule($id, $data){
        $objModule = $data['object'];
        unset ($data['object']);
        

        $this->addLuceneIndex($id, $data, $objModule);

        return $this->update('id', $id, $data);
    }

    function getModuleByID($id){
        return $this->getRow('id', $id);
    }
    
    
    function getModulesByYearID($id) {;
        $where = "where year_id='$id'";
        return $this->getModules($where);
    }
    
     function getmoduleparent($id)
    {
        $sql = "select * from tbl_unesco_oer_modules where id = '$id'";

        return $this->getArray($sql);
    }
    
    function getmodulebyparent($id)
    {
        $sql = "select * from tbl_unesco_oer_modules where parentid = '$id'";

        return $this->getArray($sql);
    }
}
?>
