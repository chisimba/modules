<php?

class dbutil extends dbtable{

public init(){
}


function getStory($category){

$sql=
"select ********* where category =$category";
$data=$this->getArray($sql);

return $data;
}

}

?>




