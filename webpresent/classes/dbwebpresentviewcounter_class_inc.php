<?php



// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}


class dbwebpresentviewcounter extends dbtable
{

    /**
    * Method to construct the class.
    */
    public function init()
    {
        parent::init('tbl_webpresent_views');
    }

    public function addView($id)
    {
        return $this->insert(array(
                'fileid' => $id,
                'dateviewed' => date('Y-m-d'),
                'datetimeviewed' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            ));
    }


}
?>