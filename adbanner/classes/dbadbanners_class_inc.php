<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}

class dbadbanners extends dbtable
{
    public function init() {
        parent::init('tbl_ad_banners');
		$this->table = 'tbl_ad_banners';
        $this->objUser = $this->getObject( 'user', 'security' );
    }
		
    public function addBanner($title,$description,$width,$height,$imageName,$imagePath,$url) {
	$false =0;
        $data=array('banner_title'=>$title, 'comment_desc'=>$description, 'banner_width'=>$width, 'banner_height'=>$height, 'image_name'=>$imageName, 'image_path'=>$imagePath, 'image_url'=>$url, 'date_created'=>$this->now(), 'date_updated'=>$this->now(), 'deleted'=>$false, 'is_active'=>$false);
        $this->insert($data);
    }

    public function getBanners() {
		$clause = "WHERE deleted = 0 ORDER BY date_updated desc ";
        $data=$this->getAll($clause);
        return $data;
    }

	public function getBanner($id) {
		$query = "SELECT * FROM tbl_ad_banners where id='".$id."'";
		$data = $this->getArray($query); 
		return $data;
	}

	public function updateBanner($id,$title,$description,$width,$height,$active) {
		 $pk = "id";
		 $data=array('banner_title'=>$title,'comment_desc'=>$description, 'banner_width'=>$width, 'banner_height'=>$height, 'is_active'=>$active, 'date_updated'=>$this->now());
		 $this->update($pk,$id,$data);
	}

	public function changeImage($id,$imageName,$imagePath,$url) {
		$pk = "id";
        $data=array('image_name'=>$imageName,'image_path'=>$imagePath,'image_url'=>$url, 'date_updated'=>$this->now());
        $this->update($pk,$id,$data);
	}

	public function editImageInfo($id,$url) {
		$pk = "id";
        $data=array('image_url'=>$url, 'date_updated'=>$this->now());
        $this->update($pk,$id,$data);
		
	}
	
	public function deleteBanner($id) {
		$pk = "id";
		$del = 1;
		$data=array('deleted'=>$del,'date_updated'=>$this->now());
		$this->update($pk,$id,$data);
	}

	public function activateBanner($id) {
		$pk = "id";
		$true = 1;
		$data=array('is_active'=>$true,'date_updated'=>$this->now());
		$this->update($pk,$id,$data);
	}

   
}
?>
