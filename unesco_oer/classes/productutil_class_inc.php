<?php

$this->loadClass('link', 'htmlelements');

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

class productutil extends object {

    private $_institutionGUI;

    public function init() {
        $this->_institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getObject("dbproducts", "unesco_oer");
        $this->objDbAvailableProductLanguages = $this->getObject("dbavailableproductlanguages", "unesco_oer");
        $this->objUser = $this->getObject("user", "security");
        $this->objbookmarkmanager = $this->getObject('bookmarkmanager');
    }

    /**
     * This function populates a page with the original products in a gridview
     * @param <type> $product
     * @return <type> $content
     */
    public function navigation($product) {
        $content = '    
        <div class="breadCrumb">';

        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $origional, "page" => '1a_tpl.php')));
        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_products', 'unesco_oer');
        $abLink->cssClass = "blueText noUnderline";
        $content.= $abLink->show();


        $content.= '| Filter';
        $content.='
                  </a>
                      </div>       ';



        return $content;
    }

    public function populateGridView($product) {


        $objProduct = $this->newObject('product', 'unesco_oer');
        $objProduct->loadProduct($product);

        $uri = $this->uri(array("action" => 'ViewProduct', "id" => $product['id']));
        $abLink = new link($uri);
        $abLink->cssClass = "listingLanguageLinkAndIcon";
        $abLink->link = $product['title'];

        $thumbLink = new link($uri);
        $thumbLink->link = '<img src="' . $product['thumbnail'] . '" width="79" height="101">';

        $parentid = $product['id'];

        $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
        $CommentLink->cssClass = 'adaptationLinks';
        $CommentLink->link = $product['noOfAdaptations'] . ' ' . $this->objLanguage->languageText('mod_unesco_oer_adaptations', 'unesco_oer');

        $key = array_search('language0', $product);
        $endKey = count($product);
        $productSize = $endKey - $key;

//TODO Ntsako find out what makes a product new
        if ($product['new'] == 'true') {
            $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
        } else {
            $content.= '<div class="newImageIcon"></div>';
        }

//This some how forces the page to display the 0
        if ($product['noOfAdaptations'] == 0) {
            $product['noOfAdaptations'] = 0;
        }

        $bookLink = new link('#');
        $bookLink->cssClass = "booklinksLinks";
        $bookLink->cssId = $product['id'];
        $linkText = '<div class="imageTopFlag"></div>';
        $bookLink->link = $linkText;

        $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $parentid, 'nextAction' => 'ViewProduct', 'cancelAction' => 'home'));
        $adaptLink = new link($uri);
        $adaptLink->cssClass = "adaptationLinks";
        $linkText = '<div class="imageBotomFlag"></div>';
        $adaptLink->link = $linkText;

        $objUser = $this->getObject('user', 'security');
        $imageBottomFlag = $adaptLink->show();

        $content.='
                                <div class="imageGridListing">' .
                $bookLink->show()
                . $thumbLink->show() . '
                                    ' . $imageBottomFlag . '
                                </div>
                                <br>
                                <div class="blueListingHeading">' . $abLink->show() . '</div>
                                    <br>
                                <div class="listingLanguageLinkAndIcon">
                                    <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                    <div class="listingLanuagesDropdownDiv">';
//                                        <select name="" class="listingsLanguageDropDown">';

        $index = 0;

        $dropdown = new dropdown($product['id'] . "_dropdown");

        $translations = $objProduct->getTranslationsList();
        $langs = $this->objLanguage->getLangs();
        $langs['en'] = 'English';
        foreach ($translations as $translation) {
            $prodLanguage = $langs[$translation['language']];
//            $selected = ($product['id'] == $translation['id']) ? 'selected' : '';
//            $content .= '<option '. $selected .' value="'. $translation['id'].'">' . $prodLanguage . '</option>';
            $dropdown->addOption($translation['id'], $prodLanguage);
        }
        $dropdown->cssClass = "listingsLanguageDropDown";
        $dropdown->setSelected($product['id']);
        $dropdown->addOnchange("javascript: viewProduct('{$dropdown->cssId}');");
        $content .= $dropdown->show();
//         $prodLanguages = $this->objDbproductlanguages->getLanguageNameByID($product['language']);
//         $content .= '<option value="">' . $prodLanguages . '</option>';
//        foreach ($product as $languages) {
////Check if languages is empty
//            foreach ($languages as $language) {
////print_r($language);
//                $content .= '<option value="">' . $language . '</option>';
//                $index++;
//            }
        // }
