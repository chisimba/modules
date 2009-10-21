<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
	die("You cannot view this page directly");
}
// end security check

class adban extends object {
	
	/**
	 * Class Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function init() {
		try {
			$this->objAddAd = $this->getObject("dbadbanners");
            $this->loadClass('link','htmlelements');

		}
		catch (Exception $e) {
			throw customException($e->getMessage());
			exit();
		}
	}


	function displayBanner($id) {
		$imgs = $this->objAddAd->getBanner($id);
		$content="";
                
		foreach ($imgs as $data) {	
			$path = $data['image_path'];
			$nam = $data['image_name'];
			$url = $data['image_url'];
			$wid = $data['banner_width'];
			$hei = $data['banner_height'];

			$content.=  "<a target='_blank' href='".$url."'>";
			$content.=  "<img width='".$wid."' height='".$hei."' src='".$path."'/>";
			$content.=  "</a>";
                       
		}
      	return $content;
	}


//returns the latest non-deleted banner 
	function displayBannerInBlock() {
		$imgs = $this->objAddAd->getBanners();
		$content="";
                
		foreach ($imgs as $data) {
			$path = $data['image_path'];
			$nam = $data['image_name'];
			$url = $data['image_url'];
			$wid = $data['banner_width'];
			$hei = $data['banner_height'];
			$ac = $data['deleted'];
			$id = $data['id'];
			

			$content.=  "<a target='_blank' href='".$url."'>";
			$content.=  "<img width='".$wid."' height='".$hei."' src='".$path."'/>";
			$content.=  "</a>";
			if ($ac == 0) {
				$this->objAddAd->activateBanner($id);
				return $content;
				
			}
                       
		}
      	return $content;
		
		
	}

	

}

?>
