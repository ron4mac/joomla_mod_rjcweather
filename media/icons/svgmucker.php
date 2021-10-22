<?php
$self = htmlentities($_SERVER['PHP_SELF']);

$finfo = finfo_open(FILEINFO_MIME);
if (!$finfo) die('Opening fileinfo database failed');

$dir = !empty($_GET['d']) ? htmlentities($_GET['d']) : '.';
$dirts = $dir.'/';

$html = <<<EOT
<div class="bckb b-blu" onclick="backc('#aaeeff')"> </div>
<div class="bckb b-grn" onclick="backc('#99ff99')"> </div>
<div class="bckb b-red" onclick="backc('#ff2244')"> </div>
<div class="bckb b-blk" onclick="backc('#000000')"> </div>
<div class="bckb b-wht" onclick="backc('#ffffff')"> </div>
<div class="bckb b-trn" onclick="backc()"> </div>
EOT;
$qtrs = explode('/', htmlentities($_SERVER['QUERY_STRING']));
if (count($qtrs)>1) {
	array_pop($qtrs);
	$html .= '<a href="'.htmlentities($_SERVER['SCRIPT_URL']).'?'.implode('/',$qtrs).'">&lt; BACK</a><br>';
}

$files = scandir($dir);
foreach ($files as $file) {
	if ($file[0]=='.') continue;
	$fpath = $dirts.$file;
	if (is_dir($fpath)) {
		$html .= '<a href="'.$self.'?d='.$fpath.'"><div class="fold"><span>'.$file.'</span>';
		$html .= '<img src="folder.svg" height="64px" /></div></a>';
		continue;
	}
	if (substr(finfo_file($finfo,$fpath),0,6) != 'image/') continue;
	$html .= '<div style="float:left;margin:0 16px 16px 0;padding: 0 8px;border:1px solid #EEEEEE"><p style="text-align:center">'.$file.'</p>';
	$html .= '<a data-fancybox="gallery" href="'.$fpath.'"><img class="aimg" src="'.$fpath.'" height="96px" /></a></div>';
}
$html .= '<br style="clear:both" />';
//$svgobj = simplexml_load_file($dirts.'rain.svg');
//echo'<xmp>';var_dump($svgobj);echo'</xmp>';
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<style>
.bckb {
	float:right;
	width:24px;
	height:24px;
	border:1px solid #AAAAAA;
	margin-left:4px;
	cursor:pointer;
}
.b-blu {background-color:#aaeeff;}
.b-grn {background-color:#99ff99;}
.b-red {background-color:#ff2244;}
.b-blk {background-color:#000000;}
.b-wht {background-color:#ffffff;}
.b-trn {background-image:url(back1.png);}

.fold {
	float:left;
	position:relative;
	margin:0 16px 16px 0;
	padding:0 8px;
	border:2px solid #CCEEFF
}
.fold span {
	position:absolute;
	top:50%;
	left:50%;
	transform:translate(-50%,-50%);
}
.aimg {
	background-image:url(back1.png);
}
</style>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
function backc (colr) {
	var elms = document.getElementsByClassName("aimg");
	if (colr) {
		for (var i = 0; i < elms.length; i++) {
			elms[i].style.backgroundColor = colr;
			elms[i].style.backgroundImage = "none";
		}
	} else {
		for (var i = 0; i < elms.length; i++) {
			elms[i].style.backgroundColor = "none";
			elms[i].style.backgroundImage = "url(back1.png)";
		}
	}
}
</script>
</head>
<body>
<?php echo $html; ?>
<?php //echo'<xmp>';var_dump($_SERVER);echo'</xmp>'; ?>
</body>
</html>