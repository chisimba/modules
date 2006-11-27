<?php
/**
* dbsubmissions class extends dbtable
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbsubmissions class for managing the data in the tbl_etd_submissions table.
* @author Megan Watson
* @author Jonathan Abrahams
* @copyright (c) 2004 UWC
* @version 0.2
* @modified Megan Watson 2006 10 27 Ported to chisimba framework
*/

class dbsubmissions extends dbtable
{
    /**
    * @var string $subType The type of submission - etd/other.
    * Used to distiguish the source of the document in the database table.
    */
    private $subType = 'etd';

    /**
    * @var string $metaType The type of extended metadata for the document being submitted.
    * Thesis (tbl_etd_metadata_thesis) vs qualified (tbl_etd_metadata_qualified) metadata
    */
    private $metaType = 'thesis';

    /**
    * Constructor method
    */
    public function init()
    {
        parent::init('tbl_etd_submissions');
        $this->table = 'tbl_etd_submissions';
        
        $this->dcTable = 'tbl_dublincoremetadata';
        $this->thesisTable = 'tbl_etd_metadata_thesis';
        //$this->qualifiedTable = 'tbl_etd_metadata_qualified';
        $this->embargoTable = 'tbl_etd_embargos';

        $this->xmlMeta =& $this->getObject('xmlmetadata', 'etd');
        
    }

    /**
    * Method to set the table type where the extended metadata is stored.
    *
    * @access public
    * @param string $type The table type - thesis / qualified
    * @return
    */
    public function setDocType($type = 'thesis')
    {
        $this->metaType = $type;
    }

    /**
    * Method to set the type of submission - etd/other.
    * Used to distiguish the source of the document in the database table.
    *
    * @access public
    * @param string $type The submission type
    * @return
    */
    public function setSubmitType($type)
    {
        $this->subType = $type;
    }

    /**
    * Method to update a submitted resource.
    *
    * @param string $id The Id of the resource.
    * @param string $userId The user Id for the modifier.
    * @return string $id
    */
    public function editSubmission($userId, $id = NULL)
    {
        $fields = array();

        if($id){
            $fields['modifierid'] = $userId;
            $fields['datemodified'] = date('Y-m-d H:i:s');
            $this->update('id', $id, $fields);
        }else{
            $fields['creatorid'] = $userId;
            $fields['datecreated'] = date('Y-m-d H:i:s');
            $fields['submissiontype'] = $this->subType;
            $id = $this->insert($fields);
        }
        return $id;
    }
    
