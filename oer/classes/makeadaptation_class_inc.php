<?php

/**
 * Contains util methods for managing adaptation records
 *
 * @author pwando
 */
class makeadaptation extends object {

    private $dboer_adaptations;
    private $objUser;
    private $dbproducts;

    function init() {
        $this->dboer_adaptations = $this->getObject('dboer_adaptations', 'oer');
        $this->objUser = $this->getObject("user", "security");
        $this->dbproducts = $this->getObject("dbproducts", "oer");
    }

    /**
     * Validates the contents of new product field values in step 1. If all are valid, these
     * are save, else the form is returned with the errors highlighted
     * @return type 
     */
    function addNewAdaptation() {
        $data = array(
            'parent_productid' => $this->getParam("parentproduct_id"),
            'userid' => $this->objUser->userId(),
            'section_title' => $this->getParam("section_title"),
            'current_path' => $this->getParam("selectnode"),
            'section_content' => $this->getParam("section_content"),
            'status' => $this->getParam("status"),
            'attachment' => $this->getParam("attachment"),
            'keywords' => $this->getParam("keywords"),
            'contributed_by' => $this->getParam("contributed_by"),
            'adaptation_notes' => $this->getParam("adaptation_notes")
            );
            $id = $this->dboer_adaptations->addNewAdaptation($data);
        return $id;
    }
    /**
     * updates adaptation details
     * @return type 
     */
    function updateAdaptation() {
        $id = $this->getParam("id");
        $data = array(
            'parent_productid' => $this->getParam("parentproduct_id"),
            'userid' => $this->objUser->userId(),
            'section_title' => $this->getParam("section_title"),
            'current_path' => $this->getParam("selectnode"),
            'section_content' => $this->getParam("section_content"),
            'status' => $this->getParam("status"),
            'attachment' => $this->getParam("attachment"),
            'keywords' => $this->getParam("keywords"),
            'contributed_by' => $this->getParam("contributed_by"),
            'adaptation_notes' => $this->getParam("adaptation_notes")
        );

        $this->dboer_adaptations->updateAdaptation($data, $id);
        return $id;
    }

    /**
     * Validates the contents of new adaptation field values in step 1. If all are valid, these
     * are save, else the form is returned with the errors highlighted
     * @return type
     */
    function saveNewAdaptationStep1() {
        $parentid = $this->getParam("id");
        $mode = $this->getParam("mode", "edit");
        if ($mode == "edit") {
            $data = array(
                "title" => $this->getParam("title"),
                "alternative_title" => $this->getParam("alternative_title"),
                "author" => $this->getParam("author"),
                "othercontributors" => $this->getParam("othercontributors"),
                "publisher" => $this->getParam("publisher"),
                "keywords" => $this->getParam("keywords"),
                "institutionid" => $this->getParam("institution"),
                "language" => $this->getParam("language"));

            $this->dbproducts->updateOriginalProduct($data, $parentid);
            return $parentid;
        } else {
            //get thumbnail of original product
            $parentData = $this->dbproducts->getProduct($parentid);
            $thumbnail = $parentData['thumbnail'];

            $data = array(
                "title" => $this->getParam("title"),
                "alternative_title" => $this->getParam("alternative_title"),
                "author" => $this->getParam("author"),
                "othercontributors" => $this->getParam("othercontributors"),
                "publisher" => $this->getParam("publisher"),
                "keywords" => $this->getParam("keywords"),
                "institutionid" => $this->getParam("institution"),
                "language" => $this->getParam("language"),
                "parent_id" => $parentid,
                "thumbnail" => $thumbnail,
                "translation_of" => "",
                "description" => "",
                "abstract" => "",
                "oerresource" => "",
                "provenonce" => "",
                "accredited" => "",
                "accreditation_body" => "",
                "accreditation_date" => "",
                "contacts" => "",
                "relation_type" => "",
                "relation" => "",
                "coverage" => "",
                "status" => "",
            );
            $result = $this->dbproducts->saveOriginalProduct($data);
            return $result;
        }
    }

    /**
     * updates adaptation step 1 details
     * @return type
     */
    function updateAdaptationStep1() {
        $id = $this->getParam("id");
        $data = array(
            "title" => $this->getParam("title"),
            "alternative_title" => $this->getParam("alternative_title"),
            "author" => $this->getParam("author"),
            "othercontributors" => $this->getParam("othercontributors"),
            "publisher" => $this->getParam("publisher"),
            "language" => $this->getParam("language"),
        );

        $this->dbproducts->updateOriginalProduct($data, $id);
        return $id;
    }

    /**
     * used for deleting an adaptation
     */
    function deleteAdaptation() {
        $id = $this->getParam("id");
        $this->dbproducts->deleteOriginalProduct($id);
    }

    /**
     * Updates the adaptation's step 2 details
     * @return type
     */
    function updateAdaptationStep2() {
        $id = $this->getParam("id");
        $data = array(
            "translation_of" => $this->getParam("translation"),
            "description" => $this->getParam("description"),
            "abstract" => $this->getParam("abstract"),
            "provenonce" => $this->getParam("provenonce"),
        );

        $this->dbproducts->updateOriginalProduct($data, $id);
        return $id;
    }

    /**
     * Updates the adaptation's step 3 details
     * @return type
     */
    function updateAdaptationStep3() {
        $id = $this->getParam("id");
        $data = array(
            "oerresource" => $this->getParam("oerresource"),
            "accredited" => $this->getParam("accredited"),
            "accreditation_body" => $this->getparam("accreditationbody"),
            "accreditation_date" => $this->getParam("accreditationdate"),
            "contacts" => $this->getParam("contacts"),
            "relation_type" => $this->getParam("relationtype"),
            "relation" => $this->getParam("relatedproduct"),
            "coverage" => $this->getParam("coverage"),
            "status" => $this->getParam("status"),
            "rights" => $this->getParam("creativecommons")
        );

        $this->dbproducts->updateOriginalProduct($data, $id);
        return $id;
    }

}

?>
