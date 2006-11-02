<?php
/* ----------- data class extends dbTable for tbl_userparamsadmin------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* 
* Model class for writing INIFiles.The class provides data access features for administering the list
* of user parameters used in the userparams module.
* @author Prince Mbekwa
* @todo userconfig properties' set and get
* @todo module config (especially from module admin)
* @package config
*/
require_once('Config.php');
class dbuserparamsadmin extends object 
{

    /**
    * Constructor method to define the table
    */
    public $objConf = null;
    /**
     * The root object for properties read
     *
     * @access private
     * @var string
    */
    protected $_property;
    /**
     * The options value for altconfig read / write
     *
     * @access private
     * @var string
    */
    protected $_options;

    /**
     * The sysconfig object for sysconfig storage
     *
     * @access private
     * @var array
     */
    protected $_sysconfigVars;

    /**
     * The global error callback for altconfig errors
     *
     * @access public
     * @var string
    */
    public $_errorCallback;
    /**
     * Insert array()
     *
     * @var array
     */
    
    protected  $insert =array();
    /**
     * Update array holder
     *
     * @var array
     */
    
    protected $update = array();
    /**
     * sysconfig object
     *
     * @var varient
     */
    
    protected $sysconf = null;
    /**
     * Set ini directives
     *
     * @var varient
     */
    
    protected $SettingsDirective = null;
    /**
     * Holds values to be inserted into Ini
     *
     * @var string
     */
    
    protected $SettingsValue = null;
    /**
     * user object
     *
     * @var object
     */
    protected $objUser;
    /**
     * The root object for configs read
     *
     * @access private
     * @var string
    */
    protected $_root = false;
    
    /**
     * languagetext object
     *
     * @var object
     */
    public $Text;
    
    public $file;
    
    //Initialize class
    function init() {
    	    //pull in our objects
    		$this->objConf = new Config();
    		$this->sysconf = & $this->getObject('altconfig','config');
            $this->objUser = & $this->getObject("user", "security");
            $this->Text = &$this->newObject('language','language');
    		$this->file =& $this->getObject('mkdir','files');        
            
    }
     /**
     * Method to create initial IniFile config elements. This method will create 
     * blank ini params.
     * @example
     * 	   $config_container ="MAIL"/"Settings"	 
     *     $settings = array("name"=>"Bruce Banner","email"=> "hulk@angry.green.guy")
     * 	   $iniPath = "/config/"
     * 	   $iniName ="my.ini"	
     * @param string $config_container. This describes the main header section of the iniFile 
     * @param array $settings. The values that need initializing
     * @param string $iniPath. File path
     * @param string $iniName. File name
     */
    public function createConfig($config_container=false,$settings,$iniPath=false,$iniName)
    {
    	try {
	    	//define the main header setting
	   		if (isset($config_container)) {
	   			$Section =& new Config_Container("section", "Settings");
	   		}else{
	   			$Section =& new Config_Container("section", "{$config_container}");
	
	   		}
	    	
	        // create variables/values
	       if (is_array($settings)) {
	        	foreach ($settings as $key => $value) {
	        		$Section->createDirective("{$key}", "{$value}");
	        	}
	         } 
	       
		    // reassign root
			$this->objConf->setRoot($Section);
	
			// write configuration to file
			$result = $this->objConf->writeConfig("{$iniPath}"."{$iniName}", "INIFile");
			
			if ($result==false) {
				throw new Exception($this->Text('word_read_fail'));
			}
    	}catch (Exception $e){
    		$this->errorCallback($e);
    	}
    }
   
