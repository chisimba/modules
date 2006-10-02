<?
$leftMenu=& $this->newObject('sidemenu','toolbar');
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

$this->loadClass('link', 'htmlelements');
$this->loadClass("form","htmlelements");
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('button','htmlelements');
 
/**
*Texts
*/
$earch = $this->objLanguage->languageText("mod_klorclient_search");

$objDb =& $this->newObject('klorclients','klorclient');
$fileList = $objDb->fileList();

$form = new form('search',$this->uri(array('action'=>'search')));
//$textinput = & $this->newObject('textinput','htmlelements');
$textarea = new textinput('search',$search, 2, 15);
$form->addToForm('&nbsp;'.$textarea->show());
$objButton=new button('save');
$objButton->setToSubmit();
//$objButton->setValue($this->objLanguage->languageText("mod_contextadmin_save"));
$objButton->setValue("Go");


$form->addToForm($objButton);
//--------search end 



/**
*New  Links 
*/
$objhome = new link($this->uri(null,'klorclient'));
$objhome->link='Home';
$objLink = new link($this->uri(null,'contextadmin'));
$objLink->link='contextadmin';



$objLogin = new link($this->uri(null,'login'));



$objImport = new link($this->uri(array('action'=>'exportcontext'),'klorclient'));
$objImport->link='Import Courses from Context';

//---------------------links-----------------------------

$clientLink=$this->uri(array('action'=>'client'));
$localLink=$this->uri(array('action'=>'local'));
$searchLink=$this->uri(array('action'=>'search'));
$downloadLink=$this->uri(array('action'=>'download'));
$uploadLink=$this->uri(array('action'=>'upload'));
$UpdateLink=$this->uri(array('action'=>'getfile'));
$ForumLink=$this->uri(null,'forum');
$LoginLink=$this->uri(null,'login');
$FileTree=$this->uri(array('action'=>'filesearch'));



//---------------------------------------------------

//buttons ----- 

$home_icon;
$home_icon = $this->getObject('geticon','htmlelements');
$home_icon->setIcon('home');
$lblView = "Home";	
$home_icon->alt = $lblView;
$home_icon->align=false;

$uriHome = $this->uri(
array(
'action' => ''
)
);
$HomeLink   = "<a href=\"{$uriHome}\">".$home_icon->show()."</a>";

//------------

$discussion_icon;
$discussion_icon = $this->getObject('geticon','htmlelements');
$discussion_icon->setIcon('modules/forum');
$lblView = "Discussion forum";	
$discussion_icon->alt = $lblView;
$discussion_icon->align=false;

$uriDiscussion = $ForumLink;
$DiscussionLink   = "<a href=\"{$uriDiscussion}\">".$discussion_icon->show()."</a>";

$uriLogin = $LoginLink;
$Login_Link   = "<a href=\"{$uriLogin}\">".'Login'."</a>";

$upload_icon;
$upload_icon = $this->getObject('geticon','htmlelements');
$upload_icon->setIcon('upload');
$lblView = "Upload a File";	
$upload_icon->alt = $lblView;
$upload_icon->align=false;

$uriUpload = $this->uri(
array(
'action' => 'upload'
)
);
$UploadLink   = "<a href=\"{$uriUpload}\">".$upload_icon->show()."</a>";

//-------
//$tree_icon;
$tree_icon = $this->getObject('geticon','htmlelements');
$tree_icon->setIcon('modules/tree');
$lblView = "File Tree";	
$tree_icon->alt = $lblView;
$tree_icon->align=false;

$uriTree = $FileTree;

$TreeLink   = "<a href=\"{$uriTree}\">".$tree_icon->show()."</a>";

//------------

$remote_icon = $this->getObject('geticon','htmlelements');
$remote_icon->setIcon('expand');
$lblView = "Connect to remote server";	
$remote_icon->alt = $lblView;
$remote_icon->align=false;



$uriRemote = $this->uri(
array(
'action' => 'remote'
)
);

$RemoteLink   = "<a href=\"{$uriRemote}\">".$remote_icon->show()."</a>";
//buttons ----- 

$import_icon = $this->getObject('geticon','htmlelements');
$import_icon->setIcon('folder_up');
$lblView = "Import from Context Courses";	
$import_icon->alt = $lblView;
$import_icon->align=false;



$uriImport = $this->uri(
array(
'action' => 'exportcontext'
)
);

$ImportLink   = "<a href=\"{$uriImport}\">".$import_icon->show()."</a>";
//buttons ----- 


$doc_icon = $this->getObject('geticon','htmlelements');
$doc_icon->setIcon('paper');
$lblView = "Documentation";	
$doc_icon->alt = $lblView;
$doc_icon->align=false;



$uriDoc = 'http://cvs.uwc.ac.za/viewcvs/viewcvs.cgi/KEWL.NextGen-Documentation/KNG_EndUserDocs_2006/manuals/freecourseware/';

$DocLink   = "<a href=\"{$uriDoc}\"> ".$doc_icon->show()."</a>";
//buttons ----- 



$objklorserver = new link('modules/klorserver/server.php');
$objklorserver->link='Web Services Exposed';
$objklorclient = new link($this->uri(null,'klorclient'));
$objklorclient->link='klorclient';

$webservices = $this->getObject('geticon','htmlelements');
$webservices->setIcon('modules/webservices');
$lblView = "Wsdl Methods";	
$webservices->alt = $lblView;
$webservices->align=false;



$uriWeb = new link('modules/klorserver/server.php');
$uriWeb->link=$webservices->show();

//$webservicesLink   = "<a href=\".$uriWeb->show()."\>"</a>";







//---------------Sidemenooooouuuu-------------------

//-----------adding this to sidemenu
$leftColumn = "";
$leftColumn .= '<p>' .'&nbsp;'. '</p>';
$leftColumn .= '<b>' .$earch. ' for'.':</b>'.$this->objHelp->show('search','klorclient');
$leftColumn .= '<p>' .$form->show(). '</p>';
$leftColumn .= '<p>' .'<br>'.$HomeLink.'Home'. '</p>';
$leftColumn .= '<p>' .'<br>'.$DocLink.'Documentation'. '</p>';
$leftColumn .= '<p>' .$DiscussionLink.'Discussion'. '</p>';
$leftColumn .= '<p>'.$TreeLink.'File Tree'. '</p>';
if(!$this->objUser->isAdmin())
{
		
}else{
$leftColumn .= '<p>'.$RemoteLink.'Remote Server'. '</p>';
$leftColumn .= '<p>'.$UploadLink.'Upload'. '</p>';
$leftColumn .= '<p>'.$ImportLink.'Get Local Content'. '</p>';

}
$leftColumn .= '<p>'.$Login_Link. '</p>';

//$leftColumn .= '<p>' ."<a href=$objLogin class='pseudobutton' >Login</a><br>\n". '</p>';

//$leftColumn .= '<p>' .$webservicesLink.'Webservice Wsdl'. '</p>';

//-----------------------------------
$cssLayout->setLeftColumnContent($leftColumn);
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>
