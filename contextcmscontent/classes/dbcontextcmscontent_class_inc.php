<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
 * The class contextcmscontent uses the context as abstracted layer and
 * this class to control the content for the context
 * 
 * @package contextcmscontent
 * @category contextcmscontent
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @version
 * @author Wesley Nitsckie 
 */

class dbcontextcmscontent extends dbTable  {
    
    /**
     * Contructor
     * 
     */
    public function init()
    {
        parent::init('tbl_contextcmscontent');
    }
    
    /**
     * Method to get sections for a given context
     * 
     * @access public
     * @return array
     * @param string $contextCode The ContextCode
     */
    public function getSections($contextCode)
    {
        try{
            
            
            $objSections = $this->newObject('dbsections', 'cmsadmin');
            
            $arrSections = $this->getAll("WHERE contextcode = '".$contextCode."'");
            
            $newArray = array();
            
            foreach ($arrSections as $sections)
            {
                $newArray[] = $objSections->getRow('id', $sections['sectionid']);
            }
            
            return $newArray;
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
    /**
     * Method to get all the pages for the context
     * @param string $contextCode The context Code
     * @return array
     * @access public
     * 
     */
    public function getContextPages($contextCode)
    {
        try{    
                $objContent = & $this->newObject('dbcontent', 'cmsadmin');
                
                $sectionArr = $this->getSections($contextCode);
                $newArray = array();
                 print '<pre>';
              //  print_r($sectionArr);
                print '</pre>';
                foreach($sectionArr as $section)
                {
                    $arr = $objContent->getAll("WHERE sectionid = '".trim($section['id'])."'");
                   
                    foreach ($arr as $ar)
                    {
                        $newArray[] = $ar;    
                    }
                    
                }
                
                return $newArray;
        }catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
        
    }
    
    /**
     * Method to get the content for a given section
     * 
     * @param string $sectionId The Section ID
     * @access public
     * @return array
     */
    public function getContentBySection($sectionId)
    {
        
        return ;
    }
    
    
    /**
     * Method to add a section for a context
     * 
     * @return boolean
     * @param string $contextCode The context Code
     * @access public
     * 
     */
    public function addSectionToContext($contextCode, $sectionId)
    {
        
        try{
            $params = array('contextcode' => $contextCode , 'sectionid' => $sectionId);
            return $this->insert($params);
        } 
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
}