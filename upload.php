<html>
<head>
<title>Citation Analysis | Step II</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
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
					<li><a href="#">By Shibu Lijack & Sundar</a></li>
				</ul>
			</div>
		</div>
<div id="page">
<div id="widebar">
<?php
   // Configuration - Your Options
   	$dir='files';
   	if ( !file_exists($dir) ) {
  	mkdir ($dir, 0777);
 	}
      $allowed_filetypes = array('.txt','.rtf','.doc','.pdf'); // These will be the types of file that will pass the validation.
      $max_filesize = 524288; // Maximum filesize in BYTES (currently 0.5MB).
      $upload_path = "./".$dir."/"; // The place the files will be uploaded to (currently a 'files' directory).
 
   $filename1 = $_FILES['userfile1']['name']; // Get the name of the file (including file extension).
   $ext1 = substr($filename1, strpos($filename1,'.'), strlen($filename1)-1); // Get the extension from the filename.
   for($i=1;$i<=2;$i++)
   {
   $fname[$i]="in".$i.$ext1;
   $path[$i]="./files/".$fname[$i];
   }
   // Check if the filetype is allowed, if not DIE and inform the user.
   if(!in_array($ext1,$allowed_filetypes))
      die('<h2>Error</h2><p>Please select a valid file.</p><p><a href="index.html">Try again</a></p>');
 
   // Now check the filesize, if it is too large then DIE and inform the user.
   if(filesize($_FILES['userfile1']['tmp_name']) > $max_filesize)
      die('The file you attempted to upload is too large.');
 
   // Check if we can upload to the specified path, if not DIE and inform the user.
   if(!is_writable($upload_path))
      die('You cannot upload to the specified directory, please CHMOD it to 777.');
      
   $filename2 = $_FILES['userfile2']['name']; // Get the name of the file (including file extension).
   $ext2 = substr($filename2, strpos($filename2,'.'), strlen($filename2)-1); // Get the extension from the filename.
 
   // Check if the filetype is allowed, if not DIE and inform the user.
   if(!in_array($ext2,$allowed_filetypes))
      die('The file you attempted to upload is not allowed.');
 
   // Now check the filesize, if it is too large then DIE and inform the user.
   if(filesize($_FILES['userfile2']['tmp_name']) > $max_filesize)
      die('The file you attempted to upload is too large.');
 
   // Check if we can upload to the specified path, if not DIE and inform the user.
   if(!is_writable($upload_path))
      die('You cannot upload to the specified directory, please CHMOD it to 777.');
 
   // Upload the file to your specified path.
   for($j=1;$j<=2;$j++)
   {
   $res=move_uploaded_file($_FILES['userfile'.$j]['tmp_name'],$upload_path . $fname[$j]);
   //if((move_uploaded_file($_FILES['userfile1']['tmp_name'],$upload_path . $fname)) && (copy($_FILES['userfile2']['tmp_name'],$upload_path . $filename2)))
   
   //$f1 = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
   //var_dump($f1);

   chmod($path[$j], 0777);
   
   }
   if($res==1)
   		echo '<h2>Bravo!</h2><center><img src="images/Step2.png"></center><br><p>The files were successfully uploaded to our server</p>';
   else
        echo '<h2>Alas!</h2></h2><center><img src="images/Step2.png"></center><br><p>There was an error during the file upload.  Please try again.</p>'; // It failed :(.
//exec('/Applications/XAMPP/xamppfiles/htdocs/compiler/');
exec('./pd.o');
?>
<a href="./pattern.php"><input type=submit value="Click to continue"></a>
</div>
</div>
<div id="footer">
		<p>Project by <a href="mailto:sundarrajamanickam@rocketmail.com">Sundar</a> & <a href="mailto:shibulijack@gmail.com">Shibu Lijack</a> | Designed by <a href="http://www.shibulijack.wordpress.com/">CyberJack</a></p>
	</div>
	</div>
</body>
</html>