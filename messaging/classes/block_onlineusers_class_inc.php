<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The class to display a help block for sudoku
*
* @author Kevin Cyster
*/
class block_onlineusers extends object
{
   /*
    * @var object $objLanguage The language class in the language module
    * @access private
    */
    public $objLanguage;

    /*
    * @var string $title The title of the block
    * @access public
    */
    public $title;

    /**
    * Constructor for the class
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        
        $this->loadClass('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        //Set the title
        $this->title = $this->objLanguage->languageText('mod_messaging_userschatting', 'messaging');
    }

    /**
    * Method to output a block with information on how help works
    */
    public function show()
	{
        $script = '<script type="text/javaScript">
            Event.observe(window, "load", init_users, false);
    
            function init_users(){
                var url = "index.php";
                var target = "listDiv";
                var pars = "module=messaging&action=getusers";
                var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars, onComplete: users_timer});
            }

            function users_timer(){
                setTimeout("init_users()", 5000);
            }
        </script>';
        $str = $script;

        $objLayer = new layer();
        $objLayer->id = 'listDiv';
        $objLayer->cssClass = 'list';
        $userLayer = $objLayer->show();
        $str .= $userLayer;
        
        return $str;
    }
}
?>