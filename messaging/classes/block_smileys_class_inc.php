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
class block_smileys extends object
{
    /*
    * @var object $objLanguage The language class in the language module
    * @access private
    */
    private $objLanguage;

    /*
    * @var object $objIcon The geticon class in the htmlelements module
    * @access private
    */
    private $objIcon;

    /*
    * @var string $title The title of the block
    * @access public
    */
    public $title;

    /*
    * @var array $shortList An associated array containg the smileys name and code
    * @access static
    */
    public $shortList;
    
    /**
    * Constructor for the class
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objIcon = $this->getObject('geticon', 'htmlelements');
        
        $title = $this->objLanguage->languageText('mod_messaging_wordsmileys', 'messaging');
        $label = $this->objLanguage->languageText('mod_messaging_smileys', 'messaging');  
        $help = $this->objLanguage->languageText('mod_messaging_helpclick', 'messaging');
        
        $this->objIcon->setIcon('help_small');
        $this->objIcon->align = 'top';
        $this->objIcon->title = $help;
        $this->objIcon->extra = ' onclick="alert(\''.$label.'\')"';
        $helpIcon = '<a href="#">'.$this->objIcon->show().'</a>';
        
        $this->title = $title.'&nbsp;'.$helpIcon;
        
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->shortList = array(
            'angry' => '[ >:-( ]',
            'cool' => '[ B-) ]',
            'evil' => '[ }:-) ]',
            'exclamation' => '[ ! ]',
            'big_grin' => '[ :-D ]',
            'question' => '[ ? ]',
            'raspberry' => '[ :-P ]',
            'sad' => '[ :-( ]',
            'smile' => '[ :-) ]',
            'wink' => '[ ;-) ]',
        );
    }

    /**
    * Method to output a block with information on how help works
    */
    public function show()
	{
        $script = '<script type="text/javaScript">
            var namelist = new Array("angry", "cool", "evil", "exclamation", "big_grin", "question", "raspberry", "sad", "smile", "wink");
            
            var codelist = new Array("[>:-(]", "[B-)]", "[}:-)]", "[!]", "[:-D]", "[?]", "[:-P]", "[:-(]", "[:-)]", "[;-)]");
            
            Event.observe(window, "load", init_smiley, false);
        
            function init_smiley(){
                for(var i = 0; i <= namelist.length-1; i++){
                    Event.observe(namelist[i], "click", addSmiley, false);                    
                }
            }
            
            function addSmiley()
            {
                var element = document.getElementById("input_chat");
                for(i = 0; i <= namelist.length-1; i++){
                    if(namelist[i] == this.id){
                        if(element.value == ""){
                            element.value = codelist[i];
                        }else{
                            element.value = element.value + " " + codelist[i];
                        }
                    }
                }
            }
        </script>';
        echo $script;

        $moreLabel = $this->objLanguage->languageText('mod_messaging_wordmore', 'messaging');
        $moreTitleLabel = $this->objLanguage->languageText('mod_messaging_smileytitle', 'messaging');
        
        $objPopup = new windowpop();
        $objPopup->title = $moreTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action'=>'moresmileys',
        )));
        $objPopup->set('linktext','[...'.$moreLabel.'...]');
        $objPopup->set('width','600');
        $objPopup->set('height','500');
        $objPopup->set('left','100');
        $objPopup->set('top','100');
        $objPopup->set('scrollbars', 'yes');
        $objPopup->putJs(); // you only need to do this once per page
        $morePopup = $objPopup->show();

        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $array = array_chunk($this->shortList, '2', TRUE);
        foreach($array as $line){
            $objTable->startRow();
            foreach($line as $smiley => $code){
                $this->objIcon->setIcon($smiley, 'gif', 'icons/smileys/');
                $this->objIcon->title = '';
                $this->objIcon->extra = '';
                $icon = $this->objIcon->show();
                
                $objTable->addCell('<div id="'.$smiley.'" style="cursor: pointer;">'.$icon.'</div>', '', '', '', '', '');
                $objTable->addCell('<nobr><font class="warning"><b>'.$code.'</b></font></nobr>', '', '', '', '', '');
            }
            $objTable->endRow();
        }
        $objTable->startRow();
        $objTable->addCell($morePopup, '', '', 'center', '', 'colspan="4"');
        $objTable->endRow();
        $smileyTable = $objTable->show();
        $str = $smileyTable;  
              
        return $str;
    }
}
?>