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
 * Description of dbcurricula_class_inc
 *
 * @author manie
 */
class dbcurricula extends dbtable
{
    private $objUser;

    function init() {
        parent::init("tbl_unesco_oer_curriculum");
        $this->objUser = $this->getObject('user', 'security');
    }

    function getCurricula($filter = NULL) {
        return $this->getAll($filter);
    }

    function addCurriculum($product_id, $title, $forward, $background, $description,$parentid, $remark) {
        $data = array(
            'product_id' => $product_id,
            'title' => $title,
            'forward'=> $forward,
            'background'=> $background,
            'introductory_description'=> $description,
            'parentid' => $parentid,
            'remark' => $remark
        );

        $id = $this->insert($data);

        if ($id != FALSE) {
            $this->addLuceneIndex($id, $data);
        }

        return $id;
    }

    function addLuceneIndex($id, $curriculumArray) {
            // Call Object
            $objIndexData = $this->getObject('indexdata', 'search');

            // Prep Data
            $docId = 'unesco_oer_curriculum_'.$id;
            $docDate = $this->now();
            $url = $this->uri(array('action' => 'ViewProductSection', 'productID' => $curriculumArray['product_id'], 'path' => $id) ,'unesco_oer');
            $title = stripslashes($curriculumArray['title']);

            // Remember to add all info you want to be indexed to this field
            $contents = stripslashes($curriculumArray['title']).' '. stripcslashes($curriculumArray['forward']).' '.stripslashes($curriculumArray['background']).' '.stripslashes($curriculumArray['introductory_description']);

            // A short overview that gets returned with the search results
            $objTrim = $this->getObject('trimstr', 'strings');
            $teaser = $objTrim->strTrim(strip_tags(stripslashes($curriculumArray['introductory_description'])), 300);

            $module = 'unesco_oer';

            $userId = $this->objUser->userId();

            // Add to Index
            $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents,
            $teaser, $module, $userId);
    }

    function updateCurriculum($id, $data){
        $del = $data['deleted'];
        if (empty($del) || $del == 0) {
            $this->addLuceneIndex($id, $data);
        } else {
            $objIndexData = $this->getObject('indexdata', 'search');
            $docId = "unesco_oer_curriculum_$id";
            $objIndexData->removeIndex($docId);
        }

        return $this->update('id', $id, $data);
    }

    function getCurriculaByProductID($id) {
        $where = "where product_id='$id'";
        return $this->getCurricula($where);
    }

    function getCurriculumByID($id){
        return $this->getRow('id', $id);
    }

     function getCurriculaparent($id)
    {
        $sql = "select * from $this->_tableName where id = '$id'";

        return $this->getArray($sql);
    }

    function getCurriculabyparent($id)
    {
        $sql = "select * from tbl_unesco_oer_curriculum where parentid = '$id'";

        return $this->getArray($sql);
    }


}


?>