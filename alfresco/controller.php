<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * The alfresco controller
 *
 * @author Warren Windvogel <warren.windvogel@wits.ac.za>
 * @copyright Wits University 2010
 * @license http://opensource.org/licenses/lgpl-2.1.php
 * @package alfresco
 */
class alfresco extends controller {

    /**
     * @var object $objLanguage: The language class in the language module
     * @access private
     */
    private $objLangauge;

    /**
     * @var object $objAlfrescoDisplay: The alfresco display class
     * @access public
     */
     public $objAlfrescoDisplay;

    /**
     * @var object $objAuthentication: The authentication object
     * @access private
     */
     private $objAuthentication;

    /**
     * @var object $objAlfrescoApi: The alfresco API object
     * @access private
     */
     private $objAlfrescoApi;

   /**
    * Method to initialise the controller
    *
    * @access public
    * @return void
    */
    public function init()
    {
         //Load the language class
         $this->objLangauge = $this->getObject('language', 'language');
         //Load the display class
         $this->objAlfrescoDisplay = $this->getObject('alfrescodisplay', 'alfresco');
         //Load the authentication object
         //$this->objAuthentication = $this->getObject('baseauth', 'alfresco');
         //Load the alfresco user admin object
         $this->objAlfrescoApi = $this->getObject('alfrescoapi', 'alfresco');

    }

    /**
    * Method the engine uses to kickstart the module
    *
    * @access public
    * @param string $action: The action to be performed
    * @return void
    */
    public function dispatch( $action )
    {
        switch($action){
            //For testing
            case 'getstores':
                $content = $this->objAlfrescoApi->createChildContent('WarrenPic', 'Mypic', 'My weddingpics for testing', '/var/www/vre/usrfiles/users/1/110_0326.JPG');
                $this->setVarByRef('templateContent', $content);
                return 'display_tpl.php';
            break;
            //For testing
            case 'login':
                $username = $this->getParam('username');
                $password = $this->getParam('password');
                $content = $this->objAlfrescoApi->login($username, $password);
                $this->setVarByRef('templateContent', $content);
                return 'display_tpl.php';
            break;
            //Display alfresco in iFrame
            case 'alfresco':
            default:
                $content = $this->objAlfrescoDisplay->displayiframe('680', '800');
                $this->setVarByRef('templateContent', $content);
                return 'display_tpl.php';
            break;
            //For testing
            case 'createuser':
                $username = $this->getParam('username');
                $password = $this->getParam('password');
                $result = $this->objAlfrescoApi->createUser($username, $password);
                $this->setVarByRef('templateContent', $result);
                return 'display_tpl.php';
            break;
            //For testing
            case 'getuser':
                $username = $this->getParam('username');
                $result = $this->objAlfrescoApi->getUserDetails($username);
                var_dump($result);
                $this->setVarByRef('templateContent', $result);
                return 'display_tpl.php';
            break;
            //Display list of Alfresco content to copy link to content into Chisimba content
            case 'showcontent':
                $currentNode = $this->objAlfrescoApi->getCompanyHome();
                $content = $this->objAlfrescoDisplay->outputTable('Alfresco Content', $currentNode);
                $this->setVarByRef('templateContent', $content);
                return 'display_tpl.php';
            break;
            //For testing
            case 'changepw':
                $content = $this->objAlfrescoApi->changePassword('admin', '', '');
                $this->setVarByRef('templateContent', $content);
                return 'display_tpl.php';
            break;

        }
    }

}
?>