<?php

$this->loadClass('link', 'htmlelements');
//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
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
         $this->objDbproductlanguages = $this->getObject("dbproductlanguages", "unesco_oer");
        $this->objDbAvailableProductLanguages = $this->getObject("dbavailableproductlanguages", "unesco_oer");
        $this->objUser = $this->getObject("user", "security");
    }

    /**
     * This function populates a page with the original products in a gridview
     * @param <type> $product
     * @return <type> $content
     */
    public function populateGridView($product) {


        $content = '';
        $uri = $this->uri(array("action" => 'ViewProduct', "id" => $product['id']));
        $abLink = new link($uri);
        $abLink->cssClass = "listingLanguageLinkAndIcon";
        $abLink->link = $product['title'];

        $thumbLink = new link($uri);
        $thumbLink->link = '<img src="' . $product['thumbnail'] . '" width="79" height="101">';

        $parentid = $product['id'];

        $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
        $CommentLink->cssClass = 'adaptationLinks';
        $CommentLink->link = $product['noOfAdaptations'] . ' Adaptations';

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

        $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $parentid , 'prevAction' => 'home'));
        $adaptLink = new link($uri);
        $adaptLink->cssClass = "adaptationLinks";
        $linkText = '  ';
        $adaptLink->link = $linkText;

        $objUser = $this->getObject('user', 'security');
        $imageBottomFlag = $this->objUser->isLoggedIn() ? '<div class="imageBotomFlag">'. $adaptLink->show() .'</div>' : '';

        $content.='
                                <div class="imageGridListing">
                                    <div class="imageTopFlag"></div>
                                    '. $thumbLink->show() .'
                                    '. $imageBottomFlag .'
                                </div>
                                <br>
                                <div class="blueListingHeading">' . $abLink->show() . '</div>
                                    <br>
                                <div class="listingLanguageLinkAndIcon">
                                    <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                    <div class="listingLanuagesDropdownDiv">
                                        <select name="" class="listingsLanguageDropDown">';

        $index = 0;
         $prodLanguages = $this->objDbproductlanguages->getLanguageNameByID($product['language']);
         $content .= '<option value="">' . $prodLanguages . '</option>';

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
                                        </select>
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
    public function populateListView($start,$end,$products) {

        $content = '     
                                      <script src="http://code.jquery.com/jquery-latest.js"></script>
                            <script>
                           $(document).ready(function(){';



       // foreach ($data as $products) {
        
        for ($i = $start; $i < ($end); $i++) { 
            
             $temp = str_replace (" ", "", $products[$i]['title']);

            $divheading = '.' . $temp . 'Div';
            $linkheading = '.' . $temp . 'Link';
            $titleheading = '.' . $temp . 'Title';

            $content.= "
                  $('$divheading').hide();

                  $('$linkheading').show();
                 


 

                  $('$linkheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading ').slideToggle(); 

                  });";
        }

        $content .= '        


                                    });

                            </script>
                                        ';











      //  foreach ($data as $products) {
         for ($i = $start; $i < ($end); $i++) { 

             
             $temp = str_replace (" ", "", $products[$i]['title']);
            $divheading = $temp. 'Div';
            $linkheading = $temp . 'Link';
            $titleheading = $temp . 'Title';

            $products[$i]['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($products[$i]['id']);
            $languages = $this->objDbAvailableProductLanguages->getProductLanguage($products[$i]['id']);
            $product = $products[$i] + $languages;


         //   $editbutton = new button( "Submit","Submit");
          //  $editbutton->cssClass = "listingLanguageLinkAndIcon";


            $parentid = $product['id'];
          $textname = $temp . "text";
          $commentboxname = $temp . "comment";
            $textinput = new textinput($textname);
            $textinput->value = $product['title'];

            $commentText = new textarea($commentboxname);
            $commentText->setCssClass("commentTextBox");

            //TODO make parameter pagename dynamic
            $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));

            $button = new button('submitComment'," Submit Comment");
            $time = time();
            //  $userid = objdbuserextra->
             $userid = $this->objUser->userId();

            

            $location = $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            
            
            $button->onclick = "javascript:bookmarkupdate('$time','$parentid','$userid','$textname','$commentboxname')";
      




            $form = new form('3a_comments_ui', $uri);
            $form->addToForm($textinput);
            $form->addToForm("<br><br>");
            $form->addToForm($commentText);
            $form->addToForm("<br><br>");

            $form->addToForm($button->show()); //TODO use text link instead of button










            $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['id'])));
            $abLink->cssClass = "listingLanguageLinkAndIcon";
            $abLink->link = $product['title'];

            $parentid = $product['id'];

            $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
            $CommentLink->cssClass = 'adaptationLinks';
            $CommentLink->link = $product['noOfAdaptations'] . ' Adaptations';

            /* if ($product['new'] == 'true') {
              $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
              } */

