<?php
$rs = array (
	'type'           => 'file',
	'max_size'       => 102400,
	'min_size'       => 2048,
	'max_width'      => 2000,
	'min_height'     => 50,
	'max_height'     => 2000,
	'content_types'  => array('jpg', 'gif', 'png'),
	'validation'     => 'upload',
	'value'          => '',
	'pre-filter'     => array( array( '\Tipui\Builtin\Libs\Strings', 'Escape' ), array( false ) ),
);