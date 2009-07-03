<?php
class contactinfo extends object
{
    public function init()
    {
        $this->loadClass("form","htmlelements");
        $this->loadClass("dropdown","htmlelements");
        $this->loadClass("radio","htmlelements");
        $this->loadClass("textinput","htmlelements");
        $this->loadClass("textarea","htmlelements");
        $this->loadClass("htmlheading","htmlelements");
        $this->loadClass("button","htmlelements");
        $this->loadClass("link","htmlelements");
        $this->loadClass("label","htmlelements");
        $this->loadClass("layer","htmlelements");
        $this->objLanguage=$this->getObject("language","language");
    }

        /* --------------- displayForm() -----------------------------------------------------
                 * The function displayForm() will display a form with the questions and textboxes. If  *
                 * there is already info in the tables in the database, the information will be placed  *
                 * in the textboxes and when submit is clicked the form will be updated. If there is no *
         * info in the db then the information will be added. Keep in mind that each course must*
         * be unique else the first occurance of the course will take precedence.		*
                 * USAGE: $str = $this->objContact->displayForm($coursenumber);				*
                 * --------------------------------------------------------------------------------------
                 */
    public function displayForm($coursenum)
    {
        // set up language items
        $h1Label = $this->objLanguage->languageText('mod_ads_h1','ads');
        $h2aLabel = $this->objLanguage->languageText('mod_contactdetails_h2a','ads');
        $h2bLabel = $this->objLanguage->languageText('mod_contactdetails_h2b','ads');
        $h3aLabel = $this->objLanguage->languageText('mod_contactdetails_h3a','ads');
        $h3bLabel = $this->objLanguage->languageText('mod_contactdetails_h3b','ads');

        /*	//---- Getting course name
            $objDbCourse= $this->getObject("dbcoursetable");
            $courserow = $objDbCourse->getSingleRow($coursenum);
            $coursename=$courserow["coursename"];

            //--- defining coursenum for passing
            $coursenumber = new textinput ("coursenumber",$coursenum,'hidden','');
            $coursenumbershown = $coursenumber->show();
        */
        //--- Getting Course Info
        $objDbContact = $this->getObject("dbcontacttable");
        $data = $objDbContact->getSingleRow($coursenum);
        if (count($data)==0)
        {
            //--- If Blank form created for submitting
            //Setting Up Form
            $objForm = new form('contactdetailsform', $this->uri(array('action' => 'submit')));
            //----Labels created empty
            $h1default = '';
            $h2adefault = '';
            $h2bdefault = '';
            $h3adefault = '';
            $h3bdefault = '';
        } else
        {
            //--- If information exists for created for updating
            //Setting Up Form
            $objForm = new form('contactdetailsform', $this->uri(array('action' => 'update')));
            //--- Labels set to values
            $h1default = $data["academicname"];
            $h2adefault = $data["schoolname"];
            $h2bdefault = $data["headsign"];
            $h3adefault = $data["telnum"];
            $h3bdefault = $data["emailadd"];
        } //--- end If...

        //Setting up input text boxes
        $objInputh1 = new textinput('h1ans', $h1default, '', '100');
        $h1Input = $objInputh1->show();

        $objInputh2a = new textinput('h2aans',$h2adefault, '', '100');
        $h2aInput = $objInputh2a->show();

        $objInputh2b = new textinput('h2bans',$h2bdefault , '', '100');
        $h2bInput = $objInputh2b->show();

        $objInputh3a = new textinput('h3aans',$h3adefault, '', '100');
        $h3aInput = $objInputh3a->show();

        $objInputh3b = new textinput('h3bans',$h3bdefault, '', '100');
        $h3bInput = $objInputh3b->show();

        //Buttons OK and cancel
        $this->objSubmitButton=new button('Submit');
        $this->objSubmitButton->setValue('Submit');
        $this->objSubmitButton->setToSubmit();

        $this->objResetButton=new button('Reset');
        $this->objResetButton->setValue('Reset');
        $this->objResetButton->setToReset();

        $this->objCancelButton=new button('Reset');
        $this->objCancelButton->setValue('Cancel');
        $this->objCancelButton->setToReset();

        //Defining table
        $objTable = new htmltable();
        $objTable->cellpadding = '2';
        $objTable->border='0';
        $objTable->startRow();
        $objTable->addCell("1. ",'5','','','','');
        $objTable->addCell($h1Label, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell(" ",'','','','','');
        $objTable->addCell($h1Input, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell("2.a. ",'5','','','','');
        $objTable->addCell($h2aLabel, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell(" ",'','','','','');
        $objTable->addCell($h2aInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell("2.b. ",'5','','','','');
        $objTable->addCell($h2bLabel, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell(" ",'','','','','');
        $objTable->addCell($h2bInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell("3.a. ",'5','','','','');
        $objTable->addCell($h3aLabel, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell(" ",'','','','','');
        $objTable->addCell($h3aInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell("3.b. ",'5','','','','');
        $objTable->addCell($h3bLabel, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell(" ",'','','','','');
        $objTable->addCell($h3bInput, '', '', '', '', '');
        $objTable->endRow();
        //$objTable->startRow();
        //$objTable->addCell(" ",'5','','','','');
        //$objTable->addCell($this->objSubmitButton->show()." ".$this->objCancelButton->show(), '', '', 'center', '', '');
        //$objTable->endRow();
        $infoTable = $objTable->show();

        //--- heading for page with course name
        $courseheading="<h3> Course Name: ".$coursename." </h3>";
        //Setting Up Form by adding all objects....
        //$objForm->addToForm($courseheading);
        $objForm->addToForm($infoTable);
        $objForm->addToForm('<br/> ');
        $objForm->addToForm($this->objSubmitButton);
        $objForm->addToForm($this->objResetButton);
        $objForm->addToForm($this->objCancelButton);
        $objForm->addToForm($coursenumbershown);
        $composeForm = $objForm->show();

        $pageData= $composeForm;

        //Defining Layer
        $objLayer = new layer();
        $objLayer->padding = '10px';
        $objLayer->str = $pageData;
        $pageLayer = $objLayer->show();
        return $pageLayer;
    }

        /* --------------- displayCourses() -----------------------------------------------------
         * The function displayCourses() will read out all the courses available from the database
         * and write is as a List in a string. This string is then returned. The string will create
         * a link list with each course name and when accessed will be sent to the corresponding view
         * USAGE: $str = $this->objContact->displayCourses();
         * --------------------------------------------------------------------------------------
         */
    public function displayCourses()
    {
        //--- Create object of class dbcoursetable
        $objDbCourse=$this->getObject("dbcoursetable");
        //--- load class link from htmlelements
        $this->loadClass("link","htmlelements");

        //--- Retrieve all the info from the db
        $coursedata = $objDbCourse->getInfo();
        $menu_str="";

        //--- check if data is in table
        if (count($coursedata)==0)
        {
            $menu_str = "There are no Courses <br/>";
        } else //--- if there is draw the menu with the links
        {
            $menu_str="<OL align='left' type='I'>";
            foreach ($coursedata as $data)
            {
                $courseid = $data["coursenum"];
                $link1=new link($this->uri(array('action'=>'view','menutitle'=>'View course details',
                                     'coursenum'=>$courseid),"contactdetails"));
                $link1->link=$data["coursename"];
                $menu_str.="<li> ".$link1->show();
            }
            $menu_str.="</OL>";
        } //-- end if

        //--- Adding space
        $menu_str.="<br/> ";

        //---- Back to Menu Link
        $this->loadClass("link","htmlelements");
        $link1=new link($this->uri(array('action'=>'menu','menutitle'=>'Main Menu'),"contactdetails"));
        $link1->link="Back to Main Menu";
        $menu_str.=$link1->show();

        //--- Return the Menu
        return $menu_str;
    } //---- End Function displayCourses()
}
?>
