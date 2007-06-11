<?php
/**
* dbDegrees class extends dbTable
* @package etd
* @filesource
*/

/**
* Class for accessing the table listing the degrees, departments and faculties in a university
* @author Megan Watson
* @copyright (c) 2006 University of the Western Cape
* @version 0.1
*/

class dbDegrees extends dbTable
{
    /**
    * Constructor for the class
    *
    * @access public
    * @return void
    */
    public function init()
    {
        parent::init('tbl_etd_degrees');
        $this->table = 'tbl_etd_degrees';
                        
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        
        $this->loadClass('dropdown', 'htmlelements');
    }
    
    /**
    * Method to add a new degree or faculty.
    *
    * @access private
    * @return void
    */
    public function addItem($name, $type, $id = NULL)
    {    
        $fields = array();
        $fields['name'] = $name;
        $fields['type'] = $type;
        
        if(isset($id) && !empty($id)){
            $fields['modifierid'] = $this->objUser->userId();
            $fields['updated'] = date('Y-m-d H:i:s');
            $this->update('id', $id, $fields);
        }else{
            $fields['creatorid'] = $this->objUser->userId();
            $fields['datecreated'] = date('Y-m-d H:i:s');
            $fields['updated'] = date('Y-m-d H:i:s');
            $id = $this->insert($fields);
        }
        return $id;
    }
    
    /**
    * Method to get all degrees / faculties
    *
    * @access private
    * @return array The list of degrees / faculties
    */
    public function getList($type = 'faculty')
    {
        $sql = "SELECT * FROM {$this->table} WHERE type = '{$type}' ORDER BY name";
        
        return $this->getArray($sql);
    }
    
    /**
    * Method to remove a degree / faculty from the list
    *
    * @access public
    * @return void
    */
    public function deleteItem($id)
    {
        $this->delete('id', $id);
    }
    
    /**
    * Method to return a dropdown list of faculties / departments
    *
    * @access public
    * @return string html
    */
    public function getDropList($type = 'faculty', $select = '')
    {
        $data = $this->getList($type);
        
        $objDrop = new dropdown($type);
        
        if(!empty($data)){
            foreach($data as $item){
                $value = htmlentities($item['name']);
                $objDrop->addOption($value, $value);
            }
        }
        if($type == 'department'){
            $objDrop->addOption('', ' --- ');
        }
        $objDrop->setSelected($select);
        
        return $objDrop->show();
    }
}
?>