//        if ($index == 0) {
//            $content .= '<option value="">' . $prodLanguages[0]['name'] . '</option>';  // REMOVE HARDCODE ENGLISH WHEN LANGUAGES TABLES FIXED
//        }



        $content .='
                                        <!--</select> -->
                                    </div>
                                </div>
                                <br>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                    <div class="listingAdaptationLinkDiv"> ' . $CommentLink->show() . '</div>
                                </div>
                              
                                
              
';
        return $content;
    }

    /**
     * This function populates a page with the original products in a listview
     * @param <type> $product
     * @return <type> $content
     */
    public function populateListView($start, $end, $products) {

        $content = '     
                                      <script src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
                            <script>
                           $(document).ready(function(){';



        // foreach ($data as $products) {

        for ($i = $start; $i < ($end); $i++) {

            $temp = str_replace(" ", "", $products[$i]['id']);

            $divheading = '#' . $temp . 'Div';
            $linkheading = '#' . $temp . 'Link';

            $buttonidheading = '#' . $temp . 'btn';


            $content.= "

 

                  $('$linkheading').click(function(){

                  $('$divheading').slideToggle();
       

                  });
            
            $('$buttonidheading').click(function () {
             $('$divheading').slideToggle();
            
                                });

            
           ";
        }

        $content .= '        
            });

                     </script>
                                        ';


        //  foreach ($data as $products) {
        for ($i = $start; $i < ($end); $i++) {

            $temp = str_replace(" ", "", $products[$i]['id']);
            $divheading = $temp . 'Div';
            $linkheading = $temp . 'Link';

            $products[$i]['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($products[$i]['id']);
            $languages = $this->objDbAvailableProductLanguages->getProductLanguage($products[$i]['id']);
            $product = $products[$i] + $languages;


         //   $editbutton = new button( "Submit","Submit");
          //  $editbutton->cssClass = "listingLanguageLinkAndIcon";
            
         $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $products[$i]['id'] , 'nextAction' => 'ViewProduct', 'cancelAction' => 'home'));
         $adaptLink = new link($uri);
         $adaptLink->cssClass = "adaptationLinks";
         $linkText = 'Make Adaptation';
         $adaptLink->link = $linkText;


          $parentid = $product['id'];
            $textname = $temp . "text";
            $commentboxname = $temp . "comment";
            $buttonid = $temp . 'btn';
            $textinput = new textinput($textname);
            $textinput->value = $product['title'];

            $commentText = new textarea($commentboxname);
            $commentText->setCssClass("commentTextBox");

            //TODO make parameter pagename dynamic
            $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));

            $button = new button('submitComment', "Save Bookmark");
            $button->cssId = $buttonid;


            $time = time();
            //  $userid = objdbuserextra->
            $userid = $this->objUser->userId();

        //    echo $userid;

            $location = $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


            $button->onclick = "  javascript:bookmarksave('$time','$parentid','$userid','$textname','$commentboxname')  ";
            //javascript:bookmarkupdate('$time','$parentid','$userid','$textname','$commentboxname'); 


            $form = new form('3a_comments_ui', $uri);
            $form->addToForm("Label * <br>");
            $form->addToForm($textinput);
            $form->addToForm("<br>Bookmark Description *<br> ");
            $form->addToForm($commentText);
            $form->addToForm("<br><br>");

            $form->addToForm($button->show()); //TODO use text link instead of button


            $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['id'])));
            $abLink->cssClass = "listingLanguageLinkAndIcon";
            $abLink->link = $product['title'];

            $parentid = $product['id'];

            $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
            $CommentLink->cssClass = 'adaptationLinks';
            $CommentLink->link = $product['noOfAdaptations'] . ' ' . $this->objLanguage->languageText('mod_unesco_oer_adaptations', 'unesco_oer');

            /* if ($product['new'] == 'true') {
              $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
              } */

