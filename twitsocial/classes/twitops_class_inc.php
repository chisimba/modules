<?php
// security check - must be included in all scripts
if (! /**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
/**
 *
 */
/**
 * @access     public
 */
class twitops extends object {

    /**
     *
     * @var langauge an object reference.
     */
    public $objLanguage;

    public $objCurl;

    protected $objDbTwit;
    public $objUser;

    /**
     * Method that initializes the objects
     *
     * @access private
     * @return nothing
     */
    public function init() {
        $this->objUser = $this->getObject ( 'user', 'security' );
        $this->objLanguage = $this->getObject ( 'language', 'language' );
        $this->objCurl = $this->getObject ( 'curl', 'utilities' );
        $this->objDbTwit = $this->getObject ( 'dbtwits' );
        $this->objUser = $this->getObject('user', 'security');
    }

    public function curlTwitter($url, $parentid) {
        $xml = $this->objCurl->exec ( $url );
        $xml = simplexml_load_string ( $xml );
        $userarr = array ();
        foreach ( $xml->user as $twit ) {
            $name = $twit->name;
            $sname = $twit->screen_name;
            $location = $twit->location;
            $description = $twit->description;
            $image_url = $twit->profile_image_url;
            $url = $twit->url;
            $follow_count = $twit->followers_count;
            $twit = array ('name' => $name, 'screen_name' => $sname, 'location' => $location, 'description' => $description, 'image_url' => $image_url, 'url' => $url, 'follow_count' => $follow_count, 'parent_id' => $parentid );
            $userarr [] = $twit;
        }
        $this->objDbTwit->saveRecords ( $userarr );

        return TRUE;
    }

    public function recursiveCheck($checks) {
        foreach($checks as $check) {
            $parentid = $check['id'];
            $url = $this->createURL($check['screen_name']);
            $this->curlTwitter($url, $parentid);
            $this->objDbTwit->setCheck($check['id']);
            sleep(60);

        }
    }

    public function createURL($screen_name) {
        return "http://twitter.com/statuses/friends/$screen_name.xml";
    }
} //end of class
?>