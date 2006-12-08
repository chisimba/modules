<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* Data access class for the cmsadmin module. Used to access data in the layouts table. 
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR 
* @license GNU GPL
* @author Wesley Nitsckie
* @author Warren Windvogel
*/

class dblayouts extends dbTable
{

	   /**
	    * Class Constructor
	    *
	    * @access public
	    * @return void
	    */
        public function init()
        {
        	try {                 
                parent::init('tbl_cms_layouts');
           } catch (Exception $e){
       		    echo 'Caught exception: ',  $e->getMessage();
        	    exit();
     	   }
        }

        /**
         * Method to get the layouts
         *
         * @access public
         * @return array $layouts An array associative arrays of all layouts
         */
        public function getLayouts()
        {
            $layouts = $this->getAll();
            return $layouts;
        }

        /**
         * Method to get the layout record
         * 
         * @access public
         * @param string $name The name of the layout
         * @return array $layout An associative array containing the layout details
         */
        public function getLayout($name)
        {
            $layout = $this->getRow('name', $name);
            return $layout;
        }
        /**
         * Method to get the description of a layout by referencing its name
         * 
         * @access public
         * @param string $name The name of the layout
         * @return string $description The layout description
         */
        public function getLayoutDescription($name)
        {
            $layout = $this->getRow('name', $name);
            $description = $layout['description'];
            return $description;
        }
}
?>