    /**
    * Method to check if the user is currently in the process of submitting a document, returns the submission id
    *
    * @access public
    * @param string $userId The current user
    * @return string $submitId The users submission
    */
    public function getUserSubmission($userId)
    {
        $sql = 'SELECT id FROM '.$this->table;
        $sql .= " WHERE creatorid = '{$userId}' AND status = 'assembly'";
        
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            return $data[0]['id'];
        }
        return FALSE;
    }

    /**
    * Method to get a resource from the submissions table with metadata.
    *
    * @access public
    * @param string $id The Id of the resource.
    * @param bool $archive TRUE = get data from the xml - not archived; default is FALSE = get data from the database - archived
    */
    public function getSubmission($id)//, $archive = FALSE)
    {
        $sql = "SELECT submit.id as submitid, submit.*, extra.id AS metaid, extra.*, dc.id as dcid, dc.* FROM {$this->table} AS submit, ";
        
        if($this->metaType == 'qualified'){
            $sql .= "{$this->qualifiedTable} AS extra, ";
        }else{
            $sql .= "{$this->thesisTable} AS extra, ";
        }        
        $sql .= "{$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = extra.submitid AND dc.id = extra.dcmetaid ";
        $sql .= "AND submit.id = '{$id}'";
        
        $data = $this->getArray($sql);
                
        if(!empty($data)){
            return $data[0];
        }
        return array();
        
        
        /*
        $sqlSelect = ''; $sqlJoin = '';
        if(!$archive){
            $sqlSelect = ', extra.id AS metaId, extra.*, dc.id AS dcId, dc.* ';
            if($this->metaType == 'qualified'){
                $sqlJoin = 'LEFT JOIN '.$this->qualifiedTable.' AS extra ON etd.id = extra.submitId ';
            }else{
                $sqlJoin = 'LEFT JOIN '.$this->thesisTable.' AS extra ON etd.id = extra.submitId ';
            }
            $sqlJoin .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = extra.dcMetaId ';
        }

        $sql = 'SELECT etd.id as submitId, etd.*'.$sqlSelect.' FROM '.$this->table.' AS etd '.$sqlJoin;
        $sql .= "WHERE etd.id = '$id'";
        $data = $this->getArray($sql);
        
        $sql = 'SELECT * FROM '.$this->table.' AS etd ';
        $sql .= "WHERE etd.id = '$id'";
        $data = $this->getArray($sql);
        
        if($archive){
            $data = $this->getXmlMeta($data);
        }
        if(!empty($data)){
            return $data[0];
        }
        return array();
        */
    }

    /**
    * Method to fetch a set of resources based on given information.
    * The method can either match the info, or match similar information.
    */
    public function fetchResources($criteria = array(), $start = 0, $limit = NULL)
    {
        $sqlNorm = "SELECT * FROM {$this->table} AS submit, ";
        $sqlCount = "SELECT COUNT(*) AS count FROM {$this->table} AS submit, ";
        
        if($this->metaType == 'qualified'){
            $sql = "{$this->qualifiedTable} AS extra, ";
        }else{
            $sql = "{$this->thesisTable} AS extra, ";
        }        
        $sql .= "{$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = extra.submitid AND dc.id = extra.dcmetaid ";
        $sql .= "AND submissiontype = '{$this->subType}' AND status = 'archived' ";
        
        if(!empty($criteria)){
            $critSql = '';
            foreach($criteria as $item){
                if(!empty($critSql)){
                    $critSql .= ' OR ';
                }
                $critSql .= "{$item['field']} {$item['compare']} '{$item['value']}'";
            }
            $sql .= " AND ($critSql) ";
        }
            
        if(!is_null($limit)){
            $sql .= " LIMIT $limit";
        }
        //$offset = "LIMIT 10 OFFSET $start";
        
        $data = $this->getArray($sqlNorm.$sql);//.$offset);
        $count = $this->getArray($sqlCount.$sql);
        
        return array($data, $count[0]['count']);
        
        /*
        $sqlNorm = 'SELECT * ';
        $sqlNorm .= 'FROM '.$this->table.' AS submit ';

        $sqlCount = 'SELECT COUNT(*) as count FROM '.$this->table.' AS submit ';

        //$sql = $this->getSession('filter', NULL);
        
        //if(is_null($sql)){
        
            if($this->metaType == 'qualified'){
                $sql .= 'LEFT JOIN '.$this->qualifiedTable.' AS extra ON submit.id = extra.submitId ';
            }else{
                $sql .= 'LEFT JOIN '.$this->thesisTable.' AS extra ON submit.id = extra.submitId ';
            }
            
            $sql .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = extra.dcMetaId ';
            $sql .= "WHERE submissionType = '{$this->subType}' AND status = 'archived' ";
            
            if(!empty($criteria)){
                $critSql = '';
                foreach($criteria as $item){
                    if(!empty($critSql)){
                        $critSql .= ' OR ';
                    }
                    $critSql .= $item['field'].' '.$item['compare']." '".$item['value']."'";
                }
                $sql .= " AND ($critSql) ";
            }
            
            if(!is_null($limit)){
                $sql .= " LIMIT $limit";
            }
        //}

        //$this->setSession('filter', $sql);

        $offset = "LIMIT 10 OFFSET $start";

        $data = $this->getArray($sqlNorm.$sql.$offset);

        $count = $this->getArray($sqlCount.$sql);

        */

    }

    /**
    * Method to get a list of non-archived submissions by status
    *
    * @access public
    * @param
    * @return array $data The submissions
    */
    public function getNewSubmissions()
    {
        $sql = 'SELECT * FROM '.$this->table;
        $sql .= " WHERE status != 'archived' ";
        $sql .= " AND submissiontype = '{$this->subType}' ";
        
        $data = $this->getArray($sql);
        
        // For each submission get the related xml file
        if(!empty($data)){
            foreach($data as $key => $item){
                $xml = $this->getXmlMeta($item['id']);
                $newdata = array_merge($data[$key], $xml);
                $data[$key] = $newdata;
            }
        }
        
        return $data;
    }

    /**
    * Method to get the xml files for a submission
    *
    * @access private
    * @param string $submitId The submission id
    * @return array The xml data
    */
    private function getXmlMeta($submitId)
    {
        $xmlData = array();
        $xml = $this->xmlMeta->openXML($this->subType.'_'.$submitId);
        if(!empty($xml)){
            $xmlData = array_merge($xml['metadata']['dublincore'], $xml['metadata'][$this->metaType]);
        }
        return $xmlData;
    }

    /**
    * Method to delete a resource
    *
    * @access public
    * @param string $id The id of the submission to delete
    * @return
    */
    public function deleteSubmission($id)
    {
        $this->delete('id', $id);
    }