//This some how forces the page to display the 0
            if ($product['noOfAdaptations'] == 0) {
                $product['noOfAdaptations'] = 0;
            }

            $content.="
                  <div class='productsListView'>
                   <h2>" . $abLink->show() . "</h2><br>
                    <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/icon-new.png' alt='New' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'>new</div>
                  	</div>
                    <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/small-icon-adaptations.png' alt='Adaptation' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'><a href='#' class='adaptationLinks'>" . $CommentLink->show() . " </a></div>
                    </div>
                    <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/small-icon-bookmark.png' alt='Bookmark' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'>
                        

            </div>
                    
                <a href='javascript:void(0)'   class='$linkheading'>Bookmark
              
                
                        

                            
                  
                    
                

        

      
                   </div>
                 
                   
                
        
                




              
              <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/small-icon-make-adaptation.png' alt='Make Adaptation' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'><a href='#' class='adaptationLinks'>make adaptation</a></div>
                  </div>
                    <div class='productlistViewLeftFloat'>
                      <img src='skins/unesco_oer/images/icon-languages.png' alt='Languages search' width='24' height='24'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'>
                        	<select name='' class='listingsLanguageDropDown'>
                
                
                
                
                ";

            $index = 0;
            $prodLanguages = $this->objDbproductlanguages->getLanguageNameByID($product['language']);
         $content .= '<option value="">' . $prodLanguages . '</option>';
//            foreach ($product as $languages) {
////Check if languages is empty
//                foreach ($languages as $language) {
////print_r($language);
//                    $content .= '<option value="">' . $language . '</option>';
//                    $index++;
//                }
//            }
//            if ($index == 0) {
//                $content .= '<option value="">' . $prodLanguages[0]['name']. '</option>';
//            }

            $content .= "</select>
                        </div>
        
                    </div> <br><br><br><br>
       
       
                    
   <div class='$divheading'> 
                
 
                
             
                " . $form->show().  "
                                            
                  
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
        $content = '';
        $abLink = new link($this->uri(array("action" => 'ViewProduct', 'id' => $adaptedProduct['id'])));
        $CommentLink->cssClass = 'adaptationLinks';
        $abLink->link = $adaptedProduct['title'];

        /* if ($product['new'] == 'true') {
          $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
          } */

        /*
         * TODO Ntsako add code to check if the product was adapted by an institution or a group
         */

//This some how forces the page to display the 0
        if ($product['noOfAdaptations'] == 0) {
            $product['noOfAdaptations'] = 0;
        }

        $content.='
                   <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>
                   <div class="imageGridListing">
                       <div class="imageTopFlag"></div>
                       <img src="' . $adaptedProduct['thumbnail'] . '" width="79" height="101" alt="Placeholder">
                       <div class="imageBotomFlag"></div>
                   </div>
                   <br>
                   <div class="orangeListingHeading">' . $abLink->show() . '</div>';

