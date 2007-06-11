<?php
/**
* dbThesis class extends dbtable
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbThesis class for managing the data in the tbl_etd_metadata_thesis table.
* Class provides functionality for browsing metadata via the viewbrowse class.
*
* @author Megan Watson
* @author Jonathan Abrahams
* @copyright (c) 2005 UWC
* @version 0.2
* @modified by Megan Watson on 2006 11 04 ported to 5ive/chisimba
*/

class dbThesis extends dbtable
{
    /**
    * @var string Property to store the first column field used by the browse object
    */
    protected $col1Field = '';

    /**
    * @var string Property to store the second column field used by the browse object
    */
    protected $col2Field = '';

    /**
    * @var string Property to store the third column field used by the browse object
    */
    protected $col3Field = '';

    /**
    * @var string Property to store the first column table heading used by the browse object
    */
    protected $col1Header = '';

    /**
    * @var string Property to store the second column table heading used by the browse object
    */
    protected $col2Header = '';

    /**
    * @var string Property to store the third column table heading used by the browse object
    */
    protected $col3Header = '';

    /**
    * @var string Property to store the search title used by the browse object
    */
    public $type = '';

    /**
    * @var string Property to store the object type used by the browse object
    */
    public $_browseType = '';

    /**
    * @var string Property to set the submission type - distinguish between etd's and other documents.
    */
    protected $subType = 'etd';
    
    /**
    * @var string Property to show the number of records returned in a result set
    */
    public $recordsFound = 0;

