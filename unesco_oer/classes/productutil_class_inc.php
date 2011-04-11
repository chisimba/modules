<?php

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

class productutil extends object
{

    public function init()
    {

    }

    /**
     * This function populates a page with the original products in a gridview
     * @param <type> $product
     * @return <type> $content
     */
    public function populateGridView($product)
    {
        $content = '';

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

        $content.='
                                <div class="imageGridListing">
                                    <div class="imageTopFlag"></div>
                                    <img src="' . $product['thumbnail'] . '" width="79" height="101">
                                    <div class="imageBotomFlag"></div>
                                </div>
                                <br>
                                <div class="blueListingHeading">' . $product['title'] . '</div>
                                <div class="listingLanguageLinkAndIcon">
                                    <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                    <div class="listingLanuagesDropdownDiv">
                                        <select name="" class="listingsLanguageDropDown">
                                            <option value="">' . $product['language'] . '</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                    <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks"> ' . $product['noOfAdaptations'] . ' adaptations</a></div>
                                </div>
';
        return $content;
    }

    /**
     * This function populates a page with the original products in a listview
     * @param <type> $product
     * @return <type> $content
     */
    public function populateListView($product)
    {
        $content = '';

        /* if ($product['new'] == 'true') {
          $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
          } */

        //This some how forces the page to display the 0
        if ($product['noOfAdaptations'] == 0) {
            $product['noOfAdaptations'] = 0;
        }

        $content.='
                  <div class="productsListView">
                   <h2>' . $product['title'] . '</h2><br>
                    <div class="productlistViewLeftFloat">
                        <img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"class="imgFloatRight">
                        <div class="listingAdaptationLinkDiv">new</div>
                  	</div>
                    <div class="productlistViewLeftFloat">
                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">' . $product['noOfAdaptations'] . ' adaptations </a></div>
                    </div>
                    <div class="productlistViewLeftFloat">
                        <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18"class="imgFloatRight">
                        <div class="listingAdaptationLinkDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                  </div>
                    <div class="productlistViewLeftFloat">
                        <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="Make Adaptation" width="18" height="18"class="imgFloatRight">
                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">make adaptation</a></div>
                  </div>
                    <div class="productlistViewLeftFloat">
                      <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                        <div class="listingAdaptationLinkDiv">
                        	<select name="" class="listingsLanguageDropDown">
                            	<option value="">' . $product['language'] . '</option>
                            </select>
                        </div>
                    </div>
                </div>
                    ';
        return $content;
    }

    /**
     * This function populates a page with the adapted products in a gridview
     * @param <type> $adaptedProduct
     * @return <type> $content
     */
    public function populateAdaptedGridView($adaptedProduct)
    {
        $content = '';

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
                   <div class="orangeListingHeading">' . $adaptedProduct['title'] . '</div>
                   <div class="adaptedByDiv">Adapted by:</div>
                   <div class="gridSmallImageAdaptation">
                       <img src="' . $adaptedProduct['institutionLogo'] . '" alt="Adaptation placeholder" class="smallAdaptationImageGrid">
                       <span class="greyListingHeading">' . $adaptedProduct['adaptedBy'] . '</span>
                   </div>
                   <div class="gridAdaptationLinksDiv">
                       <a href="#" class="productAdaptationGridViewLinks">School</a> |
                       <a href="#" class="productAdaptationGridViewLinks">' . $adaptedProduct['countryOfAdaptation'] . '</a> <br>
                       <a href="#" class="productAdaptationGridViewLinks">' . $adaptedProduct['languageOfAdaptation'] . '</a>
                   </div>
                    ';
        return $content;
    }

