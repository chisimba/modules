<?php
    class getdata extends object {
        
        public function init() {}
        
        public function getcoursehistory() {
            $str = "[{
    text:'Aeronautical Engineering',
    expanded: true,
    children:[{
        text:'18/08/2009_1',
        id:'ver1',
        leaf:true
    },{
        text:'18/08/2009_2',
        id:'ver2',
        leaf:true
    },{
        text:'17/08/2009_1',
        id:'ver3',
        leaf:true
    },{
        text:'16/08/2009_1',
        id:'ver4',
        leaf:true
    },{
        text:'15/08/2009_1',
        id:'ver5',
        leaf:true
    },{
        text:'15/08/2009_2',
        id:'ver6',
        leaf:true
    },{
        text:'18/05/2009_1',
        id:'ver7',
        leaf:true
    },{
        text:'18/05/2009_4',
        id:'ver8',
        leaf:true
    }]
}]";
            
            return $str;
        }
    }

?>
