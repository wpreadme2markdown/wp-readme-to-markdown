<?php
//no file, serve form
if ( !$_FILES ) { include 'form.html'; exit(); }

//grab file contents from temp location, no need to move
$readme = file_get_contents( $_FILES['file']['tmp_name'] );

//Convert Headings
//original code from https://github.com/markjaquith/WordPress-Plugin-Readme-Parser/blob/master/parse-readme.php
//using here in reverse to go from WP to GitHub style headings
$readme = preg_replace( "|^=([^=]+)=*?\s*?\n|im", 	'###$1###'."\n",    $readme );
$readme = preg_replace( "|^==([^=]+)=*?\s*?\n|im",  '##$1##'."\n",   	$readme );
$readme = preg_replace( "|^===([^=]+)=*?\s*?\n|im",	'#$1#'."\n", 		$readme );

//parse contributors, donate link, etc.
$readme = preg_replace( "|^([^:\n]+): (.+)$|im", "**$1:** $2  ", $readme );

header('Content-disposition: attachment; filename=readme.md');
header('Content-type: ' . $_FILES['file']['type'] );

echo $readme;