    /**
    * Constructor method
    */
    public function init()
    {
        parent::init('tbl_etd_metadata_thesis');
        $this->table = 'tbl_etd_metadata_thesis';
        $this->dcTable = 'tbl_dublincoremetadata';
        $this->submitTable = 'tbl_etd_submissions';
        $this->bridgeTable = 'tbl_etd_collection_submission';

        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objUser =& $this->getObject('user', 'security');

        // Default browse type
        $this->setBrowseType( 'author' );
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
    * Method to insert thesis metadata
    *
    * @access public
    * @param array $fields The fields and values to add / update
    * @param string $id The table row to update if exists
    * @return string $id
    */
    public function insertMetadata($fields, $id = NULL)
    {
        if($id){
            $fields['modifierid'] = $this->objUser->userId();
            $fields['datemodified'] = $this->now();
            $fields['updated'] = $this->now();
            $this->update('id', $id, $fields);
        }else{
            $fields['creatorid'] = $this->objUser->userId();
            $fields['datecreated'] = $this->now();
            $fields['updated'] = $this->now();
            $id = $this->insert($fields);
        }
        return $id;
    }

    /**
    * Method to delete thesis metadata
    *
    * @access public
    * @param string $id The table row to delete
    * @return
    */
    public function deleteMetadata($id)
    {
        $this->delete('id', $id);
    }

    /**
    * Method to get resource metadata
    *
    * @access public
    * @param string $id The table row to fetch
    * @return array The resource
    */
    public function getMetadata($id)
    {
        $sql = "SELECT dc.id AS dcId, thesis.id AS thesisId, thesis.*, dc.* ";
        $sql .= "FROM {$this->table} AS thesis, {$this->dcTable} AS dc ";
        $sql .= "WHERE dc.id = thesis.dcMetaId AND thesis.id = '$id'";
        
        $data = $this->getArray($sql);
        return $data[0];
    }

    /**
    * Method to get metadata records limited to a given number of results.
    *
    * @access public
    * @param string $limit The number of metadata records to return. Default = 10 results.
    * @param string $start The metadata record for the result set to start from. Default = Null, return from the start.
    * @return array Metadata result set
    */
    public function getData($limit = 10, $start = NULL, $joinId = NULL)
    {
        $sqlNorm = "SELECT dc.{$this->col1Field} as col1, dc.{$this->col2Field} as col2, dc.{$this->col3Field} as col3, thesis.id as id ";
        $sqlFound = "SELECT COUNT(*) AS count ";
        
        $sql = "FROM {$this->table} AS thesis, {$this->submitTable} AS submit, {$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = thesis.submitid AND dc.id = thesis.dcmetaid ";
        $sql .= "AND submit.submissiontype = '{$this->subType}' AND submit.status = 'archived' ";
        
        $sqlLimit = "ORDER BY LOWER({$this->col1Field}) ";
        
        $sqlLimit .= $limit ? "LIMIT $limit " : NULL;
        $sqlLimit .= $start ? "OFFSET $start " : NULL;
        
        // Get result set
        $data = $this->getArray($sqlNorm.$sql.$sqlLimit);
        
        // Get number of results
        $data2 = $this->getArray($sqlFound.$sql);
        if(!empty($data2)){
            $this->recordsFound = $data2[0]['count'];
        }
        
        return $data;
    }

    /**
    * Method to get metadata records by letter.
    *
    * @access public
    * @param string $letter The letter to display. Default = a.
    * @param string $limit The number of metadata records to return. Default = 10 results.
    * @param string $start The metadata record for the result set to start from. Default = Null, return from the start.
    * @return array Metadata result set
    */
    public function getByLetter($letter = 'a', $limit = 10, $start = NULL, $joinId = NULL)
    {
        $letter = strtolower($letter);
        $sqlNorm = "SELECT dc.{$this->col1Field} as col1, dc.{$this->col2Field} as col2, dc.{$this->col3Field} as col3, thesis.id as id ";
        $sqlFound = "SELECT COUNT(*) AS count ";
        
        $sql = "FROM {$this->table} AS thesis, {$this->submitTable} AS submit, {$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = thesis.submitid AND dc.id = thesis.dcmetaid ";
        $sql .= "AND submit.submissiontype = '{$this->subType}' AND submit.status = 'archived' ";
        $sql .= "AND ( LOWER({$this->col1Field}) LIKE '$letter%' ";
        
        if(strtolower($letter) == 'a'){
            $sql .= "AND NOT ( LOWER({$this->col1Field}) LIKE 'a %' OR LOWER({$this->col1Field}) LIKE 'an %') ";
        }
        if(strtolower($letter) == 't'){
            $sql .= "AND NOT ( LOWER({$this->col1Field}) LIKE 'the %') ";
        }
        
        $sql .= " OR LOWER({$this->col1Field}) LIKE 'the $letter%' ";
        $sql .= " OR LOWER({$this->col1Field}) LIKE 'a $letter%' ";
        $sql .= " OR LOWER({$this->col1Field}) LIKE 'an $letter%' ";
        $sql .= " OR LOWER({$this->col1Field}) LIKE '`n $letter%' ) ";
        
        $sqlLimit = "ORDER BY LOWER({$this->col1Field}) ";
        
        $sqlLimit .= $limit ? "LIMIT $limit " : NULL;
        $sqlLimit .= $start ? "OFFSET $start " : NULL;
                
        // Get result set
        $data = $this->getArray($sqlNorm.$sql.$sqlLimit);
                
        // Get number of results
        $data2 = $this->getArray($sqlFound.$sql);
        if(!empty($data2)){
            $this->recordsFound = $data2[0]['count'];
        }
        
        return $data;
    }

    /**
    * Method to set the type of browse mode, and set the fields to be used for the search
    *
    * @access public
    * @param string $type The type of object to be used by the browse class
    */
    public function setBrowseType( $type = NULL )
    {

        switch ( $type ) {
            case 'author':
                $this->col1Field = 'dc_creator';
                $this->col2Field = 'dc_title';
                $this->col3Field = 'dc_date';
                $this->col1Header = $this->objLanguage->languageText('word_author');
                $this->col2Header = $this->objLanguage->languageText('word_title');
                $this->col3Header = $this->objLanguage->languageText('word_year');
                $this->type = $this->objLanguage->languageText('word_authors');
                break;
            case 'title':
                $this->col1Field = 'dc_title';
                $this->col2Field = 'dc_creator';
                $this->col3Field = 'dc_date';
                $this->col1Header = $this->objLanguage->languageText('word_title');
                $this->col2Header = $this->objLanguage->languageText('word_author');
                $this->col3Header = $this->objLanguage->languageText('word_year');
                $this->type = $this->objLanguage->languageText('word_titles');
                break;
            case 'faculty':
                $this->col1Field = 'thesis_degree_discipline';
                $this->col2Field = 'dc_creator';
                $this->col3Field = 'dc_date';
                $this->col1Header = $this->objLanguage->languageText('word_faculty');
                $this->col2Header = $this->objLanguage->languageText('word_author');
                $this->col3Header = $this->objLanguage->languageText('word_year');
                $this->type = $this->objLanguage->languageText('word_titles');
                break;
            default :
                $type = 'author';
                $this->col1Field = 'dc_creator';
                $this->col2Field = 'dc_title';
                $this->col1Header = $this->objLanguage->languageText('word_author');
                $this->col2Header = $this->objLanguage->languageText('word_title');
                $this->col3Header = '';
                $this->type = $this->objLanguage->languageText('word_authors');
        }
        // Set object property
        $this->_browseType = $type;
    }

    /**
    * Method to get the headings to use when displaying the search results.
    *
    * @access public
    * @return array Column headings
    */
    public function getHeading()
    {
        return array('col1' => $this->col1Header, 'col2' => $this->col2Header, 'col3' => $this->col3Header);
    }

    /**
    * Method to execute a search using a given filter - used by the simple and advanced searches.
    *
    * @access public
    * @param string $filter The search criteria.
    * @param string $limit The limit on the results returned.
    * @return array The result set and count
    */
    function search($filter, $limit = NULL, $start = 0)
    {
        $sqlNorm = 'SELECT thesis.id AS id, dc.dc_creator AS col2, dc.dc_title AS col1, dc.dc_date AS col3 ';
        $sqlCount = 'SELECT COUNT(*) AS count ';
        
        $sql = "FROM {$this->table} AS thesis, {$this->dcTable} AS dc, {$this->submitTable} AS submit ";
        $sql .= "WHERE thesis.dcmetaid = dc.id AND thesis.submitid = submit.id ";
        $sql .= "AND submit.submissiontype = '{$this->subType}' AND ({$filter}) ";
        
        $sqlLimit = "ORDER BY dc_date ";
        $sqlLimit .= $limit ? " LIMIT $limit OFFSET $start" : '';
        
        $data = $this->getArray($sqlNorm.$sql.$sqlLimit);
        
        $count = 0;
        $data2 = $this->getArray($sqlCount.$sql);
        if(!empty($data2)){
            $count = $data2[0]['count'];
        }

        return array($data, $count);
    }

    /**
    * Method to get a list of submissions.
    *
    * @access public
    * @return array Submissions list
    */
    public function getAllMeta()
    {
        $sql = 'SELECT dc_title, dc_creator, dc_subject, dc_identifier, thesis.id AS metaid ';
        $sql .= "FROM {$this->table} AS thesis, {$this->dcTable} AS dc ";
        $sql .= "WHERE dc.id = thesis.dcmetaid ";
        $sql .= "ORDER BY dc_date";
        $data = $this->getArray($sql);

        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }
    
    /**
    * Method to execute a search using a given filter - used by external searches.
    *
    * @access public
    * @param string $filter The search criteria.
    * @param string $limit The limit on the results returned.
    * @return array The result set and count
    */
    function search2($keyword)
    {
        $sqlNorm = 'SELECT thesis.id AS id, dc.*, thesis.* ';
        $sqlCount = 'SELECT count(*) AS cnt ';
        
        $sql = "FROM {$this->table} AS thesis, {$this->dcTable} AS dc, {$this->submitTable} AS submit 
                WHERE thesis.dcmetaid = dc.id AND thesis.submitid = submit.id 
                AND submit.submissiontype = '{$this->subType}' 
                AND (dc.dc_creator LIKE '%$keyword%' OR dc.dc_title LIKE '%$keyword%'
                OR dc.dc_subject LIKE '%$keyword%') ";
                
        $sqlOrder = "ORDER BY dc.dc_date ";
        
//        echo $sqlNorm.$sql.$sqlOrder;
        
        $data = $this->getArray($sqlNorm.$sql.$sqlOrder);
        $data2 = $this->getArray($sqlCount.$sql);
        $count = isset($data2[0]['cnt']) ? $data2[0]['cnt'] : 0;
        
        return array($data, $count);
    }



/* *** functions below have not been ported *** */

    /**
    * Method to get etd metadata
    */
    function getSubmitMetadata($submitId)
    {
        $sql = 'SELECT dc.id AS dcId, thesis.id AS thesisId, thesis.*, dc.* FROM '.$this->table.' AS thesis ';
        $sql .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = thesis.dcMetaId ';
        $sql .= "WHERE thesis.submitId = '$submitId'";

        $data = $this->getArray($sql);
        return $data[0];
    }

    /**
    * Method to update the thesis metadata.
    */
    function updateMetaData($id)
    {
        $degree = $this->getParam('degree', NULL);
        $dept = $this->getParam('department', NULL);
        $grant = $this->getParam('grantor');

        $fields = array();
        if(!is_null($degree)){
            $fields['thesis_degree_name'] = $degree;
            $fields['thesis_degree_level'] = $degree;
        }
        if(!is_null($dept)){
            $fields['thesis_degree_discipline'] = $dept;
        }
        if(!is_null($grant)){
            $fields['thesis_degree_grantor'] = $grant;
        }

        $this->update('id', $id, $fields);
        return $id;
    }

    /**
    * Method to get a list of submissions in a collection.
    */
    function getMetaInCollection($collectId)
    {
        
        //SELECT dc.dc_title, dc.dc_creator, thesis.thesis_degree_name FROM tbl_etd_metadata_thesis as thesis, tbl_dublincoremetadata as dc, tbl_etd_submissions as sub WHERE sub.id = 'init_3724_1161983558'
        
        $sql = 'SELECT dc_title, dc_creator, dc_subject, thesis.submitId ';
        $sql .= 'FROM '.$this->table.' AS thesis ';
        $sql .= 'LEFT JOIN '.$this->bridgeTable.' AS bridge ON bridge.submissionId = thesis.submitId ';
        $sql .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = thesis.submitId ';
        $sql .= "WHERE bridge.collectionId = '$collectId'";
        
        
        $data = $this->getArray($sql);

        if(!empty($data)){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to get metadata records using search data.
    * @param string $search The search data to use.
    * @param string $limit The number of metadata records to return. Default = 10 results.
    * @param string $start The metadata record for the result set to start from. Default = Null, return from the start.
    */
    function getBySearch($search, $limit = 10, $start = NULL, $joinId = NULL)
    {
        $sql = "SELECT DISTINCT dc.{$this->col1Field} as col1, dc.{$this->col2Field} as col2, dc.{$this->col3Field} as col3, thesis.id";
        $sql .= ' FROM '.$this->table.' AS thesis ';
        $sqljoin = 'LEFT JOIN '.$this->submitTable.' AS submit ON submit.id = thesis.submitId ';
        $sqljoin .= 'LEFT JOIN '.$this->dcTable.' AS dc ON dc.id = thesis.dcMetaId ';

        $join = ''; $joinFilter = '';
        if(isset($joinId) && !empty($joinId)){
            $join = ' LEFT JOIN '.$this->bridgeTable.' AS bridge ON bridge.submissionId = thesis.submitId';
            $joinFilter = " AND bridge.collectionId = '$joinId'";
        }

        $filter = " WHERE submissionType = '{$this->subType}' AND (";
        $filter .= "LOWER({$this->col1Field}) LIKE '%$search%' ";
        $filter .= " AND status = 'archived' ";
        $filter .= $joinFilter.')';

        $orderBy = " ORDER by LOWER({$this->col1Field})";
        $sqlLimit = $limit ? " LIMIT $limit" : NULL;
        $sqlLimit.= $start ? " OFFSET $start" : NULL;
        $results = $this->getArray( $sql.$sqljoin.$join.$filter.$orderBy.$sqlLimit );

        // Get bounds
        $sqlFound = "SELECT DISTINCT COUNT(*) as found FROM ".$this->table.' AS thesis ';
        $row = $this->getArray( $sqlFound.$sqljoin.$join.$filter );
        $this->recordsFound = $row[0]['found'];
        return $results;
    }
}
?>