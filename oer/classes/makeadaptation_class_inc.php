<?php

/**
 * Contains util methods for managing adaptation records
 *
 * @author pwando
 */
class makeadaptation extends object {

    private $dbsectioncontent;
    private $dbSectionNodes;
    private $objUser;
    private $dbProducts;
    private $dbCurriculums;

    function init() {
        $this->dbsectioncontent = $this->getObject('dbsectioncontent', 'oer');
        $this->dbSectionNodes = $this->getObject('dbSectionNodes', 'oer');
        $this->dbCurriculums = $this->getObject('dbcurriculums', 'oer');
        $this->objUser = $this->getObject("user", "security");
        $this->dbProducts = $this->getObject("dbProducts", "oer");
    }

    /**
     * Validates the contents of new product field values in step 1. If all are valid, these
     * are save, else the form is returned with the errors highlighted
     * @return type 
     */
    function addNewAdaptationSection() {
        $nodeid = $this->getParam("id");
        $title = $this->getParam("section_title");
        $data = array(
            'node_id' => $nodeid,
            'title' => $title,
            'content' => $this->getParam("section_content"),
            'status' => $this->getParam("status"),
            'contributedby' => $this->getParam("contributed_by"),
            'userid' => $this->objUser->userId(),
            'keywords' => $this->getParam("keywords"),
            'current_path' => $this->getParam("selectnode")
        );
        $id = $this->dbsectioncontent->addSectionContent($data);
        //Update section node title
        $nodedata = array(
            'title' => $title
        );
        $this->dbSectionNodes->updateSectionNode($nodedata, $nodeid);
        return $id;
    }

    /**
     * updates adaptation details
     * @return type 
     */
    function updateAdaptationSection() {
        $id = $this->getParam("id");
        $adaptationSectionContent = $this->dbsectioncontent->getSectionContent($id);
        $sContentId = $adaptationSectionContent["id"];
        $nodeid = $this->getParam("node_id");
        $title = $this->getParam("section_title");
        $data = array(
            //'node_id' => $this->getParam("node_id"),
            'title' => $title,
            'content' => $this->getParam("section_content"),
            'status' => $this->getParam("status"),
            'contributedby' => $this->getParam("contributed_by"),
            'userid' => $this->objUser->userId(),
            'keywords' => $this->getParam("keywords"),
            'current_path' => $this->getParam("selectnode")
        );

        $this->dbsectioncontent->updateSectionContent($data, $sContentId);
        //Update section node title
        $nodedata = array(
            'title' => $title
        );
        $this->dbSectionNodes->updateSectionNode($nodedata, $nodeid);
        return $nodeid;
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
                "groupid" => $this->getParam("group"),
                "language" => $this->getParam("language"));


            $this->dbProducts->updateOriginalProduct($data, $parentid);
            return $parentid;
        } else {
            //get thumbnail of original product
            $parentData = $this->dbProducts->getProduct($parentid);
            $thumbnail = $parentData['thumbnail'];

            $data = array(
                "title" => $this->getParam("title"),
                "alternative_title" => $this->getParam("alternative_title"),
                "author" => $this->getParam("author"),
                "othercontributors" => $this->getParam("othercontributors"),
                "publisher" => $this->getParam("publisher"),
                "keywords" => $this->getParam("keywords"),
                "institutionid" => $this->getParam("institution"),
                "groupid" => $this->getParam("group"),
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

            $adaptationId = $this->dbProducts->saveOriginalProduct($data);
            //Clone product curriculum
            $adaptationCC = $this->dbCurriculums->getCurriculum($parentid);

            if ($adaptationCC != Null) {
                $ccdata = array(
                    "product_id" => $adaptationId,
                    "title" => $adaptationCC["title"],
                    "forward" => $adaptationCC["forward"],
                    "background" => $adaptationCC["background"],
                    "introduction" => $adaptationCC["introduction"],
                    "status" => $adaptationCC["status"],
                    "deleted" => $adaptationCC["deleted"]
                );
                $ccId = $this->dbCurriculums->addCurriculum($ccdata);
            }

            //Import product sections into this adaptation
            $prodNodes = $this->dbSectionNodes->getSectionNodes($parentid);

            foreach ($prodNodes as $prodNode) {
                $nodeId = $prodNode["id"];
                $sndata = array(
                    "product_id" => $adaptationId,
                    "section_id" => $prodNode["section_id"],
                    "title" => $prodNode["title"],
                    "path" => $prodNode["path"],
                    "status" => $prodNode["status"],
                    "nodetype" => $prodNode["nodetype"],
                    "level" => $prodNode["level"],
                    "deleted" => $prodNode["deleted"]
                );

                //Create a clone section-node for this adaptation
                $secId = $this->dbSectionNodes->addSectionNode($sndata);

                //Get section content if any
                $sectionContent = $this->dbsectioncontent->getSectionContent($nodeId);
                if ($sectionContent) {
                    $data = array(
                        "node_id" => $secId,
                        "title" => $sectionContent["title"],
                        "deleted" => $sectionContent['deleted'],
                        "content" => $sectionContent["content"],
                        "status" => $sectionContent["status"],
                        "contributedby" => $sectionContent["contributedby"],
                        "userid" => $sectionContent["userid"],
                        "keywords" => $sectionContent["keywords"],
                        "current_path" => $sectionContent["current_path"]
                    );
                    $newSectionId = $this->dbsectioncontent->addSectionContent($data);
                }
            }
            return $adaptationId;
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

        $this->dbProducts->updateOriginalProduct($data, $id);
        return $id;
    }

    /**
     * Function that deletes adaptation
     *
     * @param string $id adaptation Id to be deleted
     * @return void
     */
    
    function deleteAdaptation($id) {
        //Delete adaptation curriculum
        $adaptationCC = $this->dbCurriculums->getCurriculum($id);
        if ($adaptationCC != Null) {
            $ccdata = array(
                "deleted" => 1
            );
            $this->dbCurriculums->updateCurriculum($ccdata, $adaptationCC["id"]);
        }

        //Import product sections into this adaptation
        $prodNodes = $this->dbSectionNodes->getSectionNodes($id);

        foreach ($prodNodes as $prodNode) {
            $nodeId = $prodNode["id"];
            $sndata = array(
                "deleted" => "Y"
            );
            //Delete section node
            $this->dbSectionNodes->updateSectionNode($sndata, $nodeId);

            //Get section content if any
            $sectionContent = $this->dbsectioncontent->getSectionContent($nodeId);
            //Delete section content
            if ($sectionContent) {
                $data = array(
                    "deleted" => "Y"
                );
                $this->dbsectioncontent->updateSectionContent($data, $sectionContent["id"]);
            }
        }

        //Delete the adaptation
        $this->dbProducts->deleteOriginalProduct($id);
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

        $this->dbProducts->updateOriginalProduct($data, $id);
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

        $this->dbProducts->updateOriginalProduct($data, $id);
        return $id;
    }

}

?>