	/**
     * Method to write sysconfig Properties options.
     * For use when writing sysconfig Properties options
     *
     * @access public
     * @param PHParray $propertyValues which consists of :
     * @var string $pmodule The module code of the module owning the config item
	 * @var string $pname The name of the parameter being set, use UPPER_CASE
	 * @var string $plabel A label for the config parameter, usually a language string
	 * @var string $value The value of the config parameter
	 * @var boolean $isAdminConfigurable TRUE | FALSE Whether the parameter is admin configurable or not
     * @param string property used to set property value of incoming config string
     * $property can either be:
     * 1. PHPArray
     * 2. XML
     * @return boolean  TRUE for success / FALSE fail .
     *
     */
    public function writeProperties($mode, $userId)
    {
    	try {
	    	// set xml root element
			$this->_options = array('name' => 'userConfigSettings');
			$id=$this->getParam('id', NULL);
	        $pname = $this->getParam('pname', NULL);
	        $ptag = $this->getParam('ptag', NULL);
	
	        // if edit use update
	        if ($mode=="edit") {
	              $this->setItem($pname,$ptag);
	        				
	        }#if
	        // if add use insert
	        if ($mode=="add") {
	        	
	            $this->insert = array(
	              $pname => $ptag);
	              
	              $this->writeConfig($this->insert);
	             	
	        }#if
			
			

	    	if ($this->objConf!=TRUE) {
				throw new Exception($this->Text('word_read_fail'));
			}else{
				return true;
			}
    	}catch (Exception $e){
    		$this->errorCallback ($this->Text('word_caught_exception').$e->getMessage());
    		exit();
    	}

    }
    /**
     * Method to parse config options.
     * For use when reading configuration options
     *
     * @access protected
     * @param string $config xml file or PHPArray to parse
     * @param string $property used to set property value of incoming config string
     * $property can either be:
     * 1. PHPArray
     * 2. XML
     * @return boolean True/False result.
     *
     */
    public function readConfig($config=false,$property='PHPArray')
    {

    	try {
    		// read configuration data and get reference to root
    		 $path = $this->sysconf->getcontentBasePath();
    		 $path .=  "users/";
    		 $path .= $this->objUser->userId().'/';
    	  
			if (!file_exists($path.'userconfig_properties.ini')) {
				$values = array(
								'Google API key'=>'',
								'ICQ number'=>'',
								'Yahoo ID'=>'',
								'Skype ID'=>'',
								'MSN ID' =>'');
			  $result = $this->file->mkdirs($path);
			  if ($result==true) {
			  	$result = $this->createConfig('Settings',$values,$path,'userconfig_properties.ini');
			  }
			  
			}  		
              $this->_root =& $this->objConf->parseConfig("{$path}".'userconfig_properties.ini','IniFile');
			
    		if (PEAR::isError($this->_root)) {
    			return false;
    		}
    	    	
    		return $this->_root;
    	}catch (Exception $e)
    	{
    		 $this->errorCallback ($this->Text('word_caught_exception').$e->getMessage());
    	}

    }
    /**
     * Get all items in the ini file 
     *
     * @return array
     */
    public function getAll()
    {
    	
	    	if ($this->_root==NULL) {
	    		$this->readConfig();
	    		return $this->_root->toArray;
	    	}else{
	    		return $this->_root->toArray;
	    	}
    	
    }
    public function delete($values,$index)
    {
    	if (is_array ( $values ) ) {
     		unset ( $values['root']['Settings'][$index] );
     		array_unshift ( $values, array_shift ( $values ) );
     		$this->writeConfig($values);
    		return true;
     	}
   		else {
     		return false;
     	}
   		
    }
    /**
     * Method to wirte config options.
     * For use when writing configuration options
     *
     * @access public
     * @param string values to be saved
     * @param string property used to set property value of incoming config string
     * $property can either be:
     * 1. PHPArray
     * 2. XML
     * @return boolean  TRUE for success / FALSE fail .
     *
     */
    public function writeConfig($values,$property='IniFile')
    {
    	// set xml root element
    	try {
    		$this->_options = array('name' => 'Settings');
    		
    		// read configuration data and get reference to root
    		$path = $this->sysconf->getcontentBasePath();
    		$path .=  "users/". $this->objUser->userId().'/';
    		if ($this->_root==false) {
    			$this->readConfig();
    		}
    		
            $this->_root =& $this->objConf->parseConfig($values,'PHPArray');
			if (($path !== false) && (file_exists($path.'userconfig_properties.ini'))) {
			  	unlink($path.'userconfig_properties.ini');		
			}  		
    		$this->objConf->writeConfig("{$path}".'userconfig_properties.ini',$property, $this->_options);

    		$this->readConfig();
    		return true;
    	}catch (Exception $e)
    	{
    		 $this->errorCallback ($this->Text('word_caught_exception').$e->getMessage());
    		 exit();
    	}

    }
   
