<?php

class latestpoll extends object
{

    public function init()
    {
		$this->loadClass('link', 'htmlelements');
		$this->loadClass('form', 'htmlelements');
		$this->loadClass('radio', 'htmlelements');
		$this->loadClass('button', 'htmlelements');
        
        $this->objPolls = $this->getObject('dbnewspolls');
        $this->objPollOptions = $this->getObject('dbnewspollsoptions');
        
        $this->objLanguage = $this->getObject('language', 'language');
    }
    
    public function show()
    {
        $str = '<h3>'.$this->objLanguage->languageText('mod_news_latestpoll', 'news', 'Latest Poll').'</h3>';
        
        $latestPoll = $this->objPolls->getLatestPoll();
        
        if ($latestPoll == FALSE) {
            $str .= '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_news_noactivepolls', 'news', 'No Active Polls').'</div>';
        } else {
            
            $this->appendArrayVar('headerParams', '
<script type="text/javascript">
//<![CDATA[
    function submitpoll()
    {
        var input = document.getElementsByTagName("input");
        var selected = "selected";
        var results = [];
        for ( var i = 0; i < input.length; i++ ) {
            // Find all the fields that have the specified name
            if ( input[i].name == \'poll\' ) {
                if ( input[i].checked ) {
                    selected = input[i].value;
                }
            }
        }
        
        if (selected == "selected") {
            alert("Please Choose an Option");
        } else {
            saveResult(selected);
        }
    }

    function saveResult(vote)
    {
        var url = \'index.php\';
        var pars = \'module=news&action=savevote&vote=\'+vote;
        var myAjax = new Ajax.Request( url, {method: \'get\', parameters: pars, onComplete: showPollResponse} );
    }

    function showPollResponse (originalRequest) {
        var newData = originalRequest.responseText;
        $(\'poll\').innerHTML = newData;
        adjustLayout();
    }

//]]>
</script>
');
            
            $str .= nl2br('<p>'.htmlentities($latestPoll['pollquestion']).'</p>');
            
            $pollOptions = $this->objPollOptions->getPollOptions($latestPoll['id']);
            
            $radio = new radio ('poll');
            $radio->setBreakSpace('<br />');
            foreach ($pollOptions as $option)
            {
                $radio->addOption($option['id'], ' - '.htmlentities($option['optionstr']));
            }
            
            $str .= '<div id="poll"><form name="pollform" id="pollform">'.$radio->show().'</form>';
            
            $button = new button('save', $this->objLanguage->languageText('mod_news_castvote', 'news', 'Cast Vote'));
            $button->setOnClick('submitpoll();');
            
            $str .= '<p>'.$button->show().'</p></div>';
            
        }
        
        return $str;
    }


    

    
    

}
?>