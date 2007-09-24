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


class dbwebpresentdownloadcounter extends dbtable
{

    /**
    * Method to construct the class.
    */
    public function init()
    {
        parent::init('tbl_webpresent_downloads');
    }

    public function addDownload($id, $type)
    {
        return $this->insert(array(
                'fileid' => $id,
                'filetype' => $type,
                'datedownloaded' => date('Y-m-d'),
                'datetimedownloaded' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            ));
    }


}
?>