//This some how forces the page to display the 0
            if ($product['noOfAdaptations'] == 0) {
                $product['noOfAdaptations'] = 0;
            }

            $content.="
                  <div class='productsListView'>
                  <table width=\"100%\">
                  <tr>
                   <td width=\"100%\" align = \"left\" valign=\"top\">
                   <h2>" . $abLink->show() . "</h2><br/>
                    </td>
                   </tr>
                    <tr><td>
                    <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/icon-new.png' alt='New' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'>new</div>
                  	</div>
                    <div class='listingIconsHoldingDiv'>
                        <img src='skins/unesco_oer/images/small-icon-adaptations.png' alt='Adaptation' width='18' height='18'class='smallLisitngIcons'>
                        <div class='textNextToTheListingIconDiv'><a href='#' class='adaptationLinks'>" . $CommentLink->show() . " </a></div>
                    </div>
                    <div class='listingIconsHoldingDiv'>
                        <img src='skins/unesco_oer/images/small-icon-bookmark.png' alt='Bookmark' width='18' height='18'class='imgFloatRight'>
                        <div class='textNextToTheListingIconDiv'>
                
                    </div>
                   
                    
                <a href='javascript:void(0)'   id='$linkheading'>Bookmark    </a></div>
             
                 </div>            
              <div class='listingIconsHoldingDiv'>
                        <img src='skins/unesco_oer/images/small-icon-make-adaptation.png' alt='Make Adaptation' width='18' height='18'class='smallLisitngIcons'>
                        <div class='textNextToTheListingIconDiv'>". $adaptLink->show() ."</div>
                  </div>
                    <div class='listingIconsHoldingDiv'>
                      <img src='skins/unesco_oer/images/icon-languages.png' alt='Languages search' width='24' height='24'class='smallLisitngIcons'>
                        <div class='textNextToTheListingIconDiv'>
                        	<select name='' class='listingsLanguageDropDown'>
       
                ";

            $index = 0;
            $prodLanguages = $this->objLanguage->getLangs();
            $langs['en'] = 'English';
            $content .= '<option value="">' . $prodLanguages[$product['language']] . '</option>';
            $content .= "</select>
                        </div>
        
                    </div> <br><br><br><br>
        </td>
                    </tr>
                    </table>
   <div id='$divheading'  style= 'display:none' > 
      
                " . $form->show() . "
          
                </div>
                
             </div>
        

        ";
        }
        return $content;
    }

    /**
     * This function populates a page with the adapted products in a gridview
     * @param <type> $adaptedProduct
     * @return <type> $content
     */
    public function populateAdaptedGridView($adaptedProduct) {








        $uri = $this->uri(array("action" => 'ViewProduct', "id" => $adaptedProduct->getIdentifier()));
        $abLink = new link($uri);
        $abLink->cssClass = "listingLanguageLinkAndIcon";
        $abLink->link = $adaptedProduct->getTitle();

        $thumbLink = new link($uri);
        $thumbLink->link = '<img src="' . $adaptedProduct->getThumbnailPath() . '" width="79" height="101">';
        /* if ($product['new'] == 'true') {
          $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
          } */

        /*
         * TODO Ntsako add code to check if the product was adapted by an institution or a group
         */

////This some how forces the page to display the 0
//        if ($product['noOfAdaptations'] == 0) {
//            $product['noOfAdaptations'] = 0;
//        }

        $bookLink = new link('#');
        $bookLink->cssClass = "booklinksLinks";
        $bookLink->cssId = $adaptedProduct->getIdentifier();
        $linkText = '<div class="imageTopFlag"></div>';
        $bookLink->link = $linkText;

        $defaultParamStr = 'module=unesco_oer&action=FilterProducts&adaptationstring=parent_id+is+not+null+and+deleted+%3D+0&page=2a_tpl.php';
        $condition = array('action' => 'JavaFilter');

        $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $adaptedProduct->getIdentifier(), 'nextAction' => 'ViewProduct', 'cancelAction' => 'FilterProducts', 'cancelParams' => $this->getCurrentParameterString($condition, $defaultParamStr)));
        $adaptLink = new link($uri);
        $adaptLink->cssClass = "adaptationLinks";
        $linkText = '<div class="imageBotomFlag"></div>';
        $adaptLink->link = $linkText;

        $objUser = $this->getObject('user', 'security');
        $imageBottomFlag = $adaptLink->show();

        $content.='
                   <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                   <div class="imageGridListing">' . $bookLink->show() . '
                     
                       ' . $thumbLink->show() . '
                       ' . $imageBottomFlag . '
                   </div>
                   <br>
                   <div class="orangeListingHeading">' . $abLink->show() . '</div>';

        $objInstitutionManager = $this->getObject('institutionmanager');
