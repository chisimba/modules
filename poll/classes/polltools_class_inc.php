<?php
/**
* polltools class extends object
* @package poll
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* polltools class
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class polltools extends object
{   
    /**
    * @var string $contextCode The current context .. to do.
    * @access private
    */
    private $contextCode = 'root';
    
    /**
    * Constructor method
    */
    public function init()
    {
        try {
            $this->dbQuestions = $this->getObject('dbquestions', 'poll');
            $this->dbPoll = $this->getObject('dbpoll', 'poll');
            
            $this->objUser = $this->getObject('user', 'security');
            $this->objLanguage = $this->getObject('language', 'language');
            
            $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
            $this->objIcon = $this->newObject('geticon', 'htmlelements');
            $this->loadClass('htmlheading', 'htmlelements');
            $this->loadClass('htmltable', 'htmlelements');
            $this->loadClass('link', 'htmlelements');
            $this->loadClass('textarea', 'htmlelements');
            $this->loadClass('textinput', 'htmlelements');
            $this->loadClass('label', 'htmlelements');
            $this->loadClass('radio', 'htmlelements');
            $this->loadClass('button', 'htmlelements');
            $this->loadClass('form', 'htmlelements');
        } catch (Exception $e){
            throw customException($e->getMessage());
            exit();
        }
    }
    
    /**
    * Method to display the left side menu
    *
    * @access public
    * @return string html
    */
    public function leftMenu()
    {
        $pollBlock = $this->getPollBlock();
        $configBlock = $this->getConfigBlock();
        
        $lbPoll = $this->objLanguage->languageText('word_poll');
        $hdConfig = $this->objLanguage->languageText('word_configuration');
        
        $str = $this->objFeatureBox->show($lbPoll, $pollBlock);
        $str .= $this->objFeatureBox->show($hdConfig, $configBlock);
        
        return $str;
    }
    
    /**
    * Method to display a form for adding or editing a poll
    *
    * @access public
    * @param array The data for editing
    * @return string html
    */
    public function showAdd($data = NULL)
    {
        $hdAdd = $this->objLanguage->languageText('phrase_createnewpoll');
        $lbQuestion = $this->objLanguage->languageText('word_question');
        $lbType = $this->objLanguage->languageText('phrase_questiontype');
        $lbYesNo = $this->objLanguage->languageText('mod_poll_yesno', 'poll');
        $lbBool = $this->objLanguage->languageText('mod_poll_truefalse', 'poll');
        $lbUser = $this->objLanguage->languageText('phrase_userdefined');
        $lbVisible = $this->objLanguage->languageText('phrase_isvisible');
        $lbAddAns = $this->objLanguage->languageText('phrase_addanswer');
        $lbAnswer = $this->objLanguage->languageText('word_answer');
        $lbYes = $this->objLanguage->languageText('word_yes');
        $lbNo = $this->objLanguage->languageText('word_no');
        $btnSave = $this->objLanguage->languageText('word_save');
        
        $question = ''; $type = 'yes'; $visible = 'yes'; $id = '';
        if(!empty($data)){
            $hdAdd = $this->objLanguage->languageText('phrase_editpoll');
            $question = $data['question'];
            $type = $data['question_type'];
            $visible = $data['is_visible'];
            $id = $data['id'];
        }
        
        $objHead = new htmlheading();
        $objHead->str = $hdAdd;
        $objHead->type = 1;
        $str = $objHead->show();
        
        // question text
        $objLabel = new label($lbQuestion, 'input_question');
        $objText = new textarea('question', $question);
        $objText->setId('input_question');
        $formStr = '<p>'.$objLabel->show().': <br />'.$objText->show().'</p>';
        
        // question type
        $objLabel = new label($lbType, 'input_type');
        $objRadio = new radio('type');
        $objRadio->addOption('yes', '&nbsp;'.$lbYesNo);
        $objRadio->addOption('bool', '&nbsp;'.$lbBool);
        //$objRadio->addOption('user', '&nbsp;'.$lbUser); ... to do.
        $objRadio->setSelected($type);
        $objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
        $objRadio->extra = '';
        $formStr .= '<p>'.$objLabel->show().': <br />'.$objRadio->show().'</p>';
        
        // is visible
        $objLabel = new label($lbVisible, 'input_visible');
        $objRadio = new radio('visible');
        $objRadio->addOption('1', '&nbsp;'.$lbYes);
        $objRadio->addOption('0', '&nbsp;'.$lbNo);
        $objRadio->setSelected($visible);
        $objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
        $objRadio->extra = '';
        $formStr .= '<p>'.$objLabel->show().': <br />'.$objRadio->show().'</p>';
        
        // Display answer layer ... to do.
        
        if(!empty($id)){
            $objInput = new textinput('id', $id, 'hidden');
            $formStr .= $objInput->show();
        }
        
        $objButton = new button('save', $btnSave);
        $objButton->setToSubmit();
        $formStr .= '<p>'.$objButton->show().'</p>';
        
        $objForm = new form('addquestion', $this->uri(array('action' => 'savequestion')));
        $objForm->addToForm($formStr);
        $str .= $objForm->show();
        
        return $str;
    }
    
    /**
    * Method to display the recently added polls in a table
    *
    * @access public
    * @return string html
    */
    public function showPolls($pollData)
    {
        $hdPolls = $this->objLanguage->languageText('word_polls');
        $lbQuestion = $this->objLanguage->languageText('word_question');
        $lbOrder = $this->objLanguage->languageText('word_order');
        $lnAdd = $this->objLanguage->languageText('phrase_createnewpoll');
        $lbNoPolls = $this->objLanguage->languageText('mod_poll_nopollscreated', 'poll');
        $lbDelete = $this->objLanguage->languageText('mod_poll_deletequestionconfirm', 'poll');
        
        $objHead = new htmlheading();
        $objHead->str = $hdPolls;
        $objHead->type = 1;
        $str = $objHead->show();
        
        if(!empty($pollData)){
            $objTable = new htmltable();
            $objTable->cellpadding = '5';
            $objTable->cellspacing = '2';
            
            $hdArr = array();
            $hdArr[] = $lbOrder;
            $hdArr[] = $lbQuestion;
            $hdArr[] = '';
            
            $objTable->addHeader($hdArr);
            
            $class = 'odd';
            foreach($pollData as $item){
                $class = ($class == 'even') ? 'odd':'even';
                
                $icons = $this->objIcon->getEditIcon($this->uri(array('action' => 'showedit', 'id' => $item['id'])));
                $icons .= $this->objIcon->getDeleteIconWithConfirm('', array('action' => 'deletequestion', 'id' => $item['id']), 'poll', $lbDelete);
                
                $rowArr = array();
                $rowArr[] = $item['order_num'];
                $rowArr[] = $item['question'];
                $rowArr[] = $icons;
                
                $objTable->addRow($rowArr);
            }
            $str .= $objTable->show();
        }else{
            $str .= '<p class="noRecordsMessage">'.$lbNoPolls.'</p>';
        }
        
        $objLink = new link($this->uri(array('action' => 'showadd')));
        $objLink->link = $lnAdd;
        $str .= '<p>'.$objLink->show().'</p>';
        
        return $str;
    }
    
    /**
    * Method to display the configurations
    *
    * @access private
    * @return string html
    */
    private function getConfigBlock()
    {
        $id = $this->getSession('pollId');
        $config = $this->dbPoll->getPollData($id);
        $rate = 'weekly'; $date = date('Y-m-d');
        if(!empty($config)){
            $rate = $config['cycle_rate'];
            $date = $config['active_date'];
        }
        
        $lbCycle = $this->objLanguage->languageText('phrase_cyclerate');
        $lbDate = $this->objLanguage->languageText('mod_poll_datefirstpoll', 'poll');
        $lbWeekly = $this->objLanguage->languageText('word_weekly');
        $lbBiWeekly = $this->objLanguage->languageText('word_biweekly');
        $lbMonthly = $this->objLanguage->languageText('word_monthly');
        $btnSave = $this->objLanguage->languageText('word_save');
        
        $objLabel = new label($lbCycle.': ', 'input_rate');
        $objRadio = new radio('rate');
        $objRadio->setId('input_rate');
        $objRadio->addOption('weekly', '&nbsp;'.$lbWeekly);
        $objRadio->addOption('biweekly', '&nbsp;'.$lbBiWeekly);
        $objRadio->addOption('monthly', '&nbsp;'.$lbMonthly);
        $objRadio->setSelected($rate);
        $objRadio->setBreakSpace('<br />');
        
        $str = '<p>'.$objLabel->show().'<br />'.$objRadio->show().'</p>';
        
        $objLabel = new label($lbDate.': ', 'input_date');
        $objInput = new textinput('date', $date);
        $objInput->setId('input_date');
        
        $str .= '<p>'.$objLabel->show().'<br />'.$objInput->show().'</p>';
        
        $objButton = new button('save', $btnSave);
        $objButton->setToSubmit();
        $str .= '<p>'.$objButton->show().'</p>';
        
        $objForm = new form('configure', $this->uri(array('action' => 'saveconfig')));
        $objForm->addToForm($str);
        
        return $objForm->show();
    }
    
    /**
    * Method to display current poll
    *
    * @access public
    * @return string html
    */
    public function getPollBlock()
    {
        $config = $this->dbPoll->getPollByContext($this->contextCode);
        
        if(empty($config)){
            return '';
        }
        
        $days = 0; $num = 1;
        $active = strtotime($config['active_date']);
        $now = time();
        
        if($now != $active){
            $days = floor(($now-$active) / (60*60*24));
        }
                    
        if($days > 0){
            switch($config['cycle_rate']){
                case 'weekly':
                    $num = ceil($days / 7);
                    break;
                case 'biweekly':
                    $num = ceil($days / 14);
                    break;
                case 'monthly':
                    $num = ceil($days / 30);
                    break;
            }
        }
        
        $data = $this->dbQuestions->getCurrentPoll($config['id'], $num);
        
        if(empty($data)){
            return '';
        }
        
        $lbYes = $this->objLanguage->languageText('word_yes');
        $lbNo = $this->objLanguage->languageText('word_no');
        $lbFalse = $this->objLanguage->languageText('word_false');
        $lbTrue = $this->objLanguage->languageText('word_true');
        $btnSave = $this->objLanguage->languageText('word_save');
        
        $str = '';
        if(!empty($data)){
            $str = '<p>'.$data['question'].'</p>';
            
            // radio buttons for the answer
            $objRadio = new radio('answer');
            
            switch($data['question_type']){
                case 'bool':
                    $objRadio->addOption('true', '&nbsp;'.$lbTrue);
                    $objRadio->addOption('false', '&nbsp;'.$lbFalse);
                    $objRadio->setSelected('true');
                    break;
                   
                case 'yes':
                default:
                    $objRadio->addOption('yes', $lbYes);
                    $objRadio->addOption('no', $lbNo);
                    $objRadio->setSelected('yes');
                    break;
            }
            $objRadio->setBreakSpace('<br />');
            $formStr = '<p>'.$objRadio->show().'</p>';
            
            $objInput = new textinput('questionId', $data['id'], 'hidden');
            $formStr .= $objInput->show();
            $objInput = new textinput('questionType', $data['question_type'], 'hidden');
            $formStr .= $objInput->show();
            
            $objButton = new button('save', $btnSave);
            $objButton->setToSubmit();
            $formStr .= '<p>'.$objButton->show().'</p>';
            
            $objForm = new form('response', $this->uri(array('action' => 'saveresponse')));
            $objForm->addToForm($formStr);
            $str .= $objForm->show();
        }
        return $str;
    }
}
?>