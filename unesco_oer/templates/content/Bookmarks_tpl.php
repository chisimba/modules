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
         $this->objDbproductlanguages = $this->getObject("dbproductlanguages", "unesco_oer");
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
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>UNESCO</title>

        <!--[if IE]>
            <style type="text/css" media="screen">
            body {
    	behavior: url(csshover.htc);
            }
            </style>
        <![endif]-->
    </head>

    <body>
        <div class="blueHorizontalStrip"></div>
        <div class="mainWrapper">
            <div class="topContent">
                <?php
                if ($this->objUser->isLoggedIn()) {
                ?>
                    <div class="logOutSearchDiv">
                        <div class="logoutSearchDivLeft">
                            <div class="nameDiv"><?php echo $this->objUser->fullname(); ?></div>
                            <div class="logoutDiv">
                                <div class="textNextToRightFloatedImage"><a href="?module=security&action=logoff" class="prifileLinks">Log out</a></div>
                                <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">
                            </div>
                            <div class="profileBookmarkGroupsMessengerDiv">
                                <table class="profileBookmarkGroupsMessengerTable" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><img src="skins/unesco_oer/images/icon-my-profile.png"></td>
                                        <td><a href="#" class="prifileLinks">
                                           <?php
                                            $abLink = new link($this->uri(array("action" => "editUserDetailsForm",'id'=>$this->objUseExtra->getUserbyUserIdbyUserID($this->objUser->userId()),'userid'=>$this->objUser->userId())));
                                            $abLink->link = 'My Profile';
                                            echo $abLink->show();
                                            ?> </a></td>
                                        <td><img src="skins/unesco_oer/images/icon-my-bookmarks.png"></td>
                                        <td><a href="#" class="prifileLinks">My Bookmarks</a></td>
                                        <td><img src="skins/unesco_oer/images/icon-my-administration-tools.png"></td>
                                        <td><a href="#" class="prifileLinks">
                                            <?php
                                            $abLink = new link($this->uri(array("action" => "controlpanel")));
                                            $abLink->link = 'Administration Tools';
                                            echo $abLink->show();
                                            ?>
                                        </a></td>
                                </tr>
                                <tr>
                                    <td><img src="skins/unesco_oer/images/icon-my-groups.png"></td>
                                    <td><a href="#" class="prifileLinks">My Groups</a></td>
                                    <td><img src="skins/unesco_oer/images/icon-my-messenger.png"></td>
                                    <td><a href="#" class="prifileLinks">My Messenger</a></td>
                                  
                                            </a></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <div class="logoutSearchDivRight">
                        <div class="searctInputTextDiv">
                            <div class="searchGoButton"> <!-- <a href="">
<!--                                    <img src="skins/unesco_oer/images/button-search.png" width="17" height="17" class="searchGoImage"></a>-->



                                    <a href="" class="searchGoLink">




                                    </a></div>
                            <div class="searchInputBoxDiv">

<!--                              <input type="text" name="" id="" class="searchInput" value="Type search term here...">-->

                                <?php

  $Search = $this->getobject('filterdisplay', 'unesco_oer');
    echo $Search->Search($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum);
                                ?>




                                                            <!--                                     <select name="" id="" class="searchDropDown">
                                                                                                <option value="">All</o    <input type="text" name="" id="" class="searchInput" value="Type search term here...">
                                                                                            <select name="" id="" class="searchDropDown">ption>
                                                                                            </select>-->
                                        </div>
                                        <div class="textNextToRightFloatedImage">Search</div><!--
                                        <img src="skins/unesco_oer/images/icon-search.png" alt="Search" class="imgFloatLeft">-->
                                    </div>
                                    <div class="facebookShareDiv">

                                        <!-- AddThis Button BEGIN -->
                                        <div class="shareDiv">
            <!--                                            <a class="addthis_button" href="#"><img src="#" width="125" height="16" alt="Bookmark and Share"></a><script type="text/javascript" src="#"></script>

                                             AddThis Button END -->
                                        </div>

                                        <div class="likeDiv">


                                        </div>


                                    </div>
                                </div>
                            </div>
                <?php
                } else {
                ?>

                    <div id="loginDiv">

                        <div id="loginDiv">
                            <img src="skins/unesco_oer/images/icon-logout.png" alt="logout" class="imgFloatLeft">  <a href="?module=security&action=login" >Log in</a>
                            <div id="loginDiv">
                                <img src="skins/unesco_oer/images/icon-filter-number-of-adaptations.png" alt="logout" class="imgFloatLeft">  <a href="?module=unesco_oer&action=userRegistrationForm" >Register</a>
                            </div>

                        </div>
                    </div>
                <?php
                }
                ?>
                                        <div class="logoAndHeading">
                                            <img src="skins/unesco_oer/images/logo-unesco.gif" class="imgFloatRight" alt="logo">
                                            <div class="logoText">
                                                <span class="greyTitleText">Unesco&rsquo;s Open Educational Resources Platform</span><br>
                                                <h1>UNESCO OER PRODUCTS</h1>
                                            </div>
                                        </div>
                                        <div class="languagesDiv">
                                            <a href="?module=unesco_oer&action=changelanguege&langugae=EN" class="languagesLinksActive">English</a> |
                                            <a href="?module=unesco_oer&action=changelanguege&langugae=FR" class="languagesLinks">Français</a> |
                                            <a href="?module=unesco_oer&action=changelanguege&langugae=SP" class="languagesLinks">Español</a> |
                                            <a href="" class="languagesLinks">Русский</a> |
                                            <a href="" class="languagesLinks">لعربية</a> |
                                            <a href="" class="languagesLinks">中文</a>
                                        </div>
                                        <div class="mainNavigation">
                                            <div class="navitemOnstate">
                                                <div class="navitemInnerOnstate">
                            <?php
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is null and deleted = 0', "page" => '1a_tpl.php')));
                                        $abLink->link = 'BOOKMARKS';
                                        echo $abLink->show();
                            ?>
                                    </div>
                                </div>
                                <div class="mainNavPipe">&nbsp;</div>
                                <div class="navitem">
                                    <div class="navitemInner">
                            <?php
                                        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is not null and deleted = 1', "page" => '2a_tpl.php')));
                                        $abLink->link = 'PRODUCT ADAPTATIONS';
                                        echo $abLink->show();
                            ?>

                                    </div>
                                </div>
                                <div class="mainNavPipe"></div>
                                <div class="navitem">
                                    <div class="navitemInner"><a href="#">GROUPS</a></div>
                                </div>
                                 <div class="mainNavPipe"></div>
                                <div class="navitem">
                                    <div class="navitemInner"><a href="#">REPORTING</a></div>
                                </div>
                                <div class="mainNavPipe"></div>
                                <div class="navitem">
                                    <div class="navitemInner"><a href="#">ABOUT</a></div>
                                </div>
                                <div class="mainNavPipe"></div>
                                <div class="navitem"><div class="navitemInner"><a href="#">CONTACT</a></div></div>
                            </div>
                        </div>
            
            
            
            

                        <div class="mainContentHolder">
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
                               
                                 
                            $addlink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '1a_tpl.php')));
                            $addlink->cssId = "addbookmark";
                            $addlink->cssClass = "linksTextNextToSubIcons";
                            $addlink->link = "Add Bookmark";
                            
                            

                            
                            echo $addlink->show();
                            
                               
                      echo '     <img src="skins/unesco_oer/images/icon-group-leave-group.png" alt="Leaave Group" width="18" height="18" class="smallLisitngIcons">
                        
                        </div>
                      
                        <div class="groupSubLinksList">';
                      
                         
                            
                            
                            $booklink = new link("#");
                            $booklink->cssId = "deletebookmark";
                            $booklink->cssClass = "linksTextNextToSubIcons";
                            $booklink->link = "Delete Bookmark";
                            
                            

                            
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
                           
            <div class="footerDiv">
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set One</div>
                    <a href="" class="footerLink">Link 1</a><br>
                    <a href="" class="footerLink">Link 2</a><br>
                    <a href="" class="footerLink">Link 3</a>
                </div>
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set Two</div>
                    <a href="" class="footerLink">Link 4</a><br>
                    <a href="" class="footerLink">Link 5</a><br>
                    <a href="" class="footerLink">Link 6</a>
                </div>
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set Three</div>
                    <a href="" class="footerLink">Link 7</a><br>
                    <a href="" class="footerLink">Link 8</a><br>
                    <a href="" class="footerLink">Link 9</a>
                </div>
                <div class="footerLinksLists">
                    <div class="footerLinksHeadings">Links Set Four</div>
                    <a href="" class="footerLink">Link 10</a><br>
                    <a href="" class="footerLink">Link 11</a><br>
                    <a href="" class="footerLink">Link 12</a>
                </div>
                <div class="footerBottomText">
                    <img src="skins/unesco_oer/images/icon-footer.png" alt="CC" width="80" height="15" class="imageFooterPad">
                    <a href="" class="footerLink">UNESCO</a> |
                    <a href="" class="footerLink">Communication and Information</a> |
                    <a href="" class="footerLink">About OER Platform</a> |
                    <a href="" class="footerLink">F.A.Q.</a> |
                    <a href="" class="footerLink">Glossary</a> |
                    <a href="" class="footerLink">Terms of use</a> |
                    <a href="" class="footerLink">Contact</a> |
                    <a href="" class="footerLink">Sitemap</a> | &copy; UNESCO 1995-2011
                </div>
            </div>
        </div>
    </body>
</html>

   
   
    