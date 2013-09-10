<?php
// 'page':
$rs = array (
	'type'           => 'hidden',
	'MinLength'      => 1,
	'MaxLength'      => 6,
	'size'           => 25,
	'validation'     => 'number',
	'value'          => '',
	'default'        => '',
	'pre-filter'     => array( array( 'Strings', 'NumbersOnly' ) ),
);
?>