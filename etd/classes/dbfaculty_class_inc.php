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
    * Class constructor extends dbthesis constructor
    */
    public function init()
    {
        parent::init();
        
        $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
        $this->loadClass('htmltable', 'htmlelements'); 
        $this->loadClass('link', 'htmlelements');
    }
    
    /**
    * Method to display a list of faculties
    *
    * @access public
    * @return string html
    */
    public function listFaculties()
    {
        $data = $this->getData('');
        //echo '<pre>'; print_r($data);
        
        $head = $this->objLanguage->languageText('word_faculties');
        $lbNoFaculty = $this->objLanguage->languageText('mod_etd_nofacultiesavailable', 'etd');
        
        $str = '<br />';
        if(!empty($data)){
            $class = 'even';
            $objTable = new htmltable();
            $i = 1;
            foreach($data as $item){
                $class = ($class == 'even') ? 'odd' : 'even';
                
                $objLink = new link($this->uri(array('action' => 'viewfaculty', 'id' => $item['id'])));
                $objLink->link = $item['col1'];
                $name = $objLink->show();
                
                $objTable->addRow(array($i++, $name), $class);
            }
            $str .= $objTable->show();
        }else{
            $str = '<p class="noRecordsMessage">'.$lbNoFaculty.'</p>';
        }
        
        return $this->objFeatureBox->showContent($head, $str);
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
        $sqlNorm = "SELECT DISTINCT thesis.thesis_degree_faculty as col1, count(*) as cnt, thesis.thesis_degree_faculty as id ";
        $sqlFound = "SELECT COUNT(*) AS count ";
        
        $sql = "FROM {$this->table} AS thesis, {$this->submitTable} AS submit, {$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = thesis.submitid AND dc.id = thesis.dcmetaid AND thesis.thesis_degree_faculty != '' ";
        $sql .= "AND submit.submissiontype = '{$this->subType}' AND submit.status = 'archived' ";
        $sql .= "GROUP BY thesis.thesis_degree_faculty ";
        
        $sqlLimit = "ORDER BY thesis.thesis_degree_faculty ";
        
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
        $sqlNorm = "SELECT thesis.thesis_degree_faculty as col1, count(*) as cnt, thesis.thesis_degree_faculty as id ";
        $sqlFound = "SELECT COUNT(*) AS count ";
        
        $sql = "FROM {$this->table} AS thesis, {$this->submitTable} AS submit, {$this->dcTable} AS dc ";
        
        $sql .= "WHERE submit.id = thesis.submitid AND dc.id = thesis.dcmetaid AND thesis.thesis_degree_faculty != '' ";
        $sql .= "AND submit.submissiontype = '{$this->subType}' AND submit.status = 'archived' ";
        $sql .= "AND ( LOWER(thesis.thesis_degree_faculty) LIKE '$letter%' ";
        
        if(strtolower($letter) == 'a'){
            $sql .= "AND NOT ( LOWER(thesis.thesis_degree_faculty) LIKE 'a %' OR LOWER(thesis.thesis_degree_faculty) LIKE 'an %') ";
        }
        if(strtolower($letter) == 't'){
            $sql .= "AND NOT ( LOWER(thesis.thesis_degree_faculty) LIKE 'the %') ";
        }
        
        $sql .= " OR LOWER(thesis.thesis_degree_faculty) LIKE 'the $letter%' ";
        $sql .= " OR LOWER(thesis.thesis_degree_faculty) LIKE 'a $letter%' ";
        $sql .= " OR LOWER(thesis.thesis_degree_faculty) LIKE 'an $letter%' ";
        $sql .= " OR LOWER(thesis.thesis_degree_faculty) LIKE '`n $letter%' ) ";
        
        $sql .= "GROUP BY thesis.thesis_degree_faculty ";
        $sqlLimit = "ORDER BY thesis.thesis_degree_faculty ";
        
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