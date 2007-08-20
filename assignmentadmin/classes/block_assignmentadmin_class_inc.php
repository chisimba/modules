<?php
/**
* @package assignmentadmin
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* The assignment admin block class displays a block with an alert if students have handed in.
* @author Megan Watson
*/

class block_assignmentadmin extends object
{
    /**
    * Constructor
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText('mod_assignmentadmin_name', 'assignmentadmin');

        $this->objSubmit = $this->getObject('dbassignmentsubmit', 'assignment');
        $objDbContext = &$this->getObject('dbcontext', 'context');

        $this->contextCode = $objDbContext->getContextCode();

        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
    }

    /**
    * Method to check for new hand-ins on assignments
    */
    public function checkSubmits()
    {
        $hdAssign = $this->objLanguage->languageText('mod_assignmentadmin_assignments');
        $hdMarked = $this->objLanguage->languageText('mod_assignmentadmin_marked','assignmentadmin');
        $hdSubmitted = $this->objLanguage->languageText('mod_assignmentadmin_submitted');

        $submits = $this->objSubmit->getContextSubmissions($this->contextCode);
        $assigns = array(); $str = ''; $i = 0;

        if(!empty($submits)){
            foreach($submits as $item){
                if(!isset($assigns[$item['id']])){
                    $assigns[$item['id']]['submitted'] = 0;
                    $assigns[$item['id']]['marked'] = 0;
                }
                $assigns[$item['id']]['submitted']++;
                $assigns[$item['id']]['name'] = $item['name'];
                $assigns[$item['id']]['closing_date'] = $item['closing_date'];
                if(!empty($item['mark'])){
                    $assigns[$item['id']]['marked']++;
                }
            }

            if(!empty($assigns)){
                $hd = array();
                $hd[] = $hdAssign;
                $hd[] = $hdMarked.' / '.$hdSubmitted;
                $objTable = new htmltable();
                $objTable->cellpadding = 2;
                $objTable->cellspacing = 2;
                $objTable->addHeader($hd);

                foreach($assigns as $item){
                    if($item['marked'] < $item['submitted']){
                        $class = (($i++ % 2) == 0) ? 'odd':'even';
                        $num = $item['marked'].' / '.$item['submitted'];
                        $objTable->startRow();
                        $objTable->addCell($item['name'], '', '', '', $class);
                        $objTable->addCell($num, '', '', 'center', $class);
                        $objTable->endRow();
                    }
                }
                return $objTable->show();
            }
        }
        return '';
    }

    /**
    * Display link to Assignment Admin
    */
    public function getLink()
    {
        $url = $this->uri('', 'assignmentadmin');
        $this->objIcon->setModuleIcon('assignmentadmin');
        $objLink = new link($url);
        $objLink->link = $this->objIcon->show();
        $lnStr = '<p>'.$objLink->show();
        $objLink = new link($url);
        $objLink->link = $this->title;
        $lnStr .= '&nbsp;'.$objLink->show().'</p>';

        return $lnStr;
    }

    /**
    * Display function
    */
    public function show()
    {
        if(is_null($this->contextCode)){
            return '';
        }
        return $this->checkSubmits().$this->getLink();
    }
}
?>