//Check the creator of the adaptation
        if ($adaptedProduct['group_thumbnail'] != NULL) {
            $content .='
                <div class="adaptedByDiv greenColor">Managed by:</div>
                <div class="gridSmallImageAdaptation">
                    <img src="' . $adaptedProduct['group_thumbnail'] . '" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                    <span class="greyListingHeading">' . $adaptedProduct['creator'] . '</span>
                </div>
                <div class="gridAdaptationLinksDiv">
                    <a href="#" class="productAdaptationGridViewLinks">' . $adaptedProduct['type'] . '</a> |
                    <a href="#" class="productAdaptationGridViewLinks">' . $adaptedProduct['country'] . '</a> <br>
                    <a href="#" class="productAdaptationGridViewLinks">' . $adaptedProduct['language'] . '</a>
                </div>
                ';
        } else {
            $this->_institutionGUI->getInstitution($adaptedProduct['creator']);
            $thumbnail = $this->_institutionGUI->showInstitutionThumbnail();
            $type = $this->_institutionGUI->showInstitutionType();
            $country = $this->_institutionGUI->showInstitutionCountry();
            $name = $this->_institutionGUI->showInstitutionName();
            $creator = $adaptedProduct['creator'];

            $institutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $creator)));
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
                    <a class="productAdaptationGridViewLinks">' . $adaptedProduct['language'] . '</a>
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
        $content = '';
        $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['id'])));
        $abLink->cssClass = "listingLanguageLinkAndIcon";
        $abLink->link = $adaptedProduct['title'];

        $parentid = $adaptedProduct['id'];

        $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
        $CommentLink->cssClass = 'adaptationLinks';
        $CommentLink->link = $adaptedProduct['noOfAdaptations'] . ' Adaptations';

        /* if ($product['new'] == 'true') {
          $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
          } */

//This some how forces the page to display the 0
        if ($product['noOfAdaptations'] == 0) {
            $product['noOfAdaptations'] = 0;
        }

        /*
         * TODO Ntsako add code to check if the product was adapted by an institution or a group
         */

        $content.='
                    <div class="adaptationListView">
                        <div class="productAdaptationListViewLeftColumn">
                            <h2><a href="#" class="adaptationListingLink">' . $abLink->show() . ' </a></h2><br>
                            <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                            <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (' . $CommentLink->show() . ')</a></div>
                        </div>';
        if ($adaptedProduct['group_thumbnail'] != NULL) {
            $content .= '<div class="productAdaptationListViewMiddleColumn">
                                <img src="images/icon-managed-by.png" alt="Managed by" width="24" height="24"><br>
                                <span class="greenText">Managed by</span>
                            </div>
                            <div class="productAdaptationListViewRightColumn">
                                <h2 class="greenText">' . $adaptedProduct['creator'] . '</h2>
                                <br>
                                <div class="productAdaptationViewDiv">
                                    <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                    <div class="listingAdaptationLinkDiv">
                                        <a href="#" class="bookmarkLinks">' . $adaptedProduct['language'] . '</a> | <a href="#" class="bookmarkLinks"></a>
                                    </div>
                                </div>

                                <div class="productAdaptationViewDiv">
                                    <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                    <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                                </div>

                                <div class="productAdaptationViewDiv">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                    <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
                                </div>
                            </div>
                        </div>
                        ';
        } else {
            $this->_institutionGUI->getInstitution($adaptedProduct['creator']);
            $name = $this->_institutionGUI->showInstitutionName();
            $creator = $adaptedProduct['creator'];

            $institutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $creator)));
            $institutionLink->cssClass = 'darkGreyColour';
            $institutionLink->link = $name;

            $content .='<div class="productAdaptationListViewMiddleColumn">
                            <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                            Adapted by
                        </div>
                        <div class="productAdaptationListViewRightColumn">
                            <h2 class="darkGreyColour">' . $institutionLink->show() . '</h2>
                            <br>
                            <div class="productAdaptationViewDiv">
                                <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                <div class="listingAdaptationLinkDiv">
                                    <a href="#" class="bookmarkLinks">' . $adaptedProduct['language'] . '</a> | <a href="#" class="bookmarkLinks"></a>
                                </div>
                            </div>

                            <div class="productAdaptationViewDiv">
                                <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                                <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="bookmarkLinks">bookmark</a></div>
                            </div>

                            <div class="productAdaptationViewDiv">
                                <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                                <div class="listingAdaptationLinkDiv paddingSpaceProductAdaptationRightColumnListView"><a href="#" class="adaptationLinks">make adaptation</a></div>
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
        $content = '';
        $parentid = $product['id'];

        $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
        $CommentLink->cssClass = 'adaptationLinks';
        $CommentLink->link = $product['noOfAdaptations'] . ' Adaptations';
        $thumbnailPath = '';
        if (file_exists($product['institution_thumbnail']) && is_file($product['institution_thumbnail'])) {
            $thumbnailPath = $product['institution_thumbnail'];
        } else {
            $thumbnailPath = 'skins/unesco_oer/images/most-product-cover-placeholder.jpg';
        }
        $content .= '   <div class="leftImageTabsList"><img src="' . $thumbnailPath . '" alt="' . $product['creator'] . '" width="45" height="49"></div>
                                <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $CommentLink->show() . '</a>
                                </div>
                                <div class="tabsListingSpace"></div>';
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
            $product = $objDbProducts->getProductById($childProduct['relation']);