//Check the creator of the adaptation
        if ($adaptedProduct->getInstitutionID()) {
            $objInstitutionManager->getInstitution($adaptedProduct->getInstitutionID());
            $thumbnail = $objInstitutionManager->getInstitutionThumbnail();
            $type = $objInstitutionManager->getInstitutionType();
            $country = $objInstitutionManager->getInstitutionCountry();
            $name = $objInstitutionManager->getInstitutionName();

            $institutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $adaptedProduct->getInstitutionID())));
            $institutionLink->cssClass = 'darkGreyColour';
            $institutionLink->link = $name;

            $content .='<div class="adaptedByDiv">Adapted by:</div>
                <div class="gridSmallImageAdaptation">
                    <img src="' . $thumbnail . '" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                    <span class="greyListingHeading">' . $institutionLink->show() . '</span>
                </div>
                <div class="gridAdaptationLinksDiv">
                    <a class="productAdaptationGridViewLinks">' . $type . '</a> |
                    <a class="productAdaptationGridViewLinks">' . $country . '</a> <br>
                    <a class="productAdaptationGridViewLinks">' . $adaptedProduct->getLanguageName() . '</a>
                </div>
                ';
        } else {
            $groupInfo = $adaptedProduct->getGroupInfo();
            $objCountry = $this->getObject('languagecode', 'language');
            $country = $objCountry->getName($groupInfo['country']);

            $groupLink = new link($this->uri(array("action" => '11a', 'id' => $groupInfo['id'], "page" => '10a_tpl.php')));
            $groupLink->cssClass = 'darkGreyColour';
            $groupLink->link = $groupInfo['name'];

            $content .='
                <div class="adaptedByDiv greenColor">Managed by:</div>
                <div class="gridSmallImageAdaptation">
                    <img src="' . $groupInfo['thumbnail'] . '" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                    <span class="greyListingHeading">' . $groupLink->show() . '</span>
                </div>
                <div class="gridAdaptationLinksDiv">
<!--                    <a href="#" class="productAdaptationGridViewLinks">' . 'type' . '</a> | -->
                    <a href="#" class="productAdaptationGridViewLinks">' . $country . '</a> | <!--<br>-->
                    <a href="#" class="productAdaptationGridViewLinks">' . $adaptedProduct->getLanguageName() . '</a>
                </div>
                ';
        }
        return $content;
    }

    /**
     * This function populates a page with the adapted products in a listview
     * @param <type> $adaptedProduct
     * @return <type> $content
     */
    public function populateAdaptedListView($adaptedProduct) {
        
        
        $this->loadClass('link', 'htmlelements');
        
          $bookLink = new link('#');
        $bookLink->cssClass = "bookmarklinks";
         $bookLink->cssId = $adaptedProduct->getIdentifier();
        $linkText = 'Bookmark';
        $bookLink->link = $linkText;

        
        
        
        $content = '';
        $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $adaptedProduct->getIdentifier())));
        $abLink->cssClass = "adaptationListingLink";
        $abLink->link = $adaptedProduct->getTitle();
        $parentid = $adaptedProduct->getParentID();

        $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $adaptedProduct->getIdentifier())));
        $CommentLink->cssClass = 'adaptationLinks';
        $CommentLink->link = $adaptedProduct->getNoOfAdaptations() . ' ' . $this->objLanguage->languageText('mod_unesco_oer_adaptations', 'unesco_oer');


        /*
         * TODO Ntsako add code to check if the product was adapted by an institution or a group
         */

        $content.='
                    <div class="adaptationListView">
                        <div class="productAdaptationListViewLeftColumn">
                            <h2><a href="#" class="adaptationListingLink">' . $abLink->show() . ' </a></h2><br/><br/>
                            <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                           <div class="listingAdaptationLinkDiv">  ' . $CommentLink->show() . ' </div>
                        </div>';
        $instutionID = $adaptedProduct->getInstitutionID();
        if (empty ($instutionID)) {
            $groupLink = new link($this->uri(array("action" => '11', 'id' => $adaptedProduct->getGroupID())));
            $groupLink->cssClass = "greenText";
            $groupLink->link = $adaptedProduct->getGroupName();
            $content .= '<div class="productAdaptationListViewMiddleColumn">
                                <img src="skins/unesco_oer/images/icon-managed-by.png" alt="Managed by" width="24" height="24"><br>
                                <span class="greenText">Managed by</span>
                            </div>
                            <div class="productAdaptationListViewRightColumn">
                                <h2 class="greenText">' . $groupLink->show() . '</h2>
                                <br>
                                <div class="productAdaptationViewDiv">
                                    <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv">
                                        <a href="#" class="bookmarkLinks">' . $adaptedProduct->getLanguageName() . '</a> | <a href="#" class="bookmarkLinks"></a>
                                    </div>
                                </div>

                                <div class="productAdaptationViewDiv">
                                    <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv">'. $bookLink->show().'</div>
                                </div>

                                <div class="productAdaptationViewDiv">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="smallLisitngIcons">
                                    <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                </div>
                            </div>
                        </div>
                        ';
        } else {
            $this->_institutionGUI->getInstitution($instutionID);
            $name = $this->_institutionGUI->showInstitutionName();
            $creator = $adaptedProduct->getGroupName();

            $institutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $instutionID)));
            $institutionLink->link = $name;

            $content .='<div class="productAdaptationListViewMiddleColumn">
                            <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                            <span class="pinkText centerAlign">Adapted by</span>
                        </div>
                        <div class="productAdaptationListViewRightColumn">
                            <h2 class="darkGreyColour">' . $institutionLink->show() . '</h2>
                            <br>
                            <div class="productAdaptationViewDiv">
                                <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv">
                                    <a href="#" class="bookmarkLinks">' . $adaptedProduct->getLanguageName() . '</a> | <a href="#" class="bookmarkLinks"></a>
                                </div>
                            </div>

                            <div class="productAdaptationViewDiv">
                                <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv">'. $bookLink->show().'</div>
                            </div>

                            <div class="productAdaptationViewDiv">
                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                            </div>
                        </div>
                        </div>';
          
            
        }
        return $content;
    }

    /**
     * This function populates a "section" with the most adapted products in a most adapted tab
     * @param <type> $product
     * @return <type> $content
     */
    public function populateMostAdapted($product) {



        $origprouct = $this->objDbProducts->getProductByID($product['id']);

        if ($origprouct['deleted'] == '0') {

            $content = '';
            $parentid = $product['id'];

            $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
            $CommentLink->cssClass = 'adaptationLinks';
            $CommentLink->link = $this->objDbProducts->getNoOfAdaptations($product['id']) . ' ' . $this->objLanguage->languageText('mod_unesco_oer_adaptations', 'unesco_oer');
            $thumbnailPath = '';
//        if (file_exists($product['institution_thumbnail']) && is_file($product['institution_thumbnail'])) {
//            $thumbnailPath = $product['institution_thumbnail'];
//        } else {
//            $thumbnailPath = 'skins/unesco_oer/images/most-product-cover-placeholder.jpg';
//        }
            if (is_file($product['thumbnail'])) {
                $thumbnailPath = $product['thumbnail'];
            } else {
                $thumbnailPath = 'skins/unesco_oer/images/most-product-cover-placeholder.jpg';
            }
            $content .= '   <div class="leftImageTabsList"><img src="' . $thumbnailPath . '" alt="' . $product['creator'] . '" width="45" height="49"></div>
                                <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $CommentLink->show() . '</a>
                                </div>
             <div class="tabsListingSpace"></div>';
        }
        return $content;
    }

    /**
     * This function populates a "section of a page" with the most adapted products in a most adapted tab
     * @param <type> $objDbProducts, $objDbGroups, $objDbInstitution
     * @return <type> $content
     */
    public function displayMostAdapted(&$objDbProducts, &$objDbGroups, &$objDbInstitution, $displayAllMostAdaptedProducts) {
        $content = '';
//TODO Ntsako this might need Java script to implement properly as these tabs have to be updated independently
//Maybe have a table for the most Adapted, Rated and Commented to limit access times to the database

        $MostAdaptedProducts = $objDbProducts->getMostAdaptedProducts($displayAllMostAdaptedProducts);

        foreach ($MostAdaptedProducts as $childProduct) {
//Get the original products
            $product = $objDbProducts->getProductById($childProduct['parent_id']);
//Get number of adaptations for the product
            $product['noOfAdaptations'] = $childProduct['total'];
//Check if the creator is a group or an institution
            $isGroupCreator = $objDbGroups->isGroup($product['creator']);

//            if ($isGroupCreator == true) {
//                $thumbnail = $objDbGroups->getGroupThumbnail($product['creator']);
//            } else {
//                $this->_institution = $this->_institutionGUI->getInstitution($product['creator']);
//                //$thumbnail = $objDbInstitution->getInstitutionThumbnail();
//            }
//            $product['institution_thumbnail'] = $thumbnail['thumbnail'];
//$product['institution'] = $this->objInstitution->getInstitution();
            $content .= $this->populateMostAdapted($product);
        }

        $content .= '<div class="rightTextTabsList">
                <a href="#" class="adaptationLinks">' . $this->viewMostAdaptedLink($displayAllMostAdaptedProducts)->show() . '</a>
                                        </div>';

        return $content;
    }

    public function displayMostCommented(&$objDbProducts, &$objDbComments) {
        $content = '';
        $mostCommentedProducts = $objDbComments->getMostCommented(3);

        foreach ($mostCommentedProducts as $commentedProduct) {
            $product = $objDbProducts->getProductById($commentedProduct['product_id']);

            $product['noOfAdaptations'] = $objDbProducts->getNoOfAdaptations($commentedProduct['product_id']);
            $content .= $this->populateMostCommented($product);
        }

        return $content;
    }

    public function populateMostCommented($product) {

        $origprouct = $this->objDbProducts->getProductByID($product['id']);

        if ($origprouct['deleted'] == '0') {
            $content = '';

            $parentid = $product['id'];

            $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
            $CommentLink->cssClass = 'adaptationLinks';
            $CommentLink->link = $product['noOfAdaptations'] . ' ' . $this->objLanguage->languageText('mod_unesco_oer_adaptations', 'unesco_oer');

            $content .= '   <div class="leftImageTabsList"><img src="' . $product['thumbnail'] . '" alt="placeholder" width="45" height="49"></div>
                                        <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $CommentLink->show() . '</a>
                                        </div>
                                        <div class="tabsListingSpace"></div>';
        }
        return $content;
    }

    /**
     * This function populates a "section" with the most rated products in a most adapted tab
     * @param <type> $product
     * @return <type> $content
     */
    public function populateMostRated($product) {

        $origprouct = $this->objDbProducts->getProductByID($product['id']);

        if ($origprouct['deleted'] == '0') {
            $ratedLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['id'])));
            $ratedLink->cssClass = 'adaptationLinks';
            $ratedLink->link = "Rating = " . $product['rating'];

            $content = '';

            $content .= '   <div class="leftImageTabsList"><img src="' . $product['thumbnail'] . '" alt="placeholder" width="45" height="49"></div>
                                                <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $ratedLink->show() . ' </a>
                                                </div>
                                                <div class="tabsListingSpace"></div>   ';
        }
        return $content;
    }

    /**
     * This function populates a "section of a page" with the most rated products in a most adapted tab
     * @param <type> $objDbProducts, $objDbGroups, $objDbInstitution
     * @return <type> $content
     */
    public function displayMostRated(&$objDbProducts, &$objDbGroups, &$objDbInstitution, &$objDbProductRatings) {
        $content = '';
//TODO Ntsako this might need Java script to implement properly as these tabs have to be updated independently
//Maybe have a table for the most Adapted, Rated and Commented to limit access times to the database

        $mostRatedProducts = $objDbProductRatings->getMostRatedProducts();

        foreach ($mostRatedProducts as $childProduct) {
//Get the original products
            $product = $objDbProducts->getProductById($childProduct['product_id']);

//Get number of adaptations for the product
            $product['rating'] = $childProduct['avg_score'];

////Check if the creator is a group or an institution
            $isGroupCreator = $objDbGroups->isGroup($product['creator']);

            if ($isGroupCreator == true) {
                $thumbnail = $objDbGroups->getGroupThumbnail($product['creator']);
            } else {
                $thumbnail = $objDbInstitution->getInstitutionThumbnail($product['creator']);
            }




//$product['institution'] = $this->objInstitution->getInstitution();
            $content .= $this->populateMostRated($product);
        }

        return $content;
    }

    /**
     * This function Builds the String to Send to the DBhandler and return the total number of entries according to the selected Filter
     * @param <type>$AuthFilter,$ThemeFilter,$LangFilter,$page,$sort,$TotalPages,$adaptationstring,$Model,$Handbook,$Guide,$Manual,$Besoractile
     * @return <type> $TotalEntries
     */

    /**
     * This function creates the link to display more adaptations
     * @param <type> $displayAllMostAdaptedProducts checks if the user has chosen to display all the most adapted products
     * @return <type> $moreAdaptedProductsLink;
     */
    private function viewMostAdaptedLink($displayAllMostAdaptedProducts) {
        if ($displayAllMostAdaptedProducts) {
            $mostAdaptedProductsArray = array("action" => 'viewAllMostAdaptedProducts', "displayAllMostAdaptedProducts" => false);
            $moreAdaptedProductsLink = new link($this->uri($mostAdaptedProductsArray));
            $moreAdaptedProductsLink->link = 'less...';
        } else {
            $mostAdaptedProductsArray = array("action" => 'viewAllMostAdaptedProducts', "displayAllMostAdaptedProducts" => true);
            $moreAdaptedProductsLink = new link($this->uri($mostAdaptedProductsArray));
            $moreAdaptedProductsLink->link = 'more...';
        }

        return $moreAdaptedProductsLink;
    }

    public function populatebookmark($product) {

        $content = '     
                           <script src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
                            <script>
                           $(document).ready(function(){';

        //   foreach ($data as $products) {
        //  for ($i = $start; $i < ($end); $i++) { 

        $temp = str_replace(" ", "", $product['title']);

        $divheading = '.' . $temp . 'Div';
        $linkheading = '.' . $temp . 'Link';
        $titleheading = '.' . $temp . 'Title';
        $btnheading = '#' . $temp . 'btn';
        $cancelbtnheading = '#' . $product['id'] . "cancelbtn";
        $cancelbtnid = $product['id'] . "cancelbtn";
        $content.= "
                  $('$divheading').hide();

                  $('$linkheading').show();
           
                  $('$linkheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading ').slideToggle(); 

                  });
            
            $('$btnheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading ').slideToggle(); 

                  });
            
               $('$cancelbtnheading').click(function(){

                  $('$divheading').slideToggle();
                  $('$titleheading ').slideToggle();
                   
                 

                  });"

        ;


        $content .= '        

                                    });

                            </script>
                                        ';


        //  foreach ($data as $products) {
        //  for ($i = $start; $i < ($end); $i++) { 
        $temp = str_replace(" ", "", $product['title']);
        $divheading = $temp . 'Div';
        $linkheading = $temp . 'Link';
        $titleheading = $temp . 'Title';
        $btnheading = $temp . 'btn';



        $parentid = $product['id'];

        $textname = $temp . "text";
        $commentboxname = $temp . "comment";
        $textinput = new textinput($textname);
        $textinput->value = $product['title'];

        $commentText = new textarea($commentboxname);
        $commentText->setCssClass("commentTextBox");
        //TODO make parameter pagename dynamic


        $button = new button('submitComment', "Save bookmark");
        $button->cssId = $btnheading;

        $cancelbtn = new button('Cancel', "Cancel");
        $cancelbtn->cssId = $cancelbtnid;


        $time = time();
        $userid = $this->objUser->userId();
        //  $userid = objdbuserextra->

        $location = $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $bookmarks = $this->objbookmarkmanager->getBookmarkbyID($product['id'], $userid);
        $bookmarkid = $bookmarks[0]['id'];

        if ($bookmarks[0]['product_id'] != $parentid) {
            $button->onclick = "javascript:bookmarksave('$time','$parentid','$userid','$textname','$commentboxname') ;";
            $textinput->value = $product['title'];
        } else {

            $button->onclick = "  javascript:bookmarkupdate('$time','$textname','$commentboxname','$bookmarkid')  ";
            $textinput->value = $bookmarks[0]['label'];
            $commentText->value = $bookmarks[0]['description'];
        }



        $form = new form('3a_comments_ui', $uri);
        $form->addToForm("Label * <br>");
        $form->addToForm($textinput);
        $form->addToForm("<br>Bookmark Description *<br> ");
        $form->addToForm($commentText);
        $form->addToForm("<br><br>");

        $form->addToForm($button->show()); //TODO use text link instead of button
        $form->addToForm($cancelbtn->show());





        /* if ($product['new'] == 'true') {
          $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
          } */

