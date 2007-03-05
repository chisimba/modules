<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The class to display a block for smiley icons
*
* @author Kevin Cyster
*/
class block_smileys extends object
{
    /*
    * @var object $objIcon: The geticon class in the htmlelements module
    * @access private
    */
    private $objIcon;

    /*
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;

    /*
    * @var string $title: The title of the block
    * @access public
    */
    public $title;

    /*
    * @var array $shortList: An associated array containg the smileys name and code
    * @access private
    */
    private $shortList;
    
    /**
    * Constructor for the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // load html element classes
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->objIcon = $this->getObject('geticon', 'htmlelements');
        
        // system classes
        $this->objLanguage = $this->getObject('language', 'language');
        
        // language items
        $title = $this->objLanguage->languageText('mod_messaging_wordsmileys', 'messaging');
        $label = $this->objLanguage->languageText('mod_messaging_smileys', 'messaging');  
        $help = $this->objLanguage->languageText('mod_messaging_helpclick', 'messaging');
        
        // help icon
        $this->objIcon->setIcon('help_small');
        $this->objIcon->align = 'top';
        $this->objIcon->title = $help;
        $this->objIcon->extra = ' onclick="alert(\''.$label.'\')"';
        $helpIcon = '<a href="#">'.$this->objIcon->show().'</a>';
        
        // title
        $this->title = $title.'&nbsp;'.$helpIcon;        

        // smiley icon array
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
    * Method to output a block with smiley icons
    *
    * @access public
    * @return string $str: The output string
    */
    public function show()
	{
        // javascript
        $script = '<script type="text/javaScript">
            var namelist = new Array("angry", "cool", "evil", "exclamation", "big_grin", "question", "raspberry", "sad", "smile", "wink");
            
            var codelist = new Array("[>:-(]", "[B-)]", "[}:-)]", "[!]", "[:-D]", "[?]", "[:-P]", "[:-(]", "[:-)]", "[;-)]");
            
            function addSmiley(elementId)
            {
                var msg = document.getElementById("input_message");
                for(i = 0; i <= namelist.length-1; i++){
                    if(namelist[i] == elementId){
                        if(msg.value == ""){
                            msg.value = codelist[i];
                        }else{
                            msg.value = msg.value + " " + codelist[i];
                        }
                    }
                }
                msg.focus();
            }
        </script>';
        echo $script;

        // language items
        $moreLabel = $this->objLanguage->languageText('mod_messaging_wordmore', 'messaging');
        $moreTitleLabel = $this->objLanguage->languageText('mod_messaging_smileytitle', 'messaging');
        
        // popup link for more smiley icons
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

        // main table
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
                
                $objTable->addCell('<div id="'.$smiley.'" style="cursor: pointer;" onclick="addSmiley(this.id)">'.$icon.'</div>', '', '', '', '', '');
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