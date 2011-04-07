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

class featuredproductutil extends object
{

    public function init()
    {
        }

/*This function populates a page with the current FEATURED UNESCO PRODUCTS
 *@param<type> $product
 * return string  a unesco featured product thumbnail and title
 */

    function featuredProductView($product)
    {
        $content = '';
        $content.='
            <img src="' . $product['thumbnail'] . '" alt="Featured" width="136" height="176"><br>
                <div class="greyListingHeading">"'. $product['title'] . '"</div>
                    <br>
                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                    ';
        return $content;
        }
        
        function featuredProductViewSpan($product) {
        $content = '';
        $content.='
            <img src="' . $product['thumbnail'] . '" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                                <div class="featuredAdaptationRightContentDiv">
                                    <span class="greyListingHeading">"' . $product['title'] . '</span>
                                    <br><br>
            ';
        return $content;
    }

    //The function below does not need to be here



    function getlocationcoords($lat, $lon, $width, $height) {
                                    $x = (($lon + 180) * ($width / 360));
                                    $y = ((($lat * -1) + 90) * ($height / 180));
                                    return array("x" => round($x), "y" => round($y));
                                }


}

?>