//This so
        $content.="
                  
           
                    
                <a href='javascript:void(0)'   class='$linkheading'> <img src='skins/unesco_oer/images/small-icon-bookmark.png' alt='Email' width='19' height='15'></a>
   
                
                ";




        $content .= "
                    
   <div class='$divheading'> 
                
 
                
             
                " . $form->show() . "
     
                </div>

        ";

        return $content;
    }

    function getCurrentParameterString($condtion = NULL, $default = NULL) {
        $parameterList = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);
        $parameterList = str_replace('&', '__', $parameterList);
        if ($condtion != NULL) {
            $parameterArray = $this->createParameterArray($parameterList);
            foreach ($condtion as $key => $value) {
                if ($parameterArray[$key] == $value)
                    return str_replace('&', '__', $default);
            }
        }
        return $parameterList;
    }

    function createParameterArray($parameterString) {
        if (empty($parameterString))
            return NULL;
        $tempArray = explode('__', $parameterString);
        $parameterArray = array();
        foreach ($tempArray as $paramPairString) {
            $paramPairArray = explode('=', $paramPairString);

            $parameterArray[urldecode($paramPairArray[0])] = urldecode($paramPairArray[1]);
        }
        //    unset($parameterArray['action']);
        //    unset($parameterArray['module']);
        return $parameterArray;
    }

