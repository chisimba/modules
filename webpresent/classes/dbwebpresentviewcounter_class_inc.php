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

    public function getMostViewedToday()
    {
        $sql = 'SELECT tbl_webpresent_files . * , (

SELECT count( * )
FROM tbl_webpresent_views
WHERE tbl_webpresent_files.id = tbl_webpresent_views.fileid
) AS viewcount
FROM tbl_webpresent_files
WHERE (

SELECT count( * ) AS viewcount
FROM tbl_webpresent_views
WHERE tbl_webpresent_files.id = tbl_webpresent_views.fileid
) > 0
ORDER BY (
SELECT count( * ) AS viewcount
FROM tbl_webpresent_views
WHERE tbl_webpresent_files.id = tbl_webpresent_views.fileid
) DESC LIMIT 5';
        return $this->getArray($sql);
    }

    public function getMostViewedTodayTable()
    {
        $files = $this->getMostViewedToday();

        $str = 'Most Viewed Today';

        $content = '';

        if (count($files) > 0)
        {
            $table = $this->newObject('htmltable', 'htmlelements');
/*
            $table->startHeaderRow();
            $table->addHeaderCell('&nbsp;', 20, NULL, 'center');
            $table->addHeaderCell('Presentation');
            $table->addHeaderCell('Views', 50, NULL, 'center');
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
                $table->addCell($file['viewcount'], 20, 'top', 'center');

                $table->endRow();
            }

            $content = $table->show();
        }

        $objFeatureBox = $this->newObject('featurebox', 'navigation');

        return $objFeatureBox->show($str, $content);

    }


}
?>