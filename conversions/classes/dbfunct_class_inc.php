<?php
/**
 * provides methods for accessing database table tbl_conversions.
 *
 * @author     Nonhlanhla Gangeni <2539399@uwc.ac.za>
 * @package    convertions
 * @copyright  UWC 2007
 * @filesource
 */
class dbconv extends dbTable
{

    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @return void  
     * @access public
     */
    public function init() 
    {
        // initialize the table
        parent::init('tbl_conversions');
    }

    /**
     * Short description for public
     * 
     * Long description (if any) ...
     * 
     * @param  unknown $id Parameter description (if any) ...
     * @return string  Return description (if any) ...
     * @access public 
     */
    public function getConversion($id) 
    {
        $sql = "WHERE id = '$id'";
        return $this->getAll($sql);
    }
}
?>