    /**
     * This function populates a page with the adapted products in a listview
     * @param <type> $adaptedProduct
     * @return <type> $content
     */
    public function populateAdaptedListView($adaptedProduct)
    {
        $content = '';

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
                            <h2><a href="#" class="adaptationListingLink">' . $adaptedProduct['title'] . ' </a></h2><br>
                            <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                            <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">See all adaptations (' . $adaptedProduct['noOfAdaptations'] . ')</a></div>
                        </div>

                        <div class="productAdaptationListViewMiddleColumn">
                            <img src="skins/unesco_oer/images/icon-adapted-by.png" alt="Adapted by" width="24" height="24"><br>
                            Adapted by
                        </div>
                        <div class="productAdaptationListViewRightColumn">
                            <h2 class="darkGreyColour">' . $adaptedProduct['adaptedBy'] . '</h2>
                            <br>
                            <div class="productAdaptationViewDiv">
                                <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                <div class="listingAdaptationLinkDiv">
                                    <a href="#" class="bookmarkLinks">English*</a> | <a href="#" class="bookmarkLinks">German*</a>
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
        return $content;
    }

        /**
     * This function populates a "section" with the most adapted products in a most adapted tab
     * @param <type> $product
     * @return <type> $content
     */
    public function populateMostAdapted($product){
        $content = '';

        $content .=        '   <div class="leftImageTabsList"><img src="' . $product['institution_thumbnail'] . '" alt="placeholder" width="45" height="49"></div>
                                <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $product['noOfAdaptations']. ' adaptations</a>
                                </div>
                                <div class="tabsListingSpace"></div>';
        return $content;
    }

            /**
     * This function populates a "section of a page" with the most adapted products in a most adapted tab
     * @param <type> $objDbProducts, $objDbGroups, $objDbInstitution
     * @return <type> $content
     */

    public function displayMostAdapted(&$objDbProducts, &$objDbGroups, &$objDbInstitution)
    {
        $content ='';
                                                    //TODO Ntsako this might need Java script to implement properly as these tabs have to be updated independently
                                            //Maybe have a table for the most Adapted, Rated and Commented to limit access times to the database

                                            $MostAdaptedProducts = $objDbProducts->getMostAdaptedProducts();

                                            foreach ($MostAdaptedProducts as $childProduct) {
                                                //Get the original products
                                                $product = $objDbProducts->getProductById($childProduct['parent_id']);
                                                //Get number of adaptations for the product
                                                $product['noOfAdaptations'] = $childProduct['total'];
                                                //Check if the creator is a group or an institution
                                                $isGroupCreator = $objDbGroups->isGroup($product['creator']);

                                                if ($isGroupCreator == true) {
                                                    $thumbnail = $objDbGroups->getGroupThumbnail($product['creator']);
                                                } else {
                                                    $thumbnail = $objDbInstitution->getInstitutionThumbnail($product['creator']);
                                                }

                                                $product['institution_thumbnail'] = $thumbnail['thumbnail'];
                                                //$product['institution'] = $this->objInstitution->getInstitution();
                                                $content .= $this->populateMostAdapted($product);
                                            }

                                            return $content;
    }

    public function displayMostCommented(&$objDbProducts, &$objDbComments){
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

        $content .= '   <div class="leftImageTabsList"><img src="' . $product['thumbnail'] . '" alt="placeholder" width="45" height="49"></div>
                                <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">' . $product['noOfAdaptations'] . ' adaptations</a>
                                </div>
                                <div class="tabsListingSpace"></div>';
        return $content;
    }

            /**
     * This function populates a "section" with the most rated products in a most adapted tab
     * @param <type> $product
     * @return <type> $content
     */
    public function populateMostRated($product){
        $content = '';

        $content .=        '   <div class="leftImageTabsList"><img src="' . $product['institution_thumbnail'] . '" alt="placeholder" width="45" height="49"></div>
                                <div class="rightTextTabsList">
                        	' . $product['title'] . '<br><a href="#" class="adaptationLinks">Rating = ' . $product['rating']. ' </a>
                                </div>
                                <div class="tabsListingSpace"></div>';
        return $content;
    }

