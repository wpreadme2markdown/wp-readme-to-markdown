<?php
/**
 * Converts WordPress-flavored markup from standard readme.txt files
 * to Github-flavored markup for a readme.md file
 * @author Benjamin J. Balter -- http://ben.balter.com
 * @version 1.0
 */
//no file, serve form
if ( !$_FILES ) { include 'form.html'; exit(); }

//grab file contents from temp location, no need to move
$readme = file_get_contents( $_FILES['file']['tmp_name'] );

//Convert Headings
//original code from https://github.com/markjaquith/WordPress-Plugin-Readme-Parser/blob/master/parse-readme.php
//using here in reverse to go from WP to GitHub style headings
$readme = preg_replace( "|^=([^=]+)=*?\s*?\n|im",  '###$1###'."\n",    $readme );
$readme = preg_replace( "|^==([^=]+)=*?\s*?\n|im",  '##$1##'."\n",    $readme );
$readme = preg_replace( "|^===([^=]+)=*?\s*?\n|im", '#$1#'."\n",   $readme );

//parse contributors, donate link, etc.
$readme = preg_replace( "|^([^:\n]+): (.+)$|im", "**$1:** $2  ", $readme );

//guess plugin slug from plugin name
//@todo better way to do this?
preg_match( "|^#([^#]+)#*?\s*?\n|im", $readme, $matches );
$plugin = str_replace( ' ', '-', strtolower( trim( $matches[1] ) ) );

//process screenshots, if any
if ( preg_match( "|## Screenshots ##(.*?)## [a-z]+ ##|ism", $readme, $matches ) ) {

	//parse screenshot list into array
	preg_match_all( "|^[0-9]+\. (.*)$|im", $matches[1], $screenshots, PREG_SET_ORDER );

	//replace list item with markdown image syntax, hotlinking to plugin repo
	//@todo assumes .png, perhaps should check that file exists first?
	$i = 1;
	foreach ( $screenshots as $screenshot ) {
		$readme = str_replace( $screenshot[0], "###{$i}. {$screenshot[1]}###\n![{$screenshot[1]}](http://s.wordpress.org/extend/plugins/{$plugin}/screenshot-{$i}.png)\n", $readme );
		$i++;
	}

}

header('Content-disposition: attachment; filename=readme.md');
header('Content-type: ' . $_FILES['file']['type'] );

echo $readme;