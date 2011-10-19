<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$adaptationstring = 'relation is null';


        $this->_institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getObject("dbproducts", "unesco_oer");
        $this->objDbAvailableProductLanguages = $this->getObject("dbavailableproductlanguages", "unesco_oer");
        $this->objUser = $this->getObject("user", "security");

if ($finalstring == null)

   {
            $finalstring = 'relation is null';
             $TotalEntries = 'relation is null';
    }

    $js = '<script language="JavaScript" src="'.$this->getResourceUri('filterproducts.js').'" type="text/javascript"></script>';
    $this->appendArrayVar('headerParams', $js);
    
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
   
            

                 
                            <div class="subNavigation"></div>
                            <!-- Left Colum -->
                            
                            
                            
                      
                            
                            
                            
            
                    <br><br>
                   
          <div class="wideLeftFloatDiv">
        	<!-- Left Colum -->
                <div class="groupsleftColumnDiv  tenPixelTopPadding">
                	
                        <div class="greyDivider"></div>
                        <br>
                       	<div class="groupSubLinksList">
                               <?php
                               
                                 
                            $addlink = new link($this->uri(array("action" => 'FilterProducts', "page" => '1a_tpl.php')));
                            $addlink->cssId = "addbookmark";
                            $addlink->cssClass = "linksTextNextToSubIcons";
                            $addlink->link = $this->objLanguage->languageText('mod_unesco_oer_bookmark_add', 'unesco_oer');
                            
                            

                            
                            echo $addlink->show();
                            
                               
                      echo '     <img src="skins/unesco_oer/images/icon-group-leave-group.png" alt="Leaave Group" width="18" height="18" class="smallLisitngIcons">
                        
                        </div>
                      
                        <div class="groupSubLinksList">';
                      
                         
                            
                            
                            $booklink = new link("#");
                            $booklink->cssId = "deletebookmark";
                            $booklink->cssClass = "linksTextNextToSubIcons";
                            $booklink->link = $this->objLanguage->languageText('mod_unesco_oer_bookmark_delete', 'unesco_oer');
                            
                            

                            
                            echo $booklink->show();
                            
                          
                            
      echo '       <img src="skins/unesco_oer/images/icon-group-subgroups.png" alt="Sub Groups" width="18" height="18" class="smallLisitngIcons">
                     
                        </div>
                      
                        <div class="greyDivider"></div>
                   
                </div>

 
                          
                            <!-- Center column DIv -->
                            <div class="centerColumnDiv">
                                
                                       <div id="filterDiv" title ="1a" >'; 
                                       
                                           
                  
                                        //Creates chisimba table
                                        $objTable = $this->getObject('htmltable', 'htmlelements');
                                     

                                      
                                            $userid = $this->objUser->userId(); 
                                          $bookmark = $this->objbookmarkmanager->getBookmark($userid);
                                     
                                            

                                            echo $this->objbookmarkmanager->populateListView($bookmark);
                                            
                                        
                                            
                                            
                                            
                    ?>
                                           
                                   
                      

                       
                            </div>
                        </div>
                        </div>
       
</html>

   
   
    