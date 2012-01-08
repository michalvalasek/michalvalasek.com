<?php ini_set("memory_limit", "200000000"); // for large images so that we do not get "Allowed memory exhausted"?>
<?php
$requested_width = $_REQUEST['width'];
$requested_height = $_REQUEST['height'];

$partial_name = str_replace('.jpg','',strtolower($_REQUEST['file']));

if (file_exists($partial_name.'.jpg')){
	list($original_width, $original_height) = getimagesize($partial_name.'.jpg');
} else {
	// if the original file is not there then stop everything and show a default thumb
	echo '<p>No &quot;file&quot; was defined in url.</p><p>Script powered by thewebhelp.com <a href="http://www.thewebhelp.com/php/quick_photo_gallery/">quick photo gallery</a>.</p>';
	exit;
}

header("Content-type: image/jpg");

// if original image is bigger than requested then calculate proportions in order to scale it down
if(($original_width>$requested_width || $original_height >$requested_height) and isset($requested_width) and isset($requested_height) and $requested_width!='' and $requested_height!=''){
	
	$proportions = $original_width/$original_height;

	// show the image at the maximum height size but ...
	$new_height = $requested_height;
	$new_width = round($requested_height*$proportions);
	// but ... if above calculated width is over the given limit then scal it down according to given width
	if(round($requested_height*$proportions)>$requested_width){
		$new_width = $requested_width;
		$new_height = round($requested_width/$proportions);
	}

}else {
	$new_width = $original_width;
	$new_height = $original_height;
	// show original file without any resizing "image_cache/20.jpg"
	if(file_exists('image_cache/'.$partial_name.'.jpg')){
		header("Location: image_cache/".$partial_name.".jpg");
		exit;
	}
}


// now that we know the resized file check to see if file already exists "image_cache/20_800x600.jpg" and load it, else create it below
if(file_exists('image_cache/'.$partial_name.'_'.$new_width.'x'.$new_height.'.jpg')){
	header("Location: image_cache/".$partial_name."_".$new_width."x".$new_height.".jpg");
	exit;
}


$file = $partial_name.'.jpg';

// set quality to 100
if(!isset($quality)){
	$quality= 100;
}

// get width and height of original image
$imagedata = getimagesize($file);
$original_width = $imagedata[0];	
$original_height = $imagedata[1];

// if orignal image is smaller than 550px then do NOT increase its size to 550
if($original_width<$new_width){
	$new_width = $original_width;
}

// calculating width or height if one was not defined
// leave this one first
if(!isset($new_width) and !isset($new_height)){
	$new_width = $original_width;
	$new_height = $original_height;
}
if(!isset($new_height)){
	$new_height = $new_width*($original_height/$original_width);
}
if(!isset($new_width)){
	$new_width = $new_height*($original_width/$original_height);
}

$smaller_image = imagecreatetruecolor($new_width, $new_height);
$original_image = imagecreatefromjpeg($file);

imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $imagedata[0], $imagedata[1]);



// save the image
if($new_width>140 and $new_height>100){
	imagejpeg($smaller_image,'image_cache/'.$partial_name.'_'.$new_width.'x'.$new_height.'.jpg',$quality);
} else {
	imagejpeg($smaller_image,'image_cache/'.$partial_name.'_'.$new_width.'x'.$new_height.'.jpg',$quality);
}

// make the image 644 (0644)
chmod('image_cache/'.$partial_name.'_'.$new_width.'x'.$new_height.'.jpg',0644);

imagedestroy($original_image);
imagedestroy($smaller_image);
imagedestroy($watermarked_image);

// read the new image
header('Location: image_cache/'.$partial_name.'_'.$new_width.'x'.$new_height.'.jpg');
exit;
?>
