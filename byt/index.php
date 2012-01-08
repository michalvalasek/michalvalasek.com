<?php if(isset($_REQUEST['big_image']) and $_REQUEST['big_image']!=''){?>
<?php 
$image_title = strtolower($_REQUEST['big_image']);
$image_title = str_replace('_',' ',$image_title);
$image_title = str_replace('.jpg','',$image_title);
?>
<?php }?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php if(isset($_REQUEST['big_image']) and $_REQUEST['big_image']!=''){?>
<title><?php echo $image_title;?></title>
<?php } else {?>
<title>Quick Photo Gallery</title>
<?php }?>

</head>

<body>





<?php if(isset($_REQUEST['big_image']) and $_REQUEST['big_image']!=''){?>

<h1 style="text-transform:capitalize;"><?php echo $image_title;?></h1>

<?php list($image_width, $image_height) = getimagesize($_REQUEST['big_image']); ?>
<div style="border:1px solid #EBEBEB; margin-right:10px; margin-bottom:10px; padding:5px; width:<?php echo$image_width;?>px; display:block; clear:both; text-align:center;"><img src="image.php?file=<?php echo $_REQUEST['big_image'];?>&width=500&height=375" alt="<?php echo $image_title;?>" title="<?php echo $image_title;?>"/></div>

<?php }?>


<?php
if(!function_exists('get_file_extension')){
	function get_file_extension($filename) { 
		$filename = strtolower($filename) ; 
		$exts = split("[/\\.]", $filename) ; 
		$n = count($exts)-1; 
		$exts = $exts[$n]; 
		return $exts; 
	} 
}

if ($handle = opendir('.')) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if(get_file_extension(strtolower($file)) == 'jpg' and $file!= 'no_image.jpg'){
		
		$image_title = strtolower($file);
		$image_title = str_replace('_',' ',$image_title);
		$image_title = str_replace('.jpg','',$image_title);
		
			echo '<div style="border:1px solid #EBEBEB; margin-right:10px; margin-bottom:10px; padding:5px; width:auto; display:block; float:left;"><a href="gallery_index.php?big_image='.$file.'"><img border="0" src="image.php?file='.$file.'&width=200&height=60" alt="'.$image_title.'" title="'.$image_title.'"/></a></div>';
		}
    }
    closedir($handle);
}
?> 
<div style="clear:both;">
<p style="margin:0px; padding:3px; background-color:#FAFAFA; border:1px solid #EBEBEB; font-size:10px; font-family:Arial, Helvetica, sans-serif;">Script powered by thewebhelp.com <a style="color:#006699;" href="http://www.thewebhelp.com/php/quick_photo_gallery/">quick photo gallery</a>.</p>
</div>

</body>
</html>