    /**
    * Method to get a system configuration parameter.
    *
    * @var string $pvalue The value code of the config item
    * @var string $pname The name of the parameter being set, use UPPER_CASE
    * @return  string $value The value of the config parameter
    */
    public function getItem($pname, $pvalue)
    {
    	try {
    			//Read conf
    			if ($this->_root==NULL) {
    				$read = $this->readConfig();
    			}
    			if ($read==FALSE) {
    				return $read;
    			}

               //Lets get the parent node section first

        		$Settings =& $this->_root->getItem("section", "Settings");
        		//Now onto the directive node
        		//check to see if one of them isset to search by
        		if(isset($pname)){
        		  $this->SettingsDirective =& $Settings->getItem("directive", "{$pname}");
        		  $pname = $this->SettingsDirective->getContent();
       			  return $pname;
        		}
        		if(isset($pvalue)){
        			$this->SettingsValue =& $Settings->getItem("directive", "{$pvalue}");
        			$pvalue = $this->SettingsValue->getContent();
       				return $pvalue;
        		}
        		

    	}catch (Exception $e){
    		$this->errorCallback ($this->Text('word_caught_exception').$e->getMessage());
    		exit();
    	}
    } #function getItem
    /**
    * Method to get a system configuration parameter.
    *
    * @var string $pvalue The value code of the config item
    * @var string $pname The name of the parameter being set, use UPPER_CASE
    * @return  string $value The value of the config parameter
    */
    public function setItem($pname, $pvalue)
    {
    	try {
    			//Read conf
    			
    			if ($this->_root==NULL) {
    				$read = $this->readConfig();
    				}
    			
    			
               //Lets get the parent node section first
				
        		$Settings =& $this->_root->getItem("section", "Settings");
        		
        		//Now onto the directive node
        		//check to see if one of them isset to search by
        		  $this->SettingsDirective =& $Settings->getItem("directive", "{$pname}");
        		  $this->SettingsDirective->setContent($pvalue);
        		  $result =$this->objConf->writeConfig();
       			  return $result;
        		
        		

    	}catch (Exception $e){
    		$this->errorCallback ($this->Text('word_caught_exception').$e->getMessage());
    		exit();
    	}
    } #function setItem
    /**
    * The error callback function, defers to configured error handler
    *
    * @param string $error
    * @return void
    * @access public
    */
    
    
    /** Added by jameel for the websearch module
    * Method to check if a configuration parameter is set
    * 
    * @var string $module The module code of the module owning the config item
    * @var string $name The name of the parameter being set
    */
    
    public function checkIfSet($pname, $userId=NULL)
    {
        
        if ($pname >= 1 && $userId >= 1 ) {
            return true;
        } else {
            return false;
       } #if
    } #function checkIfSet
    
    
    
    /** 
    * Added by jameel for the websearch module
    * Method to read a user parameter. This is the preferred
    * method for routine lookups.
    * 
    * @var string $pname the parameter name of the parameter to lookup
    * @var string $userId The userId of the user being looked up
    * @var string $defaultValue The default value for the parameter
    * @return the value of the parameter, or $defaultValue if not found
    * 
    * @todo -cdbuserparams This is very inefficient as it needs to build 
    *  all of the properties just to look up one.
    * 
    */
    
    public function getValue($pname, $userId=NULL, $defaultValue=NULL)
    {
        //IF there is no userId supplied, then it is for the current user
		if (is_null($userId)) {
		    $userId = $this->objUser->userId();
		}
        //If the properties are not set then set them
        if (!isset($this->$pname)) {
            $this->setProperties($userId);
        } 
        //If the value is set return it, else return the default
        if (isset($this->$pname)) {
            return $this->$pname;
        } else {
            return $defaultValue;
        } 
    } #function getValue

    public function errorCallback($exception)
    {
    	echo customException::cleanUp($exception);
    	exit();
    }


} #end of class
?>
