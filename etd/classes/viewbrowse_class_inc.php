<?php
/**
* View Browse class extends object
* @package etd
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* View Browse class for browsing etd authors, Titles, and Collections.
* @author Megan Watson
* @author Jonathan Abrahams
* @copyright (c) 2004 UWC
* @version 0.2
* @modified by Megan Watson on 2006 11 04 Ported to 5ive / chisimba
*/

class viewBrowse extends object
{
    /**
    * @var true|false The access controll section;
    */
    private $allowManage = FALSE;

    /**
    * @var array An associative array, containin rows of data, with col1, col2, and id as its columns.
    */
    private $data = array();

    /**
    * @var array An associative array, containin column headings for col1 and col2.
    */
    private $header = array();

    /**
    * @var string The display limit
    */
    private $limit = 10;

    /**
    * @var string The display start point
    */
    private $start = 0;

    /**
    * @var string Property to store the object type used by the browse object
    */
    private $_browseType = '';

    /**
    * @var string Property to store the text displayed above the browse table
    * (for getData not by letter or by search)
    */
    private $browseText = '';

    /**
    * @var string Property to store the id used to filter the object data
    */
    private $join = NULL;

    /**
    * @var string Property to store the additional filter on the object data
    */
    private $extraFilter = NULL;

    /**
    * @var string Property to store the id used to filter the object data
    */
    private $pageTitle = NULL;

    /**
    * @var string Property used to hide the alphabet search.
    */
    private $showAlpha = TRUE;

    /**
    * @var string Property used to hide the search box.
    */
    private $showSearch = TRUE;

    /**
    * @var string Property used to display a print/email button.
    */
    private $showPrint = FALSE;

    /**
    * @var string Property used to add extra content to the page
    */
    private $extra = NULL;

    /**
    * @var string Property used to set the number of columns in the result set
    */
    private $numCols = 2;

    /**
    * @var string Property used to determine whether to display the edit icon
    */
    private $allowEdit = TRUE;

    /**
    * @var string Property used to determine whether to display the edit icon
    */
    private $allowDelete = TRUE;

    /**
    * @var string Property used to determine whether to display a extra icon
    */
    private $allowOther = FALSE;

    /**
    * @var string Property to set the module name
    */
    private $module = 'etd';

    /**
    * @var string Property to determine whether to use a sort table in the results.
    */
    private $sortTable = FALSE;

    /**
    * @var string Property to determine whether to display the results in a new window.
    */
    private $popUpWin = FALSE;

    /**
    * Constructor method
    */
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language', 'language');
        $this->objHeading =& $this->getObject('htmlheading', 'htmlelements');
        $this->objAlphabet =& $this->getObject( 'alphabet', 'navigation' );
        $this->objIcon = & $this->getObject( 'getIcon', 'htmlelements' );
        $this->loadClass( 'htmltable', 'htmlelements' );
        $this->objSortTable = & $this->getObject( 'sorttable', 'htmlelements' );

