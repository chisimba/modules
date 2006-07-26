<?php
/* ----------- wrapper for ogg vorbis / theora player applet ------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* 
* 
* @author Derek Keats
* 
*/
class buildplayer extends object
{

    /**
    * 
    * @var string $codeBase The codebase for the Java applet
    * 
    */
    var $codeBase;

    /**
    * 
    * @var string $soundFile A fully qualified URL to the sound file
    * 
    */
    var $soundFile;
    
    /**
    * 
    * @var string object $objConfig A string to hold the config object
    * 
    */
    var $objConfig;
 

    /**
    * 
    * Constructor method to instantiate the database and 
    * user objects. 
    * 
    */
    function init() 
    {
        
        //Set up the path for the error file
        $this->objConfig = &$this->getObject('altconfig', 'config');
        //Load the sound file from the URL in the querystring 
        $this->loadSound();
        
    }
    
    /**
    * 
    * Method to render the music player
    * @access Public
    * @return The player applet code
    * 
    */
    function show()
    {
        if (!$this->soundFile=="") {
            return $this->__startApplet()
              . $this->__getParam("CODE")
              . $this->__getParam("CODEBASE")
              . $this->__getParam("ARCHIVE")
              . $this->__getParam("NAME")
              . $this->__getParam("TYPE")
              . $this->__getParam("SCRIPTABLE")
              . $this->__getParam("SKIN")
              . $this->__getParam("START")
              . $this->__getParam("SONG")
              . $this->__getParam("INIT")
              . $this->__getParam("LOCATION")
              . $this->__getParam("USERAGENT")
              . $this->__endApplet();
        } else {
            return "err";
        }

    }
    
    /**
    * 
    * Method to load the sound from the querystrng or 
    * a form submission.
    * 
    * @return True It always returns true
    * 
    */
    function loadSound()
    {
        $errFile = $this->objConfig->getsiteRoot()."modules/soundplayer/resources/sounds/error.ogg";
        
        //Get the sound file from the query string, get error file if none
        $sndFile = $this->getParam('sndfile', $errFile);
        if ($this->__isValidFile($sndFile)) {
            $this->soundFile = $sndFile;
        } else {
            $this->soundFile = $errFile;
        }
        return TRUE;
    }
    
    /**
    * Method to Set the Sound File
    */
    function setSoundFile($file)
    {
        $errFile = $this->objConfig->getsiteRoot()."modules/soundplayer/resources/sounds/error.ogg";
        
        if ($this->__isValidFile($file)) {
            $this->soundFile = $file;
        }
        
        return TRUE;
    }
    
    
    /*-------------------- PRIVATE METHODS ----------------------------------*/
    
    /**
    * 
    * Method to return the APPLET tag with all its content
    * 
    * @return The APPLET part of the tag
    * @access Private
    * 
    */
    function __startApplet()
    {
        return "<APPLET CODE = \"javazoom.jlgui.player.amp.PlayerApplet\" 
    	  JAVA_CODEBASE = \"modules/soundplayer/resources/lib/\" 
    	  ARCHIVE = \"jlguiapplet2.3.2.jar,jlgui2.3.2-light.jar,
    	  tritonus_share.jar,basicplayer2.3.jar, mp3spi1.9.2.jar,
    	  jl1.0.jar, vorbisspi1.0.1.jar, jorbis-0.0.13.jar,
    	  jogg-0.0.7.jar, commons-logging-api.jar\" 
    	  WIDTH = \"285\" HEIGHT = \"348\" NAME = \"player\">";
    }
    
    /**
    * 
    * Method to return the /APPLET closing tag
    * 
    * @return The /APPLET part of the tag
    * @access Private
    * 
    */
    function __endApplet()
    {
        return "</APPLET>";
    }
    
    /**
    * 
    * Method to set one of the APPLET parameters
    * 
    * @return The <PARAM tag for the parameter
    * @access Private
    * 
    */
    function __getParam($paramName)
    {
        switch ($paramName) {
            case NULL:
                return NULL;
                break;
            case "CODE":
                return "<PARAM NAME = \"CODE\" "
                  . "VALUE = \"javazoom.jlgui.player.amp.PlayerApplet\" />\n";
                break;
            case "CODEBASE":
                return "<PARAM NAME = \"CODEBASE\" "
                  . "VALUE = \"modules/soundplayer/resources/lib/\" />\n";
                break;
            case "ARCHIVE":
                return "<PARAM NAME = \"ARCHIVE\" "
                  . "VALUE = \"jlguiapplet2.3.2.jar, "
	              . "jlgui2.3.2-light.jar, tritonus_share.jar, basicplayer2.3.jar, "
	              . "mp3spi1.9.2.jar, jl1.0.jar,vorbisspi1.0.1.jar, jorbis-0.0.13.jar, "
	              . "jogg-0.0.7.jar, commons-logging-api.jar\" />\n";
                break;
            case "NAME":
                return "<PARAM NAME = \"NAME\" VALUE = \"player\" />\n";
                break;
            case "TYPE":
                return "<PARAM NAME=\"type\" "
                  . "VALUE=\"application/x-java-applet;version=1.4\" />";
                break;
            case "SCRIPTABLE":
                return "<PARAM NAME=\"scriptable\" VALUE=\"true\" />";
                break;
            case "SKIN":
                $defaultSkin = $this->objConfig->getsiteRoot()."modules/soundplayer/resources/skins/wa021.wsz";
                $skin = $this->getParam('skin', $defaultSkin);
                return "<PARAM NAME = \"skin\" "
                  . "VALUE =\"" . $skin . "\" />\n";
                break;
            case "START":
                $start = $this->getParam('start', 'no');
                return "<PARAM NAME = \"start\" VALUE =\"" . $start . "\" />\n";
                break;
            case "SONG":
                return "<PARAM NAME = \"song\" "
                  . "VALUE =\"" . $this->soundFile . "\" />\n";
                break;
            case "INIT":
                $initFile = $this->objConfig->getsiteRoot()."modules/soundplayer/resources/jlgui.ini";
                return "<PARAM NAME = \"init\" VALUE =\"" . $initFile . "\" />\n";
                break;
            case "LOCATION":
                return "<PARAM NAME = \"location\" VALUE =\"url\" />\n";
                break;
            case "USERAGENT":
                return "<PARAM NAME = \"useragent\" VALUE =\"winampMPEG/2.7\" />\n";
                break;
            default:
                return NULL;
                break;
        }
    }
    
    
    /**
    * 
    * Method to validate the file
    * 
    * @param string $theFile The file to be evaluated
    * @return True|False depending on whether the file is valid or not
    * @access Private
    * 
    * @todo -c Implement .make it actually work. Currently it just returns true.
    * 
    */
    function __isValidFile($theFile)
    {
        //Reverse any conversion of htmlentities
        $theFile = $this->__unhtmlentities($theFile);
        if ($this->__isUrl($theFile)) {
            return TRUE;
        } else {
            return TRUE;
        }
        
    }
    
    
    /**
    * 
    * Method to test if the file is a valid URL
    * 
    * @param string $theFile The file to be evaluated
    * @return True|False depending on whether the file is a valid Url or not
    * @access Private
    * 
    */
    function __isUrl($url) {
        if (!preg_match('#^http\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $url)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
    * 
    * Method to reverse htmlentities for validating URL
    * 
    * @param string $str The string to reverse htmlentities for
    * @return string The reversed string
    * 
    */
    function __unhtmlentities($str)
    {
    	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
    	$trans_tbl = array_flip ($trans_tbl);
    	return strtr ($str, $trans_tbl);
    }
} #end of class
?>