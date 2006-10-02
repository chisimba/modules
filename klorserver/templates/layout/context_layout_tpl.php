<?php
$this->objSideMenu =& $this->getObject('sidemenu','toolbar');
$this->loadClass('link', 'htmlelements');
$this->loadClass("form","htmlelements");
$leftMenu=& $this->newObject('sidemenu','toolbar');
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

$this->objContext =& $this->getObject('dbcontext', 'context');
//$numberofcontexts = count($this->objContext->getAll());
//echo '<div id="leftnav">';
//  $this->userMenuBar=& $this->getObject('contextmenu','toolbar');
//echo $this->userMenuBar->show();
//echo '</div><!-- End div leftnav -->';
//contextadmin&action=courseadmin
//link
 $objAddLink = new link($this->uri(null,'contextadmin'));
$objAddLink->link="Download Content";

$objHome = new link($this->uri(null,'klorserver'));
$objHome->link= "Home";

//---------------drop down
//
$form = new form("modselect",'index.php');
$form->method='GET';
//drop down ----|
// $dropdown =& $this->newObject("dropdown", "htmlelements");
// $dropdown->name = 'customerName';
// $dropdown->addOption('','-Choose One-');
// $objDbStore =& $this->newObject('dbcontext','context');
// $customerName = $objDbStore->getMenuText();
// $dropdown->addFromDB($customerName, 'customerName', 'id');
// $row = array("<b>".'customerName'."</b>",$dropdown->show());
// //$objTable->addRow($row, 'even');
// $dropdown->extra = 'onChange = "document.modselect.submit()"';
// $form->addToForm($dropdown->show());
// //$form->addToForm('<input type="hidden" name="module" value="context"><input type="hidden" name="action" value="" name="customerId" value="id">' );

 
//Add content to left column
$leftColumn = "";
$leftColumn .= '<p>' . /*$studentIcon . '&nbsp;' . */$objHome->show() . '</p>';
$leftColumn .= '<p>' . /*$studentIcon . '&nbsp;' . */ $this->objSideMenu->joinContext() . '</p>';
$leftColumn .= '<p>' . /*$studentIcon . '&nbsp;' . */$objAddLink->show() . '</p>';
//$leftColumn .= '<p>' . /*$studentIcon . '&nbsp;' . */ //$this->userMenuBar->show() . '</p>';



$cssLayout->setLeftColumnContent($leftColumn);
$cssLayout->setMiddleColumnContent($this->getContent());


echo $cssLayout->show();








function joinContext()
    {
        $objModule =& $this->getObject('modulesadmin','modulelist');
        $contextAdminUtils =& $this->getObject('contextadminutils','contextadmin');
        $objForm =& $this->newObject('form','htmlelements');
        $objButton =& $this->newObject('button','htmlelements');
        $objDrop =& $this->newObject('dropdown','htmlelements');

        $joinCourse = ucwords($this->objLanguage->code2Txt('mod_context_joincontext',array('context'=>'course')));
        $leaveCourse = ucwords($this->objLanguage->code2Txt('mod_toolbar_leavecontext'));
        $go = $this->objLanguage->languageText('word_go');
        $inCourse = $this->objLanguage->languageText('mod_postlogin_currentlyincontext');
        $str = '';
        $myContext =  ucwords($this->objLanguage->code2Txt('mod_context_mycontext',array('contexts'=>'courses')));
        $allContext =  ucwords($this->objLanguage->code2Txt('mod_context_allcontext',array('contexts'=>'courses')));
        
        if($objModule->checkIfRegistered('context','context')){

            $this->objHead->type = 4;
            $this->objHead->str = $joinCourse;

            $str .= $this->objHead->show();

            //The Course that you are currently in
            $this->objIcon->setIcon('leavecourse');
            $this->objIcon->alt = $leaveCourse;
            $this->objIcon->title = $leaveCourse;
            $objLeaveButton = $this->objIcon->show();

            $objLink = new link($this->uri(array('action'=>'leavecontext'),'_default'));
            $objLink->link = $objLeaveButton;
            $objLeaveLink = $objLink->show();

            $contextObject =& $this->getObject('dbcontext', 'context');
            $contextCode = $contextObject->getContextCode();

            $objLink = new link($this->uri(null, 'context'));
            $objLink->link = $this->contextTitle;
            $contextLink = $objLink->show();

            // Set Context Code to 'root' if not in context
            if ($this->contextCode == ''){
                $contextTitle = $this->contextTitle;
            } else {
                $contextTitle = $contextLink.' '.$objLeaveLink;
            }

            $contextTitle = str_replace('{context}', '<strong>'.$contextTitle.'</strong>',
            $inCourse);

            $str .= '<p>'.$contextTitle.'</p>';

            // get number of courses available
            $numberofcontexts = count($this->objContext->getAll());

            // dont show course drop down if no courses are available
            if ($numberofcontexts > 0) {
                //set the filter to a session variable
                if(!$this->getParam('filter') == '')
                {
                    $this->setSession('contextfilter', $this->getParam('filter'));
                }

                $objForm = new form('joincontext',
                $this->uri(array('action'=>'joincontext'),'context'));
                $objForm->setDisplayType(3);
                
                $filter = $this->getSession('contextfilter');
                $objDrop = new dropdown('context_dropdown');
                $objDrop->cssClass = 'coursechooser';
                $objDrop->addFromDB($contextAdminUtils->getUserContext($filter),'menutext','contextCode',
                $this->contextCode);

                $objButton = new button();
                $objButton->setToSubmit();
                $objButton->setValue($go);

                
                
    
                if($this->getSession('contextfilter') == 'mycontext')
                {
                    $objLink = new link($this->uri(array('filter' => 'allcontext'), '_default'));
                    $objLink->link = $allContext;
                } else {
                    $objLink = new link($this->uri(array('filter' => 'mycontext'), '_default'));
                    $objLink->link = $myContext;
                }
                $objLink->cssClass = 'pseudobutton';
                $objFilterLink = $objLink->show();

                $objForm->addToForm('<p>'.$objDrop->show().'</p>');
                $objForm->addToForm('<p>'.$objFilterLink.'&nbsp;'.$objButton->show().'</p>');

                $str .= $objForm->show();
            }
        }
        return $str;
    }












?>