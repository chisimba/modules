<?php

/**
 * Class session containing the wrapped Alfresco API methods
 *
 * Used to utilize Alfresco API
 *
 * @author Warren Windvogel <warren.windvogel@wits.ac.za>
 *
 * @copyright Wits University 2010
 * @license http://opensource.org/licenses/lgpl-2.1.php
 *
 */

//Include API classes
require_once "Alfresco/Service/Repository.php";
require_once "Alfresco/Service/Session.php";
require_once "Alfresco/Service/SpacesStore.php";
require_once "Alfresco/Service/Node.php";

class alfrescoapi extends object {

   /** @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;

   /**
    * @var object $objConfig: The sys config class in the config module
    * @access public
    */
    public $objConfig;

   /**
    * @var object $objNode: The node class in the Alfresco PHP API
    * @access public
    */
    public $objNode;

   /**
    * @var object $objSession: The session class in the Alfresco PHP API
    * @access public
    */
    public $objSession;

   /**
    * @var object $objRepository: The repository class in the Alfresco PHP API
    * @access public
    */
    public $objRepository;

   /** @var object $objAlfAdminWsdl: The Alfresco administration webservice object
    * @access public
    */
    public $objAlfAdminWsdl;

   /** @var object $objAlfWebservices: The Alfresco webservices object
    * @access public
    */
    public $objAlfAdminWebservices;

   /**
    * @var array $session: The Alfresco API path
    * @access public
    */
    public $apiPath;

   /**
    * @var array $session: The Alfresco user username
    * @access public
    */
    public $username;

   /**
    * @var array $password: The Alfresco user password
    * @access public
    */
    public $password;

   /**
    * @var array $companyHome: The Alfresco company home
    * @access public
    */
    public $companyHome;


    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // system classes
        $this->objLanguage = $this->getObject('language','language');
        $this->objConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objContext = $this->getObject('dbcontext', 'context');

        //Get the Alfresco API details
        $apiPath = $this->objConfig->getValue('mod_alfresco_apiurl', 'alfresco');
        $username = $this->objConfig->getValue('mod_alfresco_adminuser', 'alfresco');
        $password = $this->objConfig->getValue('mod_alfresco_adminpw', 'alfresco');
        
        //Afresco API
        $this->objRepository = new Repository($apiPath);
        //Login
        $this->ticket = $this->authenticate($username, $password);
        $this->objSession = $this->createSession($this->ticket);
        
