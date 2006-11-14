<?php


print $addEditForm;

?>

<script language="javascript" type="text/javascript">
<![CDATA[
	function changeImage(el, frm)
	{
		fe = document.getElementById('imagelib');
		//alert(fe.name);
		if (el.options[el.selectedIndex].value!='')
		{
			fe.src= 'skins/echo' + el.options[el.selectedIndex].value;
		} else {
			fe.src='http://localhost/5ive/app/skins/_common/blank.png';
		}
			
	}
	

]]>
	</script>
