<?php
/**
* dbIntro class extends dbtable
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbIntro class for managing the data in the tbl_etd_intro table.
* @author Megan Watson
* @copyright (c) 2004 UWC
* @version 0.2
*/

class dbIntro extends dbtable
{
    /**
    * Constructor method
    */
    public function init()
    {
        parent::init('tbl_etd_intro');
        $this->table = 'tbl_etd_intro';
    }

    /**
    * Method to insert a new introduction into the database.
    *
    * @access public
    * @param string $userId The user Id for the creator.
    * @param string $id The Id for the introduction entry.
    * @return string The row id
    */
    public function addIntro($userId, $id = NULL)
    {
        $fields = array();
        $fields['language'] = $this->getParam('language', 'en');
        $fields['introduction'] = $this->getParam('introduction');
        $fields['updated'] = $this->now();
        if(!empty($id)){
            $fields['modifierid'] = $userId;
            $id = $this->update('id', $id, $fields);
        }else{
            $fields['creatorid'] = $userId;
            $fields['datecreated'] = $this->now();
            $id = $this->insert($fields);
        }
        return $id;
    }

    /**
    * Method to get the introduction by language.
    * @param string $lang The language given.
    */
    function getIntro($lang = 'en')
    {
        $sql = 'SELECT * FROM '.$this->table;
        if(!empty($lang)){
            $sql .= " WHERE language = '$lang'";
        }
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            return $data[0];
        }
        
        if($lang != 'en'){
            return $this->getIntro('en');
        }
        
        return '';
    }
    
    /**
    * Method to get the introduction text. Parsed.
    *
    * @access public
    * @return string
    */
    function getParsedIntro()
    {
        $lang = ''; $text = '';
        $data = $this->getIntro($lang);
        if(isset($data['introduction'])){
            $text = $data['introduction'];
        }
        return $this->parseIntro($text);
    }
    
    /**
    * Method to parse the introduction text for keywords.
    *
    * @access public
    * @param string $text The text to be parsed
    * @return string
    */
    function parseIntro($text = '')
    {
        $objConfig = $this->getObject('altconfig', 'config');
        $institution = $objConfig->getinstitutionName();
        $shortname = $objConfig->getinstitutionShortName();
        
        if(empty($text)){
            $objLanguage = $this->getObject('language', 'language');
            return $objLanguage->code2Txt('mod_etd_welcomeintro', 'etd', array('institution' => $institution, 'shortname' => $shortname));
        }
        
        $text = str_replace('[-institution-]', $institution, $text);
        $text = str_replace('[-shortname-]', $shortname, $text);
        return $text;
    }

    /**
    * Method to delete an introduction entry.
    */
    function deleteIntro($id)
    {
        $this->delete('id', $id);
    }
}
?>