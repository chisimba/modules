<?  

$header = $this->getObject('htmlheading', 'htmlelements');
$header->str = $this->objLanguage->languageText('mod_bookmark_xbelexporttitle','kbookmark');
$header->type = 1;

echo $header->show();

$textArea = $this->getObject('textarea', 'htmlelements');
$textArea->setRows(30);
$textArea->setColumns(100);
$textArea->setContent($xbelOutput);
echo '<p>'.$textArea->show().'</p>';

$link = $this->newObject('link','htmlelements');
$link->href = $this->uri(NULL);
$link->link=$this->objLanguage->languageText("word_back");
echo '<p>'.$link->show().'</p>';



function savefile(f) {
 f = f.elements;  //  reduce overhead

 var w = window.frames.w;
 if( !w ) {
  w = document.createElement( 'iframe' );
  w.id = 'w';
  w.style.display = 'none';
  document.body.insertBefore( w );
  w = window.frames.w;
  if( !w ) {
   w = window.open( '', '_temp', 'width=100,height=100' );
   if( !w ) {
    window.alert( 'Sorry, could not create file.' ); return false;
   }
  }
 }

 var d = w.document,
  ext = f.ext.options[f.ext.selectedIndex],
  name = f.filename.value.replace( /\//g, '\\' ) + ext.text;

 d.open( 'text/plain', 'replace' );
 d.charset = ext.value;
 if( ext.text==='.txt' ) {
  d.write( f.txt.value );
  d.close();
 } else {  //  '.html'
  d.close();
  d.body.innerHTML = '\r\n' + f.txt.value + '\r\n';
 }

 if( d.execCommand( 'SaveAs', null, name ) ){
  window.alert( name + ' has been saved.' );
 } else {
  window.alert( 'The file has not been saved.\nIs there a problem?' );
 }
 w.close();
 return false;  //  don't submit the form
};

?>