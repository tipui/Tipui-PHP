<?php
// 'upload-img':
$rs = array (
	'type'           => 'file',
	'size'           => '30',
	'max_size'       => Register_Items::IMG_MAX_SIZE,
	'min_size'       => Register_Items::IMG_MIN_SIZE,
	'max_width'      => Register_Items::IMG_MAX_WIDTH,
	'min_height'     => Register_Items::IMG_HEIGHT,
	'max_height'     => Register_Items::IMG_MAX_HEIGHT,
	'content_types'  => Register_Items::Valid_Types(),
	'validation'     => 'upload',
	'value'          => '',
	'pre-filter'     => array( array( 'Strings', 'Escape' ), array( false ) ),
);
?>