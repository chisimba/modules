<?php


//View template for table: tbl_quotes
//Note that you will probably need to edit this to make it actually work


//Set up the button class to make the edit, add and delet icons
$objButtons = & $this->getObject('navbuttons', 'navigation');

// Create an instance of the css layout class
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(2);

//Set the content of the left side column
$leftSideColumn2 =
$this->objLanguage->languageText("mod_freemind_jre", "freemind").'.<br/><br/>';//.$this->objLanguage->languageText("mod_freemind_getfreemind");//$this->objLanguage->code2Txt('mod_freemind',array('context'=>'course'));

//'<a href="http://www.java.com/en/download/index.jsp">'
//.$this->objLanguage->languageText("mod_obj3dviewer_jre_word")
//.'</a> & <a href="http://java.sun.com/products/java-media/3D/download.html">'
//.$this->objLanguage->languageText("mod_obj3dviewer_java3d");

//.'</a> '.$this->objLanguage->languageText("mod_obj3dviewer_jre")




// Add Left column
//$cssLayout->setLeftColumnContent($leftSideColumn);// Add the heading to the content
$this->objH =& $this->getObject('htmlheading', 'htmlelements');
$this->objH->type=3; //Heading <h3>
$this->objH->str=  $this->objLanguage->languageText("mod_freemind_mindmapping", "freemind"); //ucwords($this->objLanguage->code2Txt('mod_contextadmin_name',array('context'=>'course')));mod_freemind_mindmapping
$rightSideColumn2 = "<div align=\"center\">"
  . $this->objH->show()  . "</div>";

//Create a table
$table = $this->newObject('htmltable', 'htmlelements');
$table->cellspacing="2";
$table->width="80%";
$table->attributes="align=\"center\"";
//Create the array for the table header
$tableRow=array();

//$tableHd[]="Code";
$tableHd[]=$this->objLanguage->languageText("word_title", "freemind");
$tableHd[]=$this->objLanguage->languageText("mod_freemind_date", "freemind");

$allowAdmin = True; //You need to write your security here
if ($allowAdmin) {
    $paramArray = array('action' => 'upload');
    $tableHd[] = $objButtons->linkedButton("add",
    $this->uri($paramArray));
   $tableHd[] ='&nbsp;';


}

//Get the icon class and create an add, edit and delete instance
$objAddIcon = $this->newObject('geticon', 'htmlelements');
$objEditIcon = $this->newObject('geticon', 'htmlelements');
$objDelIcon = $this->newObject('geticon', 'htmlelements');
$objConfIcon = $this->newObject('geticon', 'htmlelements');
$objLink= &$this->newObject('link','htmlelements');

//Create the table header for display
$table->addHeader($tableHd, "heading");

//Loop through and display the records
$rowcount = 0;
if (is_array($ar)) {
    if (count($ar) > 0) {
        foreach ($ar as $line) {
            $oddOrEven = ($rowcount == 0) ? "odd" : "even";
            $tableRow[]=$line['title'];
            $tableRow[]=$line['dateCreated'];

            //The context configuration link
            $confLink = $this->uri(array('action' => 'courseadmin'));

               $paramArray = array('action' => 'viewmap','id' => $line['id']);
                $objConfIcon->setIcon('view');
                $objConfIcon->alt=$this->objLanguage->languageText("mod_freemind_viewmap", "freemind");
                $objLink->href=$this->uri($paramArray);
                $objLink->link=$objConfIcon->show();
                $config = $objLink->show().'&nbsp;';

            //The URL for the edit link
            $editLink=$this->uri(array('action' => 'edit',
              'id' =>$line['id']));
            $objEditIcon->alt=$this->objLanguage->languageText("mod_quotes_editalt", "freemind");
            $ed = $objEditIcon->getEditIcon($editLink);

            // The delete icon with link uses confirm delete utility
            $objDelIcon->setIcon("delete", "gif");
            $objDelIcon->alt=$this->objLanguage->code2Txt('mod_contextadmin_deletecontext',array('context'=>'course'));
            $delLink = $this->uri(array(
              'action' => 'delete',
              'confirm' => 'yes',
              'id' => $line['id']));
            $objConfirm = & $this->newObject('confirm','utilities');


            $rep = array('ITEM', $line['id']);
            $objConfirm->setConfirm($objDelIcon->show(),
            $delLink,$this->objLanguage->code2Txt("mod_quotes_confirm", $rep));
            $conf = $objConfirm->show();
            $tableRow[]=$config;
           // $tableRow[]=$ed;
            $tableRow[]=$conf;            //Add the row to the table for output
           $table->addRow($tableRow, $oddOrEven);
           $tableRow=array(); // clear it out
           // Set rowcount for bitwise determination of odd or even
           $rowcount = ($rowcount == 0) ? 1 : 0;

        }
    }
}

//Add the table to the centered layer
$rightSideColumn2 .= $table->show();

// Add Left column
$cssLayout2->setLeftColumnContent($leftSideColumn2);

// Add Right Column
$cssLayout2->setMiddleColumnContent($rightSideColumn2);

//Output the content to the page
echo $cssLayout2->show();
?>