//Get number of adaptations for the product
            $product['noOfAdaptations'] = $childProduct['total'];
//Check if the creator is a group or an institution
            $isGroupCreator = $objDbGroups->isGroup($product['creator']);

            if ($isGroupCreator == true) {
                $thumbnail = $objDbGroups->getGroupThumbnail($product['creator']);
            } else {
                $this->_institution = $this->_institutionGUI->getInstitution($product['creator']);
                //$thumbnail = $objDbInstitution->getInstitutionThumbnail();
            }

            $product['institution_thumbnail'] = $thumbnail['thumbnail'];
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
        $content = '';

        $parentid = $product['id'];

        $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
        $CommentLink->cssClass = 'adaptationLinks';
        $CommentLink->link = $product['noOfAdaptations'] . ' Adaptations';

        $content .= '   <div class="leftImageTabsList"><img src="' . $product['thumbnail'] . '" alt="placeholder" width="45" height="49"></div>
                                        <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $CommentLink->show() . '</a>
                                        </div>
                                        <div class="tabsListingSpace"></div>';
        return $content;
    }

    /**
     * This function populates a "section" with the most rated products in a most adapted tab
     * @param <type> $product
     * @return <type> $content
     */
    public function populateMostRated($product) {
        
        
        $ratedLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['id'])));
        $ratedLink->cssClass = 'adaptationLinks';
        $ratedLink->link = "Rating = " .$product['rating'];
        
        $content = '';

        $content .= '   <div class="leftImageTabsList"><img src="' . $product['thumbnail'] . '" alt="placeholder" width="45" height="49"></div>
                                                <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $ratedLink->show() . ' </a>
                                                </div>
                                                <div class="tabsListingSpace"></div>   ';
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

    public function BrowseAdaptation($lat, $lng) {


        $buildstring = ' where loclat = ' . "'$lat'" . ' and loclong = ' . "'$lng'";






        return $buildstring;
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







public function populateListViewtemp($data) {

        $content = '     
                                      <script src="http://code.jquery.com/jquery-latest.js"></script>
                            <script>
                           $(document).ready(function(){';



        foreach ($data as $products) {
        
      //  for ($i = $start; $i < ($end); $i++) { 

            $divheading = '.' . $products['title'] . 'Div';
            $linkheading = '.' . $products['title'] . 'Link';
            $titleheading = '.' . $products['title'] . 'Title';

            $content.= "
                  $('$divheading').hide();

                  $('$linkheading').show();
                 


 

                  $('$linkheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading ').slideToggle(); 

                  });";
        }

        $content .= '        


                                    });

                            </script>
                                        ';











       foreach ($data as $products) {
       //  for ($i = $start; $i < ($end); $i++) { 

            $divheading = $products['title'] . 'Div';
            $linkheading = $products['title'] . 'Link';
            $titleheading = $products['title'] . 'Title';

            $products['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($products['id']);
            $languages = $this->objDbAvailableProductLanguages->getProductLanguage($products['id']);
            $product = $products + $languages;


            $editbutton = new button();
            $editbutton->cssClass = "listingLanguageLinkAndIcon";


            $parentid = $product['id'];

            $textinput = new textinput("bookmarktitle");
            $textinput->value = $product['title'];

            $commentText = new textarea('newComment');
            $commentText->setCssClass("commentTextBox");

            //TODO make parameter pagename dynamic
            $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));

            $button = new button('submitComment', $this->uri(array("action" => 'bookmarkdata', "label" => $product['id'])));
            $time = time();
            //  $userid = objdbuserextra->

            $location = $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $button->onclick = "javascript:bookmarkupdate('$location','$time')";
            $userid = $this->objUser->userId();




            $form = new form('3a_comments_ui', $uri);
            $form->addToForm($textinput);
            $form->addToForm("<br><br>");
            $form->addToForm($commentText);
            $form->addToForm("<br><br>");

            $form->addToForm($button->show()); //TODO use text link instead of button










            $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['id'])));
            $abLink->cssClass = "listingLanguageLinkAndIcon";
            $abLink->link = $product['title'];

            $parentid = $product['id'];

            $CommentLink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $parentid)));
            $CommentLink->cssClass = 'adaptationLinks';
            $CommentLink->link = $product['noOfAdaptations'] . ' Adaptations';

            /* if ($product['new'] == 'true') {
              $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
              } */

