<?php

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

    /*
     * This function is included for backward compatibility
     */

    public function createProductInfo($product)
    {
        $content = '';

        if ($product['new'] == 'true') {
            $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
        } else {
            $content.= '<div class="newImageIcon"></div>';
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
                                            <option value="">Languages</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                    <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
                                </div>
';
        return $content;
    }

    /**
     * This function populates a page with the original products in a gridview
     * @param <type> $product
     * @return <type> $content
     */
    public function populateGridView($product)
    {
        $content = '';

        if ($product['new'] == 'true') {
            $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
        } else {
            $content.= '<div class="newImageIcon"></div>';
        }

        $content.='
                                <div class="imageGridListing">
                                    <div class="imageTopFlag"></div>
                                    <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                                    <div class="imageBotomFlag"></div>
                                </div>
                                <br>
                                <div class="blueListingHeading">' . $product['title'] . '</div>
                                <div class="listingLanguageLinkAndIcon">
                                    <img src="skins/unesco_oer/images/icon-languages.png" alt="Languages search" width="24" height="24"class="imgFloatRight">
                                    <div class="listingLanuagesDropdownDiv">
                                        <select name="" class="listingsLanguageDropDown">
                                            <option value="">Languages</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                                    <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
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

        $content.='
                  <div class="productsListView">
                   <h2>' . $product['title'] . '</h2><br>
                    <div class="productlistViewLeftFloat">
                        <img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"class="imgFloatRight">
                        <div class="listingAdaptationLinkDiv">new</div>
                  	</div>
                    <div class="productlistViewLeftFloat">
                        <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                        <div class="listingAdaptationLinkDiv"><a href="#" class="adaptationLinks">11 adaptations</a></div>
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
                            	<option value="">Languages</option>
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
         * TODO add code to check if the product was adapted by an institution or a group
         */

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

        /*
         * TODO add code to check if the product was adapted by an institution or a group
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

}
?>
