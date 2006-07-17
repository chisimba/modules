<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The chat block class.
* @author Jeremy O'Connor
*/
class simplechat extends object
{
    private $objLanguage;
       
    public function init()
    {
        $this->objLanguage =& $this->getObject('language','language');
    }
    public function configure($width,$height,$room=NULL)
    {
        $this->width = $width;
        $this->height = $height;
        $this->room = $room;
    }
    public function show()
	{
    	$this->loadClass("iframe","htmlelements");
    	$iframe = new iframe();
    	$iframe->name = "simplechat";
    	$iframe->width = "$this->width";
    	$iframe->height = "$this->height";
        if ($this->room == NULL) {
            $uri = $this->uri(array(
                'mode'=>'compact'
    		), 'chat');
        }
        else {
            $uri = $this->uri(array(
                'mode'=>'compact',
    			'context'=>$this->room
    		), 'chat');
        }
    	$iframe->src = $uri;    		
    	$str = $iframe->show();
        return $str;
    }
}
?>