//This some how forces the page to display the 0
            if ($product['noOfAdaptations'] == 0) {
                $product['noOfAdaptations'] = 0;
            }

            $content.="
                  <div class='productsListView'>
                   <h2>" . $abLink->show() . "</h2><br>
                    <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/icon-new.png' alt='New' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'>new</div>
                  	</div>
                    <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/small-icon-adaptations.png' alt='Adaptation' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'><a href='#' class='adaptationLinks'>" . $CommentLink->show() . " </a></div>
                    </div>
                    <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/small-icon-bookmark.png' alt='Bookmark' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'>
                        

            </div>
                    
                <a href='javascript:void(0)'   class='$linkheading'>Bookmark
              
                
                        

                            
                  
                    
                

        

      
                   </div>
                 
                   
                
        
                




              
              <div class='productlistViewLeftFloat'>
                        <img src='skins/unesco_oer/images/small-icon-make-adaptation.png' alt='Make Adaptation' width='18' height='18'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'><a href='#' class='adaptationLinks'>make adaptation</a></div>
                  </div>
                    <div class='productlistViewLeftFloat'>
                      <img src='skins/unesco_oer/images/icon-languages.png' alt='Languages search' width='24' height='24'class='imgFloatRight'>
                        <div class='listingAdaptationLinkDiv'>
                        	<select name='' class='listingsLanguageDropDown'>
                
                
                
                
                ";

            $index = 0;
            $prodLanguages = $this->objDbproductlanguages->getLanguageNameByID($product['language']);
         $content .= '<option value="">' . $prodLanguages . '</option>';
//            foreach ($product as $languages) {
////Check if languages is empty
//                foreach ($languages as $language) {
////print_r($language);
//                    $content .= '<option value="">' . $language . '</option>';
//                    $index++;
//                }
//            }
//            if ($index == 0) {
//                $content .= '<option value="">' . $prodLanguages[0]['name']. '</option>';
//            }

            $content .= "</select>
                        </div>
        
                    </div> <br><br><br><br>
       
       
                    
   <div class='$divheading'> 
                
 
                
             
                " . $form->show() . "
                                            
                  
                </div>
                
             </div>

        ";
        }
        return $content;
    }







}

?>
