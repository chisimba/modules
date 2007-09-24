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

    public function getMostDownloadedToday()
    {
        $sql = 'SELECT tbl_webpresent_files . * , (

SELECT count( * )
FROM tbl_webpresent_downloads
WHERE tbl_webpresent_files.id = tbl_webpresent_downloads.fileid
) AS downloadcount
FROM tbl_webpresent_files
WHERE (

SELECT count( * ) AS downloadcount
FROM tbl_webpresent_downloads
WHERE tbl_webpresent_files.id = tbl_webpresent_downloads.fileid
) > 0
ORDER BY (
SELECT count( * ) AS downloadcount
FROM tbl_webpresent_downloads
WHERE tbl_webpresent_files.id = tbl_webpresent_downloads.fileid
) DESC LIMIT 5';
        return $this->getArray($sql);
    }

    public function getMostDownloadedTodayTable()
    {
        $files = $this->getMostDownloadedToday();

        $str = 'Most Downloaded Today';

        $content = '';

        if (count($files) > 0)
        {
            $table = $this->newObject('htmltable', 'htmlelements');
/*
            $table->startHeaderRow();
            $table->addHeaderCell('&nbsp;', 20);
            $table->addHeaderCell('Presentation');
            $table->addHeaderCell('Downloads', 20, NULL, 'center');
            $table->endHeaderRow();
*/
            $counter = 0;

            $this->loadClass('link', 'htmlelements');

            foreach ($files as $file)
            {
                $counter++;
                $table->startRow();
                $table->addCell($counter.'.', 20, 'top', 'center');

                $fileLink = new link ($this->uri(array('action'=>'view', 'id'=>$file['id'])));
                $fileLink->link = $file['title'];

                $table->addCell($fileLink->show());
                $table->addCell($file['downloadcount'], 20, 'top', 'center');

                $table->endRow();
            }

            $content = $table->show();
        }

        $objFeatureBox = $this->newObject('featurebox', 'navigation');

        return $objFeatureBox->show($str, $content);

    }


}
?>