/* ** Methods below have not yet been ported ** */

    /**
    * Method to insert a new etd into the database.
    * @param string $userId The user Id for the creator.
    * @param string $studentId The user Id for the student.
    */
    function addETD($userId, $studentId = NULL)
    {
        $fields = array();
        $fields['authorId'] = $studentId;
        $fields['submissionType'] = $this->subType;
        $fields['creatorId'] = $userId;
        $fields['dateCreated'] = date('Y-m-d H:i:s');
        $id = $this->insert($fields);
        return $id;
    }

    /**
    * Method to add the student's user id if etd is entered by a manager.
    * @param string $id The Id of the ETD.
    * @param string $studentId The user Id for the student.
    */
    function setStudentId($id, $studentId)
    {
        $fields = array();
        $fields['authorId'] = $studentId;
        $this->update('id', $id, $fields);
        return $id;
    }

    /**
    * Method to change the status on an ETD.
    * @param string $id The Id of the ETD.
    * @param string $userId The user Id for the modifier.
    * @param string $status The status of the submission. Assembly, pending approval, awaiting metadata, archived.
    */
    function changeStatus($id, $userId, $status)
    {
        $fields['status'] = $status;
        $fields['modifierId'] = $userId;
        $fields['dateModified'] = date('Y-m-d H:i:s');
        $this->update('id', $id, $fields);
        return $id;
    }

    /**
    * Method to get the ETD submission data (no metadata) for checking status or approval level.
    */
    function getETDStatus($id)
    {
        $sql = 'SELECT * FROM '.$this->table;
        $sql .= " WHERE id = '$id'";
        $data = $this->getArray($sql);

        if(!empty($data)){
            return $data[0];
        }
        return FALSE;
    }
       
    /**
    * Method to get the comment count on a resource
    */
    function getCommentCount($id)
    {
        $sql = 'SELECT commentCount FROM '.$this->table;
        $sql .= " WHERE id = '$id'";
        $data = $this->getArray($sql);

        if(!empty($data)){
            return $data[0]['commentCount'];
        }
        return 0;
    }
    
    /**
    * Method to get the access level of a resource
    */
    function getAccessLevel($id)
    {
        $sql = 'SELECT accessLevel FROM '.$this->table;
        $sql .= " WHERE id = '$id'";
        $data = $this->getArray($sql);

        if(!empty($data)){
            return $data[0]['accessLevel'];
        }
        return 0;
    }

    /**
    * Method to set the level of approval on an ETD.
    * @param string $id The Id of the ETD.
    * @param string $userId The user Id for the modifier.
    * @param string $level The level of approval.
    * @param string $access The level of access - private = user only; public = available; protected = metadata is available, file is hidden
    * @return string $id
    */
    function changeApproval($id, $userId, $level, $status, $access = 'private')
    {
        $fields['approvalLevel'] = $level;
        $fields['status'] = $status;
        $fields['accessLevel'] = $access;
        $fields['modifierId'] = $userId;
        $fields['dateModified'] = date('Y-m-d H:i:s');
        $this->update('id', $id, $fields);
        return $id;
    }

    /**
    * Method to set the access level of an ETD.
    * @param string $id The Id of the ETD.
    * @param string $userId The user Id for the modifier.
    * @param string $access The level of access - private = user only; public = available; protected = metadata is available, file is hidden
    * @return string $id
    */
    function changeAccess($id, $userId, $access)
    {
        $fields['accessLevel'] = $access;
        $fields['modifierId'] = $userId;
        $fields['dateModified'] = date('Y-m-d H:i:s');
        $this->update('id', $id, $fields);
        return $id;
    }

    /**
    * Method to get a user's submissions.
    * @param string $userId The user Id for the user.
    * @param string $status The status of the requested submissions.
    */
    function getUserEtd($userId, $status=NULL)
    {
        $sqlSelect = ''; $sqlJoin = '';
        if($status == 'archived'){
            $sqlSelect = ', extra.id as mdId, extra.*, dc.* ';
            if($this->metaType == 'qualified'){
                $sqlJoin = 'LEFT JOIN '.$this->qualifiedTable.' AS extra ON etd.id = extra.submitId ';
            }else{
                $sqlJoin = 'LEFT JOIN '.$this->thesisTable.' AS extra ON etd.id = extra.submitId ';
            }
            $sqlJoin .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = extra.dcMetaId ';
        }

        $sql = 'SELECT etd.id as submitId, embargo.id as embId, etd.*, embargo.* '.$sqlSelect;
        $sql .= 'FROM '.$this->table.' AS etd '.$sqlJoin;
        $sql .= 'LEFT JOIN '.$this->embargoTable.' AS embargo ON etd.id = embargo.submissionId ';
        $sql .= "WHERE submissionType = '{$this->subType}' AND (";
        $sql .= "(etd.creatorId = '$userId' OR etd.authorId = '$userId')";

        if($status){
            $sql .= " AND etd.status = '$status'";
        }else{
            $sql .= " AND etd.status != 'archived'";
        }
        $sql .= ')';

        $data = $this->getArray($sql);

        if($status != 'archived'){
            $data = $this->getXmlMeta($data);
        }
        return $data;
    }

    /**
    * Method to get submissions dependent on their status.
    * The method also checks the approval level of the submissions if required.
    * @param string $status The requested status of the submissions.
    * @param string $level The approval level of the submissions.
    */
    function getEtdByStatus($status, $level = NULL)
    {
        $sqlSelect = ''; $sqlJoin = '';
        if($status == 'archived'){
            $sqlSelect = ', extra.id as mdId, extra.*, dc.* ';
            if($this->metaType == 'qualified'){
                $sqlJoin = 'LEFT JOIN '.$this->qualifiedTable.' AS extra ON etd.id = extra.submitId ';
            }else{
                $sqlJoin = 'LEFT JOIN '.$this->thesisTable.' AS extra ON etd.id = extra.submitId ';
            }
            $sqlJoin .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = extra.dcMetaId ';
        }

        $sql = 'SELECT etd.id as submitId, etd.* '.$sqlSelect;
        $sql .= ' FROM '.$this->table.' AS etd '.$sqlJoin;
        $sql .= " WHERE  submissionType = '{$this->subType}' AND (etd.status = '$status'";

        if(!is_null($level)){
            $sql .= " AND etd.approvalLevel = '$level'";
        }
        $sql .= ')';

        $data = $this->getArray($sql);

        if($status != 'archived'){
            $data = $this->getXmlMeta($data);
        }
        return $data;
    }

    /**
    * Method to fetch an ETD based on given information.
    * The method can either match the info, or match similar information.
    */
    function fetchETD($author, $title, $student, $department, $start = 0, $compare = '=')
    {
        $sqlNorm = 'SELECT thesis.id, dc.id AS dcId, etd.authorId as col3, dc.dc_title as col1, ';
        $sqlNorm .= 'dc.dc_creator as col2, thesis.thesis_degree_discipline as col4 ';
        $sqlNorm .= 'FROM '.$this->table.' AS etd ';

        $sqlCount = 'SELECT COUNT(*) as count FROM '.$this->table.' AS etd ';

        $sql = $this->getSession('filter', NULL);

        if(is_null($sql)){
            $sql = 'LEFT JOIN '.$this->thesisTable.' AS thesis ON etd.id = thesis.submitId ';
            $sql .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = thesis.dcMetaId ';
            $sql .= "WHERE submissionType = '{$this->subType}' AND (dc.dc_creator $compare '$author' ";
            $sql .= "AND dc.dc_title $compare '$title' ";
            $sql .= "AND etd.authorId $compare '$student' ";
            $sql .= "AND thesis.thesis_degree_discipline $compare '$department' ";
            $sql .= ") ORDER BY dc.dc_title LIMIT 10";
        }

        $this->setSession('filter', $sql);

        $offset = " OFFSET $start";

        $data = $this->getArray($sqlNorm.$sql.$offset);

        $count = $this->getArray($sqlCount.$sql);

        return array($data, $count[0]['count']);
    }

    /**
    * Method to return the total number of archived submissions
    *
    * @access publice
    * @return int $count
    */
    function getCount()
    {
        $sql = 'SELECT count(*) AS cnt FROM '.$this->table;
        $sql .= " WHERE submissionType = '".$this->subType."' and status = 'archived'";
        
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0]['cnt'];
        }
        return 0;
    }    
}
?>