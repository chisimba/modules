<?php
	class dbcontacttable extends dbtable
	{
		public function init()
		{
			parent::init("tbl_contactdetails");
		}
		
		public function addInfo($academicName,$schoolName,$headSign,$telNumber,$emailAdd,$courseId)
		{
			$data=array("academicname"=>$academicName,"schoolname"=>$schoolName,"headsign"=>$headSign,"telnum"=>$telNumber,
				    "emailadd"=>$emailAdd,"courseId"=>$courseId);
			$this->insert($data);

		}

		public function getInfo()
		{
			$data=$this->getAll();
			return $data;
		}
		
		public function getSingleRow($coursenum)
                {
			$data=$this->getRow("courseId",$coursenum);
			return $data;
                }
		
		public function updateInfo($academicName,$schoolName,$headSign,$telNumber,$emailAdd,$courseId)
                {
                        $data=array("academicname"=>$academicName,"schoolname"=>$schoolName,"headsign"=>$headSign,"telnum"=>$telNumber,
                                    "emailadd"=>$emailAdd);
                        $this->update("courseId",$courseId,$data);

                }

	}

?>
