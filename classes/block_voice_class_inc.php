<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The class to display an exit link in a block
*
* @author Kevin Cyster
*/
class block_voice extends object
{
    /*
    * @var object $objLanguage: The language class in the language module
    * @access public
    */
    public $objLanguage;

    /*
    * @var object $title: The title of the block
    * @access public
    */
    public $title;

    /**
    * Constructor for the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // system classes
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText('mod_realtime_voice', 'realtime');
    }

    /**
    * Method to output a block with the exit link
    *
    * @access public
    * @return string $str: The output string
    */
    public function show()
	{
		$voiceUrl = "http://". $_SERVER['HTTP_HOST']."/".$modUri."realtime/voice";
		$realtimeUrl = "http://". $_SERVER['HTTP_HOST']."/".$modUri."realtime";
	
        $string = '      <applet code="avoir.realtime.voice.VoiceApplet.class" width="500" height="100">';
        $string .= '         <param name="archive" value="'.$voiceUrl.'/voice-client.jar"/> ';
        $string .= '         <param name="userName" value="' . $userName . '"/>';
        $string .= '         <param name="userLevel" value="' . $userLevel . '"/>';
        $string .= '         <param name="localdirectory" value="/tmp/"/>';
        $string .= '         <param name="realtimeModuleBase" value="'.$realtimeUrl.'"/>';
        $string .= "      </applet> ";
        return $string;
    }
}
?>