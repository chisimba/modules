<?php
class dbfaculty extends dbtable{
    var $tablename = "tbl_ads_faculty";
    
    public function init(){
        parent::init($this->tablename);
    }

    public function saveFaculty($faculty) {
        $data = array('name'=>$faculty);
        $this->insert($data);
    }

    public function saveModerator($faculty, $moderator) {
        $data = array('userid'=>$moderator);
        $this->update('name', $faculty, $data);
    }

    public function getAllFaculty() {
        return $this->getAll("order by name");
    }

    public function getFacultyRC() {
        return $this->getRecordCount();
    }

    public function getFacultyListing() {
        // create the data store
        $alldata = "
        <script type=\"text/javascript\">
        Ext.onReady(function(){
        var myData = ";
        
        $alldata .= $this->getFacultyData();

        $alldata .= "
        var store = new Ext.data.ArrayStore({
            fields: [
               {name: 'faculty'},
               {name: 'moderator'}
            ]
        });
        store.loadData(myData);

        // create the Grid
        var grid = new Ext.grid.GridPanel({
            store: store,
            columns: [
                {header: \"Faculty\", width: 300, sortable: true, dataIndex: 'faculty'},
                {header: \"Moderator\", width: 160, sortable: true, dataIndex: 'moderator'}
            ],
            stripeRows: true,
            height:350,
            width:500,
            title:'Faculty Listing'
        });
        grid.render('facultyListing');
        });

        </script>";

        return $alldata;
    }

    public function getFacultyData() {
        $data = $this->getAllFaculty();
        $rc = $this->getFacultyRC();
        $count = 1;

        $dataStore = "[";
        foreach($data as $data) {
            if($count != $rc) {
                $dataStore .= "['".$data['name']."','";
                if(strlen(trim($data['userid'])) == 0) {
                    $dataStore .= "Not Available']".",";
                }
                else {
                    $dataStore .= $data['userid']."']".",";
                }
            }
            else {
                $dataStore .= "['".$data['name']."','";
                if(strlen(trim($data['userid'])) == 0) {
                    $dataStore .= "N\A']";
                }
                else {
                    $dataStore .= $data['userid']."']";
                }
            }
            $count++;
        }
        $dataStore .= "]";

        return $dataStore;
    }
}
?>