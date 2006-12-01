<?php


// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Class for building a article box for KEWL.nextgen.
*
* The class builds a css style feature box 
*
* @author Wesley Nitsckie
* @copyright (c)2004 UWC
* @package featurebox
* @version 0.1
*/

class articlebox extends object
{
        /**
        * Method to construct the class.
        **/
        public function init()
        {
        }

        /**
         * Method to show the article
         * 
         * @param null
         * @access publc
         * @return string
         */
        public function show($content = null)
        {

            $article = '<div class="featurebox">';
            $article .= '<small>'.$content.'</small>';
            $article .= '</div>';
            return $article;

        }
}
?>
