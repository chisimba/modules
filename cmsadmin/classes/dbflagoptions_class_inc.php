<?php
    // security check - must be included in all scripts
    if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }
    // end security check

/**
* Data access class for the flag manager.
*
* @package cmsadmin
* @category chisimba
* @copyright AVOIR
* @license GNU GPL
* @author Charl Mert <charl.mert@gmail.com>
*/

    class dbflagoptions extends dbTable
    {

        /**
        * The user object
        *
        * @access private
        * @var object
        */
        protected $_objUser;

        /**
        * The language object
        *
        * @access private
        * @var object
        */
        protected $_objLanguage;

        /**
        * Class Constructor
        *
        * @access public
        * @return void
        */
        public function init()
        {
            try {
                parent::init('tbl_cms_flag_options');
                $this->table = 'tbl_cms_flag_options';
                $this->_objUser = & $this->getObject('user', 'security');
                $this->_objLanguage = & $this->newObject('language', 'language');
                $this->_objSysConf = $this->getObject('dbsysconfig', 'sysconfig');
            } catch (Exception $e){
                throw customException($e->getMessage());
                exit();
            }
        }



        /**
         * Method to add a template to the database
         * @param string $title The title of the new flag option
         * @param string $text The Description of the flag option
         * @param bool $published Whether flag option will be visible or not
         * @access public
         * @return flagOptionId on success and FALSE on faliure
         */
        public function addOption($title, $text, $published)
        {
            $creatorid = $this->_objUser->userId();

            $fullText = str_ireplace("<br />", " <br /> ", $body);

            $newArr = array(
                          'title' => $title ,
                          'text' => $description ,
                          'published' => $imagePath ,
                          'created' => $this->now(),
                          'created_by' => $creatorid
            );
            $newId = $this->insert($newArr);
			
            return $newId;
        }


		/**
         * Method to edit a flag option
         * @param string $id The id of the flag option to edit
         * @param string $title The title of the new flag option
         * @param string $text The Description of the flag option
         * @param bool $published Whether flag option will be visible or not
         * @access public
         * @return flagOptionId on success and FALSE on faliure
         */
        public function editOption($id, $title, $text, $published)
        {
            $creatorid = $this->_objUser->userId();

            $fullText = str_ireplace("<br />", " <br /> ", $body);

            $newArr = array(
                          'title' => $title ,
                          'text' => $description ,
                          'published' => $imagePath ,
                          'created' => $this->now(),
                          'created_by' => $creatorid
            );
            $newId = $this->update($id, $newArr);
			
            return $newId;
        }
		
		
        /**
        * Method to delete an option
        *
        * @param string $id The id of the flag option
        * @return boolean
        * @access public
        */
        public function deleteOption($id)
        {
            //Delete Option
            $result = $this->delete('id', $id);
            
            return $result;
        }


       /**
        * Method to retrieve an option record
        *
        * @param string $id 
        * @return boolean
        * @access public
        */
        public function getOption($id)
        {
            $result = $this->getAll(" WHERE id = '{$id}'");

			if (!empty($result)) {
				return FALSE;
			}
            
            return $result;
        }

		
        /**
         * Method to toggle the publish field
         *
         * @param string id The id if the flag option
         * @access public
         * @return boolean
         * @author Wesley Nitsckie
         */
        public function togglePublish($id)
        {
            $row = $this->getOption($id);

            if ($row['published'] == 1) {
                return $this->update('id', $id , array('published' => 0) );
            } else {
                return $this->update('id', $id , array('published' => 1) );
            }
        }


	}

?>
