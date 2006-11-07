<?
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
class block_help extends object
{
    public $title;
    
    /**
    * Constructor for the class
    */
    public function init()
    {
        $this -> objLanguage =& $this -> getObject('language', 'language');
        $this -> objInput =& $this -> getObject('textinput', 'htmlelements');
        //Set the title
        $this -> title = $this -> objLanguage -> languageText('word_instructions', 'sudoku');
    }
    
    /**
    * Method to output a block with information on how help works
    */
    public function show()
	{
        //Add the text to the output
        $ret = $this -> objLanguage -> languageText('mod_sudoku_help', 'sudoku');
        
        return $ret;
    }
}
?>