//Include the following div in the pages where it is required
//
//<div id="bubble_tooltip">
//    <div class="bubble_top"><span></span></div>
//    <div class="bubble_middle"><span id="bubble_tooltip_content">Content is comming here as you probably can see.Content is comming here as you probably can see.</span></div>
//    <div class="bubble_bottom"></div>
//</div>
    function getToolTip($toolTip = NULL, $html = NULL) {
        $js = '<script language="JavaScript" src="' . $this->getResourceUri('bubble-tooltip.js') . '" type="text/javascript"></script>';
        $this->appendArrayVar('headerParams', $js);
        $link = '<link href="' . $this->getResourceUri('bubble-tooltip.css') . '" rel="stylesheet" type="text/css"/>';
        $this->appendArrayVar('headerParams', $link);
        if (empty($toolTip)) {
            $objLanguage = $this->getObject('language', 'language');
            $toolTip = $objLanguage->languageText('mod_unesco_oer_no_tooltip', 'unesco_oer');
        }
        if (empty($html))
            $html = "<img src='skins/unesco_oer/images/icon-help.png' alt='help' width='15' height='15'>";

        return ' <div class="tempME" style="display:inline;"  onmouseover="showToolTip(event,\'' . $toolTip . '\');return false" onmouseout="hideToolTip()">' . $html . '</div>';
    }

    function smart_trim($text, $max_len, $trim_middle = false, $trim_chars = '...') {
        $text = trim($text);

        if (strlen($text) < $max_len) {

            return $text;
        } elseif ($trim_middle) {

            $hasSpace = strpos($text, ' ');
            if (!$hasSpace) {
                /**
                 * The entire string is one word. Just take a piece of the
                 * beginning and a piece of the end.
                 */
                $first_half = substr($text, 0, $max_len / 2);
                $last_half = substr($text, -($max_len - strlen($first_half)));
            } else {
                /**
                 * Get last half first as it makes it more likely for the first
                 * half to be of greater length. This is done because usually the
                 * first half of a string is more recognizable. The last half can
                 * be at most half of the maximum length and is potentially
                 * shorter (only the last word).
                 */
                $last_half = substr($text, -($max_len / 2));
                $last_half = trim($last_half);
                $last_space = strrpos($last_half, ' ');
                if (!($last_space === false)) {
                    $last_half = substr($last_half, $last_space + 1);
                }
                $first_half = substr($text, 0, $max_len - strlen($last_half));
                $first_half = trim($first_half);
                if (substr($text, $max_len - strlen($last_half), 1) == ' ') {
                    /**
                     * The first half of the string was chopped at a space.
                     */
                    $first_space = $max_len - strlen($last_half);
                } else {
                    $first_space = strrpos($first_half, ' ');
                }
                if (!($first_space === false)) {
                    $first_half = substr($text, 0, $first_space);
                }
            }

            return $first_half . $trim_chars . $last_half;
        } else {

            $trimmed_text = substr($text, 0, $max_len);
            $trimmed_text = trim($trimmed_text);
            if (substr($text, $max_len, 1) == ' ') {
                /**
                 * The string was chopped at a space.
                 */
                $last_space = $max_len;
            } else {
                /**
                 * In PHP5, we can use 'offset' here -Mike
                 */
                $last_space = strrpos($trimmed_text, ' ');
            }
            if (!($last_space === false)) {
                $trimmed_text = substr($trimmed_text, 0, $last_space);
            }
            return $trimmed_text . $trim_chars;
        }
    }

    function addThis($img_link, $width = 125, $height = 16) {

        $content = '
            <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=xa-4e09cecf254052c9">
                <img src="' . $img_link . '" width="' . $width . '" height="' . $height . '" alt="Share">
            </a>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e09cecf254052c9"></script>
        ';
        return $content;
    }

}

?>
