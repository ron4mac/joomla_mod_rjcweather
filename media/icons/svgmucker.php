<?php
$dir = 'icons';
$dirts = $dir.'/';
$files = scandir($dir);
foreach ($files as $file) {
	if ($file[0]=='.') continue;
	echo '<div style="float:left;margin:0 16px 16px 0;padding: 0 8px;border:1px solid #EEEEEE"><p style="text-align:center">'.$file.'</p>';
	echo '<img src="'.$dirts.$file.'" height="32px" /></div>';
}
echo '<br style="clear:both" />';
//$svgobj = simplexml_load_file($dirts.'rain.svg');
//echo'<xmp>';var_dump($svgobj);echo'</xmp>';
