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

class productutil extends object {

    public function init() {

    }

    public function createProductInfo($product) {
        $content = '';

        if ($product['new'] == 'true') {
            $content.=' <div class="newImageIcon"><img src="skins/unesco_oer/images/icon-new.png" alt="New" width="18" height="18"></div>';
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

}

?>
