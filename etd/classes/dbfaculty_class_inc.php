<?php
/**
* dbFaculty class extends dbtable
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbFaculty class provides functionality for browsing facultys metadata via the viewbrowse class.
*
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

$this->loadClass('dbthesis', 'etd');
class dbFaculty extends dbthesis
{
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
        $sqlNorm = "SELECT DISTINCT thesis.thesis_degree_discipline as col1, count(*) as cnt, thesis.thesis_degree_discipline as id ";
        $sqlFound = "SELECT COUNT(*) AS count ";
        
        $sql = "FROM {$this->table} AS thesis, {$this->submitTable} AS submit, {$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = thesis.submitid AND dc.id = thesis.dcmetaid ";
        $sql .= "AND submit.submissiontype = '{$this->subType}' AND submit.status = 'archived' ";
        $sql .= "GROUP BY thesis.thesis_degree_discipline ";
        
        $sqlLimit = "ORDER BY thesis.thesis_degree_discipline ";
        
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
        $sqlNorm = "SELECT thesis.thesis_degree_discipline as col1, count(*) as cnt, thesis.thesis_degree_discipline as id ";
        $sqlFound = "SELECT COUNT(*) AS count ";
        
        $sql = "FROM {$this->table} AS thesis, {$this->submitTable} AS submit, {$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = thesis.submitid AND dc.id = thesis.dcmetaid ";
        $sql .= "AND submit.submissiontype = '{$this->subType}' AND submit.status = 'archived' ";
        $sql .= "AND ( LOWER(thesis.thesis_degree_discipline) LIKE '$letter%' ";
        
        if(strtolower($letter) == 'a'){
            $sql .= "AND NOT ( LOWER(thesis.thesis_degree_discipline) LIKE 'a %' OR LOWER(thesis.thesis_degree_discipline) LIKE 'an %') ";
        }
        if(strtolower($letter) == 't'){
            $sql .= "AND NOT ( LOWER(thesis.thesis_degree_discipline) LIKE 'the %') ";
        }
        
        $sql .= " OR LOWER(thesis.thesis_degree_discipline) LIKE 'the $letter%' ";
        $sql .= " OR LOWER(thesis.thesis_degree_discipline) LIKE 'a $letter%' ";
        $sql .= " OR LOWER(thesis.thesis_degree_discipline) LIKE 'an $letter%' ";
        $sql .= " OR LOWER(thesis.thesis_degree_discipline) LIKE '`n $letter%' ) ";
        
        $sql .= "GROUP BY thesis.thesis_degree_discipline ";
        $sqlLimit = "ORDER BY thesis.thesis_degree_discipline ";
        
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
    * Method to get the headings to use when displaying the search results.
    */
    function getHeading()
    {
        return array('col1' => $this->col1Header);
    }
}
?>