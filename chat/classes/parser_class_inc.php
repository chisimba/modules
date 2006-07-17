<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}

/**
* Parser class for chat
* @author Jeremy O'Connor
* @copyright 2004-2005 University of the Western Cape
*/

class parser extends object {
    public function init()
    {
    }
    /**
    * Parse the string entered by the user.
    * @param string $str The string to parse.
    * @return string The result of the parser.
    */
    public function Parse($str)
    {
        // Check for websearch.
        if ($str[0]=='?') {
            $searchTerm = substr($str,1,1024);
            //Instantiate the google api class
            $objGapi=$this->getObject('googleapi','websearch');
            //Do the search and set some of the properties for use
            $ar = $objGapi->doSearch($searchTerm, 0, 5);            
            if ( $ar ) {
                if (count($ar)>0) {
                    $result = "";
                    foreach ($ar as $line) {
                        $url = $line['URL'];
                        $link = "<a href=\"" . $url . "\" target=\"_blank\">" . $line['title'] . "</a>";
                        $result .= $link . "<br/>";
                    }
                }
            } else {
                $result = $objGapi->error;
            } //if ( $ar )
        }
        else {
            $result = $str;
        }
        return $result;
    }
}
?>