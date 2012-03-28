<html>
<head>
<title>Citation Analysis | Shibu & Sundar</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
</head>
<body>
<div id="wrapper">
	<div id="wrapper2">
		<div id="header">
			<div id="logo">
				<h1>Plagiarism Detection</h1>
			</div>
			<div id="menu">
				<ul>
					<li><a href="index.html">Home</a></li>
				</ul>
			</div>
		</div>
<div id="page">
<div id="widebar">
<?php 

function ncd($x, $y) { 
  $cx = strlen(gzcompress($x));
  $cy = strlen(gzcompress($y));
  $result=(strlen(gzcompress($x . $y)) - min($cx, $cy)) / max($cx, $cy);
  return $result;
} 

function ncd_new($sx, $sy, $prec=0, $MAXLEN=9000) {
# NCD with gzip artifact correctoin and percentual return.
# sx,sy = strings to compare. 
# Use $prec=-1 for result range [0-1], $pres=0 for percentual, 
# For NCD definition see http://arxiv.org/abs/0809.2553
  $x = $min = strlen(gzcompress($sx));
  $y = $max = strlen(gzcompress($sy));
  $xy= strlen(gzcompress($sx.$sy));
  $a = $sx;
  if ($x>$y) { # swap min/max
    $min = $y;
    $max = $x;
    $a = $sy;
  }
  $res = ($xy-$min)/$max; # NCD definition.

  #Little strings):
 	if ($MAXLEN<0 || $xy<$MAXLEN) {
    $aa= strlen(gzcompress($a.$a));
    $ref = ($aa-$min)/$min;
    $res = $res - $ref; # correction
  }
  return ($prec<0)? $res: 100*round($res,2+$prec);
}

//File 1  
$f1 = file_get_contents('./ref/refmtch1.txt', FILE_USE_INCLUDE_PATH);
$stripstr = array('/\bis\b/i', '/\bwas\b/i', '/\bthe\b/i', '/\ba\b/i');
$file1 = preg_replace($stripstr, '', $f1);
//var_dump($file1);
$arr1=explode(";;",$file1);
$count1=count($arr1);

//File 2
$f2 = file_get_contents('./ref/refmtch2.txt', FILE_USE_INCLUDE_PATH);
$stripstr = array('/\bis\b/i', '/\bwas\b/i', '/\bthe\b/i', '/\ba\b/i');
$file2 = preg_replace($stripstr, '', $f2);
$arr2=explode(";;",$file2);
$count2=count($arr2);

$total=ncd_new($file1,$file2);
$fp = fopen('data.txt', 'w');
?>
<div class="output">
<p><h2>Shared References</h2></p>
<center><img src="images/Step3.png"></center><br>
<br><p>Matching pattern using <b>NCD algorithm</b></p><p>Overall pattern match: <b><?php echo $total;?>%</b></p><h1><?php if($total==0) echo "Plagiarism Detected!!!";?></h1><p><i>* If two texts possess a NCD value less than 30% they can be considered as work of plagiarism.</i></p>
<table width="100%" cellspacing="0">
<thead><th colspan="2">Paper #1</th>
<th colspan="2">Paper #2</th>
<th rowspan="2">NCD Value</th></tr>
<tr>
<th>Ref No.</th>
<th>Reference Text</th>
<th>Ref No.</th>
<th>Reference Text</th></thead>
<tbody>
<?php
for ($i=0;$i<$count1-1;$i++)
{	
	for ($j=0;$j<$count2-1;$j++)
	{
		$value=ncd_new($arr1[$i], $arr2[$j]);
		if($value<=30)
		{ 
		GLOBAL $search;
		$search=$arr1[$i];?>
		<tr><td><?php echo $i+1;?></td><td><?php echo $arr1[$i];?></td><td><?php echo $j+1; ?></td><td><?php echo $arr2[$j];?></td><td><?php echo $value."%";?></td></tr>
		<?php
		$a=$i+1;
		$b=$j+1;
		$out=$a."=".$b.";";
		fwrite($fp,$out);
		}
	}
}
fclose($fp);

?>
</tbody>
</table><br>
<br>
<?php 
$j=0;
$k=0;
$c=0;
while($j<strlen($search))
{
	//if($c<3)
	{
		while($search[$j]!='.')
		{
			$c++;
			$s_word[$k] .= $search[$j];
			$j++;
			}
			$k++;
			$j++;
	}	
}
echo "actual:".$search;
$len=strlen($search);
$k=0;
global $final_search;
for($i=2;$i<$len;$i++)
{
	if($search[$i]==' ')
	{
		$final_search .="%20";
		$count++;
	}
	else if($search[$i]=='.')
	{
		if($count>5)
			break;
		else
			$final_search .=$search[$i];
		}
	else
		$final_search .=$search[$i];
}

$url = "https://ajax.googleapis.com/ajax/services/search/web?v=1.0&"
    . "q=".$final_search."&userip=USERS-IP-ADDRESS";

// sendRequest
// note how referer is set manually
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, 'http://shibulijack.wordpress.com');
$body = curl_exec($ch);
curl_close($ch);
//var_dump($body);
// now, process the JSON string
$json = json_decode($body);
// now have some fun with the results...
$k=0;
foreach ($json->responseData->results as $res) {
        $search_url[$k]=$res->url;
        $search_format[$k]=$res->fileFormat;
        $k++;
}
//var_dump($search_format);
for($j=0;$j<$k;$j++)
{
	if($search_format[$j]="PDF/Adobe Acrobat")
	{
		$pdf_url=$search_url[$j];
		break;
	}
}
//var_dump($pdf_url);
echo $pdf_url;
$test="http://www.garfield.library.upenn.edu/essays/v15p293y1992-93.pdf";
$search_grab = file_get_contents($pdf_url);
$fw=fopen('search_result.pdf', 'w');
fwrite($fw,$search_grab);
move_uploaded_file($search_grab, './files/');
?>
</div>
</div>
</div>
<div id="footer">
		<p>Project by <a href="mailto:sundarrajamanickam@rocketmail.com">Sundar</a> & <a href="mailto:shibulijack@gmail.com">Shibu Lijack</a> | Designed by <a href="http://www.shibulijack.wordpress.com/">CyberJack</a></p>
	</div>
</div>
</body>
</html>