        //Initiate the Alfresco webservice object
        $adminApiPath = $apiPath.'/AdministrationService?wsdl';
        $this->objAlfAdminWebservices = new AlfrescoWebService($adminApiPath, array('trace' => true, 'exceptions' => true), $this->ticket);
        //Spaces store object
        $this->objSpacesStore = new SpacesStore($this->objSession);
        //Store object
        $this->objStore = new Store($this->objSession, "SpacesStore");
        //Get the root node
        $rootNode = $this->objStore->getRootNode();
        $rootNodeId = $this->objStore->rootNode->id;
        $storeAddress = $this->objStore->address;
        $store = $this->objSession->getStore($storeAddress, $this->ticket);
        //Initiate the node object
        $this->objNode = new Node($this->objSession, $store, $rootNodeId);

    }

    /**
     * Method to authenticate a user
     *
     * @param string $username The users username
     * @param string $password THe users password
     * @access public
     * @return mixed $ticket The authentication ticket
     */
    public function authenticate($username, $password)
    {
        $this->ticket = $this->objRepository->authenticate($username, $password);

        return $this->ticket;
    }

    /**
     * Method to start a session
     *
     * @param string $ticket The authentication ticket
     * @access public
     * @return mixed $result
     */
    public function createSession($ticket = NULL)
    {
        if(is_null($ticket) && isset($_SESSION['ticket'])){
            $ticket = $_SESSION['ticket'];
        }
        $this->objSession = $this->objRepository->createSession($ticket);

        return $this->objSession;
    }

    /**
     * Method to authenticate a user and create a session
     *
     * @param string $username The users username
     * @param string $password THe users password
     * @access public
     * @return mixed $result
     */
    public function login($username, $password)
    {
        //Authenticate the user
        $this->ticket = $this->authenticate($username, $password);
        //Start a session
        $this->objSession = $this->createSession($this->ticket);

        return $this->objSession;
    }

    /**
     * Method to get the administration object
     *
     * @param string $path The Alfresco API path
     * @access public
     * @return object $objAlfAdmin
     */
     public function getAlfAdminWsdl($path = NULL)
     {
         //Get the API path from sys vars
         if(is_null($path) || $path == ""){
             $path = $this->objConfig->getValue('mod_alfresco_apiurl', 'alfresco');
         }
         //Get the ticket
         if(is_null($this->ticket) || $this->ticket == ""){
             $tick = $this->authenticate('admin', 'overdrive');
         } else {
             $tick = $this->ticket;
         }
         $this->objAlfAdminWsdl = WebServiceFactory::getAdministrationService($path, $tick);

         return $this->objAlfAdminWsdl;
     }

    /**
     * Method to get a users details
     *
     * @param string $username The users Alfresco username
     * @access public
     * @return array $details The users details
     */
    public function getUserDetails($username)
    {
         $response = $this->objAlfAdminWebservices->__call('getUser', array(array('userName'=>$username)));

         return $response;
    }

    /**
     * Method to change a users password
     *
     * @param string $username The users username
     * @param string $old_password The users old password
     * @param string $new_password The new password requested
     * @access public
     * @return mixed $response The webservice response
     */
     public function changePassword($username, $old_password, $new_password)
     {
         $response = $this->objAlfAdminWebservices->__call('changePassword', array(array('userName'=>$username, 'oldPassword'=>$old_password, 'newPassword'=>$new_password)));

         return $response;
     }
     /**
      * Method to create a new user
      *
      * @param array $userDetails An array containing the users details
      * @access public
      * @return mixed $response The webservice response
      */
     public function createUser($username, $password)
     {
         //Create the user details array
         $userDetails = array();
         $userDetails[] = array('userName'=>$username, 'password'=>$password);
         //Webservice call
         $response = $this->objAlfAdminWebservices->__call('createUsers', array(array('newUsers'=>$userDetails)));
         //var_dump($response);
         //Return the webservice response
         return $response;
     }

     /**
      * Method to get the root node
      *
      * @access public
      * @return $rootNode The root node
      */
     public function getRootNode()
     {
         $rootNode = $this->objStore->getRootNode();

         return $rootNode;
     }

    /**
     * Method to return the company home
     *
     * @access public
     * @return $companyHome The company home
     */
    public function getCompanyHome()
    {
   	   if ($this->companyHome == null)
   	   {
   	   	  $nodes = $this->objSession->query($this->objStore, 'PATH:"app:company_home"');
	           $this->companyHome = $nodes[0];
   	   }
   	   return $this->companyHome;
    }

    /**
     * Method to create a node
     *
     * @param string $nodeName The name of the folder
     * @access public
     * @return object $nodeDetails The new node object
     */
     public function createChildFolder($nodeName, $nodeTitle = NULL, $nodeDescription = NULL)
     {
         //Node name
         $name = 'cm_'.$nodeName;
         //Create the child node
         $nodeDetails = $this->getCompanyHome()->createChild('cm_folder', 'cm_contains', $name);
         //Set node name
         $nodeDetails->cm_name = $nodeName;
         //Set node title
         if(!is_null($nodeTitle)){
             $nodeDetails->cm_title = $nodeTitle;
         }
         //Set node desciption
         if(!is_null($nodeDescription)){
             $nodeDetails->cm_description = $nodeDescription;
         }
         //Save node
         $result = $this->objSession->save();
         //Add check to see if everything went well
         if($result == ""){

         }
         return $nodeDetails;
     }

    /**
     * Method to create a node
     *
     * @param string $nodeName The name of the folder
     * @access public
     * @return object $nodeDetails The new node object
     */
     public function createChildContent($nodeName, $nodeTitle = NULL, $nodeDescription = NULL, $file = NULL)
     {
         //Node name
         $name = 'cm_'.$nodeName;
         //Create the child node
         $nodeDetails = $this->getCompanyHome()->createChild('cm_content', 'cm_contains', $name);
         //Set node name
         $nodeDetails->cm_name = $nodeName;
         //Set node title
         if(!is_null($nodeTitle)){
             $nodeDetails->cm_title = $nodeTitle;
         }
         //Set node desciption
         if(!is_null($nodeDescription)){
             $nodeDetails->cm_description = $nodeDescription;
         }
         if(!is_null($file)){
             // Set the content onto the standard content property for nodes of type cm:content.
	     // We are going to assume the mimetype and encoding for ease
             $file_info = new finfo(FILEINFO_MIME);	// object oriented approach!
             $mime_type = $file_info->buffer(file_get_contents($file));
             var_dump($mime_type);
             $contentData = $nodeDetails->updateContent("cm_content", $mime_type, "UTF-8");

             // Set the content to be the content file uploaded from the client
             $contentData->writeContentFromFile($file);
         }
         
         //Save node
         $result = $this->objSession->save();
         //Add check to see if everything went well
         if($result == ""){

         }
         return $nodeDetails;
     }
     
     /**
      * Method to get the content download URL
      * @param object $node The node object
      * @return string $url The download URL
      */
    public function getURL($node)
    {
      global $path;

      $result = null;
      if ($node->type == "{http://www.alfresco.org/model/content/1.0}content")
      {
      	 $contentData = $node->cm_content;
      	 if ($contentData != null)
      	 {
         	$result = $contentData->getUrl();
      	 }
      }
      else
      {
         $result = "index.php?".
                     "&uuid=".$node->id.
                     "&name=".$node->cm_name.
                     "&path=".$path;
      }

      return $result;
    }

}
?>