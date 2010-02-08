<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
 * The controller for the adbanner module
 *
 * @package adbanner
 * @category chisimba
 * @copyright AVOIR
 * @license GNU GPL
 * @author Charl Mert
 */

class adbanner extends controller
{
	/**
	 * Class Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function init() {
		try {  
			$this->objLanguage =  $this->getObject('language', 'language');
			
			$this->objAddAd = $this->getObject("dbadbanners");
			
			$this->objMenu = $this->getObject("adbannermenu"); 

			$this->objBanner=$this->getObject('adban');

		} 
		catch (customException $e) {
			throw customException($e->getMessage());
			exit();
		}	    
	}

	/**
	 *
	 * This is a method that overrides the parent class to stipulate whether
	 * the current module requires login. Having it set to false gives public
	 * access to this module including all its actions.
	 *
	 * @access public
	 * @return bool FALSE
	 */
	public function requiresLogin() {
		return FALSE;
	}


	/**
	 * Method to handle actions from templates
	 * 
	 * @access public
	 * @param string $action Action to be performed
	 * @return mixed Name of template to be viewed or function to call
	 */
	public function dispatch() {
		$action = $this->getParam('action');

		switch ($action) {
	
			case 'addbanner': 
				return "add_banner_tpl.php";

			case 'submitaddbannerform': 
				return $this->addBannerForm();
		
			case 'viewbanners': 
				return "view_banners_tpl.php";

			case 'viewbanner': 
				return "view_banner_tpl.php";

			case 'editbannerinfo': 
				return "edit_banner_info_tpl.php";

			case 'submiteditinfoform': 
				return $this->editBannerInfoForm();

			case 'editbannerimage':
				return "edit_banner_image_tpl.php";

			case 'changeimage': 
				$id=$this->getParam("id");
				$this->setVarByRef("id",$id);
				return "change_image_tpl.php";

			case 'changeimageform': 
				$id=$this->getParam("id");
				return $this->changeImage($id);

			case 'editimageinfo':
				return "edit_image_info_tpl.php";

			case 'editimageinfoform':
				return $this->editImageInfoForm();

			case 'deletebanner':
				return $this->deleteBanner();

			case 'viewbannerinblock':
				return $this->viewBannerInBlock();
				
			default:
				return 'index_tpl.php';

		}
	}


	/**
	 * Method to handle the add banner form
	 * 
	 * @access private
	 * uploads an image to file system 
	 * posts Banner information to the database
	 * 
	 */
	function addBannerForm() {

		//--Defining parameters from form
		$title = $this->getParam("title");
		$description = $this->getParam("desc");
		$url = $this->getParam("url");
		$size = $this->getParam("size");
		$siz = explode(' ', $size);
		$width = $siz[0];
		$height =  $siz[1];
		$imageName = $_FILES['image_file']['name'];
		$tmpName = $_FILES['image_file']['tmp_name'];
		
		//--the directory to upload image into
		$uploaddir = "packages/adbanner/resources/images/"; 
		$imagePath = $uploaddir . $imageName;
	
		//--make sure  there is a file to be uploaded
		if(is_uploaded_file($_FILES['image_file']['tmp_name'])) {
			move_uploaded_file($tmpName, $imagePath);
			if(!get_magic_quotes_gpc()) {
				$imagePath = addslashes($imagePath);
			} 
			//--writing information into the database
			$this->objAddAd->addBanner($title,$description,$width,$height,$imageName,$imagePath,$url);
		}
		else {
			echo "Please choose a file to upload";
			return;
		}
		return "view_banners_tpl.php";
	}

	function changeImage($id) {
	
		$url = $this->getParam("url");	
		$imageName = $_FILES['image_file']['name'];
		$tmpName = $_FILES['image_file']['tmp_name'];
		
		//--the directory to upload image into
		$uploaddir = "packages/adbanner/resources/images/"; 
		$imagePath = $uploaddir . $imageName;
	
		if(is_uploaded_file($_FILES['image_file']['tmp_name'])) {
			move_uploaded_file($tmpName, $imagePath);
			if(!get_magic_quotes_gpc()){
				$imagePath = addslashes($imagePath);
			} 
			$this->objAddAd->changeImage($id,$imageName,$imagePath,$url); 
		}
		else {
			echo "Please choose a file to upload";
			return;
		}
		return "view_banners_tpl.php";

	}

	function editBannerInfoForm() {

		//--Defining parameters from form
		$tit = $this->getParam("title");
		$des = $this->getParam("desc");
		$id = $this->getParam("id");
		$size = $this->getParam("size");
		$siz = explode(' ', $size);
		$width = $siz[0];
		$height =  $siz[1];
	
		$this->objAddAd->updateBanner($id,$tit,$des,$width,$height);
		
		return "view_banners_tpl.php";
	}

	function deleteBanner() {

		//--Defining parameters from form
		$id = $this->getParam("id");
		$this->objAddAd->deleteBanner($id);
		
		return "view_banners_tpl.php";
	}

	function editImageInfoForm() {
		$id = $this->getParam("id");
		$url = $this->getParam("url");
		$this->objAddAd->editImageInfo($id,$url);
		
		return "view_banners_tpl.php";
	}

	function viewBannerInBlock() {
		$id = $this->getParam("id");
		return $this->objBanner->displayBanner($id);

	}


}
?>