        $this->loadClass('link', 'htmlelements');
        $this->loadClass( 'button', 'htmlelements' );
        $this->loadClass( 'textinput', 'htmlelements');
        $this->loadClass( 'label', 'htmlelements' );
        $this->loadClass( 'form', 'htmlelements' );
    }

    /**
    * Method to create a new browse object.
    * @param mixed $object The object containing the data for the browse template.
    * @param true|false $allow TRUE means show icons, FALSE means do not show them.
    * @param array $data An associative array, containin rows of data, with col1, col2, and id as its columns.
    * @param array $header An associative array, containin column headings for col1 and col2.
    */
    public function create( $object= NULL,
        $allow=FALSE,
        $type = NULL,
        $browsetype = NULL,
        $data = array(),
        $header = array(),
        $options = array( '10', '20', '30', '40', '50', '60', '70', '80', '90', '100' ) )
    {

        // Access control section
        $this->setAccess( $allow );

        // Setup the display limit
        $this->options = $options ;

        // Get the objects data.
        if( !is_null( $object ) ) {
            $this->type = $object->type;
            $this->_browseType = $object->_browseType;
            $this->setHeader( $object->getHeading() );

            // What are we looking for?
//            $this->findString = $this->getParam( 'searchForString', FALSE );
            $this->findLetter = $this->getParam( 'searchForLetter', NULL );
            $this->displayLimit = $this->getParam( 'displayLimit', 10 );
            $this->displayStart = $this->getParam( 'displayStart', 0 );
            $this->join = $this->getParam('joinId', $this->join);
            $this->extraFilter = $this->getParam('extraFilter', $this->extraFilter);

//            if( $this->findString ) {
//                $this->setData( $object->getBySearch( $this->findString, $this->displayLimit, $this->displayStart, $this->join, $this->extraFilter ) );
//                $this->displayMax = $object->recordsFound;
//
//            } else 

            if ( $this->findLetter && $this->findLetter!='listall' ) {
                $this->setData( $object->getByLetter( $this->findLetter, $this->displayLimit, $this->displayStart, $this->join, $this->extraFilter ) );
                $this->displayMax = $object->recordsFound;

            } else {
                $this->setData($object->getData($this->displayLimit, $this->displayStart, $this->join, $this->extraFilter));
                $this->displayMax = $object->recordsFound;

            }
        } else {
            // Get object properties
            $this->type = $type;
            $this->_browseType=$browsetype;
            // Table headings
            $this->setHeader( $header );
            // Dummy Data
            $this->setData( $data );
        }
    }

    /**
    * Method to set the join id. Must be used before the create method.
    * @param string $id The id to filter the join on.
    */
    public function setJoin($id)
    {
        $this->join = $id;
    }

    /**
    * Method to set an additional filter. Must be used before the create method.
    * @param string $filter The filter.
    */
    public function setExtraFilter($filter)
    {
        $this->extraFilter = $filter;
    }

    /**
    * Method to set the page title.
    * The method can also be used to set the browse title - above the table of results.
    */
    public function setPageTitle($pageTitle = NULL, $browseText = NULL)
    {
        $this->pageTitle = $pageTitle;
        $this->browseText = $browseText;
    }
    
    /**
    * Method to set the browse title
    */
    public function setBrowseText($browseText = NULL)
    {
        $this->browseText = $browseText;
    }

    /**
    * Method set the access of the Manage rights.
    * @param true|false $allow TRUE means show icons, FALSE means do not show them.
    */
    public function setAccess( $allow = FALSE )
    {
        $this->allowManage = $allow;
    }

    /**
    * Method to set the module name. Default is etd.
    * The method also sets the delete confirm message.
    * @param string $module The name of the module using the class.
    * @param string $deleteConfirm The confirmation message code for the delete function.
    */
    public function setModuleName($module, $deleteConfirm)
    {
        $this->module = $module;
        $this->deleteConfirm = $deleteConfirm;
    }

    /**
    * Method set the data to be displayed.
    * @param array $data An associative array, containin rows of data, with col1, col2, and id as its columns.
    */
    public function setData( $data = array() )
    {
        $this->data = empty($data) ? array() : $data;
    }

    /**
    * Method set table column headers.
    * @param array $header An associative array, containin column headings for col1 and col2.
    */
    public function setHeader( $header = array() )
    {
        $this->header = $header;
    }

    /**
    * Method to display or hide the alphabet search.
    * @param bool $show True = show the alphabet, false = hide.
    */
    public function showAlpha($show = TRUE)
    {
        $this->showAlpha = $show;
    }

    /**
    * Method to display or hide the search box.
    * @param bool $show True = show the search, false = hide.
    */
    public function showSearch($show = TRUE)
    {
        $this->showSearch = $show;
    }

    /**
    * Add a print button and email button to the page content.
    * @param string $footer A print button and email button to add to the page below the search results.
    */
    public function showPrint()
    {
        $this->showPrint = TRUE;
    }

    /**
    * Method to set the use of a sort table.
    */
    public function useSortTable()
    {
        $this->sortTable = TRUE;
    }

    /**
    * Method to set the use of a sort table.
    */
    public function usePopUpWin()
    {
        $this->popUpWin = TRUE;
    }

    /**
    * Add extra content to the page.
    * @param string $extra A formatted string to add to the page above the search results.
    */
    public function addExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
    * Method to set the number of columns to be displayed.
    * @param int $num The number of columns
    */
    public function setNumCols($num)
    {
        $this->numCols = $num;
    }

    /**
    * Method to determine the allowed managerial actions.
    */
    public function setManagement($edit=TRUE, $delete=TRUE, $other=FALSE)
    {
        $this->allowEdit = $edit;
        $this->allowDelete = $delete;
        $this->allowOther = $other;
    }

    /**
    * Method to build a nav item
    * @param string Label used by the Icon Link of for the Text Link
    * @param string Icon to use for the Icon Link.
    * @param array The Link Params.
    * @return array icon - only the image, label - only the text, linkedIcon - link as icon, linkedText - link as text
    */
    private function buildNav( $label, $icon, $arrURI )
    {
        // The link
        $uri = $this->uri( $arrURI, $this->module );

        // The Icon
        $icn = $this->objIcon;
        $icn->setIcon($icon);

        // The Icon disabled
        $icnDisabled = $this->objIcon;
        $icnDisabled->setIcon($icon.'_grey');

        // The Link as Icon
        $lnkIcn = new link( $uri );
        $lnkIcn->link = $icn->show();
        $lnkIcn->title = $label;

        // The Link as Text
        $lnkTxt = new link( $uri );
        $lnkTxt->link = $label;

        $result =  array(
            'icon'=> $icn,
            'iconDisabled'=> $icnDisabled,
            'label'=>$label,
            'linkedIcon'=> $lnkIcn,
            'linkedText'=>$lnkTxt );

        return $result;
    }

    /**
    * Method to show the browse object.
    */
    public function show()
    {
        // The calling action:
        $action = $this->getParam('action');

        // Setup language elements
        $pageTitle = $this->pageTitle;
        if(is_null($pageTitle)){
            $pageTitle = $this->objLanguage->code2Txt('mod_etd_browsePageTitle', 'etd', array('TYPE'=>$this->type));
        }

        // Get previous values
//        $valueSearchFor= $this->getParam( 'searchForString', NULL );
        $valueDisplaySelected = $this->getParam( 'displayLimit', NULL );
        $valueSearchForLetter = $this->getParam( 'searchForLetter', NULL );

        // Access Control
        $addIcon = $this->objIcon->getAddIcon( $this->uri(array('action'=>'add'.$this->_browseType, 'joinId'=>$this->join), $this->module) );

        // Show Page Title
        $pgTitle = &$this->objHeading;
        $pgTitle->type = 1;
        $pgTitle->str = $pageTitle.'&nbsp;';
        $pgTitle->str.= $this->allowManage ? $addIcon : NULL;

        // Put searches
        $showAlpha = ''; $showSearch = '';
        if($this->showAlpha){
            $showAlpha = $this->getAlphabet($action, $valueSearchForLetter);
        }
//        if($this->showSearch){
//            $showSearch = $this->getSearch($action, $valueSearchFor, $valueDisplaySelected, $valueSearchForLetter);
//        }

        // Layout
        $str = $pgTitle->show();
        $str .= '<P>'.$showAlpha.'</P>';
//        $str .= '<P>'.$showSearch.'</P>';
        if(!empty($this->extra)){
            $str .= '<P>'.$this->extra.'</P>';
        }

        // Tabulate the results
        $str .= $this->getBrowse($action, $valueSearchForLetter);

        // Print / email buttons
        if($this->showPrint){
            // print button
            $lbPrint = $this->objLanguage->languageText('phrase_printfriendly');
            $lbEmail = $this->objLanguage->languageText('mod_etd_emailresults', 'etd');

            $url = $this->uri(array('action' => 'printsearch', 'searchForString' => $this->findString, 'searchForLetter' => $this->findLetter, 'displayStart' => $this->displayStart, 'displayLimit' => $this->displayLimit, 'joinId' => $this->join, 'extraFilter' => $this->extraFilter), $this->module);

            $onclick = "javascript:window.open('" .$url."', 'searchresult', 'left=100, top=100, width=500, height=400, resizable=yes, scrollbars=yes, fullscreen=0, toolbar=yes, menubar=yes')";
            $objButton = new button('print', $lbPrint);
            $objButton->setOnClick($onclick);

            $btnPrint = $objButton->show();

            // email button / form
            $url = $this->uri(array('action' => 'emailsearch', 'searchForString' => $this->findString, 'searchForLetter' => $this->findLetter, 'displayStart' => $this->displayStart, 'displayLimit' => $this->displayLimit, 'joinId' => $this->join, 'extraFilter' => $this->extraFilter), $this->module);

            $objButton = new button('email', $lbEmail);
            $objButton->setToSubmit();
            $btnEmail = $objButton->show();

            /*
            $objLink = new link($url);
            $objLink->link = $objButton->show();*/

            $btns = $btnPrint.'&nbsp;&nbsp;&nbsp;'.$btnEmail;

            $objForm = new form('emailpage', $url);
            $objForm->addToForm($btns);

             //$objLink->show();

            $str .= '<p>'.$objForm->show().'</p>';
        }
        return $str;
    }

    /**
    * Method to display the browse layer with the results and navigation.
    */
    private function getBrowse($action, $valueSearchForLetter)
    {
        $lbFirst = $this->objLanguage->languageText('word_first');
        $lbPrevious = $this->objLanguage->languageText('word_previous');
        $lbNext = $this->objLanguage->languageText('word_next');
        $lbLast = $this->objLanguage->languageText('word_last');

        $str = '';

        // Setup nav
        $nav = array();
        // Start with previous LIMIT or START
        $prevStart = $this->displayStart - $this->displayLimit;
        $prevDisabled = ($prevStart < 0) && ($this->displayStart == 0);
        $prevStart = $prevDisabled ? 0 : $prevStart;
        $prevStart = $prevStart < 0 ? 0 : $prevStart;

        // Start with next LIMIT or MAX
        $nextStart = $this->displayStart + $this->displayLimit;
        $nextDisabled = ($nextStart >= $this->displayMax);
        $nextStart =  $nextDisabled ? ($nextStart - $this->displayLimit) : $nextStart;

        // Start at record 0
        $firstStart = 0;
        $firstDisabled = $prevDisabled;

        // Last record
        $lastStart = $this->displayMax-$this->displayLimit;
        $lastStart = $lastStart < 0 ? 0 : $lastStart;
        $lastDisabled = $nextDisabled;

        // Found results summary
        $start = $this->displayMax!=0 ? $this->displayStart+1 : 0;
        $end = $nextDisabled ? $this->displayMax : $this->displayStart + $this->displayLimit;
        $total = $this->displayMax;
        $foundResults =  $this->objLanguage->code2Txt( 'mod_etd_foundResult', 'etd',
            array( 'START'=> $start, 'END'=> $end, 'TOTAL'=>$total ) );

        $uri = array();
        $uri['action'] = $action;

        $uri['displayLimit'] = $this->displayLimit;
        $uri['displayStart'] = $this->displayStart;
//        $uri['searchForString'] = $valueSearchFor;
        $uri['searchForLetter'] = $valueSearchForLetter;
        $uri['joinId'] = $this->join;
        $uri['extraFilter'] = $this->extraFilter;

        // First navigation link
        $uri['displayStart'] = $firstStart;
        $firstNav = $this->buildNav( $lbFirst, 'first', $uri );
        $firstShowIcon = $firstDisabled
            ? $firstNav['iconDisabled']->show()
            : $firstNav['linkedIcon']->show();
        $firstShowText = $firstDisabled
            ? $firstNav['label']
            : $firstNav['linkedText']->show();

        // Previous navigation link
        $uri['displayStart'] = $prevStart;
        $prevNav = $this->buildNav( $lbPrevious, 'prev', $uri );
        $prevShowIcon = $prevDisabled
            ? $prevNav['iconDisabled']->show()
            : $prevNav['linkedIcon']->show();
        $prevShowText = $prevDisabled
            ? $prevNav['label']
            : $prevNav['linkedText']->show();

        // Next navigation link
        $uri['displayStart'] = $nextStart;
        $nextNav = $this->buildNav( $lbNext, 'next', $uri );
        $nextShowIcon = $nextDisabled
            ? $nextNav['iconDisabled']->show()
            : $nextNav['linkedIcon']->show();
        $nextShowText = $nextDisabled
            ? $nextNav['label']
            : $nextNav['linkedText']->show();

        // Last navigation link
        $uri['displayStart'] = $lastStart;
        $lastNav = $this->buildNav( $lbLast, 'last', $uri );
        $lastShowIcon = $lastDisabled
            ? $lastNav['iconDisabled']->show()
            : $lastNav['linkedIcon']->show();
        $lastShowText = $lastDisabled
            ? $lastNav['label']
            : $lastNav['linkedText']->show();

        $nav['left'] = array($firstShowIcon,$prevShowIcon);
        $nav['center'] = array( $firstShowText,$prevShowText, $nextShowText,$lastShowText);
        $nav['right'] = array($nextShowIcon,$lastShowIcon );

        //Top Nav
        $topNav = array();
        $topNav['left'] = implode('',$nav['left']);
        $setType = $this->type;
        if(isset($this->browseText) && !empty($this->browseText)){
            $topText = $this->browseText;
            $setType = $this->browseText;
        }else{
            $topText = $this->objLanguage->code2Txt('mod_etd_browsePageTitle', 'etd', array('TYPE'=>$this->type));
        }
        if( $valueSearchForLetter && $valueSearchForLetter !='listall' )
            $topText = $this->objLanguage->code2Txt('mod_etd_hdrSearchByLetter', 'etd',
                array('TYPE'=>$setType, 'LETTER'=>$valueSearchForLetter));
        if( $valueSearchFor )
            $topText = $this->objLanguage->code2Txt('mod_etd_hdrSearchByString', 'etd', 
                array('TYPE'=>$setType, 'STRING'=>$valueSearchFor));
        $topHeading = $this->objHeading;
        $topHeading->type = 4;
        $topHeading->str = $topText;
        $topNav['center'] = $topHeading->show();
        $topNav['right'] =  implode('',$nav['right']);

        //bottom Nav
        $bottomNav = array();
        $bottomNav['left'] = implode('',$nav['left']);
        $bottomNav['center'] = implode( '&nbsp;|&nbsp;', $nav['center']);
        $bottomNav['right'] = implode( '', $nav['right'] );

        //Number of results found
        $tblFound = new htmltable();
        $tblFound->startRow();
            $tblFound->addCell($foundResults,'100%', 'center' , 'right');
        $tblFound->endRow();
        $foundStr = $tblFound->show();

        // Top navigation links
        $tblTopNav = new htmltable();
        $tblTopNav->startRow();
            $tblTopNav->addCell($topNav['left'],'25%', 'center' , 'left');
            $tblTopNav->addCell($topNav['center'], '50%', 'center', 'center');
            $tblTopNav->addCell($topNav['right'],'25%', 'center', 'right' );
        $tblTopNav->endRow();
        $topStr = $tblTopNav->show();

        // Bottom navigation links
        $objTable = new htmltable();
        $objTable->startRow();
            $objTable->addCell($bottomNav['left'],'25%', 'center' , 'left');
            $objTable->addCell($bottomNav['center'], '50%', 'center', 'center');
            $objTable->addCell($bottomNav['right'],'25%', 'center' , 'right');
        $objTable->endRow();
        $bottomStr = $objTable->show();

        // Tabulate the results
        $str = $foundStr;
        $str .= $topStr;
        $str .= $this->getResults();
        $str .= $bottomStr;
        return $str;
    }

    /**
    * Method to get the table displaying the results.
    */
    public function getResults($showLinks = TRUE)
    {
        $tblResults = new htmltable();
        $tblResults->width = '100%';
        $tblResults->cellpadding = 5;
        $tblResults->cellspacing = 2;

        $oddEven = 'odd';
        if(empty($this->data)) {
            $tblResults->addRow(array($this->objLanguage->code2Txt('mod_etd_foundNoRecords', 'etd')), 'noRecordsMessage');
        } else {
            // Check for sort table and reinitialise
            if($this->sortTable){
                $tblResults = $this->objSortTable;
                $tblResults->width = '100%';
                $tblResults->cellpadding = 5;
                $tblResults->cellspacing = 2;
            }

            $tblResults->startHeaderRow();
                for($i = 1; $i <= $this->numCols; $i++){
                    $tblResults->addHeaderCell($this->header['col'.$i]);
                }
                $this->allowManage ? $tblResults->addHeaderCell('', '8%') : '';
            $tblResults->endHeaderRow();

            foreach( $this->data as $row ) {
                $tblResults->row_attributes = "class =\"$oddEven\"";
                $tblResults->startRow();
                    $id = $row['id'];

                    // View item
                    if($showLinks){
                        $url = $this->uri(array('action'=>'view'.$this->_browseType, 'id'=>$id, 'allowManage'=>$this->allowManage), $this->module);
                        if($this->popUpWin){
                            $objLink = new link('#');
                            $objLink->extra = "onclick = \"javascript:window.open('" .$url."', 'searchresult', 'left=50, top=50, width=600, height=500, scrollbars=1, resizable=1, fullscreen=0, toolbar=1, menubar=1')\" ";
                        }else{
                            $objLink = new link($url);
                        }
                        $count = '';
                        if(isset($row['cnt'])){
                            $count = '&nbsp;&nbsp;&nbsp;('.$row['cnt'].')';
                        }
                        $objLink->link = $row['col1'];
                        $tblResults->addCell($objLink->show().$count);
                    }else{
                        $tblResults->addCell($row['col1']);
                    }

                    for($i = 2; $i <= $this->numCols; $i++){
                        $tblResults->addCell($row['col'.$i]);
                    }

                    // Managed items
                    $this->allowManage ? $tblResults->addCell($this->getIcons($id, $row['col1'])) : NULL;
                $tblResults->endRow();
                $oddEven = $oddEven=='odd' ? 'even' : 'odd';
            }
        }
        return $tblResults->show();
    }

    /**
    * Method to get the edit and delete icons if user has access.
    */
    private function getIcons($id, $item)
    {
        $icons = '';
        if($this->allowEdit){
            $editArr = array( 'action'=>'edit'.$this->_browseType, 'id'=>$id, 'joinId'=>$this->join);
            $icons .= $this->objIcon->getEditIcon( $this->uri( $editArr, $this->module ) );
        }

        if($this->allowDelete){
            $delArr = array( 'action'=>'delete'.$this->_browseType, 'id'=>$id, 'joinId'=>$this->join );
            $conArray = array('browsetype'=>$item);
            if(isset($this->deleteConfirm) && !empty($this->deleteConfirm)){
                $confirm = $this->objLanguage->code2Txt($this->deleteConfirm, $module, $conArray);
            }else{
                $confirm = $this->objLanguage->code2Txt('mod_etd_deleteconfirm', 'etd', $conArray);
            }
            $icons .= $this->objIcon->getDeleteIconWithConfirm('', $delArr, $this->module, $confirm);
        }

        if(!($this->allowOther === FALSE)){
            $name = $this->allowOther;
            $url = $this->uri(array('action'=>'action'.$this->_browseType, 'id'=>$id), $this->module);
            $icons .= $this->objIcon->getLinkedIcon($url, $name);
        }

        return $icons;
    }

    /**
    * Method to create the alphabet to display for searching by letter.
    */
    private function getAlphabet($action, $valueSearchForLetter)
    {
        $listall = $this->objLanguage->languageText('phrase_listall');
        
        // Show Alphabetical Search
        $link = $this->uri(array('action'=>$action, 'searchForLetter'=>'LETTER', 'joinId'=>$this->join, 'extraFilter'=>$this->extraFilter), $this->module );
        $showAlpha = $this->objAlphabet->putAlpha($link, TRUE, $listall);
        
        // Hidden field - contains letter
        $hdnSearchLetter = new textinput( 'searchForLetter', $valueSearchForLetter, 'hidden' );

        return $showAlpha;
    }

    /**
    * Method to create the simple text search.
    *
    private function getSearch($action, $valueSearchFor, $valueDisplaySelected, $valueSearchForLetter)
    {
        $lblSearchFor = $this->objLanguage->code2Txt( 'mod_etd_browseSearchFor');
        $lblDisplayLimit = $this->objLanguage->languageText( 'mod_etd_display', '[Display]' );
        $lblSearch = $this->objLanguage->code2Txt( 'word_search' );

        // Show Search for string and the Display X records at a time
        $searchLabel = new label( $lblSearchFor, 'input_searchForString' );

        // Text input : to search for item
        $searchInput = new textinput( 'searchForString', $valueSearchFor);
        $uri = $this->uri(array('action'=>$action, 'displayLimit'=>$this->displayLimit, 'joinId'=>$this->join, 'extraFilter'=>$this->extraFilter), $this->module);
        $searchInput->extra = " onKeyPress='javascript: if( event.keyCode==13) { document.location=\"$uri&searchForString=\"+this.value;}'";

        // Button : to Submit search request
        $searchButton = new button( 'search', $lblSearch );
        $uri = $this->uri(array('action'=>$action, 'displayLimit'=>$this->displayLimit, 'joinId'=>$this->join, 'extraFilter'=>$this->extraFilter), $this->module);
        $searchButton->extra = " onClick='javascript: var search= getElementById( \"input_searchForString\" ); document.location=\"$uri&searchForString=\"+search.value;'";

        // Display the Limit as a dropdown
        $displayLabel = new label( $lblDisplayLimit, 'input_displayLimit' );

        // Dropdown: list preselected limits.
        $displayLimit = &$this->objDropDown;
        $displayLimit->dropdown( 'displayLimit' );
        foreach( $this->options as $option )
            $displayLimit->addOption( $option, $option );
        $displayLimit->setSelected( $valueDisplaySelected );
        $uri = $this->uri(array('action'=>$action, 'displayStart'=>$this->displayStart, 'searchForString'=>$valueSearchFor, 'searchForLetter'=>$valueSearchForLetter, 'joinId'=>$this->join, 'extraFilter'=>$this->extraFilter), $this->module);
        $displayLimit->extra = " onChange=javascript:document.location=\"$uri&displayLimit=\"+this.value;";

        // Display [-LIMIT-] results
        $displayResults = $this->objLanguage->code2Txt( 'mod_etd_displayResult',
            array( 'LIMIT'=> $displayLimit->show() ) );

        return $searchLabel->show().'&nbsp;'.$searchInput->show().'&nbsp;'.$searchButton->show().
        '&nbsp;'.$displayLabel->show().'&nbsp;'.$displayResults;
    }
    */
}
?>