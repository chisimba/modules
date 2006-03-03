<?PHP
/* -------------------- dbTable class ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

class dbmanagerdb extends dbTableManager
{
    public function init()
    {
        parent::init();
    }

    public function getSchema()
    {
        $this->dumpDatabaseToFile('dump','all','dumptest.xml');
    }
}