            /**
     * This function populates a "section of a page" with the most rated products in a most adapted tab
     * @param <type> $objDbProducts, $objDbGroups, $objDbInstitution
     * @return <type> $content
     */

    public function displayMostRated(&$objDbProducts, &$objDbGroups, &$objDbInstitution, &$objDbProductRatings)
    {
        $content ='';
                                                    //TODO Ntsako this might need Java script to implement properly as these tabs have to be updated independently
                                            //Maybe have a table for the most Adapted, Rated and Commented to limit access times to the database

                                            $mostRatedProducts = $objDbProductRatings->getMostRatedProducts();

                                            foreach ($mostRatedProducts as $childProduct) {
                                                //Get the original products
                                                $product = $objDbProducts->getProductById($childProduct['product_id']);
                                                //Get number of adaptations for the product
                                                $product['rating'] = $childProduct['avg_score'];

                                                //Check if the creator is a group or an institution
                                                $isGroupCreator = $objDbGroups->isGroup($product['creator']);

                                                if ($isGroupCreator == true) {
                                                    $thumbnail = $objDbGroups->getGroupThumbnail($product['creator']);
                                                } else {
                                                    $thumbnail = $objDbInstitution->getInstitutionThumbnail($product['creator']);
                                                }

                                                $product['institution_thumbnail'] = $thumbnail['thumbnail'];
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

     public function FilterTotalProducts($AuthFilter,$ThemeFilter,$LangFilter,$page,$sort,$TotalPages,$adaptationstring,$Model,$Handbook,$Guide,$Manual,$Besoractile) {

          $buildstring = $adaptationstring;
        if ($AuthFilter != Null)
            $buildstring .= ' and creator = ' . "'$AuthFilter'";

        if ($ThemeFilter != Null)
            $buildstring .= ' and theme = ' . "'$ThemeFilter'";

        if ($LangFilter != Null)
            $buildstring .= ' and language = ' . "'$LangFilter'";

        
if (($Model == 'on') or ($Handbook == 'on') or ($Guide == 'on') or ($Manual == 'on') or ($Besoractile == 'on') )
        $buildstring .= ' and (';

        if ($Model == 'on')
            $buildstring .= ' resource_type = "Model" or';
        if ($Handbook == 'on')
            $buildstring .= ' resource_type = "Handbook" or';
        if ($Guide == 'on')
            $buildstring .= ' resource_type = "Guide" or';
        if ($Manual == 'on')
            $buildstring .= ' resource_type = "Manual" or';
        if ($Besoractile == 'on')
            $buildstring .= ' resource_type = "Besoractile" or';

        $length = strlen($buildstring);

        if (($Model == 'on') or ($Handbook == 'on') or ($Guide == 'on') or ($Manual == 'on') or ($Besoractile == 'on') )
        {
        $buildstring=substr($buildstring,0,($length -2));

        $buildstring .= ')';
        }



        if ($sort == 'Date Added')
            $buildstring .= ' order by created_on';
        else if ($sort == 'Alphabetical')
            $buildstring .= ' order by title';

        $TotalEntries = $buildstring;

        






        return $TotalEntries;
    }


     /**
     * This functionTakes the Filtered string and Returns the Products according to the pagination filter seleted.
     * @param <type>$NumFilter,$PageNum,$TotalEntries
     * @return <type> $Buildstring
     */

      public function FilterAllProducts($NumFilter,$PageNum,$TotalEntries) {


        if ($NumFilter != null & $PageNum == null) {
            $start = 0;
            $end = $start + $NumFilter;
            $TotalEntries .= ' LIMIT ' . $start . ',' . $end;
        } else if ($NumFilter != null) {

            $temp = $NumFilter * $PageNum - 1;
            $start = $temp - $NumFilter + 1;
            $end = $NumFilter;
            $TotalEntries .= ' LIMIT ' . $start . ',' . $end;
        }

                $Buildstring = $TotalEntries;


        return $Buildstring;
    }





}
?>
